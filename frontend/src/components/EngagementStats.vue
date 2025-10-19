<template>
  <div class="engagement-stats">
    <div class="flex items-center gap-6 text-sm">
      <!-- Total Likes -->
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
        <span class="text-gray-300">{{ formatNumber(totalLikes) }}</span>
        <span class="text-gray-500">Likes</span>
      </div>

      <!-- Total Comments -->
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span class="text-gray-300">{{ formatNumber(totalComments) }}</span>
        <span class="text-gray-500">Comments</span>
      </div>

      <!-- Total Shares -->
      <!-- <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
        </svg>
        <span class="text-gray-300">{{ formatNumber(totalShares) }}</span>
        <span class="text-gray-500">Shares</span>
      </div> -->

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { videoApiService } from '../services/videoApiService'

// Reactive data
const totalLikes = ref(0)
const totalComments = ref(0)
const totalShares = ref(0)

// Methods
const formatNumber = (num: number): string => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M'
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K'
  }
  return num.toString()
}

const calculateStats = async () => {
  try {
    // Get video stats from API
    const videoStatsResponse = await videoApiService.getVideoStats()
    if (videoStatsResponse.success) {
      totalLikes.value = videoStatsResponse.data.total_likes
      totalComments.value = videoStatsResponse.data.total_comments
      totalShares.value = videoStatsResponse.data.total_shares
    }
  } catch (error) {
    console.error('Error loading stats from API:', error)
    // Fallback to localStorage
    const likes = JSON.parse(localStorage.getItem('stargate_likes') || '{}')
    totalLikes.value = Object.values(likes).reduce((sum: number, like: any) => {
      return sum + (like.count || 0)
    }, 0)

    const comments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
    totalComments.value = Object.values(comments).reduce((sum: number, commentList: any) => {
      if (Array.isArray(commentList)) {
        return sum + commentList.reduce((commentSum: number, comment: any) => {
          return commentSum + 1 + (comment.replies ? comment.replies.length : 0)
        }, 0)
      }
      return sum
    }, 0)

    const engagements = JSON.parse(localStorage.getItem('stargate_engagements') || '[]')
    totalShares.value = engagements.filter((engagement: any) => 
      engagement.action.startsWith('share_')
    ).length
  }
}

// Update stats every 30 seconds
const updateStats = () => {
  calculateStats()
  setTimeout(updateStats, 30000)
}

onMounted(() => {
  calculateStats()
  updateStats()
})
</script>

<style scoped>
.engagement-stats {
  background-color: rgba(31, 41, 55, 0.5);
  border-radius: 0.5rem;
  padding: 0.75rem;
  border: 1px solid rgba(55, 65, 81, 1);
}
</style>