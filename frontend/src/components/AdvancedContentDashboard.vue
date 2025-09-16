<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Advanced Content Management</h1>
      <p class="text-gray-300">Manage videos, interactive tutorials, and rich media content</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-primary-500/20 rounded-lg">
            <svg class="w-6 h-6 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-400">Total Videos</p>
            <p class="text-2xl font-bold text-white">{{ stats.videos }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-secondary-500/20 rounded-lg">
            <svg class="w-6 h-6 text-secondary-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-400">Tutorials</p>
            <p class="text-2xl font-bold text-white">{{ stats.tutorials }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-green-500/20 rounded-lg">
            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-400">Playlists</p>
            <p class="text-2xl font-bold text-white">{{ stats.playlists }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-yellow-500/20 rounded-lg">
            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-400">Total Views</p>
            <p class="text-2xl font-bold text-white">{{ stats.totalViews.toLocaleString() }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Tabs -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50">
      <div class="border-b border-gray-700">
        <nav class="flex space-x-8 px-6">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
              activeTab === tab.id
                ? 'border-primary-500 text-primary-400'
                : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'
            ]"
          >
            {{ tab.name }}
          </button>
        </nav>
      </div>

      <div class="p-6">
        <!-- Videos Tab -->
        <div v-if="activeTab === 'videos'">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Video Content</h2>
            <button
              @click="showAddVideoModal = true"
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              Add Video
            </button>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <div
              v-for="video in videos"
              :key="video.id"
              class="bg-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow"
            >
              <div class="relative">
                <img
                  :src="video.thumbnail"
                  :alt="video.title"
                  class="w-full h-48 object-cover"
                />
                <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                  {{ formatDuration(video.duration) }}
                </div>
                <div class="absolute bottom-2 left-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                  {{ video.quality }}
                </div>
              </div>
              <div class="p-4">
                <h3 class="text-lg font-semibold text-white mb-2">{{ video.title }}</h3>
                <p class="text-gray-300 text-sm mb-3 line-clamp-2">{{ video.description }}</p>
                <div class="flex items-center justify-between text-sm text-gray-400">
                  <span>{{ video.views.toLocaleString() }} views</span>
                  <span>{{ video.likes }} likes</span>
                </div>
                <div class="flex items-center justify-between mt-4">
                  <button
                    @click="editVideo(video)"
                    class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-500 transition-colors"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteVideo(video.id)"
                    class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tutorials Tab -->
        <div v-if="activeTab === 'tutorials'">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Interactive Tutorials</h2>
            <button
              @click="showAddTutorialModal = true"
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              Add Tutorial
            </button>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div
              v-for="tutorial in tutorials"
              :key="tutorial.id"
              class="bg-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow"
            >
              <div class="relative">
                <img
                  :src="tutorial.thumbnail"
                  :alt="tutorial.title"
                  class="w-full h-48 object-cover"
                />
                <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                  {{ tutorial.estimatedTime }} min
                </div>
                <div class="absolute bottom-2 left-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                  {{ tutorial.difficulty }}
                </div>
              </div>
              <div class="p-4">
                <h3 class="text-lg font-semibold text-white mb-2">{{ tutorial.title }}</h3>
                <p class="text-gray-300 text-sm mb-3 line-clamp-2">{{ tutorial.description }}</p>
                <div class="flex items-center justify-between text-sm text-gray-400">
                  <span>{{ tutorial.completions }} completions</span>
                  <span>{{ tutorial.rating.toFixed(1) }} ⭐</span>
                </div>
                <div class="flex items-center justify-between mt-4">
                  <button
                    @click="editTutorial(tutorial)"
                    class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-500 transition-colors"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteTutorial(tutorial.id)"
                    class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Playlists Tab -->
        <div v-if="activeTab === 'playlists'">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Content Playlists</h2>
            <button
              @click="showAddPlaylistModal = true"
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              Add Playlist
            </button>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <div
              v-for="playlist in playlists"
              :key="playlist.id"
              class="bg-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow"
            >
              <div class="relative">
                <img
                  :src="playlist.thumbnail"
                  :alt="playlist.title"
                  class="w-full h-48 object-cover"
                />
                <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                  {{ playlist.totalItems }} items
                </div>
                <div class="absolute bottom-2 left-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                  {{ formatDuration(playlist.totalDuration) }}
                </div>
              </div>
              <div class="p-4">
                <h3 class="text-lg font-semibold text-white mb-2">{{ playlist.title }}</h3>
                <p class="text-gray-300 text-sm mb-3 line-clamp-2">{{ playlist.description }}</p>
                <div class="flex items-center justify-between mt-4">
                  <button
                    @click="editPlaylist(playlist)"
                    class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-500 transition-colors"
                  >
                    Edit
                  </button>
                  <button
                    @click="deletePlaylist(playlist.id)"
                    class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Analytics Tab -->
        <div v-if="activeTab === 'analytics'">
          <h2 class="text-xl font-bold text-white mb-6">Content Analytics</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Videos -->
            <div class="bg-gray-700 rounded-lg p-6">
              <h3 class="text-lg font-semibold text-white mb-4">Top Videos</h3>
              <div class="space-y-3">
                <div
                  v-for="(video, index) in topVideos"
                  :key="video.id"
                  class="flex items-center space-x-3"
                >
                  <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                    {{ index + 1 }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-white font-medium truncate">{{ video.title }}</p>
                    <p class="text-gray-400 text-sm">{{ video.views.toLocaleString() }} views</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Top Tutorials -->
            <div class="bg-gray-700 rounded-lg p-6">
              <h3 class="text-lg font-semibold text-white mb-4">Top Tutorials</h3>
              <div class="space-y-3">
                <div
                  v-for="(tutorial, index) in topTutorials"
                  :key="tutorial.id"
                  class="flex items-center space-x-3"
                >
                  <div class="w-8 h-8 bg-secondary-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                    {{ index + 1 }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-white font-medium truncate">{{ tutorial.title }}</p>
                    <p class="text-gray-400 text-sm">{{ tutorial.completions }} completions</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Video Modal -->
    <div v-if="showAddVideoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-gray-800 rounded-xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-white mb-4">Add New Video</h3>
        <form @submit.prevent="addVideo">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
              <input
                v-model="newVideo.title"
                type="text"
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
              <textarea
                v-model="newVideo.description"
                required
                rows="3"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
              ></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Video URL</label>
                <input
                  v-model="newVideo.videoUrl"
                  type="url"
                  required
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Thumbnail URL</label>
                <input
                  v-model="newVideo.thumbnail"
                  type="url"
                  required
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                />
              </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Duration (seconds)</label>
                <input
                  v-model.number="newVideo.duration"
                  type="number"
                  required
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Quality</label>
                <select
                  v-model="newVideo.quality"
                  required
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                >
                  <option value="360p">360p</option>
                  <option value="480p">480p</option>
                  <option value="720p">720p</option>
                  <option value="1080p">1080p</option>
                  <option value="4K">4K</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                <input
                  v-model="newVideo.category"
                  type="text"
                  required
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Tags (comma-separated)</label>
              <input
                v-model="newVideo.tags"
                type="text"
                placeholder="AI, Stargate, Tutorial"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
              />
            </div>
          </div>
          <div class="flex items-center justify-end space-x-3 mt-6">
            <button
              type="button"
              @click="showAddVideoModal = false"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              Add Video
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { advancedContentService, type VideoContent, type InteractiveTutorial, type ContentPlaylist } from '../services/advancedContentService'

// State
const activeTab = ref('videos')
const videos = ref<VideoContent[]>([])
const tutorials = ref<InteractiveTutorial[]>([])
const playlists = ref<ContentPlaylist[]>([])

// Modals
const showAddVideoModal = ref(false)
const showAddTutorialModal = ref(false)
const showAddPlaylistModal = ref(false)

// New content forms
const newVideo = ref({
  title: '',
  description: '',
  videoUrl: '',
  thumbnail: '',
  duration: 0,
  quality: '1080p' as const,
  format: 'mp4' as const,
  size: 0,
  category: '',
  tags: '',
  author: 'Admin',
  isPublic: true,
  isFeatured: false
})

// Computed
const stats = computed(() => ({
  videos: videos.value.length,
  tutorials: tutorials.value.length,
  playlists: playlists.value.length,
  totalViews: videos.value.reduce((sum, video) => sum + video.views, 0)
}))

const topVideos = computed(() => {
  return [...videos.value]
    .sort((a, b) => b.views - a.views)
    .slice(0, 5)
})

const topTutorials = computed(() => {
  return [...tutorials.value]
    .sort((a, b) => b.completions - a.completions)
    .slice(0, 5)
})

const tabs = [
  { id: 'videos', name: 'Videos' },
  { id: 'tutorials', name: 'Tutorials' },
  { id: 'playlists', name: 'Playlists' },
  { id: 'analytics', name: 'Analytics' }
]

// Methods
const loadData = async () => {
  videos.value = await advancedContentService.getVideos()
  tutorials.value = await advancedContentService.getTutorials()
  playlists.value = await advancedContentService.getPlaylists()
}

const addVideo = async () => {
  try {
    const videoData = {
      ...newVideo.value,
      tags: newVideo.value.tags.split(',').map(tag => tag.trim()).filter(tag => tag)
    }
    
    await advancedContentService.addVideo(videoData)
    await loadData()
    showAddVideoModal.value = false
    
    // Reset form
    newVideo.value = {
      title: '',
      description: '',
      videoUrl: '',
      thumbnail: '',
      duration: 0,
      quality: '1080p',
      format: 'mp4',
      size: 0,
      category: '',
      tags: '',
      author: 'Admin',
      isPublic: true,
      isFeatured: false
    }
  } catch (error) {
    console.error('Failed to add video:', error)
  }
}

const editVideo = (video: VideoContent) => {
  // TODO: Implement edit functionality
  console.log('Edit video:', video)
}

const deleteVideo = async (videoId: string) => {
  if (confirm('Are you sure you want to delete this video?')) {
    try {
      await advancedContentService.deleteVideo(videoId)
      await loadData()
    } catch (error) {
      console.error('Failed to delete video:', error)
    }
  }
}

const editTutorial = (tutorial: InteractiveTutorial) => {
  // TODO: Implement edit functionality
  console.log('Edit tutorial:', tutorial)
}

const deleteTutorial = async (tutorialId: string) => {
  if (confirm('Are you sure you want to delete this tutorial?')) {
    try {
      // TODO: Implement delete tutorial
      await loadData()
    } catch (error) {
      console.error('Failed to delete tutorial:', error)
    }
  }
}

const editPlaylist = (playlist: ContentPlaylist) => {
  // TODO: Implement edit functionality
  console.log('Edit playlist:', playlist)
}

const deletePlaylist = async (playlistId: string) => {
  if (confirm('Are you sure you want to delete this playlist?')) {
    try {
      // TODO: Implement delete playlist
      await loadData()
    } catch (error) {
      console.error('Failed to delete playlist:', error)
    }
  }
}

const formatDuration = (seconds: number): string => {
  return advancedContentService.formatDuration(seconds)
}

// Lifecycle
onMounted(() => {
  loadData()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
