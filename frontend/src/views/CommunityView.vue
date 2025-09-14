<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- Community Header -->
    <div class="bg-gray-800 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold gradient-text">Community</h1>
          </div>
          <div class="flex items-center space-x-4">
            <button
              @click="activeTab = 'forum'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors duration-200',
                activeTab === 'forum'
                  ? 'bg-primary-500 text-white'
                  : 'text-gray-400 hover:text-white hover:bg-gray-700'
              ]"
            >
              Forum
            </button>
            <button
              @click="activeTab = 'profiles'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors duration-200',
                activeTab === 'profiles'
                  ? 'bg-primary-500 text-white'
                  : 'text-gray-400 hover:text-white hover:bg-gray-700'
              ]"
            >
              Profiles
            </button>
            <button
              @click="activeTab = 'leaderboard'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors duration-200',
                activeTab === 'leaderboard'
                  ? 'bg-primary-500 text-white'
                  : 'text-gray-400 hover:text-white hover:bg-gray-700'
              ]"
            >
              Leaderboard
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Community Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Forum Tab -->
      <div v-if="activeTab === 'forum'">
        <CommunityForum />
      </div>

      <!-- Profiles Tab -->
      <div v-if="activeTab === 'profiles'">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-white mb-2">Community Members</h2>
          <p class="text-gray-400">Discover and connect with other community members</p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Search Users</label>
              <input
                v-model="searchQuery"
                @input="searchUsers"
                type="text"
                placeholder="Search by username or name..."
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Sort By</label>
              <select
                v-model="sortBy"
                @change="loadUsers"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              >
                <option value="reputation">Reputation</option>
                <option value="joinDate">Join Date</option>
                <option value="posts">Posts</option>
                <option value="alphabetical">Alphabetical</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Filter</label>
              <select
                v-model="filterBy"
                @change="loadUsers"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              >
                <option value="all">All Users</option>
                <option value="active">Active Users</option>
                <option value="experts">Experts</option>
                <option value="new">New Users</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Users Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="user in filteredUsers"
            :key="user.id"
            class="bg-gray-800/50 border border-gray-700 rounded-lg p-6 hover:bg-gray-800/70 transition-colors duration-200"
          >
            <div class="flex items-center space-x-4 mb-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                <span class="text-white font-bold">{{ user.displayName.charAt(0) }}</span>
              </div>
              <div class="flex-1">
                <h3 class="text-white font-medium">{{ user.displayName }}</h3>
                <p class="text-gray-400 text-sm">@{{ user.username }}</p>
              </div>
            </div>

            <p v-if="user.bio" class="text-gray-300 text-sm mb-4 line-clamp-2">{{ user.bio }}</p>

            <div class="flex items-center justify-between text-sm">
              <div class="flex items-center space-x-4">
                <span class="text-gray-400">{{ user.stats.posts }} posts</span>
                <span class="text-gray-400">{{ user.reputation }} rep</span>
              </div>
              <div class="text-gray-400">
                {{ formatJoinDate(user.joinDate) }}
              </div>
            </div>

            <div v-if="user.badges.length" class="mt-4">
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="badge in user.badges.slice(0, 3)"
                  :key="badge.id"
                  class="text-xs"
                  :title="badge.description"
                >
                  {{ badge.icon }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Leaderboard Tab -->
      <div v-if="activeTab === 'leaderboard'">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-white mb-2">Community Leaderboard</h2>
          <p class="text-gray-400">Top contributors and most active community members</p>
        </div>

        <!-- Leaderboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
            <div class="flex items-center">
              <div class="p-3 bg-yellow-500/20 rounded-lg">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Top Contributor</p>
                <p class="text-xl font-bold text-white">{{ topContributor?.displayName || 'N/A' }}</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
            <div class="flex items-center">
              <div class="p-3 bg-green-500/20 rounded-lg">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Most Active</p>
                <p class="text-xl font-bold text-white">{{ mostActiveUser?.displayName || 'N/A' }}</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
            <div class="flex items-center">
              <div class="p-3 bg-blue-500/20 rounded-lg">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Most Helpful</p>
                <p class="text-xl font-bold text-white">{{ mostHelpfulUser?.displayName || 'N/A' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-700/50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rank</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Reputation</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Posts</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Comments</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Likes</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
                <tr
                  v-for="(user, index) in leaderboardUsers"
                  :key="user.id"
                  class="hover:bg-gray-700/30"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <span
                        class="text-lg font-bold"
                        :class="{
                          'text-yellow-400': index === 0,
                          'text-gray-300': index === 1,
                          'text-orange-400': index === 2,
                          'text-gray-400': index > 2
                        }"
                      >
                        {{ index + 1 }}
                      </span>
                      <span
                        v-if="index < 3"
                        class="ml-2 text-lg"
                      >
                        {{ index === 0 ? '🥇' : index === 1 ? '🥈' : '🥉' }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mr-3">
                        <span class="text-white text-sm font-bold">{{ user.displayName.charAt(0) }}</span>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-white">{{ user.displayName }}</div>
                        <div class="text-sm text-gray-400">@{{ user.username }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                    {{ user.reputation }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                    {{ user.stats.posts }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                    {{ user.stats.comments }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                    {{ user.stats.likes }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { communityService, type UserProfile } from '../services/communityService'
import CommunityForum from '../components/CommunityForum.vue'

// Reactive data
const activeTab = ref<'forum' | 'profiles' | 'leaderboard'>('forum')
const users = ref<UserProfile[]>([])
const searchQuery = ref('')
const sortBy = ref<'reputation' | 'joinDate' | 'posts' | 'alphabetical'>('reputation')
const filterBy = ref<'all' | 'active' | 'experts' | 'new'>('all')

// Computed properties
const filteredUsers = computed(() => {
  let filtered = users.value

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(user =>
      user.username.toLowerCase().includes(query) ||
      user.displayName.toLowerCase().includes(query) ||
      user.bio?.toLowerCase().includes(query)
    )
  }

  // Apply category filter
  if (filterBy.value !== 'all') {
    const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)
    const monthAgo = new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)

    switch (filterBy.value) {
      case 'active':
        filtered = filtered.filter(user => new Date(user.lastActive) > weekAgo)
        break
      case 'experts':
        filtered = filtered.filter(user => user.reputation > 500)
        break
      case 'new':
        filtered = filtered.filter(user => new Date(user.joinDate) > monthAgo)
        break
    }
  }

  // Apply sorting
  switch (sortBy.value) {
    case 'reputation':
      filtered.sort((a, b) => b.reputation - a.reputation)
      break
    case 'joinDate':
      filtered.sort((a, b) => new Date(b.joinDate).getTime() - new Date(a.joinDate).getTime())
      break
    case 'posts':
      filtered.sort((a, b) => b.stats.posts - a.stats.posts)
      break
    case 'alphabetical':
      filtered.sort((a, b) => a.displayName.localeCompare(b.displayName))
      break
  }

  return filtered
})

const leaderboardUsers = computed(() => {
  return [...users.value]
    .sort((a, b) => b.reputation - a.reputation)
    .slice(0, 20)
})

const topContributor = computed(() => {
  return leaderboardUsers.value[0]
})

const mostActiveUser = computed(() => {
  return [...users.value]
    .sort((a, b) => b.stats.posts + b.stats.comments - (a.stats.posts + a.stats.comments))[0]
})

const mostHelpfulUser = computed(() => {
  return [...users.value]
    .sort((a, b) => b.stats.likes - a.stats.likes)[0]
})

// Methods
const loadUsers = async () => {
  // In a real app, this would fetch from API
  // For now, we'll use the profiles from the community service
  const profiles = await communityService.getProfiles()
  users.value = profiles
}

const searchUsers = async () => {
  if (searchQuery.value) {
    const results = await communityService.searchUsers(searchQuery.value)
    users.value = results
  } else {
    await loadUsers()
  }
}

const formatJoinDate = (dateString: string): string => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = now.getTime() - date.getTime()
  
  const days = Math.floor(diff / 86400000)
  const months = Math.floor(days / 30)
  const years = Math.floor(months / 12)
  
  if (years > 0) return `${years}y ago`
  if (months > 0) return `${months}mo ago`
  if (days > 0) return `${days}d ago`
  return 'Today'
}

// Lifecycle
onMounted(async () => {
  await communityService.initializeSampleData()
  await loadUsers()
})
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
