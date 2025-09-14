<template>
  <div class="user-profile">
    <div class="max-w-4xl mx-auto">
      <!-- Profile Header -->
      <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-8 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
          <!-- Avatar -->
          <div class="relative">
            <div class="w-24 h-24 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
              <span class="text-white text-2xl font-bold">{{ userProfile?.displayName?.charAt(0) || 'U' }}</span>
            </div>
            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-2 border-gray-800"></div>
          </div>

          <!-- Profile Info -->
          <div class="flex-1">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
              <div>
                <h1 class="text-2xl font-bold text-white">{{ userProfile?.displayName || 'User' }}</h1>
                <p class="text-gray-400">@{{ userProfile?.username || 'username' }}</p>
                <p v-if="userProfile?.bio" class="text-gray-300 mt-2">{{ userProfile.bio }}</p>
              </div>
              
              <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <button
                  v-if="!isOwnProfile"
                  @click="toggleFollow"
                  :class="[
                    'px-6 py-2 rounded-lg font-medium transition-colors duration-200',
                    isFollowing
                      ? 'bg-gray-600 hover:bg-gray-700 text-white'
                      : 'bg-primary-500 hover:bg-primary-600 text-white'
                  ]"
                >
                  {{ isFollowing ? 'Following' : 'Follow' }}
                </button>
                <button
                  v-if="isOwnProfile"
                  @click="showEditModal = true"
                  class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors duration-200"
                >
                  Edit Profile
                </button>
              </div>
            </div>

            <!-- Profile Stats -->
            <div class="flex items-center space-x-6 mt-6">
              <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ userProfile?.stats.posts || 0 }}</div>
                <div class="text-gray-400 text-sm">Posts</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ userProfile?.stats.comments || 0 }}</div>
                <div class="text-gray-400 text-sm">Comments</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ userProfile?.stats.likes || 0 }}</div>
                <div class="text-gray-400 text-sm">Likes</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ userProfile?.stats.followers || 0 }}</div>
                <div class="text-gray-400 text-sm">Followers</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ userProfile?.stats.following || 0 }}</div>
                <div class="text-gray-400 text-sm">Following</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ userProfile?.reputation || 0 }}</div>
                <div class="text-gray-400 text-sm">Reputation</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile Details -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-semibold text-white mb-3">Profile Information</h3>
            <div class="space-y-2">
              <div v-if="userProfile?.location" class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-gray-300">{{ userProfile.location }}</span>
              </div>
              <div v-if="userProfile?.website" class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                <a :href="userProfile.website" target="_blank" class="text-primary-400 hover:text-primary-300">
                  {{ userProfile.website }}
                </a>
              </div>
              <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-gray-300">Joined {{ formatDate(userProfile?.joinDate) }}</span>
              </div>
            </div>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-white mb-3">Social Links</h3>
            <div class="flex space-x-4">
              <a
                v-if="userProfile?.socialLinks?.twitter"
                :href="userProfile.socialLinks.twitter"
                target="_blank"
                class="text-gray-400 hover:text-blue-400 transition-colors duration-200"
              >
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
              <a
                v-if="userProfile?.socialLinks?.linkedin"
                :href="userProfile.socialLinks.linkedin"
                target="_blank"
                class="text-gray-400 hover:text-blue-400 transition-colors duration-200"
              >
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
              </a>
              <a
                v-if="userProfile?.socialLinks?.github"
                :href="userProfile.socialLinks.github"
                target="_blank"
                class="text-gray-400 hover:text-gray-300 transition-colors duration-200"
              >
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Badges -->
      <div v-if="userProfile?.badges?.length" class="bg-gray-800/50 border border-gray-700 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Badges</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="badge in userProfile.badges"
            :key="badge.id"
            class="flex items-center space-x-3 p-3 bg-gray-700/30 rounded-lg"
          >
            <div class="text-2xl">{{ badge.icon }}</div>
            <div>
              <div class="text-white font-medium">{{ badge.name }}</div>
              <div class="text-gray-400 text-sm">{{ badge.description }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- User Posts -->
      <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Recent Posts</h3>
        <div v-if="userPosts.length === 0" class="text-center py-8">
          <p class="text-gray-400">No posts yet</p>
        </div>
        <div v-else class="space-y-4">
          <div
            v-for="post in userPosts"
            :key="post.id"
            class="p-4 bg-gray-700/30 rounded-lg hover:bg-gray-700/50 transition-colors duration-200"
          >
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <h4 class="text-white font-medium hover:text-primary-400 cursor-pointer">
                  {{ post.title }}
                </h4>
                <p class="text-gray-400 text-sm mt-1 line-clamp-2">{{ post.content }}</p>
                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-400">
                  <span>{{ formatTime(post.createdAt) }}</span>
                  <span>{{ post.views }} views</span>
                  <span>{{ post.likes }} likes</span>
                  <span>{{ post.comments }} comments</span>
                </div>
              </div>
              <div class="ml-4">
                <span class="text-2xl">{{ post.category.icon }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Profile Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-xl font-semibold text-white">Edit Profile</h3>
              <button
                @click="showEditModal = false"
                class="text-gray-400 hover:text-white"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="updateProfile" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Display Name</label>
                <input
                  v-model="editForm.displayName"
                  type="text"
                  required
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Bio</label>
                <textarea
                  v-model="editForm.bio"
                  rows="3"
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                <input
                  v-model="editForm.location"
                  type="text"
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Website</label>
                <input
                  v-model="editForm.website"
                  type="url"
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>

              <div class="flex justify-end space-x-4">
                <button
                  type="button"
                  @click="showEditModal = false"
                  class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="isUpdating"
                  class="px-6 py-2 bg-primary-500 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition-colors duration-200"
                >
                  <span v-if="isUpdating">Updating...</span>
                  <span v-else>Update Profile</span>
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
import { communityService, type UserProfile, type ForumPost } from '../services/communityService'

// Props
const props = defineProps<{
  userId?: string
}>()

// Reactive data
const userProfile = ref<UserProfile | null>(null)
const userPosts = ref<ForumPost[]>([])
const showEditModal = ref(false)
const isUpdating = ref(false)
const isFollowing = ref(false)

const editForm = ref({
  displayName: '',
  bio: '',
  location: '',
  website: ''
})

// Computed properties
const isOwnProfile = computed(() => {
  // In a real app, this would check against the current user ID
  return props.userId === 'current-user' || !props.userId
})

// Methods
const loadUserProfile = async () => {
  const userId = props.userId || 'current-user'
  userProfile.value = await communityService.getProfile(userId)
  
  if (userProfile.value) {
    editForm.value = {
      displayName: userProfile.value.displayName,
      bio: userProfile.value.bio || '',
      location: userProfile.value.location || '',
      website: userProfile.value.website || ''
    }
  }
}

const loadUserPosts = async () => {
  const userId = props.userId || 'current-user'
  const allPosts = await communityService.getPosts()
  userPosts.value = allPosts.filter(post => post.author.id === userId).slice(0, 5)
}

const updateProfile = async () => {
  if (!userProfile.value) return

  isUpdating.value = true

  try {
    const updatedProfile = await communityService.updateProfile(userProfile.value.id, {
      displayName: editForm.value.displayName,
      bio: editForm.value.bio,
      location: editForm.value.location,
      website: editForm.value.website
    })

    if (updatedProfile) {
      userProfile.value = updatedProfile
      showEditModal.value = false
    }
  } catch (error) {
    console.error('Failed to update profile:', error)
  } finally {
    isUpdating.value = false
  }
}

const toggleFollow = () => {
  isFollowing.value = !isFollowing.value
  // In a real app, this would call the follow/unfollow API
}

const formatDate = (dateString?: string): string => {
  if (!dateString) return 'Unknown'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
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
  await loadUserProfile()
  await loadUserPosts()
})
</script>

<style scoped>
.user-profile {
  @apply p-6;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
