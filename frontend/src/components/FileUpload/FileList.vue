<template>
  <div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-medium text-white">
        {{ title || 'Uploaded Files' }}
        <span v-if="files.length > 0" class="text-sm text-gray-400 ml-2">
          ({{ files.length }})
        </span>
      </h3>
      
      <div class="flex items-center space-x-2">
        <!-- View Toggle -->
        <button
          @click="viewMode = 'grid'"
          :class="[
            'p-2 rounded-lg transition-colors duration-200',
            viewMode === 'grid'
              ? 'bg-primary-500 text-white'
              : 'text-gray-400 hover:text-white hover:bg-gray-700'
          ]"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
        </button>
        
        <button
          @click="viewMode = 'list'"
          :class="[
            'p-2 rounded-lg transition-colors duration-200',
            viewMode === 'list'
              ? 'bg-primary-500 text-white'
              : 'text-gray-400 hover:text-white hover:bg-gray-700'
          ]"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="files.length === 0" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-300">No files uploaded</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by uploading your first file.</p>
    </div>

    <!-- Files Grid -->
    <div
      v-else-if="viewMode === 'grid'"
      class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"
    >
      <div
        v-for="file in files"
        :key="file.id"
        class="relative group bg-gray-800 rounded-lg p-4 hover:bg-gray-700 transition-colors duration-200"
      >
        <!-- File Preview -->
        <div class="aspect-square mb-3 flex items-center justify-center bg-gray-900 rounded-lg overflow-hidden">
          <!-- Image Preview -->
          <img
            v-if="file.type === 'image'"
            :src="file.url"
            :alt="file.original_name"
            class="w-full h-full object-cover"
            @error="handleImageError"
          />
          
          <!-- File Type Icon -->
          <div v-else class="text-gray-400">
            <svg v-if="file.type === 'video'" class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
            </svg>
            <svg v-else-if="file.type === 'audio'" class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
            </svg>
            <svg v-else class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>

        <!-- File Info -->
        <div class="space-y-1">
          <p class="text-sm font-medium text-white truncate" :title="file.original_name">
            {{ file.original_name }}
          </p>
          <p class="text-xs text-gray-400">
            {{ formatFileSize(file.size) }}
          </p>
          <p class="text-xs text-gray-500">
            {{ formatDate(file.uploaded_at) }}
          </p>
        </div>

        <!-- Actions -->
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
          <div class="flex space-x-1">
            <button
              @click="copyFileUrl(file)"
              class="p-1 bg-gray-900/80 text-white rounded hover:bg-gray-700 transition-colors duration-200"
              title="Copy URL"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </button>
            
            <button
              v-if="allowDelete"
              @click="deleteFile(file)"
              class="p-1 bg-red-500/80 text-white rounded hover:bg-red-600 transition-colors duration-200"
              title="Delete"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Files List -->
    <div v-else class="space-y-2">
      <div
        v-for="file in files"
        :key="file.id"
        class="flex items-center justify-between p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors duration-200"
      >
        <div class="flex items-center space-x-4">
          <!-- File Type Icon -->
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center">
              <svg v-if="file.type === 'image'" class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
              </svg>
              <svg v-else-if="file.type === 'video'" class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
              </svg>
              <svg v-else-if="file.type === 'audio'" class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217z" clip-rule="evenodd" />
              </svg>
              <svg v-else class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>

          <!-- File Details -->
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-white truncate">
              {{ file.original_name }}
            </p>
            <p class="text-sm text-gray-400">
              {{ formatFileSize(file.size) }} • {{ formatDate(file.uploaded_at) }}
            </p>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-2">
          <button
            @click="copyFileUrl(file)"
            class="p-2 text-gray-400 hover:text-white transition-colors duration-200"
            title="Copy URL"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
          </button>
          
          <button
            v-if="allowDelete"
            @click="deleteFile(file)"
            class="p-2 text-gray-400 hover:text-red-400 transition-colors duration-200"
            title="Delete"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import type { UploadedFile } from '../../services/fileUploadService'

// Use the same type as the service
type FileItem = UploadedFile

interface Props {
  files: FileItem[]
  title?: string
  allowDelete?: boolean
}

interface Emits {
  (e: 'file-deleted', fileId: number): void
  (e: 'file-copied', file: FileItem): void
}

const props = withDefaults(defineProps<Props>(), {
  allowDelete: true
})

const emit = defineEmits<Emits>()

// Reactive state
const viewMode = ref<'grid' | 'list'>('grid')

// Methods
const formatFileSize = (bytes: number): string => {
  const sizes = ['B', 'KB', 'MB', 'GB']
  if (bytes === 0) return '0 B'
  const i = Math.floor(Math.log(bytes) / Math.log(1024))
  return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i]
}

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const copyFileUrl = async (file: FileItem) => {
  try {
    await navigator.clipboard.writeText(file.url)
    emit('file-copied', file)
    // You could show a toast notification here
  } catch (error) {
    console.error('Failed to copy URL:', error)
  }
}

const deleteFile = (file: FileItem) => {
  if (confirm(`Are you sure you want to delete "${file.original_name}"?`)) {
    emit('file-deleted', file.id)
  }
}

const handleImageError = (event: Event) => {
  const target = event.target as HTMLImageElement
  target.style.display = 'none'
}
</script>
