<template>
  <div
    @click="handleClick"
    class="bg-gray-800/50 hover:bg-gray-800/70 border border-gray-700 hover:border-gray-600 rounded-lg p-6 cursor-pointer transition-all duration-200 hover:shadow-lg"
  >
    <div class="flex items-start gap-4">
      <!-- Type Icon -->
      <div class="flex-shrink-0 mt-1">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="typeIconClass">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="typeIconPath"></path>
          </svg>
        </div>
      </div>

      <!-- Content -->
      <div class="flex-1 min-w-0">
        <!-- Title -->
        <h3 class="text-lg font-semibold text-white mb-2 line-clamp-2">
          {{ formattedResult.title }}
        </h3>

        <!-- Description -->
        <p class="text-gray-300 text-sm mb-3 line-clamp-3">
          {{ formattedResult.description }}
        </p>

        <!-- Metadata -->
        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-400">
          <!-- Type Badge -->
          <span class="px-2 py-1 bg-gray-700 rounded-md font-medium">
            {{ formattedResult.type }}
          </span>

          <!-- Author -->
          <span v-if="formattedResult.metadata.author" class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ formattedResult.metadata.author }}
          </span>

          <!-- Category -->
          <span v-if="formattedResult.metadata.category" class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            {{ formattedResult.metadata.category }}
          </span>

          <!-- Date -->
          <span v-if="formattedResult.metadata.published_at || formattedResult.metadata.created_at" class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ formatDate(formattedResult.metadata.published_at || formattedResult.metadata.created_at) }}
          </span>

          <!-- Read Time (for articles) -->
          <span v-if="formattedResult.metadata.read_time" class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ formattedResult.metadata.read_time }} min read
          </span>

          <!-- Stats (for videos) -->
          <div v-if="result.type === 'video'" class="flex items-center gap-3">
            <span v-if="formattedResult.metadata.views_count" class="flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              {{ formatNumber(formattedResult.metadata.views_count) }} views
            </span>
            <span v-if="formattedResult.metadata.likes_count" class="flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
              {{ formatNumber(formattedResult.metadata.likes_count) }}
            </span>
          </div>

          <!-- Pinned Badge -->
          <span v-if="formattedResult.metadata.is_pinned" class="flex items-center gap-1 text-primary-400">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
            </svg>
            Pinned
          </span>
        </div>
      </div>

      <!-- External Link Icon -->
      <div v-if="isExternalLink" class="flex-shrink-0 mt-1">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { searchService, type SearchResult } from '@/services/searchService'

interface Props {
  result: SearchResult
}

const props = defineProps<Props>()

const emit = defineEmits<{
  click: [result: SearchResult]
}>()

// Computed properties
const formattedResult = computed(() => {
  return searchService.formatResult(props.result)
})

const isExternalLink = computed(() => {
  return props.result.url.startsWith('http')
})

const typeIconClass = computed(() => {
  const classes = {
    article: 'bg-blue-600/20 text-blue-400',
    video: 'bg-red-600/20 text-red-400',
    faq: 'bg-green-600/20 text-green-400',
    user: 'bg-purple-600/20 text-purple-400',
    comment: 'bg-yellow-600/20 text-yellow-400'
  }
  return classes[props.result.type] || 'bg-gray-600/20 text-gray-400'
})

const typeIconPath = computed(() => {
  const paths = {
    article: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    video: 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
    faq: 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    user: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    comment: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'
  }
  return paths[props.result.type] || 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
})

// Methods
const handleClick = () => {
  emit('click', props.result)
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInDays = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60 * 24))
  
  if (diffInDays === 0) {
    return 'Today'
  } else if (diffInDays === 1) {
    return 'Yesterday'
  } else if (diffInDays < 7) {
    return `${diffInDays} days ago`
  } else if (diffInDays < 30) {
    const weeks = Math.floor(diffInDays / 7)
    return `${weeks} week${weeks > 1 ? 's' : ''} ago`
  } else {
    return date.toLocaleDateString()
  }
}

const formatNumber = (num: number) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M'
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K'
  }
  return num.toString()
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
