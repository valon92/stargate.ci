<template>
  <div class="video-player-container bg-gray-900 rounded-xl overflow-hidden shadow-lg">
    <!-- Video Player -->
    <div class="relative bg-black">
      <video
        ref="videoElement"
        :src="video.videoUrl"
        :poster="video.thumbnail"
        class="w-full h-auto max-h-96 lg:max-h-[500px]"
        @loadedmetadata="onLoadedMetadata"
        @timeupdate="onTimeUpdate"
        @ended="onEnded"
        @play="onPlay"
        @pause="onPause"
        @click="togglePlayPause"
      >
        <track
          v-for="subtitle in video.subtitles"
          :key="subtitle.id"
          :src="subtitle.url"
          :label="subtitle.label"
          :srclang="subtitle.language"
          :default="subtitle.isDefault"
          kind="subtitles"
        />
        Your browser does not support the video tag.
      </video>

      <!-- Play/Pause Overlay -->
      <div
        v-if="!isPlaying"
        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 cursor-pointer"
        @click="togglePlayPause"
      >
        <div class="bg-white bg-opacity-20 rounded-full p-4 backdrop-blur-sm">
          <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M8 5v14l11-7z"/>
          </svg>
        </div>
      </div>

      <!-- Loading Overlay -->
      <div
        v-if="isLoading"
        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50"
      >
        <div class="flex items-center space-x-2 text-white">
          <svg class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>Loading...</span>
        </div>
      </div>

      <!-- Video Controls -->
      <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
        <div class="flex items-center space-x-4">
          <!-- Play/Pause Button -->
          <button
            @click="togglePlayPause"
            class="text-white hover:text-gray-300 transition-colors"
          >
            <svg v-if="!isPlaying" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M8 5v14l11-7z"/>
            </svg>
            <svg v-else class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
            </svg>
          </button>

          <!-- Progress Bar -->
          <div class="flex-1 relative">
            <div class="w-full bg-gray-600 rounded-full h-2">
              <div
                class="bg-primary-500 h-2 rounded-full transition-all duration-200"
                :style="{ width: progressPercentage + '%' }"
              ></div>
            </div>
            <input
              ref="progressBar"
              type="range"
              min="0"
              :max="duration"
              :value="currentTime"
              @input="onProgressChange"
              class="absolute inset-0 w-full h-2 opacity-0 cursor-pointer"
            />
          </div>

          <!-- Time Display -->
          <div class="text-white text-sm font-mono">
            {{ formatTime(currentTime) }} / {{ formatTime(duration) }}
          </div>

          <!-- Volume Control -->
          <div class="flex items-center space-x-2">
            <button
              @click="toggleMute"
              class="text-white hover:text-gray-300 transition-colors"
            >
              <svg v-if="isMuted" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
              </svg>
            </button>
            <input
              type="range"
              min="0"
              max="1"
              step="0.1"
              :value="volume"
              @input="onVolumeChange"
              class="w-16 h-1 bg-gray-600 rounded-lg appearance-none cursor-pointer"
            />
          </div>

          <!-- Fullscreen Button -->
          <button
            @click="toggleFullscreen"
            class="text-white hover:text-gray-300 transition-colors"
          >
            <svg v-if="!isFullscreen" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
            </svg>
            <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Video Information -->
    <div class="p-6">
      <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
          <h1 class="text-2xl font-bold text-white mb-2">{{ video.title }}</h1>
          <p class="text-gray-300 mb-4">{{ video.description }}</p>
          
          <!-- Video Stats -->
          <div class="flex items-center space-x-6 text-sm text-gray-400 mb-4">
            <div class="flex items-center space-x-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
              </svg>
              <span>{{ video.views.toLocaleString() }} views</span>
            </div>
            <div class="flex items-center space-x-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              <span>{{ video.likes }} likes</span>
            </div>
            <div class="flex items-center space-x-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
              <span>{{ formatDuration(video.duration) }}</span>
            </div>
          </div>

          <!-- Tags -->
          <div class="flex flex-wrap gap-2 mb-4">
            <span
              v-for="tag in video.tags"
              :key="tag"
              class="px-2 py-1 bg-gray-700 text-gray-300 rounded text-xs"
            >
              #{{ tag }}
            </span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-3">
          <button
            @click="toggleBookmark"
            :class="[
              'flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors',
              isBookmarked
                ? 'bg-primary-600 text-white'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
            ]"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
            </svg>
            <span>{{ isBookmarked ? 'Bookmarked' : 'Bookmark' }}</span>
          </button>

          <button
            @click="toggleLike"
            :class="[
              'flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors',
              isLiked
                ? 'bg-red-600 text-white'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
            ]"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <span>{{ video.likes }}</span>
          </button>
        </div>
      </div>

      <!-- Chapters -->
      <div v-if="video.chapters && video.chapters.length > 0" class="mb-6">
        <h3 class="text-lg font-semibold text-white mb-3">Chapters</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
          <button
            v-for="chapter in video.chapters"
            :key="chapter.id"
            @click="seekTo(chapter.startTime)"
            class="text-left p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors"
          >
            <div class="text-sm font-medium text-white mb-1">{{ chapter.title }}</div>
            <div class="text-xs text-gray-400">{{ formatTime(chapter.startTime) }} - {{ formatTime(chapter.endTime) }}</div>
            <div v-if="chapter.description" class="text-xs text-gray-500 mt-1">{{ chapter.description }}</div>
          </button>
        </div>
      </div>

      <!-- Subtitles -->
      <div v-if="video.subtitles && video.subtitles.length > 0" class="mb-6">
        <h3 class="text-lg font-semibold text-white mb-3">Subtitles</h3>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="subtitle in video.subtitles"
            :key="subtitle.id"
            @click="selectSubtitle(subtitle)"
            :class="[
              'px-3 py-1 rounded text-sm transition-colors',
              selectedSubtitle?.id === subtitle.id
                ? 'bg-primary-600 text-white'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
            ]"
          >
            {{ subtitle.label }}
          </button>
          <button
            @click="disableSubtitles"
            :class="[
              'px-3 py-1 rounded text-sm transition-colors',
              !selectedSubtitle
                ? 'bg-primary-600 text-white'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
            ]"
          >
            Off
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { advancedContentService, type VideoContent, type SubtitleTrack } from '../services/advancedContentService'

interface Props {
  video: VideoContent
  autoplay?: boolean
  startTime?: number
}

const props = withDefaults(defineProps<Props>(), {
  autoplay: false,
  startTime: 0
})

const emit = defineEmits<{
  progress: [progress: number]
  completed: []
  timeUpdate: [currentTime: number, duration: number]
}>()

// Refs
const videoElement = ref<HTMLVideoElement>()
const progressBar = ref<HTMLInputElement>()

// State
const isPlaying = ref(false)
const isLoading = ref(true)
const currentTime = ref(0)
const duration = ref(0)
const volume = ref(1)
const isMuted = ref(false)
const isFullscreen = ref(false)
const isBookmarked = ref(false)
const isLiked = ref(false)
const selectedSubtitle = ref<SubtitleTrack | null>(null)

// Computed
const progressPercentage = computed(() => {
  if (duration.value === 0) return 0
  return (currentTime.value / duration.value) * 100
})

// Methods
const onLoadedMetadata = () => {
  isLoading.value = false
  duration.value = videoElement.value?.duration || 0
  
  if (props.startTime > 0) {
    seekTo(props.startTime)
  }
  
  if (props.autoplay) {
    play()
  }
}

const onTimeUpdate = () => {
  if (videoElement.value) {
    currentTime.value = videoElement.value.currentTime
    emit('timeUpdate', currentTime.value, duration.value)
    
    // Emit progress every 10 seconds
    if (Math.floor(currentTime.value) % 10 === 0) {
      const progress = (currentTime.value / duration.value) * 100
      emit('progress', progress)
    }
  }
}

const onEnded = () => {
  isPlaying.value = false
  emit('completed')
  
  // Mark as completed in service
  if (advancedContentService.getCurrentUser()) {
    advancedContentService.completeContent(
      advancedContentService.getCurrentUser().id,
      props.video.id,
      'video'
    )
  }
}

const onPlay = () => {
  isPlaying.value = true
}

const onPause = () => {
  isPlaying.value = false
}

const onProgressChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const newTime = parseFloat(target.value)
  seekTo(newTime)
}

const onVolumeChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const newVolume = parseFloat(target.value)
  setVolume(newVolume)
}

const togglePlayPause = () => {
  if (videoElement.value) {
    if (isPlaying.value) {
      videoElement.value.pause()
    } else {
      videoElement.value.play()
    }
  }
}

const play = () => {
  if (videoElement.value) {
    videoElement.value.play()
  }
}

const pause = () => {
  if (videoElement.value) {
    videoElement.value.pause()
  }
}

const seekTo = (time: number) => {
  if (videoElement.value) {
    videoElement.value.currentTime = time
    currentTime.value = time
  }
}

const setVolume = (newVolume: number) => {
  if (videoElement.value) {
    videoElement.value.volume = newVolume
    volume.value = newVolume
    isMuted.value = newVolume === 0
  }
}

const toggleMute = () => {
  if (videoElement.value) {
    if (isMuted.value) {
      videoElement.value.volume = volume.value
      isMuted.value = false
    } else {
      videoElement.value.volume = 0
      isMuted.value = true
    }
  }
}

const toggleFullscreen = () => {
  if (!document.fullscreenElement) {
    videoElement.value?.requestFullscreen()
    isFullscreen.value = true
  } else {
    document.exitFullscreen()
    isFullscreen.value = false
  }
}

const selectSubtitle = (subtitle: SubtitleTrack) => {
  selectedSubtitle.value = subtitle
  
  // Enable subtitle track
  if (videoElement.value) {
    const tracks = videoElement.value.textTracks
    for (let i = 0; i < tracks.length; i++) {
      tracks[i].mode = tracks[i].language === subtitle.language ? 'showing' : 'hidden'
    }
  }
}

const disableSubtitles = () => {
  selectedSubtitle.value = null
  
  // Disable all subtitle tracks
  if (videoElement.value) {
    const tracks = videoElement.value.textTracks
    for (let i = 0; i < tracks.length; i++) {
      tracks[i].mode = 'hidden'
    }
  }
}

const toggleBookmark = async () => {
  if (advancedContentService.getCurrentUser()) {
    const bookmarked = await advancedContentService.toggleBookmark(
      advancedContentService.getCurrentUser().id,
      props.video.id
    )
    isBookmarked.value = bookmarked
  }
}

const toggleLike = () => {
  isLiked.value = !isLiked.value
  // TODO: Implement like functionality
}

const formatTime = (seconds: number): string => {
  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)
  const secs = Math.floor(seconds % 60)

  if (hours > 0) {
    return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
  }
  return `${minutes}:${secs.toString().padStart(2, '0')}`
}

const formatDuration = (seconds: number): string => {
  return formatTime(seconds)
}

// Keyboard shortcuts
const handleKeydown = (event: KeyboardEvent) => {
  switch (event.code) {
    case 'Space':
      event.preventDefault()
      togglePlayPause()
      break
    case 'ArrowLeft':
      event.preventDefault()
      seekTo(Math.max(0, currentTime.value - 10))
      break
    case 'ArrowRight':
      event.preventDefault()
      seekTo(Math.min(duration.value, currentTime.value + 10))
      break
    case 'KeyM':
      event.preventDefault()
      toggleMute()
      break
    case 'KeyF':
      event.preventDefault()
      toggleFullscreen()
      break
  }
}

// Lifecycle
onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
  
  // Record view
  advancedContentService.recordView(props.video.id, 'video')
  
  // Check bookmark status
  if (advancedContentService.getCurrentUser()) {
    advancedContentService.getBookmarks(advancedContentService.getCurrentUser().id)
      .then(bookmarks => {
        isBookmarked.value = bookmarks.some(b => b.contentId === props.video.id)
      })
  }
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})

// Watch for fullscreen changes
watch(isFullscreen, (newValue) => {
  if (!newValue && document.fullscreenElement) {
    document.exitFullscreen()
  }
})
</script>

<style scoped>
/* Custom range slider styles */
input[type="range"]::-webkit-slider-thumb {
  appearance: none;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #3b82f6;
  cursor: pointer;
}

input[type="range"]::-moz-range-thumb {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #3b82f6;
  cursor: pointer;
  border: none;
}
</style>
