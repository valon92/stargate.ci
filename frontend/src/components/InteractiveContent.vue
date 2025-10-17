<template>
  <div class="interactive-content">
    <!-- Like/Reaction Section -->
    <div class="reactions-section mb-4">
      <div class="flex items-center gap-4">
        <!-- Like Button -->
        <button
          @click="toggleLike"
          :class="[
            'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
            isLiked 
              ? 'bg-red-500 text-white' 
              : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
          ]"
        >
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <span>{{ likesCount }}</span>
        </button>

        <!-- Comment Button -->
        <button
          @click="toggleComments"
          :class="[
            'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
            showComments 
              ? 'bg-blue-500 text-white' 
              : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
          ]"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          <span>{{ commentsCount }}</span>
        </button>

        <!-- Share Button -->
        <button
          @click="toggleShare"
          :class="[
            'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
            showShare 
              ? 'bg-green-500 text-white' 
              : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
          ]"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
          </svg>
          <span>Share</span>
        </button>
      </div>
    </div>

    <!-- Comments Section -->
    <div v-if="showComments" class="comments-section mb-6">
      <div class="bg-gray-800/50 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-white mb-4">Comments</h3>
        
        <!-- Add Comment Form -->
        <div v-if="isSubscribed" class="mb-4">
          <div class="flex gap-2">
            <input
              v-model="newComment"
              type="text"
              placeholder="Write a comment..."
              class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              @keyup.enter="addComment"
            >
            <button
              @click="addComment"
              :disabled="!newComment.trim()"
              class="px-4 py-2 bg-primary-500 hover:bg-primary-600 disabled:bg-gray-600 text-white rounded-lg font-medium transition-colors"
            >
              Post
            </button>
          </div>
        </div>

        <!-- Subscribe to Comment Message -->
        <div v-else class="mb-4 p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg">
          <p class="text-blue-300 text-sm">
            <router-link to="/subscribe" class="text-blue-400 hover:text-blue-300 underline">
              Subscribe to Stargate.ci
            </router-link>
            to join the conversation and comment on content.
          </p>
        </div>

        <!-- Comments List -->
        <div class="space-y-4">
          <div
            v-for="comment in comments"
            :key="comment.id"
            :class="[
              'bg-gray-700/50 rounded-lg p-4 transition-all duration-200',
              comment.isPinned ? 'border-l-4 border-yellow-500 bg-yellow-900/10' : ''
            ]"
          >
            <!-- Pinned Badge -->
            <div v-if="comment.isPinned" class="flex items-center gap-2 mb-3">
              <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              <span class="text-yellow-500 text-sm font-medium">Pinned Comment</span>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                {{ comment.userAvatar }}
              </div>
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                  <span class="font-medium text-white">{{ comment.user }}</span>
                  <span class="text-gray-400 text-sm">{{ formatDate(comment.date) }}</span>
                  <span v-if="comment.isEdited" class="text-gray-500 text-xs">(edited)</span>
                </div>
                
                <!-- Comment Text -->
                <div v-if="editingComment !== comment.id" class="text-gray-300 mb-3">
                  {{ comment.text }}
                </div>
                
                <!-- Edit Form -->
                <div v-else class="mb-3">
                  <textarea
                    v-model="editText"
                    class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    rows="3"
                    placeholder="Edit your comment..."
                  ></textarea>
                  <div class="flex gap-2 mt-2">
                    <button
                      @click="saveEdit(comment.id)"
                      class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors"
                    >
                      Save
                    </button>
                    <button
                      @click="editingComment = null; editText = ''"
                      class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"
                    >
                      Cancel
                    </button>
                  </div>
                </div>

                <!-- Comment Actions -->
                <div class="flex items-center gap-4">
                  <button
                    @click="toggleCommentLike(comment.id)"
                    :class="[
                      'flex items-center gap-1 text-sm transition-colors',
                      comment.isLiked ? 'text-red-400' : 'text-gray-400 hover:text-red-400'
                    ]"
                  >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span>{{ comment.likes }}</span>
                  </button>
                  
                  <button
                    @click="replyToComment(comment.id)"
                    class="text-gray-400 hover:text-blue-400 text-sm transition-colors"
                  >
                    Reply ({{ comment.replies.length }})
                  </button>
                  
                  <button
                    @click="editComment(comment.id)"
                    class="text-gray-400 hover:text-yellow-400 text-sm transition-colors"
                  >
                    Edit
                  </button>
                  
                  <button
                    @click="deleteComment(comment.id)"
                    class="text-gray-400 hover:text-red-400 text-sm transition-colors"
                  >
                    Delete
                  </button>
                  
                  <button
                    @click="pinComment(comment.id)"
                    :class="[
                      'text-sm transition-colors',
                      comment.isPinned ? 'text-yellow-400' : 'text-gray-400 hover:text-yellow-400'
                    ]"
                  >
                    {{ comment.isPinned ? 'Unpin' : 'Pin' }}
                  </button>
                </div>

                <!-- Reply Form -->
                <div v-if="replyingTo === comment.id" class="mt-4 p-3 bg-gray-600/50 rounded-lg">
                  <textarea
                    v-model="replyText"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    rows="2"
                    placeholder="Write a reply..."
                  ></textarea>
                  <div class="flex gap-2 mt-2">
                    <button
                      @click="addReply(comment.id)"
                      class="px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded transition-colors"
                    >
                      Reply
                    </button>
                    <button
                      @click="replyingTo = null; replyText = ''"
                      class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"
                    >
                      Cancel
                    </button>
                  </div>
                </div>

                <!-- Replies -->
                <div v-if="comment.replies.length > 0" class="mt-4 space-y-3">
                  <div
                    v-for="reply in comment.replies"
                    :key="reply.id"
                    class="bg-gray-600/30 rounded-lg p-3 ml-4"
                  >
                    <div class="flex items-start gap-3">
                      <div class="w-8 h-8 bg-secondary-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                        {{ reply.userAvatar }}
                      </div>
                      <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                          <span class="font-medium text-white text-sm">{{ reply.user }}</span>
                          <span class="text-gray-400 text-xs">{{ formatDate(reply.date) }}</span>
                        </div>
                        <p class="text-gray-300 text-sm">{{ reply.text }}</p>
                        <div class="flex items-center gap-3 mt-2">
                          <button
                            @click="toggleCommentLike(reply.id)"
                            :class="[
                              'flex items-center gap-1 text-xs transition-colors',
                              reply.isLiked ? 'text-red-400' : 'text-gray-400 hover:text-red-400'
                            ]"
                          >
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span>{{ reply.likes }}</span>
                          </button>
                          <button
                            @click="deleteComment(reply.id)"
                            class="text-gray-400 hover:text-red-400 text-xs transition-colors"
                          >
                            Delete
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Load More Comments -->
        <button
          v-if="hasMoreComments"
          @click="loadMoreComments"
          class="w-full mt-4 py-2 text-primary-400 hover:text-primary-300 transition-colors"
        >
          Load more comments
        </button>
      </div>
    </div>

    <!-- Share Modal -->
    <div v-if="showShare" class="share-modal fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-white">Share Content</h3>
          <button
            @click="toggleShare"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Share Options -->
        <div class="space-y-3">
          <!-- Facebook -->
          <button
            @click="shareToFacebook"
            class="w-full flex items-center gap-3 p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <span>Share on Facebook</span>
          </button>

          <!-- X (Twitter) -->
          <button
            @click="shareToX"
            class="w-full flex items-center gap-3 p-3 bg-black hover:bg-gray-800 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
            <span>Share on X</span>
          </button>

          <!-- WhatsApp -->
          <button
            @click="shareToWhatsApp"
            class="w-full flex items-center gap-3 p-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
            <span>Share on WhatsApp</span>
          </button>

          <!-- Messenger -->
          <button
            @click="shareToMessenger"
            class="w-full flex items-center gap-3 p-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 0C5.373 0 0 4.975 0 11.111c0 3.497 1.745 6.616 4.472 8.652V24l4.086-2.242c1.09.301 2.246.464 3.442.464 6.627 0 12-4.974 12-11.111C24 4.975 18.627 0 12 0zm1.193 14.963l-3.056-3.259-5.963 3.259L10.733 8l3.13 3.259L19.99 8l-6.797 6.963z"/>
            </svg>
            <span>Share on Messenger</span>
          </button>

          <!-- Copy Link -->
          <button
            @click="copyLink"
            class="w-full flex items-center gap-3 p-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span>Copy Link</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div
      v-if="showNotification"
      :class="[
        'fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform',
        notificationType === 'success' ? 'bg-green-600 text-white' : '',
        notificationType === 'info' ? 'bg-blue-600 text-white' : '',
        notificationType === 'warning' ? 'bg-yellow-600 text-white' : ''
      ]"
    >
      <div class="flex items-center gap-2">
        <svg v-if="notificationType === 'success'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        </svg>
        <svg v-else-if="notificationType === 'info'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
        </svg>
        <svg v-else-if="notificationType === 'warning'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
        </svg>
        <span class="font-medium">{{ notificationMessage }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

interface Comment {
  id: string
  user: string
  userAvatar: string
  text: string
  date: string
  likes: number
  isLiked: boolean
  replies: Comment[]
  isPinned: boolean
  isEdited: boolean
  editDate?: string
  parentId?: string
}

interface Props {
  contentId: string
  contentType: 'video' | 'news' | 'event'
  initialLikes?: number
  initialComments?: Comment[]
}

const props = withDefaults(defineProps<Props>(), {
  initialLikes: 0,
  initialComments: () => []
})

const router = useRouter()

// Reactive data
const isLiked = ref(false)
const likesCount = ref(props.initialLikes)
const showComments = ref(false)
const showShare = ref(false)
const comments = ref<Comment[]>(props.initialComments)
const commentsCount = ref(props.initialComments.length)
const newComment = ref('')
const hasMoreComments = ref(false)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref<'success' | 'info' | 'warning'>('success')
const replyingTo = ref<string | null>(null)
const replyText = ref('')
const editingComment = ref<string | null>(null)
const editText = ref('')
const commentsPerPage = 10
const currentPage = ref(1)
const isLoadingComments = ref(false)

// Check if user is subscribed
const isSubscribed = computed(() => {
  const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
  return subscribers.length > 0
})

// Methods
const showToast = (message: string, type: 'success' | 'info' | 'warning' = 'success') => {
  notificationMessage.value = message
  notificationType.value = type
  showNotification.value = true
  
  setTimeout(() => {
    showNotification.value = false
  }, 3000)
}

const toggleLike = () => {
  if (!isSubscribed.value) {
    showToast('Subscribe to Stargate.ci to like content!', 'info')
    router.push('/subscribe')
    return
  }
  
  isLiked.value = !isLiked.value
  likesCount.value += isLiked.value ? 1 : -1
  
  // Save to localStorage with real counts
  const likes = JSON.parse(localStorage.getItem('stargate_likes') || '{}')
  if (!likes[props.contentId]) {
    likes[props.contentId] = { isLiked: false, count: props.initialLikes }
  }
  likes[props.contentId].isLiked = isLiked.value
  likes[props.contentId].count = likesCount.value
  localStorage.setItem('stargate_likes', JSON.stringify(likes))
  
  // Show notification
  showToast(isLiked.value ? 'Liked!' : 'Unliked!', 'success')
  
  // Track engagement
  trackEngagement('like', props.contentType)
}

const toggleComments = () => {
  showComments.value = !showComments.value
  if (showComments.value) {
    trackEngagement('view_comments', props.contentType)
  }
}

const toggleShare = () => {
  if (!isSubscribed.value) {
    showToast('Subscribe to Stargate.ci to share content!', 'info')
    router.push('/subscribe')
    return
  }
  
  showShare.value = !showShare.value
  if (showShare.value) {
    trackEngagement('view_share', props.contentType)
  }
}

const addComment = () => {
  if (!newComment.value.trim()) {
    showToast('Please enter a comment!', 'warning')
    return
  }
  
  if (!isSubscribed.value) {
    showToast('Subscribe to Stargate.ci to comment!', 'info')
    router.push('/subscribe')
    return
  }
  
  const comment: Comment = {
    id: Date.now().toString(),
    user: 'Subscriber', // In real app, get from user profile
    userAvatar: 'S', // In real app, get from user profile
    text: newComment.value,
    date: new Date().toISOString(),
    likes: 0,
    isLiked: false,
    replies: [],
    isPinned: false,
    isEdited: false
  }
  
  comments.value.unshift(comment)
  commentsCount.value++
  newComment.value = ''
  
  // Save to localStorage
  const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
  if (!allComments[props.contentId]) {
    allComments[props.contentId] = []
  }
  allComments[props.contentId].push(comment)
  localStorage.setItem('stargate_comments', JSON.stringify(allComments))
  
  // Show notification
  showToast('Comment added successfully!', 'success')
  
  // Track engagement
  trackEngagement('comment', props.contentType)
}

const toggleCommentLike = (commentId: string) => {
  const comment = comments.value.find(c => c.id === commentId)
  if (!comment) return
  
  comment.isLiked = !comment.isLiked
  comment.likes += comment.isLiked ? 1 : -1
  
  // Save to localStorage
  const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
  if (allComments[props.contentId]) {
    const commentIndex = allComments[props.contentId].findIndex((c: Comment) => c.id === commentId)
    if (commentIndex !== -1) {
      allComments[props.contentId][commentIndex] = comment
      localStorage.setItem('stargate_comments', JSON.stringify(allComments))
    }
  }
}

const replyToComment = (commentId: string) => {
  if (!isSubscribed.value) {
    showToast('Subscribe to Stargate.ci to reply to comments!', 'info')
    router.push('/subscribe')
    return
  }
  
  replyingTo.value = commentId
  replyText.value = ''
}

const addReply = (parentId: string) => {
  if (!replyText.value.trim()) {
    showToast('Please enter a reply!', 'warning')
    return
  }
  
  const parentComment = comments.value.find(c => c.id === parentId)
  if (!parentComment) return
  
  const reply: Comment = {
    id: Date.now().toString(),
    user: 'Subscriber',
    userAvatar: 'S',
    text: replyText.value,
    date: new Date().toISOString(),
    likes: 0,
    isLiked: false,
    replies: [],
    isPinned: false,
    isEdited: false,
    parentId: parentId
  }
  
  parentComment.replies.push(reply)
  replyText.value = ''
  replyingTo.value = null
  
  // Save to localStorage
  const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
  if (allComments[props.contentId]) {
    const commentIndex = allComments[props.contentId].findIndex((c: Comment) => c.id === parentId)
    if (commentIndex !== -1) {
      allComments[props.contentId][commentIndex] = parentComment
      localStorage.setItem('stargate_comments', JSON.stringify(allComments))
    }
  }
  
  showToast('Reply added successfully!', 'success')
  trackEngagement('reply', props.contentType)
}

const editComment = (commentId: string) => {
  if (!isSubscribed.value) {
    showToast('Subscribe to Stargate.ci to edit comments!', 'info')
    router.push('/subscribe')
    return
  }
  
  const comment = comments.value.find(c => c.id === commentId)
  if (!comment) return
  
  editingComment.value = commentId
  editText.value = comment.text
}

const saveEdit = (commentId: string) => {
  if (!editText.value.trim()) {
    showToast('Please enter a comment!', 'warning')
    return
  }
  
  const comment = comments.value.find(c => c.id === commentId)
  if (!comment) return
  
  comment.text = editText.value
  comment.isEdited = true
  comment.editDate = new Date().toISOString()
  
  // Save to localStorage
  const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
  if (allComments[props.contentId]) {
    const commentIndex = allComments[props.contentId].findIndex((c: Comment) => c.id === commentId)
    if (commentIndex !== -1) {
      allComments[props.contentId][commentIndex] = comment
      localStorage.setItem('stargate_comments', JSON.stringify(allComments))
    }
  }
  
  editingComment.value = null
  editText.value = ''
  showToast('Comment updated successfully!', 'success')
}

const deleteComment = (commentId: string) => {
  if (!isSubscribed.value) {
    showToast('Subscribe to Stargate.ci to delete comments!', 'info')
    router.push('/subscribe')
    return
  }
  
  const commentIndex = comments.value.findIndex(c => c.id === commentId)
  if (commentIndex === -1) return
  
  comments.value.splice(commentIndex, 1)
  commentsCount.value--
  
  // Save to localStorage
  const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
  if (allComments[props.contentId]) {
    allComments[props.contentId] = allComments[props.contentId].filter((c: Comment) => c.id !== commentId)
    localStorage.setItem('stargate_comments', JSON.stringify(allComments))
  }
  
  showToast('Comment deleted successfully!', 'success')
}

const pinComment = (commentId: string) => {
  if (!isSubscribed.value) {
    showToast('Subscribe to Stargate.ci to pin comments!', 'info')
    router.push('/subscribe')
    return
  }
  
  const comment = comments.value.find(c => c.id === commentId)
  if (!comment) return
  
  // Unpin all other comments first
  comments.value.forEach(c => c.isPinned = false)
  
  // Pin this comment
  comment.isPinned = true
  
  // Move pinned comment to top
  const commentIndex = comments.value.findIndex(c => c.id === commentId)
  if (commentIndex !== -1) {
    const pinnedComment = comments.value.splice(commentIndex, 1)[0]
    comments.value.unshift(pinnedComment)
  }
  
  // Save to localStorage
  const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
  if (allComments[props.contentId]) {
    allComments[props.contentId] = comments.value
    localStorage.setItem('stargate_comments', JSON.stringify(allComments))
  }
  
  showToast('Comment pinned successfully!', 'success')
}

const loadMoreComments = () => {
  // In a real app, this would load more comments from API
  hasMoreComments.value = false
}

// Share methods
const shareToFacebook = () => {
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`Check out this amazing content about Stargate Project and Cristal Intelligence on Stargate.ci! 🚀💎`)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank')
  showToast('Shared on Facebook!', 'success')
  trackEngagement('share_facebook', props.contentType)
  showShare.value = false
}

const shareToX = () => {
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`Check out this amazing content about Stargate Project and Cristal Intelligence on Stargate.ci! 🚀💎 #StargateProject #CristalIntelligence #AI`)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
  showToast('Shared on X!', 'success')
  trackEngagement('share_x', props.contentType)
  showShare.value = false
}

const shareToWhatsApp = () => {
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`Check out this amazing content about Stargate Project and Cristal Intelligence on Stargate.ci! 🚀💎`)
  window.open(`https://wa.me/?text=${text}%20${url}`, '_blank')
  showToast('Shared on WhatsApp!', 'success')
  trackEngagement('share_whatsapp', props.contentType)
  showShare.value = false
}

const shareToMessenger = () => {
  const url = encodeURIComponent(window.location.href)
  window.open(`https://www.facebook.com/dialog/send?link=${url}&app_id=YOUR_APP_ID`, '_blank')
  showToast('Shared on Messenger!', 'success')
  trackEngagement('share_messenger', props.contentType)
  showShare.value = false
}

const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href)
    showToast('Link copied to clipboard!', 'success')
    trackEngagement('share_copy_link', props.contentType)
    showShare.value = false
  } catch (err) {
    showToast('Failed to copy link', 'warning')
    console.error('Failed to copy link:', err)
  }
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return 'Just now'
  if (diffInHours < 24) return `${diffInHours}h ago`
  if (diffInHours < 168) return `${Math.floor(diffInHours / 24)}d ago`
  return date.toLocaleDateString()
}

const trackEngagement = (action: string, contentType: string) => {
  const engagement = {
    action,
    contentType,
    contentId: props.contentId,
    timestamp: new Date().toISOString(),
    userAgent: navigator.userAgent
  }
  
  // Save to localStorage
  const engagements = JSON.parse(localStorage.getItem('stargate_engagements') || '[]')
  engagements.push(engagement)
  localStorage.setItem('stargate_engagements', JSON.stringify(engagements))
  
  // In a real app, send to analytics service
  console.log('Engagement tracked:', engagement)
}

// Load saved data on mount
onMounted(() => {
  // Load likes
  const likes = JSON.parse(localStorage.getItem('stargate_likes') || '{}')
  if (likes[props.contentId]) {
    isLiked.value = likes[props.contentId].isLiked
    likesCount.value = likes[props.contentId].count
  } else {
    // Initialize with zero likes
    likesCount.value = props.initialLikes
  }
  
  // Load comments
  const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
  if (allComments[props.contentId]) {
    comments.value = allComments[props.contentId]
    commentsCount.value = allComments[props.contentId].length
  } else {
    // Initialize with empty comments
    comments.value = props.initialComments
    commentsCount.value = props.initialComments.length
  }
  
  // Add some test comments for demonstration (remove in production)
  if (comments.value.length === 0 && props.contentId === 'stargate-intro-video') {
    const testComments = [
      {
        id: 'test-1',
        user: 'AI Enthusiast',
        userAvatar: 'A',
        text: 'This is amazing! The Stargate Project will revolutionize AI infrastructure.',
        date: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(), // 2 hours ago
        likes: 5,
        isLiked: false,
        replies: [],
        isPinned: false,
        isEdited: false
      },
      {
        id: 'test-2',
        user: 'Tech Researcher',
        userAvatar: 'T',
        text: 'The $500 billion investment shows the scale of this initiative. Exciting times ahead!',
        date: new Date(Date.now() - 1 * 60 * 60 * 1000).toISOString(), // 1 hour ago
        likes: 3,
        isLiked: false,
        replies: [
          {
            id: 'test-reply-1',
            user: 'Developer',
            userAvatar: 'D',
            text: 'I agree! The infrastructure requirements are massive.',
            date: new Date(Date.now() - 30 * 60 * 1000).toISOString(), // 30 minutes ago
            likes: 1,
            isLiked: false,
            replies: [],
            isPinned: false,
            isEdited: false,
            parentId: 'test-2'
          }
        ],
        isPinned: false,
        isEdited: false
      }
    ]
    
    comments.value = testComments
    commentsCount.value = testComments.length
    
    // Save test comments to localStorage
    const allComments = JSON.parse(localStorage.getItem('stargate_comments') || '{}')
    allComments[props.contentId] = testComments
    localStorage.setItem('stargate_comments', JSON.stringify(allComments))
  }
})
</script>

<style scoped>
.interactive-content {
  @apply w-full;
}

.share-modal {
  backdrop-filter: blur(4px);
}
</style>
