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

                  <!-- Post Media Preview -->
                  <div v-if="post.images && post.images.length > 0" class="mb-4">
                    <div :class="[
                      'grid gap-2 rounded-lg overflow-hidden',
                      post.images.length === 1 ? 'grid-cols-1' : post.images.length === 2 ? 'grid-cols-2' : 'grid-cols-3'
                    ]">
                      <div
                        v-for="(image, index) in post.images.slice(0, 3)"
                        :key="index"
                        class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden cursor-pointer group"
                        @click="viewPost(post.id)"
                      >
                        <img :src="image" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div v-if="post.images.length > 3 && index === 2" class="absolute inset-0 bg-black/60 flex items-center justify-center text-white font-bold">
                          +{{ post.images.length - 3 }} more
                        </div>
                      </div>
                    </div>
                  </div>

                  <div v-if="post.videos && post.videos.length > 0" class="mb-4">
                    <div class="grid gap-2 grid-cols-1">
                      <div
                        v-for="(video, index) in post.videos.slice(0, 1)"
                        :key="index"
                        class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden cursor-pointer group"
                        @click="viewPost(post.id)"
                      >
                        <video :src="video" class="w-full h-full object-cover" muted></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-colors">
                          <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>

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

          <!-- Media Upload Section -->
          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Add Media (optional)</label>
            
            <!-- Image Upload -->
            <div class="mb-4">
              <label class="block text-xs text-gray-600 dark:text-gray-400 mb-2">Images (URLs)</label>
              <div class="space-y-2">
                <div
                  v-for="(image, index) in newPost.images"
                  :key="index"
                  class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700 rounded-lg"
                >
                  <img :src="image" alt="Preview" class="w-16 h-16 object-cover rounded">
                  <input
                    v-model="newPost.images[index]"
                    type="url"
                    placeholder="Image URL"
                    class="flex-1 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-gray-900 dark:text-white text-sm"
                  >
                  <button
                    type="button"
                    @click="removeImage(index)"
                    class="px-3 py-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors"
                  >
                    Remove
                  </button>
                </div>
                <button
                  type="button"
                  @click="addImageField"
                  class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  Add Image URL
                </button>
              </div>
            </div>

            <!-- Video Upload -->
            <div>
              <label class="block text-xs text-gray-600 dark:text-gray-400 mb-2">Videos (URLs)</label>
              <div class="space-y-2">
                <div
                  v-for="(video, index) in newPost.videos"
                  :key="index"
                  class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700 rounded-lg"
                >
                  <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                  <input
                    v-model="newPost.videos[index]"
                    type="url"
                    placeholder="Video URL"
                    class="flex-1 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-gray-900 dark:text-white text-sm"
                  >
                  <button
                    type="button"
                    @click="removeVideo(index)"
                    class="px-3 py-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors"
                  >
                    Remove
                  </button>
                </div>
                <button
                  type="button"
                  @click="addVideoField"
                  class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  Add Video URL
                </button>
              </div>
            </div>
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
            
            <!-- Post Media -->
            <div v-if="selectedPost.images && selectedPost.images.length > 0" class="mb-6">
              <div :class="[
                'grid gap-4 rounded-lg overflow-hidden',
                selectedPost.images.length === 1 ? 'grid-cols-1' : selectedPost.images.length === 2 ? 'grid-cols-2' : 'grid-cols-3'
              ]">
                <div
                  v-for="(image, index) in selectedPost.images"
                  :key="index"
                  class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden group cursor-pointer"
                >
                  <img :src="image" :alt="selectedPost.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
              </div>
            </div>

            <div v-if="selectedPost.videos && selectedPost.videos.length > 0" class="mb-6">
              <div class="grid gap-4 grid-cols-1">
                <div
                  v-for="(video, index) in selectedPost.videos"
                  :key="index"
                  class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"
                >
                  <video :src="video" controls class="w-full h-full object-cover rounded-lg"></video>
                </div>
              </div>
            </div>

            <div class="prose prose-lg max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap mb-4">
              {{ selectedPost.content }}
            </div>

            <!-- Tags -->
            <div v-if="selectedPost.tags && selectedPost.tags.length > 0" class="flex flex-wrap gap-2 mb-4">
              <span
                v-for="tag in selectedPost.tags"
                :key="tag"
                class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
              >
                #{{ tag }}
              </span>
            </div>

            <!-- Post Actions -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
              <button
                @click="toggleLike(selectedPost)"
                :class="[
                  'flex items-center gap-2 px-4 py-2 rounded-lg transition-colors',
                  selectedPost.is_liked
                    ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                ]"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span>{{ selectedPost.likes_count }}</span>
              </button>

              <button
                @click="sharePost(selectedPost)"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                </svg>
                <span>{{ selectedPost.shares_count }}</span>
              </button>
            </div>
          </div>

          <!-- Comments Section -->
          <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-black dark:text-white">
                Comments ({{ selectedPost.comments_count }})
              </h3>
              <button
                v-if="selectedPost.comments && selectedPost.comments.length > 0"
                @click="sortCommentsAscending = !sortCommentsAscending"
                class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                </svg>
                {{ sortCommentsAscending ? 'Newest First' : 'Oldest First' }}
              </button>
            </div>

            <!-- Comment Form -->
            <div v-if="isAuthenticated" class="mb-8 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-black dark:bg-gray-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                  {{ getInitials(authStore.currentUser?.name || authStore.currentUser?.email || 'U') }}
                </div>
                <div class="flex-1">
                  <textarea
                    v-model="newComment"
                    :placeholder="replyingTo ? `Replying to ${replyingTo.subscriber?.username || 'user'}...` : 'Write a comment...'"
                    rows="3"
                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none transition-all"
                    @focus="showCommentActions = true"
                  ></textarea>
                  <div v-if="replyingTo" class="mt-2 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    Replying to {{ replyingTo.subscriber?.username || 'user' }}
                    <button @click="cancelReply" class="text-red-600 dark:text-red-400 hover:underline">Cancel</button>
                  </div>
                  <div v-if="showCommentActions || newComment.trim()" class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-2">
                      <button
                        @click="showCommentActions = !showCommentActions"
                        class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                        title="Add emoji"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </button>
                    </div>
                    <div class="flex items-center gap-2">
                      <button
                        @click="cancelReply"
                        v-if="replyingTo"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                      >
                        Cancel
                      </button>
                      <button
                        @click="addComment"
                        :disabled="!newComment.trim() || isAddingComment"
                        class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium"
                      >
                        {{ isAddingComment ? 'Posting...' : replyingTo ? 'Reply' : 'Post Comment' }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Comments List -->
            <div class="space-y-4">
              <div
                v-for="comment in sortedComments"
                :key="comment.id"
                :class="[
                  'bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 transition-all hover:shadow-md',
                  comment.is_pinned ? 'ring-2 ring-yellow-400 dark:ring-yellow-500' : ''
                ]"
              >
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 shadow-md">
                    {{ getInitials(comment.subscriber?.username || 'U') }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                      <p class="font-semibold text-black dark:text-white">{{ comment.subscriber?.username || 'Anonymous' }}</p>
                      <span v-if="comment.is_pinned" class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded text-xs font-medium">
                        📌 Pinned
                      </span>
                      <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(comment.created_at) }}</p>
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300 mb-3 whitespace-pre-wrap break-words">
                      {{ comment.content }}
                    </div>
                    <div class="flex items-center gap-4">
                      <button
                        @click="toggleCommentLike(comment)"
                        :class="[
                          'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all',
                          comment.is_liked
                            ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                        ]"
                      >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span>{{ comment.likes_count || 0 }}</span>
                      </button>
                      <button
                        @click="startReply(comment)"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm font-medium"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Reply
                      </button>
                    </div>

                    <!-- Replies -->
                    <div v-if="comment.replies && comment.replies.length > 0" class="mt-4 ml-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700 space-y-3">
                      <div
                        v-for="reply in comment.replies"
                        :key="reply.id"
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3"
                      >
                        <div class="flex items-start gap-2">
                          <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-teal-600 dark:from-green-600 dark:to-teal-700 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ getInitials(reply.subscriber?.username || 'U') }}
                          </div>
                          <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                              <p class="font-semibold text-sm text-black dark:text-white">{{ reply.subscriber?.username || 'Anonymous' }}</p>
                              <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(reply.created_at) }}</p>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2 whitespace-pre-wrap break-words">{{ reply.content }}</p>
                            <div class="flex items-center gap-3">
                              <button
                                @click="toggleCommentLike(reply)"
                                :class="[
                                  'flex items-center gap-1 text-xs',
                                  reply.is_liked
                                    ? 'text-red-600 dark:text-red-400'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                                ]"
                              >
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span>{{ reply.likes_count || 0 }}</span>
                              </button>
                              <button
                                @click="startReply(reply)"
                                class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                              >
                                Reply
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="selectedPost.comments?.length === 0" class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-black dark:text-white mb-2">No comments yet</h4>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Be the first to share your thoughts!</p>
                <button
                  v-if="isAuthenticated"
                  @click="document.querySelector('textarea')?.focus()"
                  class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors text-sm font-medium"
                >
                  Write a comment
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Share Post Modal -->
    <div
      v-if="showShareModal && postToShare"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeShareModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full mx-4" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-xl font-semibold text-black dark:text-white">Share Post</h3>
          <button
            @click="closeShareModal"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="p-6">
          <div class="mb-6">
            <h4 class="text-lg font-medium text-black dark:text-white mb-2">{{ postToShare.title }}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ postToShare.subscriber?.username || 'Anonymous' }}</p>
          </div>

          <!-- Share Options -->
          <div class="grid grid-cols-2 gap-3">
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

            <!-- Email -->
            <button
              @click="shareViaEmail"
              class="flex items-center justify-center p-4 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>Email</span>
            </button>

            <!-- Native Share (Mobile) -->
            <button
              v-if="hasNativeShare"
              @click="shareViaNative"
              class="flex items-center justify-center p-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
              </svg>
              <span>More</span>
            </button>

            <!-- Copy Link -->
            <button
              @click="copyToClipboard(getPostUrl(postToShare))"
              class="flex items-center justify-center p-4 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors col-span-2"
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
const replyingTo = ref<CommunityComment | null>(null)
const showCommentActions = ref(false)
const sortCommentsAscending = ref(false)

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
  tags: [] as string[],
  images: [] as string[],
  videos: [] as string[]
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
    // Filter out empty image and video URLs
    const postData = {
      ...newPost.value,
      images: newPost.value.images?.filter(img => img.trim() !== '') || [],
      videos: newPost.value.videos?.filter(vid => vid.trim() !== '') || []
    }

    // Remove images and videos if empty
    if (postData.images.length === 0) {
      delete postData.images
    }
    if (postData.videos.length === 0) {
      delete postData.videos
    }

    const response = await communityService.createPost(postData)
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
    tags: [],
    images: [],
    videos: []
  }
  tagsInput.value = ''
}

const addImageField = () => {
  newPost.value.images = [...(newPost.value.images || []), '']
}

const removeImage = (index: number) => {
  newPost.value.images = newPost.value.images?.filter((_, i) => i !== index) || []
}

const addVideoField = () => {
  newPost.value.videos = [...(newPost.value.videos || []), '']
}

const removeVideo = (index: number) => {
  newPost.value.videos = newPost.value.videos?.filter((_, i) => i !== index) || []
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
  replyingTo.value = null
  showCommentActions.value = false
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

const sortedComments = computed(() => {
  if (!selectedPost.value?.comments) return []
  const comments = [...selectedPost.value.comments]
  return sortCommentsAscending.value 
    ? comments.sort((a, b) => new Date(a.created_at).getTime() - new Date(b.created_at).getTime())
    : comments.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())
})

const startReply = (comment: CommunityComment) => {
  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }
  replyingTo.value = comment
  showCommentActions.value = true
  // Scroll to comment form
  setTimeout(() => {
    document.querySelector('textarea')?.focus()
  }, 100)
}

const cancelReply = () => {
  replyingTo.value = null
  if (!newComment.value.trim()) {
    showCommentActions.value = false
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
      content: newComment.value.trim(),
      parent_id: replyingTo.value?.id
    })
    if (response.success) {
      newComment.value = ''
      replyingTo.value = null
      showCommentActions.value = false
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

const showShareModal = ref(false)
const postToShare = ref<CommunityPost | null>(null)

// Check if native share API is available
const hasNativeShare = computed(() => {
  return typeof navigator !== 'undefined' && 'share' in navigator
})

const sharePost = (post: CommunityPost) => {
  postToShare.value = post
  showShareModal.value = true
}

const closeShareModal = () => {
  showShareModal.value = false
  postToShare.value = null
}

const getPostUrl = (post: CommunityPost) => {
  return `${window.location.origin}/community#post-${post.id}`
}

const shareToFacebook = async () => {
  if (!postToShare.value) return
  const url = encodeURIComponent(getPostUrl(postToShare.value))
  const text = encodeURIComponent(`${postToShare.value.title} - ${postToShare.value.subscriber?.username || 'Community Post'}`)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400')
  await trackShare()
}

const shareToTwitter = async () => {
  if (!postToShare.value) return
  const url = encodeURIComponent(getPostUrl(postToShare.value))
  const text = encodeURIComponent(`Check out this post: ${postToShare.value.title}`)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
  await trackShare()
}

const shareToLinkedIn = async () => {
  if (!postToShare.value) return
  const url = encodeURIComponent(getPostUrl(postToShare.value))
  window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400')
  await trackShare()
}

const shareToWhatsApp = async () => {
  if (!postToShare.value) return
  const url = encodeURIComponent(getPostUrl(postToShare.value))
  const text = encodeURIComponent(`Check out this post: ${postToShare.value.title}\n${getPostUrl(postToShare.value)}`)
  window.open(`https://wa.me/?text=${text}`, '_blank')
  await trackShare()
}

const shareToTelegram = async () => {
  if (!postToShare.value) return
  const url = encodeURIComponent(getPostUrl(postToShare.value))
  const text = encodeURIComponent(`Check out this post: ${postToShare.value.title}`)
  window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
  await trackShare()
}

const shareViaEmail = async () => {
  if (!postToShare.value) return
  const subject = encodeURIComponent(`Community Post: ${postToShare.value.title}`)
  const body = encodeURIComponent(`Hi,\n\nI found this interesting post that might interest you:\n\n${postToShare.value.title}\n\n${postToShare.value.content.substring(0, 200)}...\n\nView full post: ${getPostUrl(postToShare.value)}\n\nBest regards`)
  window.location.href = `mailto:?subject=${subject}&body=${body}`
  await trackShare()
}

const shareViaNative = async () => {
  if (!postToShare.value) return
  
  if (typeof navigator === 'undefined' || !('share' in navigator)) {
    copyToClipboard(getPostUrl(postToShare.value))
    return
  }
  
  try {
    await navigator.share({
      title: postToShare.value.title,
      text: `Check out this post: ${postToShare.value.title}`,
      url: getPostUrl(postToShare.value)
    })
    await trackShare()
  } catch (error) {
    // User cancelled or error occurred
    if ((error as Error).name !== 'AbortError') {
      copyToClipboard(getPostUrl(postToShare.value))
    }
  }
}

const trackShare = async () => {
  if (!postToShare.value) return
  
  try {
    await communityService.sharePost(postToShare.value.id)
    postToShare.value.shares_count = (postToShare.value.shares_count || 0) + 1
    
    // Update in posts list if visible
    const postInList = posts.value.find(p => p.id === postToShare.value!.id)
    if (postInList) {
      postInList.shares_count = postToShare.value.shares_count
    }
    
    // Update in selectedPost if it's the same
    if (selectedPost.value && selectedPost.value.id === postToShare.value.id) {
      selectedPost.value.shares_count = postToShare.value.shares_count
    }
  } catch (error) {
    console.error('Failed to track share:', error)
  }
}

const copyToClipboard = async (text: string) => {
  try {
    await navigator.clipboard.writeText(text)
    alert('Link copied to clipboard!')
    closeShareModal()
    await trackShare()
  } catch (error) {
    console.error('Failed to copy:', error)
    // Fallback for older browsers
    const textArea = document.createElement('textarea')
    textArea.value = text
    textArea.style.position = 'fixed'
    textArea.style.left = '-999999px'
    document.body.appendChild(textArea)
    textArea.focus()
    textArea.select()
    try {
      document.execCommand('copy')
      alert('Link copied to clipboard!')
      closeShareModal()
      await trackShare()
    } catch (err) {
      console.error('Fallback copy failed:', err)
    }
    document.body.removeChild(textArea)
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

