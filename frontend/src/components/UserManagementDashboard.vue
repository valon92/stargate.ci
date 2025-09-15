<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">User Management</h1>
      <p class="text-gray-300">Manage users, roles, and permissions across the platform</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-blue-600/20 rounded-lg">
            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-300">Total Users</p>
            <p class="text-2xl font-bold text-white">{{ userStats.totalUsers }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-green-600/20 rounded-lg">
            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-300">Active Users</p>
            <p class="text-2xl font-bold text-white">{{ userStats.activeUsers }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-purple-600/20 rounded-lg">
            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-300">Recent Signups</p>
            <p class="text-2xl font-bold text-white">{{ userStats.recentSignups }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-orange-600/20 rounded-lg">
            <svg class="h-6 w-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-300">Roles</p>
            <p class="text-2xl font-bold text-white">{{ roles.length }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <nav class="flex space-x-8">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'py-2 px-1 border-b-2 font-medium text-sm transition-colors',
            activeTab === tab.id
              ? 'border-primary-500 text-primary-400'
              : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'
          ]"
        >
          {{ tab.name }}
        </button>
      </nav>
    </div>

    <!-- Users Tab -->
    <div v-if="activeTab === 'users'" class="space-y-6">
      <!-- Users Header -->
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Users</h2>
        <div class="flex space-x-3">
          <button
            @click="showInviteModal = true"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
          >
            Invite User
          </button>
          <button
            @click="showCreateUserModal = true"
            class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors"
          >
            Create User
          </button>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-600">
            <thead class="bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Last Login</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-600">
              <tr v-for="user in users" :key="user.id" class="hover:bg-gray-700/50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">
                          {{ user.firstName.charAt(0) }}{{ user.lastName.charAt(0) }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-white">
                        {{ user.firstName }} {{ user.lastName }}
                      </div>
                      <div class="text-sm text-gray-400">{{ user.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-500/20 text-primary-300">
                    {{ user.role?.name || 'No Role' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                      user.isActive ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'
                    ]"
                  >
                    {{ user.isActive ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                  {{ user.lastLoginAt ? formatDate(user.lastLoginAt) : 'Never' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <button
                      @click="editUser(user)"
                      class="text-primary-400 hover:text-primary-300"
                    >
                      Edit
                    </button>
                    <button
                      @click="viewUserActivity(user.id)"
                      class="text-blue-400 hover:text-blue-300"
                    >
                      Activity
                    </button>
                    <button
                      @click="deleteUser(user.id)"
                      class="text-red-400 hover:text-red-300"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Roles Tab -->
    <div v-if="activeTab === 'roles'" class="space-y-6">
      <!-- Roles Header -->
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Roles & Permissions</h2>
        <button
          @click="showCreateRoleModal = true"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
        >
          Create Role
        </button>
      </div>

      <!-- Roles Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="role in roles"
          :key="role.id"
          class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6"
        >
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">{{ role.name }}</h3>
            <div class="flex space-x-2">
              <button
                @click="editRole(role)"
                class="text-primary-400 hover:text-primary-300 text-sm"
              >
                Edit
              </button>
              <button
                v-if="!role.isSystemRole"
                @click="deleteRole(role.id)"
                class="text-red-400 hover:text-red-300 text-sm"
              >
                Delete
              </button>
            </div>
          </div>
          
          <p class="text-gray-300 text-sm mb-4">{{ role.description }}</p>
          
          <div class="space-y-2">
            <h4 class="text-sm font-medium text-gray-300">Permissions ({{ role.permissions.length }})</h4>
            <div class="flex flex-wrap gap-1">
              <span
                v-for="permission in role.permissions.slice(0, 5)"
                :key="permission.id"
                class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-700 text-gray-300"
              >
                {{ permission.name }}
              </span>
              <span
                v-if="role.permissions.length > 5"
                class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-700 text-gray-400"
              >
                +{{ role.permissions.length - 5 }} more
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity Tab -->
    <div v-if="activeTab === 'activity'" class="space-y-6">
      <!-- Activity Header -->
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">User Activity</h2>
        <div class="flex space-x-3">
          <select
            v-model="activityFilter"
            class="px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm"
          >
            <option value="all">All Activities</option>
            <option value="user">User Management</option>
            <option value="content">Content</option>
            <option value="billing">Billing</option>
          </select>
        </div>
      </div>

      <!-- Activity List -->
      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50">
        <div class="divide-y divide-gray-600">
          <div
            v-for="activity in filteredActivities"
            :key="activity.id"
            class="p-6 hover:bg-gray-700/30 transition-colors"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="text-sm font-medium text-white">
                    {{ getActivityDescription(activity) }}
                  </p>
                  <p class="text-sm text-gray-400">
                    {{ formatDate(activity.createdAt) }}
                  </p>
                </div>
              </div>
              <div class="text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                  {{ activity.resource }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <!-- Create User Modal -->
    <div v-if="showCreateUserModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCreateUserModal = false"></div>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Create User</h3>
              <button @click="showCreateUserModal = false" class="text-gray-400 hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <form @submit.prevent="createUser">
              <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">First Name</label>
                    <input
                      v-model="newUser.firstName"
                      type="text"
                      required
                      class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Last Name</label>
                    <input
                      v-model="newUser.lastName"
                      type="text"
                      required
                      class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                    />
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                  <input
                    v-model="newUser.email"
                    type="email"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                  <input
                    v-model="newUser.username"
                    type="text"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Role</label>
                  <select
                    v-model="newUser.roleId"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                  >
                    <option value="">Select a role</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">
                      {{ role.name }}
                    </option>
                  </select>
                </div>
              </div>
              
              <div class="mt-6 flex justify-end space-x-3">
                <button
                  type="button"
                  @click="showCreateUserModal = false"
                  class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="isProcessing"
                  class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
                >
                  <span v-if="isProcessing">Creating...</span>
                  <span v-else>Create User</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Invite User Modal -->
    <div v-if="showInviteModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showInviteModal = false"></div>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Invite User</h3>
              <button @click="showInviteModal = false" class="text-gray-400 hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <form @submit.prevent="inviteUser">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                  <input
                    v-model="inviteData.email"
                    type="email"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Role</label>
                  <select
                    v-model="inviteData.roleId"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                  >
                    <option value="">Select a role</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">
                      {{ role.name }}
                    </option>
                  </select>
                </div>
              </div>
              
              <div class="mt-6 flex justify-end space-x-3">
                <button
                  type="button"
                  @click="showInviteModal = false"
                  class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="isProcessing"
                  class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
                >
                  <span v-if="isProcessing">Sending...</span>
                  <span v-else>Send Invitation</span>
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
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { 
  userManagementService, 
  type UserProfile, 
  type UserRole, 
  type UserActivity 
} from '../services/userManagementService'

const { t } = useI18n()

// Reactive data
const users = ref<UserProfile[]>([])
const roles = ref<UserRole[]>([])
const activities = ref<UserActivity[]>([])
const userStats = ref({
  totalUsers: 0,
  activeUsers: 0,
  usersByRole: {} as Record<string, number>,
  recentSignups: 0
})

// UI state
const activeTab = ref('users')
const showCreateUserModal = ref(false)
const showInviteModal = ref(false)
const showCreateRoleModal = ref(false)
const isProcessing = ref(false)
const activityFilter = ref('all')

// Form data
const newUser = ref({
  firstName: '',
  lastName: '',
  email: '',
  username: '',
  roleId: ''
})

const inviteData = ref({
  email: '',
  roleId: ''
})

// Computed
const tabs = [
  { id: 'users', name: 'Users' },
  { id: 'roles', name: 'Roles & Permissions' },
  { id: 'activity', name: 'Activity Log' }
]

const filteredActivities = computed(() => {
  if (activityFilter.value === 'all') return activities.value
  return activities.value.filter(activity => 
    activity.resource === activityFilter.value
  )
})

// Methods
const loadData = async () => {
  try {
    users.value = await userManagementService.getUsers()
    roles.value = await userManagementService.getRoles()
    activities.value = await userManagementService.getAllActivities(50)
    userStats.value = await userManagementService.getUserStats()
  } catch (error) {
    console.error('Error loading data:', error)
  }
}

const createUser = async () => {
  try {
    isProcessing.value = true
    
    const userData = {
      ...newUser.value,
      isActive: true,
      isVerified: false,
      preferences: {
        language: 'en',
        timezone: 'UTC',
        notifications: {
          email: true,
          push: true,
          sms: false
        },
        privacy: {
          profileVisibility: 'public' as const,
          showEmail: false,
          showActivity: true
        },
        theme: 'dark' as const
      }
    }

    await userManagementService.createUser(userData)
    
    // Reset form
    newUser.value = {
      firstName: '',
      lastName: '',
      email: '',
      username: '',
      roleId: ''
    }

    showCreateUserModal.value = false
    await loadData()
  } catch (error) {
    console.error('Error creating user:', error)
  } finally {
    isProcessing.value = false
  }
}

const inviteUser = async () => {
  try {
    isProcessing.value = true
    
    await userManagementService.inviteUser(inviteData.value.email, inviteData.value.roleId)
    
    // Reset form
    inviteData.value = {
      email: '',
      roleId: ''
    }

    showInviteModal.value = false
    await loadData()
  } catch (error) {
    console.error('Error inviting user:', error)
  } finally {
    isProcessing.value = false
  }
}

const editUser = (user: UserProfile) => {
  // TODO: Implement edit user functionality
  console.log('Edit user:', user)
}

const deleteUser = async (userId: string) => {
  if (confirm('Are you sure you want to delete this user?')) {
    try {
      await userManagementService.deleteUser(userId)
      await loadData()
    } catch (error) {
      console.error('Error deleting user:', error)
    }
  }
}

const editRole = (role: UserRole) => {
  // TODO: Implement edit role functionality
  console.log('Edit role:', role)
}

const deleteRole = async (roleId: string) => {
  if (confirm('Are you sure you want to delete this role?')) {
    try {
      await userManagementService.deleteRole(roleId)
      await loadData()
    } catch (error) {
      console.error('Error deleting role:', error)
    }
  }
}

const viewUserActivity = (userId: string) => {
  // TODO: Implement view user activity functionality
  console.log('View user activity:', userId)
}

const getActivityDescription = (activity: UserActivity): string => {
  const user = users.value.find(u => u.id === activity.userId)
  const userName = user ? `${user.firstName} ${user.lastName}` : 'Unknown User'
  
  switch (activity.action) {
    case 'user.create':
      return `${userName} created a new user`
    case 'user.update':
      return `${userName} updated user information`
    case 'user.delete':
      return `${userName} deleted a user`
    case 'user.manage_roles':
      return `${userName} changed user role`
    default:
      return `${userName} performed ${activity.action} on ${activity.resource}`
  }
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString()
}

// Lifecycle
onMounted(() => {
  loadData()
})
</script>
