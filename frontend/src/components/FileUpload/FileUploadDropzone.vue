<template>
  <div
    @drop.prevent="handleDrop"
    @dragover.prevent="isDragOver = true"
    @dragleave.prevent="isDragOver = false"
    @click="openFileDialog"
    :class="[
      'relative border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors duration-200',
      isDragOver || isUploading
        ? 'border-primary-500 bg-primary-500/10'
        : 'border-gray-600 hover:border-gray-500',
      disabled ? 'opacity-50 cursor-not-allowed' : ''
    ]"
  >
    <input
      ref="fileInput"
      type="file"
      :multiple="multiple"
      :accept="acceptedTypes"
      @change="handleFileSelect"
      class="hidden"
      :disabled="disabled"
    />

    <!-- Upload Icon -->
    <div class="mb-4">
      <svg
        v-if="!isUploading"
        class="mx-auto h-12 w-12 text-gray-400"
        stroke="currentColor"
        fill="none"
        viewBox="0 0 48 48"
      >
        <path
          d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
      
      <!-- Loading Spinner -->
      <div v-else class="mx-auto h-12 w-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
      </div>
    </div>

    <!-- Upload Text -->
    <div class="space-y-2">
      <p v-if="!isUploading" class="text-lg font-medium text-gray-300">
        <span v-if="isDragOver">Drop files here</span>
        <span v-else>
          {{ multiple ? 'Drop files or click to upload' : 'Drop file or click to upload' }}
        </span>
      </p>
      <p v-else class="text-lg font-medium text-primary-400">
        Uploading {{ uploadProgress }}%...
      </p>
      
      <p class="text-sm text-gray-500">
        {{ acceptedTypesText }} (Max {{ maxSize }}MB {{ multiple ? 'each' : '' }})
      </p>
    </div>

    <!-- Progress Bar -->
    <div v-if="isUploading" class="mt-4">
      <div class="w-full bg-gray-700 rounded-full h-2">
        <div
          class="bg-primary-500 h-2 rounded-full transition-all duration-300"
          :style="{ width: uploadProgress + '%' }"
        ></div>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="mt-4 p-3 bg-red-500/20 border border-red-500 rounded-lg">
      <p class="text-red-400 text-sm">{{ errorMessage }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { backendApi } from '../../services/backendApi'

interface Props {
  uploadType: 'image' | 'video' | 'audio' | 'document'
  multiple?: boolean
  maxSize?: number
  context?: string
  contextId?: number
  disabled?: boolean
}

interface Emits {
  (e: 'upload-success', files: any[]): void
  (e: 'upload-error', error: string): void
  (e: 'upload-progress', progress: number): void
}

const props = withDefaults(defineProps<Props>(), {
  multiple: false,
  maxSize: 10,
  disabled: false
})

const emit = defineEmits<Emits>()

// Reactive state
const fileInput = ref<HTMLInputElement | null>(null)
const isDragOver = ref(false)
const isUploading = ref(false)
const uploadProgress = ref(0)
const errorMessage = ref('')

// Computed properties
const acceptedTypes = computed(() => {
  switch (props.uploadType) {
    case 'image':
      return 'image/jpeg,image/png,image/gif,image/webp,image/svg+xml'
    case 'video':
      return 'video/mp4,video/mpeg,video/quicktime,video/webm,video/x-msvideo'
    case 'audio':
      return 'audio/mpeg,audio/wav,audio/ogg,audio/mp4,audio/webm'
    case 'document':
      return 'application/pdf,.doc,.docx,.xls,.xlsx,.txt,.csv'
    default:
      return '*'
  }
})

const acceptedTypesText = computed(() => {
  switch (props.uploadType) {
    case 'image':
      return 'PNG, JPG, GIF, WebP, SVG'
    case 'video':
      return 'MP4, MPEG, MOV, WebM, AVI'
    case 'audio':
      return 'MP3, WAV, OGG, MP4, WebM'
    case 'document':
      return 'PDF, DOC, DOCX, XLS, XLSX, TXT, CSV'
    default:
      return 'All file types'
  }
})

// Methods
const openFileDialog = () => {
  if (props.disabled || isUploading.value) return
  fileInput.value?.click()
}

const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    handleFiles(Array.from(target.files))
  }
}

const handleDrop = (event: DragEvent) => {
  isDragOver.value = false
  if (props.disabled || isUploading.value) return
  
  const files = Array.from(event.dataTransfer?.files || [])
  handleFiles(files)
}

const handleFiles = async (files: File[]) => {
  if (files.length === 0) return

  // Reset error
  errorMessage.value = ''

  // Validate files
  const validFiles = []
  for (const file of files) {
    if (file.size > props.maxSize * 1024 * 1024) {
      errorMessage.value = `File "${file.name}" is too large. Maximum size is ${props.maxSize}MB.`
      return
    }
    
    if (!validateFileType(file)) {
      errorMessage.value = `File "${file.name}" is not a valid ${props.uploadType} file.`
      return
    }
    
    validFiles.push(file)
  }

  if (!props.multiple && validFiles.length > 1) {
    errorMessage.value = 'Only one file is allowed.'
    return
  }

  // Upload files
  try {
    isUploading.value = true
    uploadProgress.value = 0

    if (props.multiple && validFiles.length > 1) {
      await uploadMultipleFiles(validFiles)
    } else {
      await uploadSingleFile(validFiles[0])
    }
  } catch (error: any) {
    errorMessage.value = error.message || 'Upload failed. Please try again.'
    emit('upload-error', errorMessage.value)
  } finally {
    isUploading.value = false
    uploadProgress.value = 0
  }
}

const uploadSingleFile = async (file: File) => {
  const formData = new FormData()
  formData.append('file', file)
  formData.append('type', props.uploadType)
  
  if (props.context) {
    formData.append('context', props.context)
  }
  
  if (props.contextId) {
    formData.append('context_id', props.contextId.toString())
  }

  // Simulate progress for now (in real implementation, you'd track actual upload progress)
  const progressInterval = setInterval(() => {
    uploadProgress.value += 10
    emit('upload-progress', uploadProgress.value)
    if (uploadProgress.value >= 90) {
      clearInterval(progressInterval)
    }
  }, 200)

  try {
    const response = await fetch(`${backendApi.getApiBaseUrl()}/files/upload`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${backendApi.getAuthToken()}`,
      },
      body: formData,
    })

    clearInterval(progressInterval)
    uploadProgress.value = 100
    emit('upload-progress', 100)

    const result = await response.json()
    
    if (!response.ok || !result.success) {
      throw new Error(result.message || 'Upload failed')
    }

    emit('upload-success', [result.data])
  } catch (error) {
    clearInterval(progressInterval)
    throw error
  }
}

const uploadMultipleFiles = async (files: File[]) => {
  const formData = new FormData()
  
  files.forEach(file => {
    formData.append('files[]', file)
  })
  
  formData.append('type', props.uploadType)
  
  if (props.context) {
    formData.append('context', props.context)
  }
  
  if (props.contextId) {
    formData.append('context_id', props.contextId.toString())
  }

  // Simulate progress
  const progressInterval = setInterval(() => {
    uploadProgress.value += 5
    emit('upload-progress', uploadProgress.value)
    if (uploadProgress.value >= 90) {
      clearInterval(progressInterval)
    }
  }, 300)

  try {
    const response = await fetch(`${backendApi.getApiBaseUrl()}/files/upload-multiple`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${backendApi.getAuthToken()}`,
      },
      body: formData,
    })

    clearInterval(progressInterval)
    uploadProgress.value = 100
    emit('upload-progress', 100)

    const result = await response.json()
    
    if (!response.ok || !result.success) {
      throw new Error(result.message || 'Upload failed')
    }

    emit('upload-success', result.data.uploaded)
  } catch (error) {
    clearInterval(progressInterval)
    throw error
  }
}

const validateFileType = (file: File): boolean => {
  const acceptedMimes = acceptedTypes.value.split(',').map(type => type.trim())
  
  // Check if it's a wildcard
  if (acceptedMimes.includes('*')) return true
  
  // Check MIME type
  return acceptedMimes.some(type => {
    if (type.startsWith('.')) {
      // File extension check
      return file.name.toLowerCase().endsWith(type.toLowerCase())
    } else {
      // MIME type check
      return file.type === type || file.type.startsWith(type.replace('/*', '/'))
    }
  })
}
</script>
