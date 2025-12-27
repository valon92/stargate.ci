<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center">
          <div class="inline-block mb-6">
            <div class="w-24 h-24 bg-black dark:bg-gray-700 rounded-full flex items-center justify-center text-white text-4xl font-bold">
              {{ profileInitials }}
            </div>
          </div>
          <h1 class="text-4xl md:text-5xl font-bold mb-4 text-black dark:text-white">
            {{ profileData?.name || 'Profile' }}
          </h1>
          <p class="text-lg text-gray-700 dark:text-gray-300">
            {{ profileData?.email }}
          </p>
          <p v-if="profileData?.profession" class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            {{ profileData.profession }}<span v-if="profileData.company"> at {{ profileData.company }}</span>
          </p>
        </div>
      </div>
    </section>

    <!-- Profile Content -->
    <section class="py-12 bg-gray-50 dark:bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Comments</p>
                    <p class="text-2xl font-bold text-black dark:text-white mt-1">
                      {{ stats?.total_comments || 0 }}
                    </p>
                  </div>
                  <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Likes</p>
                    <p class="text-2xl font-bold text-black dark:text-white mt-1">
                      {{ stats?.total_likes || 0 }}
                    </p>
                  </div>
                  <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Shares</p>
                    <p class="text-2xl font-bold text-black dark:text-white mt-1">
                      {{ stats?.total_shares || 0 }}
                    </p>
                  </div>
                  <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Activity</p>
                    <p class="text-sm font-medium text-black dark:text-white mt-1">
                      {{ lastActivityText }}
                    </p>
                  </div>
                  <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Profile Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-black dark:text-white">Profile Information</h2>
                <button
                  @click="toggleEditMode"
                  class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors text-sm font-medium"
                >
                  {{ isEditing ? 'Cancel' : 'Edit Profile' }}
                </button>
              </div>

              <div v-if="!isEditing" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Full Name</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData?.name || 'Not set' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData?.email || 'Not set' }}</p>
                  </div>
                  <div v-if="profileData?.first_name">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">First Name</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData.first_name }}</p>
                  </div>
                  <div v-if="profileData?.last_name">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Name</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData.last_name }}</p>
                  </div>
                  <div v-if="profileData?.country">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Country</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData.country }}</p>
                  </div>
                  <div v-if="profileData?.profession">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Profession</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData.profession }}</p>
                  </div>
                  <div v-if="profileData?.company">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Company</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData.company }}</p>
                  </div>
                  <div v-if="profileData?.username">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Username</label>
                    <p class="text-black dark:text-white mt-1">{{ profileData.username }}</p>
                  </div>
                </div>
                <div v-if="profileData?.bio">
                  <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Bio</label>
                  <p class="text-black dark:text-white mt-1">{{ profileData.bio }}</p>
                </div>
                <div v-if="profileData?.interests && profileData.interests.length > 0">
                  <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Interests</label>
                  <div class="flex flex-wrap gap-2 mt-2">
                    <span
                      v-for="interest in profileData.interests"
                      :key="interest"
                      class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
                    >
                      {{ interest }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Edit Form -->
              <form v-else @submit.prevent="saveProfile" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">Full Name *</label>
                    <input
                      v-model="editForm.name"
                      type="text"
                      required
                      class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">First Name</label>
                    <input
                      v-model="editForm.first_name"
                      type="text"
                      class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">Last Name</label>
                    <input
                      v-model="editForm.last_name"
                      type="text"
                      class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">Country</label>
                    <input
                      v-model="editForm.country"
                      type="text"
                      class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">Profession</label>
                    <input
                      v-model="editForm.profession"
                      type="text"
                      class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">Company</label>
                    <input
                      v-model="editForm.company"
                      type="text"
                      class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-black dark:text-white mb-2">Bio</label>
                  <textarea
                    v-model="editForm.bio"
                    rows="4"
                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Tell us about yourself..."
                  ></textarea>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <input
                      v-model="editForm.email_notifications"
                      type="checkbox"
                      id="email-notifications"
                      class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500"
                    />
                    <label for="email-notifications" class="ml-2 text-sm text-black dark:text-white">
                      Email Notifications
                    </label>
                  </div>
                  <div class="flex gap-3">
                    <button
                      type="button"
                      @click="toggleEditMode"
                      class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      :disabled="isSaving"
                      class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50"
                    >
                      {{ isSaving ? 'Saving...' : 'Save Changes' }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-bold text-black dark:text-white mb-4">Quick Actions</h3>
              <div class="space-y-2">
                <RouterLink
                  to="/settings"
                  class="block px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium"
                >
                  ⚙️ Settings
                </RouterLink>
                <RouterLink
                  to="/news"
                  class="block px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium"
                >
                  📰 News
                </RouterLink>
                <RouterLink
                  to="/events"
                  class="block px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium"
                >
                  📅 Events
                </RouterLink>
                <RouterLink
                  to="/faq"
                  class="block px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium"
                >
                  ❓ FAQ
                </RouterLink>
              </div>
            </div>

            <!-- Account Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-bold text-black dark:text-white mb-4">Account Information</h3>
              <div class="space-y-3 text-sm">
                <div>
                  <p class="text-gray-600 dark:text-gray-400">Member since</p>
                  <p class="text-black dark:text-white font-medium">
                    {{ memberSince }}
                  </p>
                </div>
                <div>
                  <p class="text-gray-600 dark:text-gray-400">Last updated</p>
                  <p class="text-black dark:text-white font-medium">
                    {{ lastUpdated }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Success/Error Messages -->
    <div
      v-if="showMessage"
      class="fixed bottom-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg"
      :class="messageType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
    >
      {{ messageText }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { profileService, type ProfileData, type UpdateProfileRequest } from '../services/profileService'

const router = useRouter()
const authStore = useAuthStore()

const profileData = ref<ProfileData | null>(null)
const stats = ref(profileData.value?.stats)
const isEditing = ref(false)
const isSaving = ref(false)
const showMessage = ref(false)
const messageText = ref('')
const messageType = ref<'success' | 'error'>('success')

const editForm = ref<UpdateProfileRequest>({
  name: '',
  first_name: '',
  last_name: '',
  country: '',
  profession: '',
  company: '',
  bio: '',
  email_notifications: true
})

const profileInitials = computed(() => {
  if (!profileData.value?.name) return 'U'
  const names = profileData.value.name.split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase()
  }
  return profileData.value.name.substring(0, 2).toUpperCase()
})

const lastActivityText = computed(() => {
  if (!stats.value?.last_activity) return 'Never'
  const date = new Date(stats.value.last_activity)
  const now = new Date()
  const diff = now.getTime() - date.getTime()
  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  
  if (days === 0) return 'Today'
  if (days === 1) return 'Yesterday'
  if (days < 7) return `${days} days ago`
  if (days < 30) return `${Math.floor(days / 7)} weeks ago`
  return `${Math.floor(days / 30)} months ago`
})

const memberSince = computed(() => {
  if (!profileData.value?.created_at) return 'N/A'
  return new Date(profileData.value.created_at).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const lastUpdated = computed(() => {
  if (!profileData.value?.updated_at) return 'N/A'
  return new Date(profileData.value.updated_at).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const loadProfile = async () => {
  try {
    const response = await profileService.getProfile()
    if (response.success) {
      profileData.value = response.data
      stats.value = response.data.stats
    }
  } catch (error: any) {
    console.error('Failed to load profile:', error)
    showMessageToast('Failed to load profile', 'error')
  }
}

const toggleEditMode = () => {
  if (isEditing.value) {
    // Cancel editing - reset form
    resetEditForm()
  } else {
    // Start editing - populate form
    if (profileData.value) {
      editForm.value = {
        name: profileData.value.name || '',
        first_name: profileData.value.first_name || '',
        last_name: profileData.value.last_name || '',
        country: profileData.value.country || '',
        profession: profileData.value.profession || '',
        company: profileData.value.company || '',
        bio: profileData.value.bio || '',
        email_notifications: profileData.value.email_notifications ?? true
      }
    }
  }
  isEditing.value = !isEditing.value
}

const resetEditForm = () => {
  editForm.value = {
    name: '',
    first_name: '',
    last_name: '',
    country: '',
    profession: '',
    company: '',
    bio: '',
    email_notifications: true
  }
}

const saveProfile = async () => {
  isSaving.value = true
  try {
    const response = await profileService.updateProfile(editForm.value)
    if (response.success) {
      profileData.value = response.data
      stats.value = response.data.stats
      isEditing.value = false
      showMessageToast('Profile updated successfully', 'success')
      
      // Update auth store if name changed
      if (authStore.user && response.data.name) {
        authStore.updateUser({
          ...authStore.user,
          name: response.data.name
        })
      }
    }
  } catch (error: any) {
    console.error('Failed to update profile:', error)
    showMessageToast(error.message || 'Failed to update profile', 'error')
  } finally {
    isSaving.value = false
  }
}

const showMessageToast = (message: string, type: 'success' | 'error') => {
  messageText.value = message
  messageType.value = type
  showMessage.value = true
  setTimeout(() => {
    showMessage.value = false
  }, 3000)
}

onMounted(async () => {
  // Check if user is authenticated
  if (!authStore.isAuthenticated) {
    router.push('/signin')
    return
  }
  
  await loadProfile()
})
</script>

<style scoped>
.gradient-text {
  @apply text-black dark:text-white;
}
</style>

