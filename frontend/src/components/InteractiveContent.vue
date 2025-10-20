<template>
  <div class="interactive-content">
    <!-- YouTube-like User Status Bar -->
    <div class="user-status-bar mb-4 p-3 bg-gray-800/50 border border-gray-700 rounded-lg">
      <div class="flex items-center justify-between">
        <!-- User Info -->
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
            <span class="text-white font-bold text-sm">
              {{ isSubscribed && currentUser ? currentUser.username.charAt(0).toUpperCase() : 'G' }}
            </span>
          </div>
          <div>
            <span class="text-white font-medium text-sm">
              {{ isSubscribed && currentUser ? currentUser.username : 'Guest User' }}
            </span>
            <span v-if="isSubscribed && currentUser" class="text-gray-400 text-xs ml-2">
              {{ currentUser.email }}
            </span>
          </div>
        </div>

        <!-- Subscribe/Unsubscribe Button -->
        <div class="flex items-center gap-2">
          <button
            v-if="!isSubscribed"
            @click="goToSignIn"
            class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-full font-medium text-sm transition-colors"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M18 4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-6 2.5c2.49 0 4.5 2.01 4.5 4.5S14.49 15.5 12 15.5s-4.5-2.01-4.5-4.5S9.51 6.5 12 6.5zM12 17c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Sign In
          </button>
          <button
            v-else
            @click="unsubscribe"
            class="flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-full font-medium text-sm transition-colors"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            Logout
          </button>
          
          <!-- Debug Button - Remove this in production -->
          <button
            @click="createValidSubscriber"
            class="flex items-center gap-2 px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white rounded-full font-medium text-xs transition-colors"
            title="Create valid subscriber for testing"
          >
            🔧 Fix
          </button>
        </div>
      </div>
    </div>

    <!-- YouTube-like Action Buttons -->
    <div class="action-buttons mb-4">
      <div class="flex items-center gap-2">
        <!-- Like Button -->
        <button
          @click="toggleLike"
          :disabled="!isSubscribed"
          :class="[
            'flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-full font-medium transition-all duration-200',
            !isSubscribed ? 'cursor-not-allowed opacity-50' : '',
            isLiked ? 'bg-red-600 text-white hover:bg-red-700' : ''
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
          class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-full font-medium transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          <span>{{ commentsCount }}</span>
        </button>

        <!-- Share Button -->
        <button
          @click="toggleShare"
          :disabled="!isSubscribed"
          :class="[
            'flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-full font-medium transition-all duration-200',
            !isSubscribed ? 'cursor-not-allowed opacity-50' : ''
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
      <div class="bg-gray-800/90 backdrop-blur-sm border border-gray-600/50 rounded-lg p-6 shadow-xl">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-white">Comments ({{ commentsCount }})</h3>
          <div class="flex items-center gap-2 text-sm text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span>{{ commentsCount }} comments</span>
          </div>
        </div>
        
        <!-- Add Comment Form -->
        <div class="mb-6 p-4 bg-gray-700/50 rounded-lg border border-gray-600/30">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
              {{ isSubscribed && currentUser ? currentUser.username.charAt(0).toUpperCase() : 'G' }}
            </div>
            <div class="flex-1">
              <div v-if="isSubscribed && currentUser" class="mb-2">
                <span class="text-sm text-gray-300">Commenting as <strong class="text-primary-400">{{ currentUser.username }}</strong></span>
              </div>
              <textarea
                v-model="newComment"
                :disabled="!isSubscribed"
                :placeholder="isSubscribed ? 'Share your thoughts about this content...' : 'Sign in to comment... (You can view all comments without signing in)'"
                :class="[
                  'w-full px-4 py-3 border rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none',
                  isSubscribed 
                    ? 'bg-gray-600/50 border-gray-500/50' 
                    : 'bg-gray-800/50 border-gray-700/50 cursor-not-allowed'
                ]"
                rows="3"
                @keyup.ctrl.enter="addComment"
              ></textarea>
              <div class="flex items-center justify-between mt-3">
                <span class="text-xs text-gray-400">Press Ctrl+Enter to post</span>
                <button
                  @click="addComment"
                  :disabled="!newComment.trim()"
                  class="px-6 py-2 bg-primary-500 hover:bg-primary-600 disabled:bg-gray-600 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-primary-500/25"
                >
                  Post Comment
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Sign In Encouragement -->
        <div v-if="!isSubscribed" class="mb-4 p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg">
          <p class="text-blue-300 text-sm">
            <router-link to="/signin" class="text-blue-400 hover:text-blue-300 underline">
              Sign in to Stargate.ci
            </router-link>
            to like, comment, and reply! You can view all comments and likes without signing in.
          </p>
        </div>

        <!-- Comments List -->
        <div class="space-y-4 max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
          <div
            v-for="comment in comments"
            :key="comment.id"
            :class="[
              'bg-gray-700/80 backdrop-blur-sm border border-gray-600/40 rounded-lg p-5 transition-all duration-200 hover:bg-gray-700/90 hover:border-gray-500/60 shadow-lg',
              comment.isPinned ? 'border-l-4 border-yellow-500 bg-yellow-900/20 shadow-yellow-500/20' : ''
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
                <div v-if="replyingTo === comment.id" class="mt-4 p-4 bg-gray-600/70 backdrop-blur-sm border border-gray-500/40 rounded-lg shadow-lg">
                  <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-secondary-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                      {{ isSubscribed ? 'S' : 'G' }}
                    </div>
                    <div class="flex-1">
                      <textarea
                        v-model="replyText"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-500/50 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                        rows="2"
                        :placeholder="isSubscribed ? 'Write a reply...' : 'Write a reply... (Sign in to reply, but you can view all replies without signing in)'"
                        @keyup.ctrl.enter="addReply(comment.id)"
                      ></textarea>
                      <div class="flex items-center justify-between mt-3">
                        <span class="text-xs text-gray-400">Press Ctrl+Enter to reply</span>
                        <div class="flex gap-2">
                          <button
                            @click="addReply(comment.id)"
                            class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-all duration-200 shadow-md hover:shadow-primary-500/25"
                          >
                            Reply
                          </button>
                          <button
                            @click="replyingTo = null; replyText = ''"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition-all duration-200"
                          >
                            Cancel
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Replies -->
                <div v-if="comment.replies.length > 0" class="mt-4 space-y-3">
                  <div
                    v-for="reply in comment.replies"
                    :key="reply.id"
                    class="bg-gray-600/60 backdrop-blur-sm border border-gray-500/30 rounded-lg p-4 ml-6 shadow-md hover:bg-gray-600/70 transition-all duration-200"
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
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { videoApiService } from '../services/videoApiService'

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
const showComments = ref(true) // Auto-open comments section
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

// Subscription status - reactive (same as Header.vue)
const subscriptionStatus = ref(false)

// Check if user is subscribed
const isSubscribed = computed(() => {
  return subscriptionStatus.value
})

const currentUser = computed(() => {
  const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
  return subscribers.length > 0 ? subscribers[0] : null
})

// Update subscription status function (same as Header.vue)
const updateSubscriptionStatus = () => {
  const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
  subscriptionStatus.value = subscribers.length > 0
  
  // Note: We don't clear comments and likes when user logs out
  // Comments and likes should remain visible to all users
  // Only interactive actions (like, comment, reply) are disabled for non-signed-in users
}

// Methods
const showToast = (message: string, type: 'success' | 'info' | 'warning' = 'success') => {
  notificationMessage.value = message
  notificationType.value = type
  showNotification.value = true
  
  setTimeout(() => {
    showNotification.value = false
  }, 3000)
}

    const toggleLike = async () => {
      // Check if user is subscribed
      if (!isSubscribed.value) {
        showToast('Please sign in to like content!', 'warning')
        setTimeout(() => {
          showToast('You can view all likes and comments without signing in!', 'info')
        }, 1000)
        return
      }
      
      try {
        const sessionId = videoApiService.getSessionId()
        const subscriberId = videoApiService.getSubscriberId()
        
        console.log('toggleLike - contentId:', props.contentId)
        console.log('toggleLike - subscriberId:', subscriberId)
        console.log('toggleLike - sessionId:', sessionId)
        
        
        const response = await videoApiService.toggleLike(
          props.contentId,
          subscriberId,
          sessionId
        )
        
        console.log('Like response:', response)
        
        if (response.success) {
          isLiked.value = response.data.is_liked
          likesCount.value = response.data.likes_count
          
          // Show notification
          showToast(isLiked.value ? 'Liked!' : 'Unliked!', 'success')
          
          // Track engagement
          trackEngagement('like', props.contentType)
        } else {
          console.error('Like failed:', response)
          showToast('Failed to update like', 'warning')
        }
      } catch (error) {
        console.error('Error toggling like:', error)
        showToast('Failed to update like', 'warning')
      }
    }

const toggleComments = () => {
  // Allow viewing comments for all users (subscribed and non-subscribed)
  showComments.value = !showComments.value
  if (showComments.value) {
    trackEngagement('view_comments', props.contentType)
  }
}

const toggleShare = () => {
  // Check if user is subscribed
      if (!isSubscribed.value) {
        showToast('Please sign in to share content!', 'warning')
        setTimeout(() => {
          showToast('You can view all content without signing in!', 'info')
        }, 1000)
        return
      }
  
  showShare.value = !showShare.value
  if (showShare.value) {
    trackEngagement('view_share', props.contentType)
  }
}


// Go to sign in page
const goToSignIn = () => {
  router.push('/signin')
}

// Unsubscribe/Logout functionality
const unsubscribe = () => {
  if (confirm('Are you sure you want to logout from Stargate.ci?')) {
    // Remove subscriber from localStorage (logout)
    localStorage.removeItem('stargate_subscribers')
    localStorage.removeItem('stargate_session_id')
    
    // Update subscription status
    updateSubscriptionStatus()
    
    // Show notification
    showToast('Successfully logged out! You can still view comments and likes.', 'success')
    
    // Dispatch custom event to update navbar and other components
    window.dispatchEvent(new CustomEvent('subscription-changed'))
    
    // Note: Comments and likes remain visible - only interactive actions are disabled
  }
}

// Create valid subscriber for testing
const createValidSubscriber = () => {
  videoApiService.createValidSubscriber()
  showToast('Valid subscriber created! Please refresh the page.', 'success')
  
  // Dispatch custom event to update navbar
  window.dispatchEvent(new CustomEvent('subscription-changed'))
  
  setTimeout(() => {
    window.location.reload()
  }, 1500)
}

    const addComment = async () => {
      // Check if user is subscribed
      if (!isSubscribed.value) {
        showToast('Please sign in to comment!', 'warning')
        setTimeout(() => {
          showToast('You can view all comments without signing in!', 'info')
        }, 1000)
        return
      }
      
      if (!newComment.value.trim()) {
        showToast('Please enter a comment!', 'warning')
        return
      }
      
      try {
        const sessionId = videoApiService.getSessionId()
        const subscriberId = videoApiService.getSubscriberId()
        
        console.log('addComment - contentId:', props.contentId)
        console.log('addComment - subscriberId:', subscriberId)
        console.log('addComment - sessionId:', sessionId)
        console.log('addComment - comment:', newComment.value)
        
        // Get subscriber username if available
        const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
        console.log('addComment - subscribers from localStorage:', subscribers)
        console.log('addComment - subscriberId:', subscriberId)
        console.log('addComment - subscribers.length:', subscribers.length)
        
        // Use the first subscriber from localStorage (current logged-in user)
        const currentSubscriber = subscribers.length > 0 ? subscribers[0] : null
        console.log('addComment - currentSubscriber found:', currentSubscriber)
        
        if (currentSubscriber) {
          console.log('addComment - currentSubscriber.username:', currentSubscriber.username)
          console.log('addComment - currentSubscriber.id:', currentSubscriber.id)
        }
        
        const username = currentSubscriber ? currentSubscriber.username : 'Guest User'
        console.log('addComment - final username:', username)
        
        const response = await videoApiService.addComment(
          props.contentId,
          newComment.value,
          subscriberId,
          sessionId
        )
        
        console.log('Comment response:', response)
        
        if (response.success) {
          // Convert API response to local format with real username
          const comment: Comment = {
            id: response.data.id.toString(),
            user: username, // Use the username from localStorage, not API response
            userAvatar: username.substring(0, 1).toUpperCase(),
            text: response.data.content,
            date: response.data.created_at,
            likes: response.data.likes_count,
            isLiked: false,
            replies: [],
            isPinned: response.data.is_pinned,
            isEdited: response.data.is_edited
          }
          
          console.log('addComment - comment object being added:', comment)
          console.log('addComment - comment.user:', comment.user)
          console.log('addComment - comment.userAvatar:', comment.userAvatar)
          
          comments.value.unshift(comment)
          commentsCount.value++
          
          console.log('addComment - comments array after adding:', comments.value)
          newComment.value = ''
          
          // Show notification
          showToast('Comment added successfully!', 'success')
          
          // Track engagement
          trackEngagement('comment', props.contentType)
        } else {
          console.error('Comment failed:', response)
          showToast('Failed to add comment', 'warning')
        }
      } catch (error) {
        console.error('Error adding comment:', error)
        showToast('Failed to add comment', 'warning')
      }
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
  // Check if user is subscribed
      if (!isSubscribed.value) {
        showToast('Please sign in to reply to comments!', 'warning')
        setTimeout(() => {
          showToast('You can view all comments and replies without signing in!', 'info')
        }, 1000)
        return
      }
  
  replyingTo.value = commentId
  replyText.value = ''
}

const addReply = async (parentId: string) => {
  // Check if user is subscribed
      if (!isSubscribed.value) {
        showToast('Please sign in to reply to comments!', 'warning')
        setTimeout(() => {
          showToast('You can view all comments and replies without signing in!', 'info')
        }, 1000)
        return
      }
  
  if (!replyText.value.trim()) {
    showToast('Please enter a reply!', 'warning')
    return
  }
  
  try {
    const sessionId = videoApiService.getSessionId()
    const subscriberId = videoApiService.getSubscriberId()
    
    // Get subscriber username if available
    const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
    // Use the first subscriber from localStorage (current logged-in user)
    const currentSubscriber = subscribers.length > 0 ? subscribers[0] : null
    const username = currentSubscriber ? currentSubscriber.username : 'Guest User'
    
    const response = await videoApiService.addComment(
      props.contentId,
      replyText.value,
      subscriberId,
      sessionId,
      parseInt(parentId)
    )
    
    if (response.success) {
      const parentComment = comments.value.find(c => c.id === parentId)
      if (parentComment) {
        const reply: Comment = {
          id: response.data.id.toString(),
          user: username, // Use the username from localStorage, not API response
          userAvatar: username.substring(0, 1).toUpperCase(),
          text: response.data.content,
          date: response.data.created_at,
          likes: response.data.likes_count,
          isLiked: false,
          replies: [],
          isPinned: false,
          isEdited: false,
          parentId: parentId
        }
        
        parentComment.replies.push(reply)
        replyText.value = ''
        replyingTo.value = null
        
        showToast('Reply added successfully!', 'success')
        trackEngagement('reply', props.contentType)
      }
    } else {
      showToast('Failed to add reply', 'warning')
    }
  } catch (error) {
    console.error('Error adding reply:', error)
    showToast('Failed to add reply', 'warning')
  }
}

const editComment = (commentId: string) => {
  if (!isSubscribed.value) {
    showToast('Sign in to edit comments! You can view all comments without signing in.', 'info')
    router.push('/signin')
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
    showToast('Sign in to delete comments! You can view all comments without signing in.', 'info')
    router.push('/signin')
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
    showToast('Sign in to pin comments! You can view all comments without signing in.', 'info')
    router.push('/signin')
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
    const shareToFacebook = async () => {
      const url = encodeURIComponent(window.location.href)
      const text = encodeURIComponent(`Check out this amazing content about Stargate Project and Cristal Intelligence on Stargate.ci! 🚀💎`)
      window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank')
      
      // Record share in database
      try {
        const sessionId = videoApiService.getSessionId()
        const subscriberId = videoApiService.getSubscriberId()
        
        await videoApiService.addShare(
          props.contentId,
          'facebook',
          subscriberId,
          sessionId
        )
      } catch (error) {
        console.error('Error recording share:', error)
      }
      
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
    onMounted(async () => {
      // Initialize subscription status
      updateSubscriptionStatus()
      
      // Listen for subscription changes
      window.addEventListener('subscription-changed', updateSubscriptionStatus)
      window.addEventListener('storage', updateSubscriptionStatus)
      
      try {
        // Load video data from API
        const videoResponse = await videoApiService.getVideo(props.contentId)
        if (videoResponse.success) {
          likesCount.value = videoResponse.data.likes_count
          commentsCount.value = videoResponse.data.comments_count
        }
        
        // Load user interactions (only if user is logged in)
        if (isSubscribed.value) {
          const sessionId = videoApiService.getSessionId()
          const subscriberId = videoApiService.getSubscriberId()
          
          const interactionsResponse = await videoApiService.getUserInteractions(
            props.contentId,
            subscriberId,
            sessionId
          )
          
          if (interactionsResponse.success) {
            isLiked.value = interactionsResponse.data.is_liked
          }
        }
        
        // Load comments from API
        const commentsResponse = await videoApiService.getComments(props.contentId)
        if (commentsResponse.success) {
          // Get current subscriber info for proper username display
          const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
          const currentSubscriber = subscribers.length > 0 ? subscribers[0] : null
          
          // Convert API comments to local format
          comments.value = commentsResponse.data.map(comment => {
            // Use real username if this comment is from current user, otherwise use API data
            const isCurrentUserComment = currentSubscriber && (comment as any).subscriber_id === currentSubscriber.id
            const displayName = isCurrentUserComment ? currentSubscriber.username : (comment.author_name || 'Guest User')
            const displayAvatar = isCurrentUserComment ? currentSubscriber.username.charAt(0).toUpperCase() : (comment.author_avatar || 'G')
            
            console.log('Loading comment - comment.subscriber_id:', (comment as any).subscriber_id)
            console.log('Loading comment - currentSubscriber.id:', currentSubscriber?.id)
            console.log('Loading comment - isCurrentUserComment:', isCurrentUserComment)
            console.log('Loading comment - displayName:', displayName)
            console.log('Loading comment - comment.author_name:', comment.author_name)
            
            return {
              id: comment.id.toString(),
              user: displayName,
              userAvatar: displayAvatar,
              text: comment.content,
              date: comment.created_at,
              likes: comment.likes_count,
              isLiked: false,
              replies: comment.replies.map(reply => {
                // Use real username if this reply is from current user, otherwise use API data
                const isCurrentUserReply = currentSubscriber && (reply as any).subscriber_id === currentSubscriber.id
                const replyDisplayName = isCurrentUserReply ? currentSubscriber.username : (reply.author_name || 'Guest User')
                const replyDisplayAvatar = isCurrentUserReply ? currentSubscriber.username.charAt(0).toUpperCase() : (reply.author_avatar || 'G')
                
                console.log('Loading reply - reply.subscriber_id:', (reply as any).subscriber_id)
                console.log('Loading reply - isCurrentUserReply:', isCurrentUserReply)
                console.log('Loading reply - replyDisplayName:', replyDisplayName)
                
                return {
                  id: reply.id.toString(),
                  user: replyDisplayName,
                  userAvatar: replyDisplayAvatar,
                  text: reply.content,
                  date: reply.created_at,
                  likes: reply.likes_count,
                  isLiked: false,
                  replies: [],
                  isPinned: false,
                  isEdited: reply.is_edited
                }
              }),
              isPinned: comment.is_pinned,
              isEdited: comment.is_edited
            }
          })
          // Don't overwrite commentsCount - we already have the correct count from video data
          // commentsCount.value = comments.value.length
        }
      } catch (error: any) {
        console.error('Error loading data from API:', error)
        console.error('Full error details:', error)
        
        // DO NOT reset counts to 0 - keep current values
        // likesCount.value = props.initialLikes
        // commentsCount.value = props.initialComments.length
        // comments.value = props.initialComments
        
        // Show user-friendly error message
        showToast('Failed to load data. Please refresh the page.', 'warning')
      }
  
})

// Cleanup event listeners on unmount
onUnmounted(() => {
  window.removeEventListener('subscription-changed', updateSubscriptionStatus)
  window.removeEventListener('storage', updateSubscriptionStatus)
})
</script>

<style scoped>
.interactive-content {
  width: 100%;
}

.share-modal {
  backdrop-filter: blur(4px);
}

/* Custom scrollbar for comments */
.scrollbar-thin {
  scrollbar-width: thin;
}

.scrollbar-thumb-gray-600::-webkit-scrollbar-thumb {
  background-color: rgb(75 85 99);
  border-radius: 0.5rem;
}

.scrollbar-track-gray-800::-webkit-scrollbar-track {
  background-color: rgb(31 41 55);
}

.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: rgb(75 85 99);
  border-radius: 0.5rem;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background-color: rgb(107 114 128);
}

.scrollbar-thin::-webkit-scrollbar-track {
  background-color: rgb(31 41 55);
  border-radius: 0.5rem;
}
</style>
