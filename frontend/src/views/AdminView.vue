<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- Admin Header -->
    <div class="bg-gray-800 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold gradient-text">Stargate.ci Admin</h1>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-3">
              <div class="text-right">
                <div class="text-sm text-gray-300">{{ currentUser?.username }}</div>
                <div class="text-xs text-gray-500">{{ currentUser?.role }}</div>
              </div>
              <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-bold">{{ currentUser?.username?.charAt(0).toUpperCase() }}</span>
              </div>
            </div>
            <button @click="handleLogout" class="btn-outline text-sm">
              Logout
            </button>
            <RouterLink to="/" class="btn-outline text-sm">
              Back to Site
            </RouterLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Admin Navigation -->
    <div class="bg-gray-800/50 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-8">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
              activeTab === tab.id
                ? 'border-primary-500 text-primary-400'
                : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'
            ]"
          >
            {{ tab.name }}
          </button>
        </div>
      </div>
    </div>

    <!-- Session Warning -->
    <div v-if="sessionWarning" class="bg-yellow-500/10 border-b border-yellow-500/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-yellow-400 text-sm">Your session will expire in {{ sessionTimeLeft }} minutes</span>
          </div>
          <button @click="refreshSession" class="text-yellow-400 hover:text-yellow-300 text-sm font-medium">
            Extend Session
          </button>
        </div>
      </div>
    </div>

    <!-- Admin Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Content Management -->
      <div v-if="activeTab === 'content'">
        <ContentManagementDashboard />
      </div>

      <!-- Analytics Dashboard -->
      <div v-if="activeTab === 'analytics'">
        <AnalyticsDashboard />
      </div>

      <!-- User Management -->
      <div v-if="activeTab === 'user-management'">
        <UserManagementDashboard />
      </div>

      <!-- Regional Content -->
      <div v-if="activeTab === 'regional-content'">
        <RegionalContentDashboard />
      </div>

      <!-- Mobile App -->
      <div v-if="activeTab === 'mobile-app'">
        <MobileAppDashboard />
      </div>

      <!-- Advanced Content -->
      <div v-if="activeTab === 'advanced-content'">
        <AdvancedContentDashboard />
      </div>

      <!-- Articles Management -->
      <div v-if="activeTab === 'articles'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-3xl font-bold">Articles Management</h2>
          <button @click="showArticleModal = true" class="btn-primary">
            Add New Article
          </button>
        </div>

        <!-- Articles List -->
        <div class="bg-gray-800 rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
              <thead class="bg-gray-900">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                    Title
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                    Category
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                    Created
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-gray-800 divide-y divide-gray-700">
                <tr v-for="article in articles" :key="article.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-white">{{ article.title }}</div>
                    <div class="text-sm text-gray-400">{{ article.excerpt }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                      {{ article.category }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="[
                      'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                      article.published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    ]">
                      {{ article.published ? 'Published' : 'Draft' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                    {{ formatDate(article.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button @click="editArticle(article)" class="text-primary-400 hover:text-primary-300">
                      Edit
                    </button>
                    <button @click="deleteArticle(article.id)" class="text-red-400 hover:text-red-300">
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- FAQs Management -->
      <div v-if="activeTab === 'faqs'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-3xl font-bold">FAQs Management</h2>
          <button @click="showFaqModal = true" class="btn-primary">
            Add New FAQ
          </button>
        </div>

        <!-- FAQs List -->
        <div class="space-y-4">
          <div v-for="faq in faqs" :key="faq.id" class="bg-gray-800 rounded-lg p-6">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <h3 class="text-lg font-medium text-white mb-2">{{ faq.question }}</h3>
                <p class="text-gray-400 mb-4">{{ faq.answer }}</p>
                <div class="flex items-center space-x-4">
                  <span :class="[
                    'px-2 py-1 text-xs rounded-full',
                    faq.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                  ]">
                    {{ faq.active ? 'Active' : 'Inactive' }}
                  </span>
                  <span class="text-xs text-gray-500">Order: {{ faq.order }}</span>
                </div>
              </div>
              <div class="flex space-x-2 ml-4">
                <button @click="editFaq(faq)" class="text-primary-400 hover:text-primary-300">
                  Edit
                </button>
                <button @click="deleteFaq(faq.id)" class="text-red-400 hover:text-red-300">
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Messages -->
      <div v-if="activeTab === 'contacts'" class="space-y-6">
        <h2 class="text-3xl font-bold">Contact Messages</h2>

        <div class="space-y-4">
          <div v-for="contact in contacts" :key="contact.id" class="bg-gray-800 rounded-lg p-6">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <div class="flex items-center space-x-4 mb-2">
                  <h3 class="text-lg font-medium text-white">{{ contact.name }}</h3>
                  <span class="text-sm text-gray-400">{{ contact.email }}</span>
                  <span :class="[
                    'px-2 py-1 text-xs rounded-full',
                    contact.read ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800'
                  ]">
                    {{ contact.read ? 'Read' : 'Unread' }}
                  </span>
                </div>
                <h4 class="text-md font-medium text-gray-300 mb-2">{{ contact.subject }}</h4>
                <p class="text-gray-400 mb-4">{{ contact.message }}</p>
                <div class="text-xs text-gray-500">
                  {{ formatDate(contact.created_at) }}
                </div>
              </div>
              <div class="flex space-x-2 ml-4">
                <button 
                  v-if="!contact.read"
                  @click="markAsRead(contact.id)" 
                  class="text-primary-400 hover:text-primary-300"
                >
                  Mark as Read
                </button>
                <button @click="deleteContact(contact.id)" class="text-red-400 hover:text-red-300">
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Article Modal -->
    <div v-if="showArticleModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg p-6 w-full max-w-4xl max-h-full overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold">{{ editingArticle ? 'Edit Article' : 'Add New Article' }}</h3>
          <button @click="closeArticleModal" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveArticle" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
              <input 
                v-model="articleForm.title" 
                type="text" 
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
              <select 
                v-model="articleForm.category" 
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="AI">AI</option>
                <option value="Cloud">Cloud</option>
                <option value="Computing">Computing</option>
                <option value="Technology">Technology</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Excerpt</label>
            <textarea 
              v-model="articleForm.excerpt" 
              rows="3" 
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
            <textarea 
              v-model="articleForm.content" 
              rows="10" 
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Icon</label>
              <input 
                v-model="articleForm.icon" 
                type="text" 
                placeholder="🚀"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Author</label>
              <input 
                v-model="articleForm.author" 
                type="text" 
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Read Time (minutes)</label>
              <input 
                v-model.number="articleForm.read_time" 
                type="number" 
                min="1"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>
          </div>

          <div class="flex items-center">
            <input 
              v-model="articleForm.published" 
              type="checkbox" 
              id="published"
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            >
            <label for="published" class="ml-2 block text-sm text-gray-300">
              Published
            </label>
          </div>

          <div class="flex justify-end space-x-4 pt-4">
            <button type="button" @click="closeArticleModal" class="btn-outline">
              Cancel
            </button>
            <button type="submit" class="btn-primary">
              {{ editingArticle ? 'Update' : 'Create' }} Article
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- FAQ Modal -->
    <div v-if="showFaqModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold">{{ editingFaq ? 'Edit FAQ' : 'Add New FAQ' }}</h3>
          <button @click="closeFaqModal" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveFaq" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Question</label>
            <input 
              v-model="faqForm.question" 
              type="text" 
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Answer</label>
            <textarea 
              v-model="faqForm.answer" 
              rows="5" 
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            ></textarea>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Order</label>
              <input 
                v-model.number="faqForm.order" 
                type="number" 
                min="0"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>
            <div class="flex items-center mt-8">
              <input 
                v-model="faqForm.active" 
                type="checkbox" 
                id="active"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
              >
              <label for="active" class="ml-2 block text-sm text-gray-300">
                Active
              </label>
            </div>
          </div>

          <div class="flex justify-end space-x-4 pt-4">
            <button type="button" @click="closeFaqModal" class="btn-outline">
              Cancel
            </button>
            <button type="submit" class="btn-primary">
              {{ editingFaq ? 'Update' : 'Create' }} FAQ
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { authService, type AdminUser } from '../services/authService'
import ContentManagementDashboard from '../components/ContentManagementDashboard.vue'
import AnalyticsDashboard from '../components/AnalyticsDashboard.vue'
import UserManagementDashboard from '../components/UserManagementDashboard.vue'
import RegionalContentDashboard from '../components/RegionalContentDashboard.vue'
import MobileAppDashboard from '../components/MobileAppDashboard.vue'
import AdvancedContentDashboard from '../components/AdvancedContentDashboard.vue'

const router = useRouter()

// Authentication
const currentUser = ref<AdminUser | null>(null)
const sessionWarning = ref(false)
const sessionTimeLeft = ref(0)
let sessionTimer: number | null = null

// Tab Management
const activeTab = ref('content')
const tabs = [
  { id: 'content', name: 'Content Management' },
  { id: 'analytics', name: 'Analytics' },
  { id: 'user-management', name: 'User Management' },
  { id: 'regional-content', name: 'Regional Content' },
  { id: 'mobile-app', name: 'Mobile App' },
  { id: 'advanced-content', name: 'Advanced Content' },
  { id: 'articles', name: 'Articles' },
  { id: 'faqs', name: 'FAQs' },
  { id: 'contacts', name: 'Contact Messages' }
]

// Data
const articles = ref<any[]>([])
const faqs = ref<any[]>([])
const contacts = ref<any[]>([])

// Modals
const showArticleModal = ref(false)
const showFaqModal = ref(false)
const editingArticle = ref<any>(null)
const editingFaq = ref<any>(null)

// Forms
const articleForm = ref({
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  category: 'AI',
  icon: '🚀',
  author: 'Stargate Team',
  read_time: 5,
  published: true
})

const faqForm = ref({
  question: '',
  answer: '',
  order: 1,
  active: true
})

// API Base URL
const API_BASE = 'http://localhost:8000/api/v1'

// Utility Functions
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const generateSlug = (title: string) => {
  return title
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '')
}

// API Functions
const fetchArticles = async () => {
  try {
    const response = await fetch(`${API_BASE}/articles`)
    const data = await response.json()
    articles.value = data.data || []
  } catch (error) {
    console.error('Error fetching articles:', error)
  }
}

const fetchFaqs = async () => {
  try {
    const response = await fetch(`${API_BASE}/faqs`)
    const data = await response.json()
    faqs.value = data || []
  } catch (error) {
    console.error('Error fetching FAQs:', error)
  }
}

const fetchContacts = async () => {
  // Simulate contact data since we don't have admin endpoints yet
  contacts.value = [
    {
      id: 1,
      name: 'Test User',
      email: 'test@example.com',
      subject: 'Test Message',
      message: 'This is a test message to verify the contact endpoint.',
      read: false,
      created_at: new Date().toISOString()
    }
  ]
}

// Article Functions
const editArticle = (article: any) => {
  editingArticle.value = article
  articleForm.value = { ...article }
  showArticleModal.value = true
}

const saveArticle = async () => {
  try {
    if (!articleForm.value.slug) {
      articleForm.value.slug = generateSlug(articleForm.value.title)
    }

    const method = editingArticle.value ? 'PUT' : 'POST'
    const url = editingArticle.value 
      ? `${API_BASE}/admin/articles/${editingArticle.value.id}` 
      : `${API_BASE}/admin/articles`

    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(articleForm.value)
    })

    if (response.ok) {
      await fetchArticles()
      closeArticleModal()
    } else {
      console.error('Error saving article:', await response.text())
    }
  } catch (error) {
    console.error('Error saving article:', error)
  }
}

const deleteArticle = async (id: number) => {
  if (confirm('Are you sure you want to delete this article?')) {
    try {
      const response = await fetch(`${API_BASE}/admin/articles/${id}`, {
        method: 'DELETE'
      })

      if (response.ok) {
        await fetchArticles()
      } else {
        console.error('Error deleting article')
      }
    } catch (error) {
      console.error('Error deleting article:', error)
    }
  }
}

const closeArticleModal = () => {
  showArticleModal.value = false
  editingArticle.value = null
  articleForm.value = {
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    category: 'AI',
    icon: '🚀',
    author: 'Stargate Team',
    read_time: 5,
    published: true
  }
}

// FAQ Functions
const editFaq = (faq: any) => {
  editingFaq.value = faq
  faqForm.value = { ...faq }
  showFaqModal.value = true
}

const saveFaq = async () => {
  try {
    const method = editingFaq.value ? 'PUT' : 'POST'
    const url = editingFaq.value 
      ? `${API_BASE}/admin/faqs/${editingFaq.value.id}` 
      : `${API_BASE}/admin/faqs`

    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(faqForm.value)
    })

    if (response.ok) {
      await fetchFaqs()
      closeFaqModal()
    } else {
      console.error('Error saving FAQ:', await response.text())
    }
  } catch (error) {
    console.error('Error saving FAQ:', error)
  }
}

const deleteFaq = async (id: number) => {
  if (confirm('Are you sure you want to delete this FAQ?')) {
    try {
      const response = await fetch(`${API_BASE}/admin/faqs/${id}`, {
        method: 'DELETE'
      })

      if (response.ok) {
        await fetchFaqs()
      } else {
        console.error('Error deleting FAQ')
      }
    } catch (error) {
      console.error('Error deleting FAQ:', error)
    }
  }
}

const closeFaqModal = () => {
  showFaqModal.value = false
  editingFaq.value = null
  faqForm.value = {
    question: '',
    answer: '',
    order: 1,
    active: true
  }
}

// Contact Functions
const markAsRead = async (id: number) => {
  // Simulate marking as read
  const contact = contacts.value.find(c => c.id === id)
  if (contact) {
    contact.read = true
  }
}

const deleteContact = async (id: number) => {
  if (confirm('Are you sure you want to delete this contact message?')) {
    contacts.value = contacts.value.filter(c => c.id !== id)
  }
}

// Authentication Functions
const handleLogout = () => {
  authService.logout()
  router.push('/admin/login')
}

const checkAuthentication = () => {
  const user = authService.getCurrentAdminUser()
  if (!user) {
    router.push('/admin/login')
    return false
  }
  currentUser.value = user
  startSessionTimer()
  return true
}

const startSessionTimer = () => {
  if (sessionTimer) {
    window.clearInterval(sessionTimer)
  }
  
  sessionTimer = window.setInterval(() => {
    const sessionInfo = authService.getSessionInfo()
    if (!sessionInfo) {
      handleLogout()
      return
    }
    
    const minutesLeft = Math.floor(sessionInfo.expiresIn / (1000 * 60))
    sessionTimeLeft.value = minutesLeft
    
    // Show warning when 5 minutes left
    sessionWarning.value = minutesLeft <= 5 && minutesLeft > 0
    
    // Auto logout when session expires
    if (minutesLeft <= 0) {
      handleLogout()
    }
  }, 1000)
}

const refreshSession = () => {
  if (authService.refreshSession()) {
    sessionWarning.value = false
    startSessionTimer()
  } else {
    handleLogout()
  }
}

// Initialize
onMounted(() => {
  if (checkAuthentication()) {
    fetchArticles()
    fetchFaqs()
    fetchContacts()
  }
})

// Cleanup
onUnmounted(() => {
  if (sessionTimer) {
    window.clearInterval(sessionTimer)
  }
})
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-4 py-2 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg hover:shadow-xl;
}

.btn-outline {
  @apply border border-gray-600 text-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-700 hover:border-gray-500 transition-all duration-200;
}
</style>
