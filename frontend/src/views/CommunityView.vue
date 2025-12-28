<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="text-black dark:text-white">Community</span>
          </h1>
          <p class="text-xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto mb-8">
            Share your experiences, ask questions, discuss ideas, and connect with the Stargate.ci community
          </p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50 dark:bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <!-- Sidebar -->
          <div class="lg:col-span-1 space-y-6">
            <!-- Create Post Button -->
            <div v-if="isAuthenticated" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <button
                @click="showCreateModal = true"
                class="w-full bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-lg font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Post
              </button>
            </div>

            <!-- Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-bold text-black dark:text-white mb-4">Categories</h3>
              <div class="space-y-2">
                <button
                  v-for="category in categories"
                  :key="category.id"
                  @click="filterByCategory(category.id)"
                  :class="[
                    'w-full text-left px-4 py-2 rounded-lg transition-colors flex items-center gap-2',
                    selectedCategory === category.id
                      ? 'bg-black dark:bg-white text-white dark:text-black'
                      : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                  ]"
                >
                  <span>{{ category.icon }}</span>
                  <span>{{ category.name }}</span>
                </button>
              </div>
            </div>

            <!-- Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-bold text-black dark:text-white mb-4">Community Stats</h3>
              <div class="space-y-3">
                <div class="flex justify-between items-center">
                  <span class="text-gray-600 dark:text-gray-400">Total Posts</span>
                  <span class="text-black dark:text-white font-bold">{{ totalPosts }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-gray-600 dark:text-gray-400">Total Comments</span>
                  <span class="text-black dark:text-white font-bold">{{ totalComments }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-gray-600 dark:text-gray-400">Active Members</span>
                  <span class="text-black dark:text-white font-bold">{{ activeMembers }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Main Feed -->
          <div class="lg:col-span-3 space-y-6">
            <!-- Search Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  @keyup.enter="searchPosts"
                  type="text"
                  placeholder="Search posts..."
                  class="w-full px-4 py-3 pl-12 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="text-center py-12">
              <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
              <p class="mt-4 text-gray-600 dark:text-gray-400">Loading posts...</p>
            </div>

            <!-- Posts List -->
            <div v-else class="space-y-6">
              <article
                v-for="post in posts"
                :key="post.id"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow duration-300"
              >
                <!-- Post Header -->
                <div class="p-6">
                  <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-black dark:bg-gray-700 rounded-full flex items-center justify-center text-white font-bold">
                        {{ getInitials(post.subscriber?.username || 'U') }}
                      </div>
                      <div>
                        <p class="font-semibold text-black dark:text-white">
                          {{ post.subscriber?.username || 'Anonymous' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                          {{ formatDate(post.created_at) }}
                        </p>
                      </div>
                    </div>
                    <div class="flex items-center gap-2">
                      <span
                        :class="getCategoryBadgeClass(post.category)"
                        class="px-3 py-1 rounded-full text-xs font-medium"
                      >
                        {{ getCategoryName(post.category) }}
                      </span>
                      <span v-if="post.is_pinned" class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded text-xs">
                        📌 Pinned
                      </span>
                    </div>
                  </div>

                  <!-- Post Title -->
                  <h2 class="text-2xl font-bold text-black dark:text-white mb-3 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer" @click="viewPost(post.id)">
                    {{ post.title }}
                  </h2>

                  <!-- Post Content Preview -->
                  <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                    {{ post.content }}
                  </p>

                  <!-- Tags -->
                  <div v-if="post.tags && post.tags.length > 0" class="flex flex-wrap gap-2 mb-4">
                    <span
                      v-for="tag in post.tags"
                      :key="tag"
                      class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-xs"
                    >
                      #{{ tag }}
                    </span>
                  </div>

                  <!-- Post Actions -->
                  <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-6">
                      <button
                        @click="toggleLike(post)"
                        :class="[
                          'flex items-center gap-2 px-4 py-2 rounded-lg transition-colors',
                          post.is_liked
                            ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                        ]"
                      >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span>{{ post.likes_count }}</span>
                      </button>

                      <button
                        @click="viewPost(post.id)"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>{{ post.comments_count }}</span>
                      </button>

                      <button
                        @click="sharePost(post)"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                        </svg>
                        <span>{{ post.shares_count }}</span>
                      </button>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                      <span>{{ post.views_count }} views</span>
                    </div>
                  </div>
                </div>
              </article>

              <!-- Empty State -->
              <div v-if="posts.length === 0" class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">No posts yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Be the first to share your experience!</p>
                <button
                  v-if="isAuthenticated"
                  @click="showCreateModal = true"
                  class="bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-lg font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors"
                >
                  Create First Post
                </button>
              </div>

              <!-- Pagination -->
              <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-8">
                <nav class="flex space-x-2">
                  <button
                    @click="loadPage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    Previous
                  </button>
                  <button
                    v-for="page in visiblePages"
                    :key="page"
                    @click="loadPage(page)"
                    :class="[
                      'px-4 py-2 rounded-lg transition-colors',
                      page === pagination.current_page
                        ? 'bg-black dark:bg-white text-white dark:text-black'
                        : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-700'
                    ]"
                  >
                    {{ page }}
                  </button>
                  <button
                    @click="loadPage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    Next
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Create Post Modal -->
    <div
      v-if="showCreateModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeCreateModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-xl font-bold text-black dark:text-white">Create New Post</h3>
          <button
            @click="closeCreateModal"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="createPost" class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Category *</label>
            <select
              v-model="newPost.category"
              required
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
              <option value="">Select a category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.icon }} {{ cat.name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Title *</label>
            <input
              v-model="newPost.title"
              type="text"
              required
              maxlength="255"
              placeholder="Enter post title..."
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Content *</label>
            <textarea
              v-model="newPost.content"
              required
              rows="8"
              placeholder="Share your experience, ask a question, or discuss an idea..."
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
            ></textarea>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ newPost.content.length }} characters</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Tags (optional)</label>
            <input
              v-model="tagsInput"
              type="text"
              placeholder="Enter tags separated by commas (e.g., stargate, ai, experience)"
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              @input="updateTags"
            >
            <div v-if="newPost.tags && newPost.tags.length > 0" class="flex flex-wrap gap-2 mt-2">
              <span
                v-for="tag in newPost.tags"
                :key="tag"
                class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm flex items-center gap-2"
              >
                #{{ tag }}
                <button type="button" @click="removeTag(tag)" class="hover:text-red-600">×</button>
              </span>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-4">
            <button
              type="button"
              @click="closeCreateModal"
              class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isCreating"
              class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50"
            >
              {{ isCreating ? 'Creating...' : 'Create Post' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Post Detail Modal -->
    <div
      v-if="selectedPost"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closePostDetail"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-xl font-bold text-black dark:text-white">Post Details</h3>
          <button
            @click="closePostDetail"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="p-6">
          <!-- Post Content -->
          <div class="mb-6">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-12 h-12 bg-black dark:bg-gray-700 rounded-full flex items-center justify-center text-white font-bold">
                {{ getInitials(selectedPost.subscriber?.username || 'U') }}
              </div>
              <div>
                <p class="font-semibold text-black dark:text-white">{{ selectedPost.subscriber?.username || 'Anonymous' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ formatDate(selectedPost.created_at) }}</p>
              </div>
            </div>
            <h2 class="text-3xl font-bold text-black dark:text-white mb-4">{{ selectedPost.title }}</h2>
            <div class="prose prose-lg max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
              {{ selectedPost.content }}
            </div>
          </div>

          <!-- Comments Section -->
          <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-xl font-bold text-black dark:text-white mb-4">
              Comments ({{ selectedPost.comments_count }})
            </h3>

            <!-- Comment Form -->
            <div v-if="isAuthenticated" class="mb-6">
              <textarea
                v-model="newComment"
                rows="3"
                placeholder="Write a comment..."
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
              ></textarea>
              <button
                @click="addComment"
                :disabled="!newComment.trim() || isAddingComment"
                class="mt-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50"
              >
                {{ isAddingComment ? 'Posting...' : 'Post Comment' }}
              </button>
            </div>

            <!-- Comments List -->
            <div class="space-y-4">
              <div
                v-for="comment in selectedPost.comments"
                :key="comment.id"
                class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4"
              >
                <div class="flex items-start gap-3">
                  <div class="w-8 h-8 bg-black dark:bg-gray-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                    {{ getInitials(comment.subscriber?.username || 'U') }}
                  </div>
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <p class="font-semibold text-black dark:text-white">{{ comment.subscriber?.username || 'Anonymous' }}</p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ formatDate(comment.created_at) }}</p>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">{{ comment.content }}</p>
                    <div class="flex items-center gap-4">
                      <button
                        @click="toggleCommentLike(comment)"
                        :class="[
                          'flex items-center gap-1 text-sm',
                          comment.is_liked
                            ? 'text-red-600 dark:text-red-400'
                            : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                        ]"
                      >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span>{{ comment.likes_count }}</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="selectedPost.comments?.length === 0" class="text-center py-8 text-gray-600 dark:text-gray-400">
                No comments yet. Be the first to comment!
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { communityService, type CommunityPost, type CommunityComment } from '../services/communityService'

const router = useRouter()
const authStore = useAuthStore()

const posts = ref<CommunityPost[]>([])
const categories = ref<Array<{ id: string; name: string; icon: string }>>([])
const selectedCategory = ref('')
const searchQuery = ref('')
const isLoading = ref(false)
const showCreateModal = ref(false)
const selectedPost = ref<CommunityPost | null>(null)
const newComment = ref('')
const isAddingComment = ref(false)
const isCreating = ref(false)

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0
})

const newPost = ref({
  title: '',
  content: '',
  category: '',
  tags: [] as string[]
})

const tagsInput = ref('')

const isAuthenticated = computed(() => authStore.isAuthenticated)
const totalPosts = computed(() => pagination.value.total)
const totalComments = computed(() => posts.value.reduce((sum, post) => sum + post.comments_count, 0))
const activeMembers = computed(() => new Set(posts.value.map(p => p.subscriber_id)).size)

const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, pagination.value.current_page - 2)
  const end = Math.min(pagination.value.last_page, pagination.value.current_page + 2)
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

const loadPosts = async () => {
  isLoading.value = true
  try {
    const response = await communityService.getPosts({
      category: selectedCategory.value || undefined,
      search: searchQuery.value || undefined,
      per_page: pagination.value.per_page,
      page: pagination.value.current_page
    })
    
    if (response.success) {
      posts.value = response.data
      pagination.value = response.pagination
    }
  } catch (error: any) {
    console.error('Failed to load posts:', error)
  } finally {
    isLoading.value = false
  }
}

const loadCategories = async () => {
  try {
    const response = await communityService.getCategories()
    if (response.success) {
      categories.value = response.data
    }
  } catch (error) {
    console.error('Failed to load categories:', error)
  }
}

const filterByCategory = (categoryId: string) => {
  selectedCategory.value = selectedCategory.value === categoryId ? '' : categoryId
  pagination.value.current_page = 1
  loadPosts()
}

const searchPosts = () => {
  pagination.value.current_page = 1
  loadPosts()
}

const loadPage = (page: number) => {
  pagination.value.current_page = page
  loadPosts()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const createPost = async () => {
  if (!newPost.value.title || !newPost.value.content || !newPost.value.category) {
    return
  }

  isCreating.value = true
  try {
    const response = await communityService.createPost(newPost.value)
    if (response.success) {
      showCreateModal.value = false
      resetNewPost()
      loadPosts()
    }
  } catch (error: any) {
    console.error('Failed to create post:', error)
    alert(error.message || 'Failed to create post')
  } finally {
    isCreating.value = false
  }
}

const resetNewPost = () => {
  newPost.value = {
    title: '',
    content: '',
    category: '',
    tags: []
  }
  tagsInput.value = ''
}

const closeCreateModal = () => {
  showCreateModal.value = false
  resetNewPost()
}

const updateTags = () => {
  const tags = tagsInput.value.split(',').map(t => t.trim()).filter(t => t)
  newPost.value.tags = tags
}

const removeTag = (tag: string) => {
  newPost.value.tags = newPost.value.tags?.filter(t => t !== tag) || []
  tagsInput.value = newPost.value.tags.join(', ')
}

const viewPost = async (postId: number) => {
  try {
    const response = await communityService.getPost(postId)
    if (response.success) {
      selectedPost.value = response.data
    }
  } catch (error) {
    console.error('Failed to load post:', error)
  }
}

const closePostDetail = () => {
  selectedPost.value = null
  newComment.value = ''
}

const toggleLike = async (post: CommunityPost) => {
  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }

  try {
    const response = await communityService.likePost(post.id)
    if (response.success) {
      post.is_liked = response.data.is_liked
      post.likes_count = response.data.likes_count
    } else {
      alert('Failed to like post. Please try again.')
    }
  } catch (error: any) {
    console.error('Failed to like post:', error)
    alert(error.message || 'Failed to like post. Please try again.')
  }
}

const addComment = async () => {
  if (!selectedPost.value || !newComment.value.trim()) return

  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }

  isAddingComment.value = true
  try {
    const response = await communityService.addComment(selectedPost.value.id, {
      content: newComment.value.trim()
    })
    if (response.success) {
      newComment.value = ''
      // Reload post to get updated comments
      await viewPost(selectedPost.value.id)
    } else {
      alert('Failed to add comment. Please try again.')
    }
  } catch (error: any) {
    console.error('Failed to add comment:', error)
    alert(error.message || 'Failed to add comment. Please try again.')
  } finally {
    isAddingComment.value = false
  }
}

const toggleCommentLike = async (comment: CommunityComment) => {
  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }

  try {
    const response = await communityService.likeComment(comment.id)
    if (response.success) {
      comment.is_liked = response.data.is_liked
      comment.likes_count = response.data.likes_count
    } else {
      alert('Failed to like comment. Please try again.')
    }
  } catch (error: any) {
    console.error('Failed to like comment:', error)
    alert(error.message || 'Failed to like comment. Please try again.')
  }
}

const sharePost = async (post: CommunityPost) => {
  const url = `${window.location.origin}/community/post/${post.id}`
  
  // Track share in backend
  try {
    await communityService.sharePost(post.id)
    post.shares_count = (post.shares_count || 0) + 1
  } catch (error) {
    console.error('Failed to track share:', error)
    // Continue with sharing even if tracking fails
  }
  
  // Use Web Share API if available
  if (navigator.share) {
    navigator.share({
      title: post.title,
      text: post.content.substring(0, 100),
      url: url
    }).catch(() => {
      copyToClipboard(url)
    })
  } else {
    copyToClipboard(url)
  }
}

const copyToClipboard = async (text: string) => {
  try {
    await navigator.clipboard.writeText(text)
    alert('Link copied to clipboard!')
  } catch (error) {
    console.error('Failed to copy:', error)
  }
}

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = now.getTime() - date.getTime()
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)

  if (minutes < 1) return 'Just now'
  if (minutes < 60) return `${minutes}m ago`
  if (hours < 24) return `${hours}h ago`
  if (days < 7) return `${days}d ago`
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

const getInitials = (name: string): string => {
  if (!name) return 'U'
  const parts = name.split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

const getCategoryName = (category: string): string => {
  const cat = categories.value.find(c => c.id === category)
  return cat ? `${cat.icon} ${cat.name}` : category
}

const getCategoryBadgeClass = (category: string): string => {
  const classes: Record<string, string> = {
    general: 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200',
    experience: 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200',
    question: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200',
    idea: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200',
    discussion: 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200'
  }
  return classes[category] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
}

onMounted(async () => {
  await loadCategories()
  await loadPosts()
})
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.prose {
  @apply text-gray-700 dark:text-gray-300;
}

.prose p {
  @apply mb-4;
}
</style>

