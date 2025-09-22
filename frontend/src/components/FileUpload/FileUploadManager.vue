<template>
  <div class="space-y-6">
    <!-- Upload Area -->
    <div v-if="showUploader">
      <h3 class="text-lg font-medium text-white mb-4">Upload Files</h3>
      
      <FileUploadDropzone
        :upload-type="uploadType"
        :multiple="allowMultiple"
        :max-size="maxSize"
        :context="context"
        :context-id="contextId"
        :disabled="uploading"
        @upload-success="handleUploadSuccess"
        @upload-error="handleUploadError"
        @upload-progress="handleUploadProgress"
      />

      <!-- Upload Status -->
      <div v-if="uploadStatus" class="mt-4">
        <div
          :class="[
            'p-4 rounded-lg',
            uploadStatus.type === 'success'
              ? 'bg-green-500/20 border border-green-500'
              : 'bg-red-500/20 border border-red-500'
          ]"
        >
          <p
            :class="[
              'font-medium',
              uploadStatus.type === 'success' ? 'text-green-400' : 'text-red-400'
            ]"
          >
            {{ uploadStatus.message }}
          </p>
        </div>
      </div>
    </div>

    <!-- File List -->
    <div v-if="showFileList">
      <FileList
        :files="files"
        :title="fileListTitle"
        :allow-delete="allowDelete"
        @file-deleted="handleFileDelete"
        @file-copied="handleFileCopy"
      />

      <!-- Load More Button -->
      <div v-if="hasMoreFiles" class="mt-6 text-center">
        <button
          @click="loadMoreFiles"
          :disabled="loadingMore"
          class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
        >
          {{ loadingMore ? 'Loading...' : 'Load More Files' }}
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto"></div>
      <p class="mt-4 text-gray-400">Loading files...</p>
    </div>

    <!-- Error State -->
    <div v-if="error" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-red-400">Error loading files</h3>
      <p class="mt-1 text-sm text-gray-500">{{ error }}</p>
      <button
        @click="() => loadFiles()"
        class="mt-4 px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors duration-200"
      >
        Try Again
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import FileUploadDropzone from './FileUploadDropzone.vue'
import FileList from './FileList.vue'
import { fileUploadService, type UploadedFile } from '../../services/fileUploadService'

interface Props {
  uploadType: 'image' | 'video' | 'audio' | 'document'
  context?: string
  contextId?: number
  allowMultiple?: boolean
  maxSize?: number
  showUploader?: boolean
  showFileList?: boolean
  allowDelete?: boolean
  fileListTitle?: string
  autoLoad?: boolean
}

interface Emits {
  (e: 'files-uploaded', files: UploadedFile[]): void
  (e: 'file-deleted', fileId: number): void
  (e: 'file-copied', file: UploadedFile): void
  (e: 'upload-progress', progress: number): void
}

const props = withDefaults(defineProps<Props>(), {
  allowMultiple: false,
  maxSize: 10,
  showUploader: true,
  showFileList: true,
  allowDelete: true,
  autoLoad: true
})

const emit = defineEmits<Emits>()

// Reactive state
const files = ref<UploadedFile[]>([])
const loading = ref(false)
const loadingMore = ref(false)
const uploading = ref(false)
const error = ref('')
const uploadStatus = ref<{ type: 'success' | 'error'; message: string } | null>(null)
const currentPage = ref(1)
const hasMoreFiles = ref(false)

// Computed
const fileListTitle = computed(() => {
  if (props.fileListTitle) return props.fileListTitle
  
  const typeLabels = {
    image: 'Images',
    video: 'Videos',
    audio: 'Audio Files',
    document: 'Documents'
  }
  
  return typeLabels[props.uploadType] || 'Files'
})

// Methods
const loadFiles = async (page = 1) => {
  try {
    if (page === 1) {
      loading.value = true
      error.value = ''
    } else {
      loadingMore.value = true
    }

    const response = await fileUploadService.getUserFiles({
      type: props.uploadType,
      context: props.context,
      page,
      perPage: 20
    })

    if (response.success) {
      if (page === 1) {
        files.value = response.data.data || []
      } else {
        files.value.push(...(response.data.data || []))
      }
      
      currentPage.value = page
      hasMoreFiles.value = response.data.current_page < response.data.last_page
    } else {
      throw new Error('Failed to load files')
    }
  } catch (err: any) {
    error.value = err.message || 'Failed to load files'
    console.error('Error loading files:', err)
  } finally {
    loading.value = false
    loadingMore.value = false
  }
}

const loadMoreFiles = () => {
  loadFiles(currentPage.value + 1)
}

const handleUploadSuccess = (uploadedFiles: UploadedFile[]) => {
  uploading.value = false
  
  // Add new files to the beginning of the list
  files.value.unshift(...uploadedFiles)
  
  // Show success message
  uploadStatus.value = {
    type: 'success',
    message: `${uploadedFiles.length} file(s) uploaded successfully`
  }
  
  // Clear status after 5 seconds
  setTimeout(() => {
    uploadStatus.value = null
  }, 5000)
  
  emit('files-uploaded', uploadedFiles)
}

const handleUploadError = (errorMessage: string) => {
  uploading.value = false
  
  uploadStatus.value = {
    type: 'error',
    message: errorMessage
  }
  
  // Clear status after 10 seconds
  setTimeout(() => {
    uploadStatus.value = null
  }, 10000)
}

const handleUploadProgress = (progress: number) => {
  if (progress > 0) {
    uploading.value = true
  }
  
  emit('upload-progress', progress)
}

const handleFileDelete = async (fileId: number) => {
  try {
    await fileUploadService.deleteFile(fileId)
    
    // Remove file from list
    files.value = files.value.filter(file => file.id !== fileId)
    
    emit('file-deleted', fileId)
    
    // Show success message
    uploadStatus.value = {
      type: 'success',
      message: 'File deleted successfully'
    }
    
    setTimeout(() => {
      uploadStatus.value = null
    }, 3000)
  } catch (err: any) {
    uploadStatus.value = {
      type: 'error',
      message: err.message || 'Failed to delete file'
    }
    
    setTimeout(() => {
      uploadStatus.value = null
    }, 5000)
  }
}

const handleFileCopy = (file: UploadedFile) => {
  uploadStatus.value = {
    type: 'success',
    message: 'File URL copied to clipboard'
  }
  
  setTimeout(() => {
    uploadStatus.value = null
  }, 3000)
  
  emit('file-copied', file)
}

// Lifecycle
onMounted(() => {
  if (props.autoLoad && props.showFileList) {
    loadFiles()
  }
})

// Expose methods for parent components
defineExpose({
  loadFiles,
  refreshFiles: () => loadFiles(1)
})
</script>
