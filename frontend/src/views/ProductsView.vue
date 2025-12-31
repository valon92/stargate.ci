<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="text-black dark:text-white">Stargate Products</span>
          </h1>
          <p class="text-xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto mb-8">
            Discover powerful tools, APIs, and services from the Stargate Project ecosystem. Build the future of AI with our comprehensive product suite.
          </p>
          <!-- Create Product Button -->
          <div v-if="isAuthenticated" class="flex justify-center mt-6">
            <button
              @click="openCreateProductModal"
              class="group inline-flex items-center gap-3 px-8 py-4 bg-black dark:bg-white text-white dark:text-black rounded-2xl font-semibold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>Create Your Product</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50 dark:bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tabs: All Products / My Products -->
        <div v-if="isAuthenticated" class="mb-6">
          <div class="bg-white dark:bg-gray-800 rounded-xl p-1 shadow-sm border border-gray-200 dark:border-gray-700 inline-flex">
            <button
              @click="activeTab = 'all'"
              :class="[
                'px-6 py-2 rounded-lg font-medium transition-all',
                activeTab === 'all'
                  ? 'bg-black dark:bg-white text-white dark:text-black'
                  : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
              ]"
            >
              All Products
            </button>
            <button
              @click="activeTab = 'my'"
              :class="[
                'px-6 py-2 rounded-lg font-medium transition-all',
                activeTab === 'my'
                  ? 'bg-black dark:bg-white text-white dark:text-black'
                  : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
              ]"
            >
              My Products
            </button>
          </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="mb-8">
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1 relative">
                <input
                  v-model="searchQuery"
                  @input="filterProducts"
                  type="text"
                  placeholder="Search products..."
                  class="w-full px-4 py-3 pl-12 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
              <div class="flex gap-2">
                <select
                  v-model="selectedCategory"
                  @change="filterProducts"
                  class="px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
                >
                  <option value="all">All Categories</option>
                  <option value="api">APIs</option>
                  <option value="tools">Tools</option>
                  <option value="sdk">SDKs</option>
                  <option value="cloud">Cloud Services</option>
                  <option value="documentation">Documentation</option>
                  <option value="libraries">Libraries</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Products Grid -->
        <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="product in filteredProducts"
            :key="product.id"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 group"
          >
            <!-- Product Icon/Image -->
            <div class="mb-4">
              <div class="w-16 h-16 bg-black dark:bg-white rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <span class="text-3xl">{{ product.icon }}</span>
              </div>
              <div class="flex items-center gap-2 mb-2">
                <span :class="getCategoryBadgeClass(product.category)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ getCategoryName(product.category) }}
                </span>
                <span v-if="product.is_new" class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-medium">
                  New
                </span>
                <span v-if="product.is_popular" class="px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-medium">
                  Popular
                </span>
              </div>
            </div>

            <!-- Product Content -->
            <h3 class="text-xl font-bold text-black dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
              {{ product.name }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
              {{ product.description }}
            </p>

            <!-- Product Features -->
            <div v-if="product.features && product.features.length > 0" class="mb-4">
              <ul class="space-y-1">
                <li v-for="feature in product.features.slice(0, 3)" :key="feature" class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                  <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  {{ feature }}
                </li>
              </ul>
            </div>

            <!-- Product Author (if user-created) -->
            <div v-if="product.subscriber" class="mb-4 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span>By {{ product.subscriber.username || product.subscriber.name || product.subscriber.email }}</span>
            </div>

            <!-- Product Actions -->
            <div class="flex items-center gap-3 mt-6">
              <a
                v-if="product.documentation_url"
                :href="product.documentation_url"
                target="_blank"
                rel="noopener noreferrer"
                class="flex-1 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors text-center text-sm font-medium"
              >
                View Docs
              </a>
              <a
                v-if="product.github_url"
                :href="product.github_url"
                target="_blank"
                rel="noopener noreferrer"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                title="GitHub Repository"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
              </a>
              <a
                v-if="product.demo_url"
                :href="product.demo_url"
                target="_blank"
                rel="noopener noreferrer"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                title="Try Demo"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </a>
              <!-- Edit/Delete buttons for product owner -->
              <div v-if="isProductOwner(product)" class="flex gap-2 ml-auto">
                <button
                  @click="openEditProductModal(product)"
                  class="px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                  title="Edit Product"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  @click="confirmDeleteProduct(product)"
                  class="px-3 py-2 border border-red-300 dark:border-red-600 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                  title="Delete Product"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Product Stats -->
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
              <span v-if="product.downloads_count" class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                {{ formatNumber(product.downloads_count) }}
              </span>
              <span v-if="product.stars_count" class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                {{ formatNumber(product.stars_count) }}
              </span>
              <span v-if="product.users_count" class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                {{ formatNumber(product.users_count) }}+ users
              </span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20">
          <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <p class="text-gray-500 dark:text-gray-400 font-medium mb-1">No products found</p>
          <p class="text-sm text-gray-400 dark:text-gray-500">Try adjusting your search or filter criteria</p>
        </div>
      </div>
    </section>

    <!-- Create Product Modal -->
    <div
      v-if="showCreateModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeCreateModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-800 z-10">
          <h3 class="text-xl font-semibold text-black dark:text-white">Create New Product</h3>
          <button
            @click="closeCreateModal"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="submitCreateProduct" class="p-6 space-y-6">
          <!-- Product Name -->
          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Product Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="productForm.name"
              type="text"
              required
              :disabled="isCreating"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
              placeholder="e.g., My Stargate API Tool"
            >
            <p v-if="productErrors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ productErrors.name }}</p>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Description <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="productForm.description"
              required
              rows="4"
              :disabled="isCreating"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white resize-none"
              placeholder="Describe your product, its features, and how it uses Stargate or Cristal Intelligence..."
            ></textarea>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ productForm.description.length }} characters (minimum 20)</p>
            <p v-if="productErrors.description" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ productErrors.description }}</p>
          </div>

          <!-- Category and Icon -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">
                Category <span class="text-red-500">*</span>
              </label>
              <select
                v-model="productForm.category"
                required
                :disabled="isCreating"
                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
              >
                <option value="api">API</option>
                <option value="tools">Tool</option>
                <option value="sdk">SDK</option>
                <option value="cloud">Cloud Service</option>
                <option value="documentation">Documentation</option>
                <option value="libraries">Library</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">
                Icon (Emoji)
              </label>
              <input
                v-model="productForm.icon"
                type="text"
                maxlength="2"
                :disabled="isCreating"
                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white text-center text-2xl"
                placeholder="🚀"
              >
            </div>
          </div>

          <!-- URLs -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">
                Documentation URL
              </label>
              <input
                v-model="productForm.documentation_url"
                type="url"
                :disabled="isCreating"
                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
                placeholder="https://docs.example.com"
              >
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-black dark:text-white mb-2">
                  GitHub URL
                </label>
                <input
                  v-model="productForm.github_url"
                  type="url"
                  :disabled="isCreating"
                  class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
                  placeholder="https://github.com/..."
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-black dark:text-white mb-2">
                  Demo URL
                </label>
                <input
                  v-model="productForm.demo_url"
                  type="url"
                  :disabled="isCreating"
                  class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
                  placeholder="https://demo.example.com"
                >
              </div>
            </div>
          </div>

          <!-- Features -->
          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Features (one per line)
            </label>
            <textarea
              v-model="featuresInput"
              rows="4"
              :disabled="isCreating"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white resize-none"
              placeholder="Feature 1&#10;Feature 2&#10;Feature 3"
            ></textarea>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enter each feature on a new line</p>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Status
            </label>
            <select
              v-model="productForm.status"
              :disabled="isCreating"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
            >
              <option value="published">Published</option>
              <option value="draft">Draft</option>
            </select>
          </div>

          <!-- Submit Button -->
          <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button
              type="button"
              @click="closeCreateModal"
              :disabled="isCreating"
              class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isCreating"
              class="flex-1 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
              <svg v-if="isCreating" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <span>{{ isCreating ? 'Creating...' : 'Create Product' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Product Modal (similar structure, will be added) -->
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { productsService, type Product } from '../services/productsService'

const router = useRouter()
const authStore = useAuthStore()

// Meta tags
useHead({
  title: 'Stargate Products - Tools, APIs & Services | Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Discover powerful tools, APIs, and services from the Stargate Project ecosystem. Build the future of AI with our comprehensive product suite.'
    }
  ]
})

const isAuthenticated = computed(() => authStore.isAuthenticated)
const products = ref<Product[]>([])
const myProducts = ref<Product[]>([])
const isLoading = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('all')
const activeTab = ref<'all' | 'my'>('all')
const showCreateModal = ref(false)
const isCreating = ref(false)
const productErrors = ref<Record<string, string>>({})

const productForm = ref({
  name: '',
  description: '',
  category: 'tools' as const,
  icon: '🚀',
  documentation_url: '',
  github_url: '',
  demo_url: '',
  features: [] as string[],
  status: 'published' as const
})

const featuresInput = ref('')

// Demo products (fallback)
const demoProducts: Product[] = [
  {
    id: 1,
    name: 'Stargate API',
    slug: 'stargate-api',
    description: 'Comprehensive RESTful API for accessing Stargate Project data, events, and resources. Built for developers who want to integrate Stargate capabilities into their applications.',
    category: 'api',
    icon: '🚀',
    documentation_url: 'https://docs.stargate.ci/api',
    github_url: 'https://github.com/stargate/api',
    demo_url: 'https://api-demo.stargate.ci',
    features: ['RESTful API', 'Real-time updates', 'Rate limiting', 'Authentication'],
    is_popular: true,
    downloads_count: 125000,
    users_count: 5000
  },
  {
    id: 2,
    name: 'Cristal Intelligence SDK',
    slug: 'cristal-intelligence-sdk',
    description: 'Official SDK for integrating Cristal Intelligence capabilities into your applications. Available for JavaScript, Python, and Go.',
    category: 'sdk',
    icon: '💎',
    documentation_url: 'https://docs.stargate.ci/sdk',
    github_url: 'https://github.com/stargate/cristal-sdk',
    features: ['Multi-language support', 'Type-safe', 'Comprehensive docs', 'Active community'],
    is_new: true,
    is_popular: true,
    downloads_count: 89000,
    stars_count: 1200,
    users_count: 3200
  },
  {
    id: 3,
    name: 'Stargate CLI',
    slug: 'stargate-cli',
    description: 'Command-line interface for managing Stargate resources, deploying applications, and interacting with the Stargate ecosystem from your terminal.',
    category: 'tools',
    icon: '⚡',
    documentation_url: 'https://docs.stargate.ci/cli',
    github_url: 'https://github.com/stargate/cli',
    features: ['Cross-platform', 'Interactive mode', 'Plugin system', 'Auto-completion'],
    is_popular: true,
    downloads_count: 45000,
    users_count: 1800
  },
  {
    id: 4,
    name: 'AI Model Hub',
    slug: 'ai-model-hub',
    description: 'Centralized repository of pre-trained AI models from the Stargate Project. Access, download, and deploy models for your applications.',
    category: 'cloud',
    icon: '🧠',
    documentation_url: 'https://docs.stargate.ci/models',
    demo_url: 'https://models.stargate.ci',
    features: ['Model marketplace', 'Version control', 'Deployment tools', 'Performance metrics'],
    is_new: true,
    downloads_count: 250000,
    users_count: 8500
  },
  {
    id: 5,
    name: 'Stargate Documentation',
    slug: 'stargate-documentation',
    description: 'Comprehensive documentation covering all aspects of the Stargate Project, from getting started guides to advanced topics and best practices.',
    category: 'documentation',
    icon: '📚',
    documentation_url: 'https://docs.stargate.ci',
    features: ['Search functionality', 'Code examples', 'Interactive tutorials', 'API reference'],
    users_count: 15000
  },
  {
    id: 6,
    name: 'React Stargate',
    slug: 'react-stargate',
    description: 'React components and hooks for building Stargate-powered applications. Pre-built UI components, state management, and utilities.',
    category: 'libraries',
    icon: '⚛️',
    documentation_url: 'https://docs.stargate.ci/react',
    github_url: 'https://github.com/stargate/react-stargate',
    features: ['TypeScript support', 'Hooks API', 'Component library', 'Tree-shakeable'],
    downloads_count: 67000,
    stars_count: 850,
    users_count: 2400
  },
  {
    id: 7,
    name: 'Vue Stargate',
    slug: 'vue-stargate',
    description: 'Vue.js composables and components for integrating Stargate functionality into Vue applications. Built with Vue 3 Composition API.',
    category: 'libraries',
    icon: '🟢',
    documentation_url: 'https://docs.stargate.ci/vue',
    github_url: 'https://github.com/stargate/vue-stargate',
    features: ['Composition API', 'TypeScript support', 'SSR ready', 'Plugin system'],
    downloads_count: 42000,
    stars_count: 520,
    users_count: 1500
  },
  {
    id: 8,
    name: 'Stargate Cloud',
    slug: 'stargate-cloud',
    description: 'Managed cloud infrastructure for running Stargate applications. Scalable, secure, and optimized for AI workloads.',
    category: 'cloud',
    icon: '☁️',
    documentation_url: 'https://docs.stargate.ci/cloud',
    demo_url: 'https://cloud.stargate.ci',
    features: ['Auto-scaling', 'Global CDN', 'DDoS protection', '99.9% uptime'],
    is_popular: true,
    users_count: 12000
  },
  {
    id: 9,
    name: 'Data Pipeline Tools',
    slug: 'data-pipeline-tools',
    description: 'Tools for processing, transforming, and analyzing data within the Stargate ecosystem. ETL pipelines, data validation, and monitoring.',
    category: 'tools',
    icon: '🔧',
    documentation_url: 'https://docs.stargate.ci/pipeline',
    github_url: 'https://github.com/stargate/pipeline',
    features: ['Visual editor', 'Real-time processing', 'Error handling', 'Monitoring dashboard'],
    downloads_count: 38000,
    users_count: 2100
  }
]

// Load products from API
const loadProducts = async () => {
  isLoading.value = true
  try {
    const response = await productsService.getProducts({
      category: selectedCategory.value !== 'all' ? selectedCategory.value : undefined,
      search: searchQuery.value || undefined
    })
    
    console.log('📦 Products API Response:', response)
    
    if (response.success) {
      products.value = response.data || []
      console.log('✅ Loaded products:', products.value.length)
    } else {
      console.warn('⚠️ API returned success:false')
      products.value = []
    }
  } catch (error) {
    console.error('❌ Error loading products:', error)
    // Fallback to demo products if API fails
    products.value = demoProducts
  } finally {
    isLoading.value = false
  }
}

// Load user's products
const loadMyProducts = async () => {
  if (!isAuthenticated.value) return
  
  try {
    const response = await productsService.getMyProducts()
    if (response.success) {
      myProducts.value = response.data
    }
  } catch (error) {
    console.error('Error loading my products:', error)
  }
}

// Watch active tab
watch(activeTab, (newTab) => {
  if (newTab === 'my') {
    loadMyProducts()
  } else {
    loadProducts()
  }
})

const filteredProducts = computed(() => {
  const sourceProducts = activeTab.value === 'my' ? myProducts.value : products.value
  let result = sourceProducts

  // Filter by category
  if (selectedCategory.value !== 'all') {
    result = result.filter(product => product.category === selectedCategory.value)
  }

  // Filter by search query
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(product =>
      product.name.toLowerCase().includes(query) ||
      product.description.toLowerCase().includes(query) ||
      product.category.toLowerCase().includes(query)
    )
  }

  return result
})

const filterProducts = () => {
  if (activeTab.value === 'all') {
    loadProducts()
  } else {
    loadMyProducts()
  }
}

// Check if current user owns the product
const isProductOwner = (product: Product): boolean => {
  if (!isAuthenticated.value || !authStore.currentUser) return false
  return product.subscriber_id === authStore.currentUser.id
}

const getCategoryName = (category: string): string => {
  const names: Record<string, string> = {
    api: 'API',
    tools: 'Tool',
    sdk: 'SDK',
    cloud: 'Cloud',
    documentation: 'Docs',
    libraries: 'Library'
  }
  return names[category] || category
}

const getCategoryBadgeClass = (category: string): string => {
  const classes: Record<string, string> = {
    api: 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
    tools: 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
    sdk: 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
    cloud: 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200',
    documentation: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
    libraries: 'bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200'
  }
  return classes[category] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
}

const formatNumber = (num: number): string => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M'
  }
  if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K'
  }
  return num.toString()
}

// Create Product Functions
const openCreateProductModal = () => {
  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }
  
  productForm.value = {
    name: '',
    description: '',
    category: 'tools',
    icon: '🚀',
    documentation_url: '',
    github_url: '',
    demo_url: '',
    features: [],
    status: 'published'
  }
  featuresInput.value = ''
  productErrors.value = {}
  showCreateModal.value = true
}

const closeCreateModal = () => {
  showCreateModal.value = false
  productForm.value = {
    name: '',
    description: '',
    category: 'tools',
    icon: '🚀',
    documentation_url: '',
    github_url: '',
    demo_url: '',
    features: [],
    status: 'published'
  }
  featuresInput.value = ''
  productErrors.value = {}
}

const submitCreateProduct = async () => {
  productErrors.value = {}
  
  // Validation
  if (!productForm.value.name.trim()) {
    productErrors.value.name = 'Product name is required'
    return
  }
  
  if (!productForm.value.description.trim()) {
    productErrors.value.description = 'Description is required'
    return
  }
  
  if (productForm.value.description.length < 20) {
    productErrors.value.description = 'Description must be at least 20 characters'
    return
  }
  
  // Parse features from textarea
  const features = featuresInput.value
    .split('\n')
    .map(f => f.trim())
    .filter(f => f.length > 0)
  
  isCreating.value = true
  
  try {
    const response = await productsService.createProduct({
      name: productForm.value.name.trim(),
      description: productForm.value.description.trim(),
      category: productForm.value.category,
      icon: productForm.value.icon || '🚀',
      documentation_url: productForm.value.documentation_url || undefined,
      github_url: productForm.value.github_url || undefined,
      demo_url: productForm.value.demo_url || undefined,
      features: features.length > 0 ? features : undefined,
      status: productForm.value.status
    })
    
    if (response.success) {
      closeCreateModal()
      // Reload products
      if (activeTab.value === 'my') {
        await loadMyProducts()
      } else {
        await loadProducts()
      }
      // Show success message (you can use a toast library here)
      alert('Product created successfully!')
    }
  } catch (error: any) {
    console.error('Error creating product:', error)
    if (error.response?.data?.errors) {
      productErrors.value = error.response.data.errors
    } else {
      alert(error.response?.data?.message || 'Failed to create product. Please try again.')
    }
  } finally {
    isCreating.value = false
  }
}

// Edit Product Functions
const editingProduct = ref<Product | null>(null)
const showEditModal = ref(false)

const openEditProductModal = (product: Product) => {
  editingProduct.value = product
  productForm.value = {
    name: product.name,
    description: product.description,
    category: product.category,
    icon: product.icon || '🚀',
    documentation_url: product.documentation_url || '',
    github_url: product.github_url || '',
    demo_url: product.demo_url || '',
    features: product.features || [],
    status: product.status || 'published'
  }
  featuresInput.value = (product.features || []).join('\n')
  productErrors.value = {}
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editingProduct.value = null
  closeCreateModal()
}

const submitUpdateProduct = async () => {
  if (!editingProduct.value) return
  
  productErrors.value = {}
  
  // Validation
  if (!productForm.value.name.trim()) {
    productErrors.value.name = 'Product name is required'
    return
  }
  
  if (!productForm.value.description.trim()) {
    productErrors.value.description = 'Description is required'
    return
  }
  
  if (productForm.value.description.length < 20) {
    productErrors.value.description = 'Description must be at least 20 characters'
    return
  }
  
  // Parse features from textarea
  const features = featuresInput.value
    .split('\n')
    .map(f => f.trim())
    .filter(f => f.length > 0)
  
  isCreating.value = true
  
  try {
    const response = await productsService.updateProduct(editingProduct.value.id, {
      name: productForm.value.name.trim(),
      description: productForm.value.description.trim(),
      category: productForm.value.category,
      icon: productForm.value.icon || '🚀',
      documentation_url: productForm.value.documentation_url || undefined,
      github_url: productForm.value.github_url || undefined,
      demo_url: productForm.value.demo_url || undefined,
      features: features.length > 0 ? features : undefined,
      status: productForm.value.status
    })
    
    if (response.success) {
      closeEditModal()
      // Reload products
      if (activeTab.value === 'my') {
        await loadMyProducts()
      } else {
        await loadProducts()
      }
      alert('Product updated successfully!')
    }
  } catch (error: any) {
    console.error('Error updating product:', error)
    if (error.response?.data?.errors) {
      productErrors.value = error.response.data.errors
    } else {
      alert(error.response?.data?.message || 'Failed to update product. Please try again.')
    }
  } finally {
    isCreating.value = false
  }
}

// Delete Product Functions
const confirmDeleteProduct = (product: Product) => {
  if (confirm(`Are you sure you want to delete "${product.name}"? This action cannot be undone.`)) {
    deleteProduct(product)
  }
}

const deleteProduct = async (product: Product) => {
  try {
    const response = await productsService.deleteProduct(product.id)
    
    if (response.success) {
      // Reload products
      if (activeTab.value === 'my') {
        await loadMyProducts()
      } else {
        await loadProducts()
      }
      alert('Product deleted successfully!')
    }
  } catch (error: any) {
    console.error('Error deleting product:', error)
    alert(error.response?.data?.message || 'Failed to delete product. Please try again.')
  }
}

onMounted(() => {
  loadProducts()
  if (isAuthenticated.value) {
    loadMyProducts()
  }
})
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
