<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 border-b border-gray-700">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <RouterLink 
              to="/news" 
              class="flex items-center text-gray-400 hover:text-white transition-colors"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
              Back to News
            </RouterLink>
          </div>
          
          <div class="flex items-center space-x-4">
            <button 
              @click="shareArticle"
              class="flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
              </svg>
              Share
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Article Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
        <p class="mt-4 text-gray-400">Loading article...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div class="text-red-400 mb-4">
          <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <h3 class="text-xl font-bold mb-2">Article Not Found</h3>
          <p class="text-gray-400 mb-4">{{ error }}</p>
          <RouterLink to="/news" class="btn-primary">
            Back to News
          </RouterLink>
        </div>
      </div>

      <!-- Article Display -->
      <article v-else-if="article" class="prose prose-lg max-w-none">
        <!-- Article Header -->
        <header class="mb-12">
          <!-- Category Badge -->
          <div class="mb-4">
            <span :class="getCategoryBadgeClass(article.category)" class="px-3 py-1 rounded-full text-sm font-medium">
              {{ article.category }}
            </span>
          </div>

          <!-- Title -->
          <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
            {{ article.title }}
          </h1>

          <!-- Meta Information -->
          <div class="flex flex-wrap items-center gap-6 text-gray-400 mb-8">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              By {{ article.author }}
            </div>
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              {{ formatDate(article.publishedAt) }}
            </div>
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ getReadingTime(article.content) }} min read
            </div>
          </div>

          <!-- Source Information -->
          <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <p class="text-sm text-gray-400 mb-2">Source:</p>
                <p class="text-white font-medium text-lg">{{ article.source }}</p>
                <p class="text-gray-400 text-sm mt-1">Want to read the original article?</p>
              </div>
              <div class="flex items-center space-x-3 ml-6">
                <a 
                  :href="article.url" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="btn-primary flex items-center"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                  </svg>
                  Read Original Source
                </a>
              </div>
            </div>
          </div>
        </header>

        <!-- Article Content -->
        <div class="prose prose-lg max-w-none">
          <div class="text-gray-300 leading-relaxed text-lg whitespace-pre-wrap">
            {{ article.content }}
          </div>
        </div>

        <!-- Tags -->
        <div v-if="article.tags && article.tags.length > 0" class="mt-12 pt-8 border-t border-gray-700">
          <h3 class="text-lg font-semibold text-white mb-4">Tags</h3>
          <div class="flex flex-wrap gap-2">
            <span 
              v-for="tag in article.tags" 
              :key="tag"
              class="px-3 py-1 bg-gray-700 text-gray-300 rounded-full text-sm hover:bg-gray-600 transition-colors"
            >
              #{{ tag }}
            </span>
          </div>
        </div>

        <!-- Interactive Content -->
        <div class="mt-12 pt-8 border-t border-gray-700">
          <InteractiveContent
            :content-id="article.id"
            content-type="news"
          />
        </div>
      </article>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { newsApiService, type NewsArticle } from '@/services/newsApiService'
import InteractiveContent from '@/components/InteractiveContent.vue'

const route = useRoute()
const router = useRouter()

const article = ref<NewsArticle | null>(null)
const isLoading = ref(true)
const error = ref<string | null>(null)

// Load article data
const loadArticle = async () => {
  try {
    isLoading.value = true
    error.value = null
    
    const articleId = route.params.id as string
    if (!articleId) {
      throw new Error('Article ID is required')
    }

    // Get all news and find the specific article
    const newsResponse = await newsApiService.getAllNews(50)
    const foundArticle = newsResponse.articles.find(a => a.id === articleId)
    
    if (!foundArticle) {
      throw new Error('Article not found')
    }

    article.value = foundArticle
    
    // Set page title
    useHead({
      title: `${foundArticle.title} - Stargate.ci News`,
      meta: [
        {
          name: 'description',
          content: foundArticle.excerpt
        }
      ]
    })

  } catch (err: any) {
    console.error('Error loading article:', err)
    error.value = err.message || 'Failed to load article'
  } finally {
    isLoading.value = false
  }
}

// Format date
const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Get reading time
const getReadingTime = (content: string): number => {
  const wordsPerMinute = 200
  const wordCount = content.split(' ').length
  return Math.ceil(wordCount / wordsPerMinute)
}

// Get category badge class
const getCategoryBadgeClass = (category: string): string => {
  const badgeClasses: Record<string, string> = {
    stargate: 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    cristal: 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    openai: 'bg-green-500/20 text-green-400 border border-green-500/30',
    softbank: 'bg-orange-500/20 text-orange-400 border border-orange-500/30',
    arm: 'bg-red-500/20 text-red-400 border border-red-500/30',
    ai: 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30',
    technology: 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
  }
  return badgeClasses[category] || badgeClasses.technology
}

// Share article
const shareArticle = async () => {
  if (!article.value) return

  const shareData = {
    title: article.value.title,
    text: article.value.excerpt,
    url: window.location.href
  }

  try {
    if (navigator.share) {
      await navigator.share(shareData)
    } else {
      // Fallback: copy to clipboard
      await navigator.clipboard.writeText(window.location.href)
      alert('Article link copied to clipboard!')
    }
  } catch (error) {
    console.error('Error sharing article:', error)
    // Fallback: copy to clipboard
    try {
      await navigator.clipboard.writeText(window.location.href)
      alert('Article link copied to clipboard!')
    } catch (clipboardError) {
      console.error('Error copying to clipboard:', clipboardError)
    }
  }
}

// Load article on mount
onMounted(() => {
  loadArticle()
})
</script>

<style scoped>
.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200;
}

.prose {
  @apply text-gray-300;
}

.prose h1,
.prose h2,
.prose h3,
.prose h4,
.prose h5,
.prose h6 {
  @apply text-white;
}

.prose a {
  @apply text-primary-400 hover:text-primary-300;
}

.prose strong {
  @apply text-white;
}

.prose blockquote {
  @apply border-gray-600 text-gray-400;
}
</style>
