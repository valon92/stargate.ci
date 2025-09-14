<template>
  <div class="content-management-dashboard">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-white mb-2">Content Management</h2>
            <p class="text-gray-400">Manage articles, tutorials, announcements, and more</p>
          </div>
          <button
            @click="showCreateModal = true"
            class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition-colors duration-200"
          >
            Create Content
          </button>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-500/20 rounded-lg">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Total Content</p>
              <p class="text-2xl font-bold text-white">{{ stats.total }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-500/20 rounded-lg">
              <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Published</p>
              <p class="text-2xl font-bold text-white">{{ stats.published }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-500/20 rounded-lg">
              <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Drafts</p>
              <p class="text-2xl font-bold text-white">{{ stats.draft }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-500/20 rounded-lg">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Total Views</p>
              <p class="text-2xl font-bold text-white">{{ totalViews }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
            <select
              v-model="filters.type"
              @change="applyFilters"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
              <option value="">All Types</option>
              <option value="article">Article</option>
              <option value="tutorial">Tutorial</option>
              <option value="announcement">Announcement</option>
              <option value="news">News</option>
              <option value="faq">FAQ</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
              <option value="">All Status</option>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
              <option value="archived">Archived</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
            <select
              v-model="filters.category"
              @change="applyFilters"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category" :value="category">
                {{ category }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Search</label>
            <input
              v-model="filters.search"
              @input="applyFilters"
              type="text"
              placeholder="Search content..."
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
        </div>
      </div>

      <!-- Content List -->
      <div class="bg-gray-800/50 border border-gray-700 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-700/50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Author</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Views</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
              <tr v-for="item in filteredContent" :key="item.id" class="hover:bg-gray-700/30">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div>
                      <div class="text-sm font-medium text-white">{{ item.title }}</div>
                      <div class="text-sm text-gray-400">{{ item.excerpt }}</div>
                    </div>
                    <div v-if="item.featured" class="ml-2">
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-900/20 text-yellow-400">
                        Featured
                      </span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                        :class="getTypeColor(item.type)">
                    {{ item.type }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                        :class="getStatusColor(item.status)">
                    {{ item.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-300">{{ item.author }}</td>
                <td class="px-6 py-4 text-sm text-gray-300">{{ item.views }}</td>
                <td class="px-6 py-4 text-sm text-gray-300">
                  {{ formatDate(item.createdAt) }}
                </td>
                <td class="px-6 py-4 text-sm font-medium">
                  <div class="flex space-x-2">
                    <button
                      @click="editContent(item)"
                      class="text-primary-400 hover:text-primary-300"
                    >
                      Edit
                    </button>
                    <button
                      @click="toggleStatus(item)"
                      class="text-green-400 hover:text-green-300"
                    >
                      {{ item.status === 'published' ? 'Archive' : 'Publish' }}
                    </button>
                    <button
                      @click="deleteContent(item.id)"
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

      <!-- Create/Edit Modal -->
      <div v-if="showCreateModal || editingContent" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-xl font-semibold text-white">
                {{ editingContent ? 'Edit Content' : 'Create New Content' }}
              </h3>
              <button
                @click="closeModal"
                class="text-gray-400 hover:text-white"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="saveContent" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                  <input
                    v-model="contentForm.title"
                    type="text"
                    required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
                  <select
                    v-model="contentForm.type"
                    required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  >
                    <option value="article">Article</option>
                    <option value="tutorial">Tutorial</option>
                    <option value="announcement">Announcement</option>
                    <option value="news">News</option>
                    <option value="faq">FAQ</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                  <input
                    v-model="contentForm.category"
                    type="text"
                    required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Author</label>
                  <input
                    v-model="contentForm.author"
                    type="text"
                    required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Excerpt</label>
                <textarea
                  v-model="contentForm.excerpt"
                  rows="3"
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
                <textarea
                  v-model="contentForm.content"
                  rows="10"
                  required
                  class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                ></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Tags (comma-separated)</label>
                  <input
                    v-model="tagsInput"
                    type="text"
                    placeholder="tag1, tag2, tag3"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  />
                </div>

                <div class="flex items-center space-x-4">
                  <label class="flex items-center">
                    <input
                      v-model="contentForm.featured"
                      type="checkbox"
                      class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                    />
                    <span class="ml-2 text-sm text-gray-300">Featured</span>
                  </label>

                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Priority</label>
                    <input
                      v-model.number="contentForm.priority"
                      type="number"
                      min="1"
                      max="10"
                      class="w-20 px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>

              <div class="flex justify-end space-x-4">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors duration-200"
                >
                  {{ editingContent ? 'Update' : 'Create' }}
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
import { contentManagement, type ContentItem, type ContentFilter, type ContentStats } from '../services/contentManagement'

// Reactive data
const stats = ref<ContentStats>({
  total: 0,
  published: 0,
  draft: 0,
  archived: 0,
  byType: {},
  byCategory: {},
  byAuthor: {},
  popular: [],
  recent: []
})

const allContent = ref<ContentItem[]>([])
const filteredContent = ref<ContentItem[]>([])
const categories = ref<string[]>([])

const filters = ref<ContentFilter>({
  type: '',
  status: '',
  category: '',
  search: ''
})

const showCreateModal = ref(false)
const editingContent = ref<ContentItem | null>(null)

const contentForm = ref({
  title: '',
  type: 'article' as 'article' | 'tutorial' | 'announcement' | 'news' | 'faq',
  category: '',
  author: '',
  excerpt: '',
  content: '',
  featured: false,
  priority: 5
})

const tagsInput = ref('')

// Computed properties
const totalViews = computed(() => {
  return allContent.value.reduce((sum, item) => sum + item.views, 0)
})

// Methods
const loadContent = () => {
  allContent.value = contentManagement.getAllContent()
  filteredContent.value = allContent.value
  stats.value = contentManagement.getContentStats()
  categories.value = contentManagement.getCategories()
}

const applyFilters = () => {
  filteredContent.value = contentManagement.filterContent(filters.value)
}

const getTypeColor = (type: string) => {
  const colors: Record<string, string> = {
    article: 'bg-blue-900/20 text-blue-400',
    tutorial: 'bg-green-900/20 text-green-400',
    announcement: 'bg-yellow-900/20 text-yellow-400',
    news: 'bg-purple-900/20 text-purple-400',
    faq: 'bg-gray-900/20 text-gray-400'
  }
  return colors[type] || 'bg-gray-900/20 text-gray-400'
}

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    published: 'bg-green-900/20 text-green-400',
    draft: 'bg-yellow-900/20 text-yellow-400',
    archived: 'bg-gray-900/20 text-gray-400'
  }
  return colors[status] || 'bg-gray-900/20 text-gray-400'
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const editContent = (item: ContentItem) => {
  editingContent.value = item
  contentForm.value = {
    title: item.title,
    type: item.type,
    category: item.category,
    author: item.author,
    excerpt: item.excerpt || '',
    content: item.content,
    featured: item.featured,
    priority: item.priority
  }
  tagsInput.value = item.tags.join(', ')
}

const toggleStatus = (item: ContentItem) => {
  const newStatus = item.status === 'published' ? 'archived' : 'published'
  contentManagement.updateContent(item.id, { status: newStatus })
  loadContent()
}

const deleteContent = (id: string) => {
  if (confirm('Are you sure you want to delete this content?')) {
    contentManagement.deleteContent(id)
    loadContent()
  }
}

const saveContent = () => {
  const tags = tagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag)
  
  if (editingContent.value) {
    // Update existing content
    contentManagement.updateContent(editingContent.value.id, {
      ...contentForm.value,
      tags,
      status: editingContent.value.status
    })
  } else {
    // Create new content
    contentManagement.createContent({
      ...contentForm.value,
      tags,
      status: 'draft'
    })
  }
  
  closeModal()
  loadContent()
}

const closeModal = () => {
  showCreateModal.value = false
  editingContent.value = null
  contentForm.value = {
    title: '',
    type: 'article',
    category: '',
    author: '',
    excerpt: '',
    content: '',
    featured: false,
    priority: 5
  }
  tagsInput.value = ''
}

// Lifecycle
onMounted(() => {
  // Initialize sample data if needed
  contentManagement.initializeSampleData()
  loadContent()
})
</script>

<style scoped>
.content-management-dashboard {
  @apply p-6;
}
</style>
