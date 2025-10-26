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

    <!-- Share Modal -->
    <div v-if="showShareModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="closeShareModal">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-white">Share Article</h3>
          <button 
            @click="closeShareModal"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div v-if="article" class="mb-6">
          <h4 class="text-lg font-medium text-white mb-2">{{ article.title }}</h4>
          <p class="text-gray-400 text-sm">{{ article.excerpt }}</p>
        </div>

        <!-- Share Options -->
        <div class="grid grid-cols-2 gap-4">
          <!-- Facebook -->
          <button 
            @click="shareToFacebook"
            class="flex items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <span>Facebook</span>
          </button>

          <!-- Twitter/X -->
          <button 
            @click="shareToTwitter"
            class="flex items-center justify-center p-4 bg-black hover:bg-gray-800 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
            <span>X (Twitter)</span>
          </button>

          <!-- LinkedIn -->
          <button 
            @click="shareToLinkedIn"
            class="flex items-center justify-center p-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
            <span>LinkedIn</span>
          </button>

          <!-- WhatsApp -->
          <button 
            @click="shareToWhatsApp"
            class="flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
            <span>WhatsApp</span>
          </button>

          <!-- Telegram -->
          <button 
            @click="shareToTelegram"
            class="flex items-center justify-center p-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
            </svg>
            <span>Telegram</span>
          </button>

          <!-- Copy Link -->
          <button 
            @click="copyLink"
            class="flex items-center justify-center p-4 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors col-span-2"
          >
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            <span>Copy Link</span>
          </button>
        </div>
      </div>
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

// Share modal state
const showShareModal = ref(false)

// Share article
const shareArticle = async () => {
  if (!article.value) return
  showShareModal.value = true
}

const closeShareModal = () => {
  showShareModal.value = false
}

const shareToFacebook = () => {
  if (!article.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(article.value.title)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToTwitter = () => {
  if (!article.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(article.value.title)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToLinkedIn = () => {
  if (!article.value) return
  const url = encodeURIComponent(window.location.href)
  const title = encodeURIComponent(article.value.title)
  const summary = encodeURIComponent(article.value.excerpt)
  window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}&summary=${summary}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToWhatsApp = () => {
  if (!article.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`${article.value.title} - ${article.value.excerpt}`)
  window.open(`https://wa.me/?text=${text}%20${url}`, '_blank')
  closeShareModal()
}

const shareToTelegram = () => {
  if (!article.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`${article.value.title} - ${article.value.excerpt}`)
  window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank')
  closeShareModal()
}

const copyLink = async () => {
  if (!article.value) return
  try {
    await navigator.clipboard.writeText(window.location.href)
    alert('Link copied to clipboard!')
    closeShareModal()
  } catch (error) {
    console.error('Failed to copy link:', error)
    alert('Failed to copy link. Please try again.')
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
