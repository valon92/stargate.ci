<template>
  <div class="community-forum">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-white mb-2">Community Forum</h2>
            <p class="text-gray-400">Connect with the Stargate.ci community and share knowledge</p>
          </div>
          <button
            @click="showCreatePostModal = true"
            class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition-colors duration-200"
          >
            Create Post
          </button>
        </div>
      </div>

      <!-- Community Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-500/20 rounded-lg">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Total Users</p>
              <p class="text-2xl font-bold text-white">{{ communityStats.totalUsers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-500/20 rounded-lg">
              <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Total Posts</p>
              <p class="text-2xl font-bold text-white">{{ communityStats.totalPosts }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-500/20 rounded-lg">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Comments</p>
              <p class="text-2xl font-bold text-white">{{ communityStats.totalComments }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-500/20 rounded-lg">
              <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Active Users</p>
              <p class="text-2xl font-bold text-white">{{ communityStats.activeUsers }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Categories and Posts -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Categories -->
        <div class="lg:col-span-1">
          <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Categories</h3>
            <div class="space-y-3">
              <button
                v-for="category in categories"
                :key="category.id"
                @click="selectedCategory = category.id"
                :class="[
                  'w-full flex items-center justify-between p-3 rounded-lg transition-colors duration-200',
                  selectedCategory === category.id
                    ? 'bg-primary-500/20 border border-primary-500/50'
                    : 'bg-gray-700/30 hover:bg-gray-700/50'
                ]"
              >
                <div class="flex items-center space-x-3">
                  <span class="text-2xl">{{ category.icon }}</span>
                  <div class="text-left">
                    <div class="text-white font-medium">{{ category.name }}</div>
                    <div class="text-gray-400 text-sm">{{ category.postCount }} posts</div>
                  </div>
                </div>
              </button>
            </div>
          </div>

          <!-- Top Contributors -->
          <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-white mb-4">Top Contributors</h3>
            <div class="space-y-3">
              <div
                v-for="(user, index) in communityStats.topContributors.slice(0, 5)"
                :key="user.id"
                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700/30 transition-colors duration-200"
              >
                <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-bold">{{ user.displayName.charAt(0) }}</span>
                </div>
                <div class="flex-1">
                  <div class="text-white text-sm font-medium">{{ user.displayName }}</div>
                  <div class="text-gray-400 text-xs">{{ user.reputation }} reputation</div>
                </div>
                <div class="text-primary-400 text-sm font-bold">#{index + 1}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Posts -->
        <div class="lg:col-span-2">
          <div class="bg-gray-800/50 border border-gray-700 rounded-lg">
            <!-- Posts Header -->
            <div class="p-6 border-b border-gray-700">
              <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">
                  {{ selectedCategory ? categories.find(c => c.id === selectedCategory)?.name : 'All Posts' }}
                </h3>
                <div class="flex space-x-2">
                  <select
                    v-model="sortBy"
                    @change="loadPosts"
                    class="px-3 py-1 bg-gray-700 border border-gray-600 rounded text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  >
                    <option value="recent">Most Recent</option>
                    <option value="popular">Most Popular</option>
                    <option value="views">Most Views</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Posts List -->
            <div class="divide-y divide-gray-700">
              <div
                v-for="post in posts"
                :key="post.id"
                class="p-6 hover:bg-gray-700/20 transition-colors duration-200"
              >
                <div class="flex items-start space-x-4">
                  <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-bold">{{ post.author.displayName.charAt(0) }}</span>
                  </div>
                  
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2 mb-2">
                      <h4 class="text-white font-medium hover:text-primary-400 cursor-pointer">
                        {{ post.title }}
                      </h4>
                      <div v-if="post.isPinned" class="text-yellow-400 text-sm">📌</div>
                      <div v-if="post.isLocked" class="text-red-400 text-sm">🔒</div>
                      <div v-if="post.isSolved" class="text-green-400 text-sm">✅</div>
                    </div>
                    
                    <p class="text-gray-400 text-sm mb-3 line-clamp-2">{{ post.content }}</p>
                    
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-4 text-sm text-gray-400">
                        <span>by {{ post.author.displayName }}</span>
                        <span>{{ formatTime(post.createdAt) }}</span>
                        <span>{{ post.views }} views</span>
                        <span>{{ post.likes }} likes</span>
                        <span>{{ post.comments }} comments</span>
                      </div>
                      
                      <div class="flex items-center space-x-2">
                        <span
                          v-for="tag in post.tags.slice(0, 3)"
                          :key="tag"
                          class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded"
                        >
                          {{ tag }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Load More -->
            <div v-if="hasMorePosts" class="p-6 border-t border-gray-700 text-center">
              <button
                @click="loadMorePosts"
                class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200"
              >
                Load More Posts
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Create Post Modal -->
      <div v-if="showCreatePostModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-xl font-semibold text-white">Create New Post</h3>
              <button
                @click="showCreatePostModal = false"
                class="text-gray-400 hover:text-white"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="createPost" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                <input
                  v-model="newPost.title"
                  type="text"
                  required
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                <select
                  v-model="newPost.categoryId"
                  required
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                  <option value="">Select a category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.icon }} {{ category.name }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
                <textarea
                  v-model="newPost.content"
                  rows="8"
                  required
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Tags (comma-separated)</label>
                <input
                  v-model="newPost.tags"
                  type="text"
                  placeholder="ai, machine-learning, help"
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>

              <div class="flex justify-end space-x-4">
                <button
                  type="button"
                  @click="showCreatePostModal = false"
                  class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="isCreatingPost"
                  class="px-6 py-2 bg-primary-500 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition-colors duration-200"
                >
                  <span v-if="isCreatingPost">Creating...</span>
                  <span v-else>Create Post</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { communityService, type ForumCategory, type ForumPost, type CommunityStats, type UserProfile } from '../services/communityService'

// Reactive data
const categories = ref<ForumCategory[]>([])
const posts = ref<ForumPost[]>([])
const communityStats = ref<CommunityStats>({
  totalUsers: 0,
  totalPosts: 0,
  totalComments: 0,
  activeUsers: 0,
  topContributors: [],
  recentPosts: [],
  popularTags: []
})

const selectedCategory = ref<string>('')
const sortBy = ref<'recent' | 'popular' | 'views'>('recent')
const showCreatePostModal = ref(false)
const isCreatingPost = ref(false)
const hasMorePosts = ref(false)

const newPost = ref({
  title: '',
  content: '',
  categoryId: '',
  tags: ''
})

// Methods
const loadCategories = async () => {
  categories.value = await communityService.getCategories()
}

const loadPosts = async () => {
  const categoryId = selectedCategory.value || undefined
  posts.value = await communityService.getPosts(categoryId, 20)
}

const loadMorePosts = async () => {
  const categoryId = selectedCategory.value || undefined
  const morePosts = await communityService.getPosts(categoryId, 20)
  posts.value = [...posts.value, ...morePosts]
}

const loadCommunityStats = async () => {
  communityStats.value = await communityService.getCommunityStats()
}

const createPost = async () => {
  if (!newPost.value.title || !newPost.value.content || !newPost.value.categoryId) return

  isCreatingPost.value = true

  try {
    const category = categories.value.find(c => c.id === newPost.value.categoryId)
    if (!category) return

    // Get current user (in a real app, this would come from auth)
    const currentUser = {
      id: 'current-user',
      username: 'current-user',
      email: 'user@example.com',
      displayName: 'Current User',
      bio: '',
      avatar: '',
      location: '',
      website: '',
      joinDate: new Date().toISOString(),
      lastActive: new Date().toISOString(),
      reputation: 100,
      badges: [],
      stats: { posts: 0, comments: 0, likes: 0, followers: 0, following: 0 },
      preferences: { privacy: 'public' as 'public' | 'private' | 'friends', notifications: true, emailUpdates: false },
      socialLinks: {}
    }

    const tags = newPost.value.tags.split(',').map(tag => tag.trim()).filter(tag => tag)

    await communityService.createPost({
      title: newPost.value.title,
      content: newPost.value.content,
        author: currentUser as UserProfile,
      category,
      tags,
      isPinned: false,
      isLocked: false,
      isSolved: false
    })

    // Reset form
    newPost.value = {
      title: '',
      content: '',
      categoryId: '',
      tags: ''
    }

    showCreatePostModal.value = false
    await loadPosts()
    await loadCommunityStats()
  } catch (error) {
    console.error('Failed to create post:', error)
  } finally {
    isCreatingPost.value = false
  }
}

const formatTime = (timestamp: string): string => {
  const date = new Date(timestamp)
  const now = new Date()
  const diff = now.getTime() - date.getTime()
  
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)
  
  if (minutes < 60) return `${minutes}m ago`
  if (hours < 24) return `${hours}h ago`
  if (days < 7) return `${days}d ago`
  
  return date.toLocaleDateString()
}

// Lifecycle
onMounted(async () => {
  await communityService.initializeSampleData()
  await loadCategories()
  await loadPosts()
  await loadCommunityStats()
})
</script>

<style scoped>
.community-forum {
  @apply p-6;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
