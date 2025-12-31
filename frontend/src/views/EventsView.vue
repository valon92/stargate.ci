<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Modern Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
      <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmOWZhZmIiIGZpbGwtb3BhY2l0eT0iMC40Ij48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIxLjUiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-40 dark:opacity-20"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="text-center">
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-black/5 dark:bg-white/5 rounded-full mb-6 backdrop-blur-sm">
            <svg class="w-5 h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-sm font-medium text-black dark:text-white">Upcoming Events</span>
          </div>
          <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 tracking-tight">
            <span class="bg-gradient-to-r from-black to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
              Discover Events
            </span>
          </h1>
          <p class="text-xl md:text-2xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto mb-8 leading-relaxed">
            Stay informed about upcoming events, meetings, conferences, and important dates related to the Stargate Project and Cristal Intelligence.
          </p>
          <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-8">
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
              <span>Live Events</span>
            </div>
            <span>•</span>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Real-time Updates</span>
            </div>
            <span>•</span>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              </svg>
              <span>Global Access</span>
            </div>
          </div>
          
          <!-- Create Event Button for Organizers -->
          <div v-if="isAuthenticated" class="flex justify-center">
            <button
              @click="openCreateEventModal"
              class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-black to-gray-800 dark:from-white dark:to-gray-200 text-white dark:text-black rounded-2xl font-semibold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>Create New Event</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Advanced Filter Section -->
    <section class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
          <!-- Search Bar -->
          <div class="flex-1 max-w-2xl w-full">
            <div class="relative">
              <input
                v-model="searchQuery"
                @keyup.enter="searchEvents(searchQuery)"
                @input="handleSearchInput"
                type="text"
                placeholder="Search events by title, description, location..."
                class="w-full px-4 py-3 pl-12 pr-12 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all"
              >
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              <button
                v-if="searchQuery"
                @click="clearSearch"
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>

          <!-- View Toggle -->
          <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 rounded-xl p-1">
            <button
              @click="viewMode = 'grid'"
              :class="[
                'px-4 py-2 rounded-lg transition-all',
                viewMode === 'grid'
                  ? 'bg-white dark:bg-gray-700 text-black dark:text-white shadow-sm'
                  : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
              ]"
              title="Grid View"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </button>
            <button
              @click="viewMode = 'list'"
              :class="[
                'px-4 py-2 rounded-lg transition-all',
                viewMode === 'list'
                  ? 'bg-white dark:bg-gray-700 text-black dark:text-white shadow-sm'
                  : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
              ]"
              title="List View"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>

          <!-- Refresh Button -->
          <button
            @click="refreshEvents"
            :disabled="isLoading"
            class="px-4 py-3 bg-black dark:bg-white text-white dark:text-black rounded-xl font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="!isLoading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <div v-else class="animate-spin rounded-full h-5 w-5 border-2 border-current border-t-transparent"></div>
            <span class="hidden sm:inline">{{ isLoading ? 'Syncing...' : 'Refresh' }}</span>
          </button>
        </div>

        <!-- Category Filters -->
        <div class="mt-6 flex flex-wrap gap-2">
          <button 
            v-for="category in eventCategories" 
            :key="category.id"
            @click="filterByCategory(category.id)"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center gap-2',
              selectedCategory === category.id 
                ? 'bg-black dark:bg-white text-white dark:text-black shadow-lg scale-105' 
                : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'
            ]"
          >
            <span class="text-lg">{{ category.icon }}</span>
            <span>{{ category.name }}</span>
            <span v-if="getEventsCountByCategory(category.id) > 0" class="ml-1 px-2 py-0.5 bg-black/10 dark:bg-white/10 rounded-full text-xs">
              {{ getEventsCountByCategory(category.id) }}
            </span>
          </button>
        </div>

        <!-- Timeframe Filter -->
        <div class="mt-4 flex items-center gap-4">
          <select 
            v-model="selectedTimeframe" 
            @change="filterByTimeframe" 
            class="px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
          >
            <option value="all">All Time</option>
            <option value="upcoming">Upcoming</option>
            <option value="this-month">This Month</option>
            <option value="next-month">Next Month</option>
          </select>
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing <strong class="text-black dark:text-white">{{ filteredEvents.length }}</strong> of <strong class="text-black dark:text-white">{{ events.length }}</strong> events
          </div>
        </div>
      </div>
    </section>

    <!-- Events Display Section -->
    <section class="py-12 bg-gray-50 dark:bg-gray-900/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- API Events Notice -->
        <div v-if="!isLoading && events.length > 0" class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-xl">
          <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-blue-800 dark:text-blue-300 font-medium">Live Events from External APIs</h3>
              <p class="text-blue-700 dark:text-blue-200/80 text-sm">These events are fetched from external APIs including OpenAI, SoftBank, Oracle, and MGX. Data is automatically synced and updated.</p>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-20">
          <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-gray-200 dark:border-gray-700 border-t-black dark:border-t-white"></div>
          <p class="mt-6 text-lg text-gray-600 dark:text-gray-400">Loading events...</p>
        </div>

        <!-- Grid View -->
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="event in filteredEvents" 
            :key="event.id"
            class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-2xl hover:scale-[1.02] transition-all duration-300"
          >
            <!-- Event Image/Video Header -->
            <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 overflow-hidden">
              <div v-if="event.type === 'video' && event.video_url" class="absolute inset-0">
                <iframe
                  :src="getYouTubeEmbedUrl(event.video_url)"
                  class="w-full h-full"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  loading="lazy"
                ></iframe>
              </div>
              <div v-else class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                  <div class="w-16 h-16 bg-black dark:bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                    <span class="text-3xl">{{ event.icon }}</span>
                  </div>
                </div>
              </div>
              
              <!-- Badges -->
              <div class="absolute top-4 left-4 flex flex-col gap-2">
                <span :class="getEventBadgeClass(event.category)" class="px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">
                  {{ getCategoryName(event.category) }}
                </span>
                <span v-if="event.is_featured" class="px-2 py-1 bg-yellow-500/90 text-yellow-900 rounded-full text-xs font-semibold backdrop-blur-sm">
                  ⭐ Featured
                </span>
              </div>
              
              <div class="absolute top-4 right-4">
                <span :class="getSourceBadgeClass(event.source)" class="px-2 py-1 rounded-full text-xs font-medium backdrop-blur-sm">
                  {{ getSourceName(event.source) }}
                </span>
              </div>
            </div>

            <!-- Event Content -->
            <div class="p-6">
              <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mb-3">
                <div class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span>{{ formatDate(event.event_date) }}</span>
                </div>
                <span v-if="event.event_time" class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>{{ formatTime(event.event_time) }}</span>
                </span>
              </div>

              <h3 class="text-xl font-bold text-black dark:text-white mb-2 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                {{ event.title }}
              </h3>
              
              <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm line-clamp-2">
                {{ event.description }}
              </p>

              <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                </svg>
                <span class="truncate">{{ event.location }}</span>
              </div>

              <!-- Action Button -->
              <button
                @click="handleEventAction(event)"
                :class="[
                  'w-full px-4 py-3 rounded-xl font-medium transition-all duration-200 mb-4',
                  isEventPast(event.event_date)
                    ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    : 'bg-black dark:bg-white text-white dark:text-black hover:bg-gray-900 dark:hover:bg-gray-100'
                ]"
              >
                {{ getEventActionText(event) }}
              </button>

              <!-- Modern Interactive Actions -->
              <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button
                  @click="toggleEventLike(event)"
                  :class="[
                    'flex items-center gap-2 px-3 py-2 rounded-lg font-medium transition-all duration-200 flex-1',
                    eventLikes[event.id]?.isLiked
                      ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400'
                      : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                  ]"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                  </svg>
                  <span class="text-sm">{{ eventLikes[event.id]?.count || 0 }}</span>
                </button>
                <button
                  @click="toggleEventComments(event)"
                  class="flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 flex-1"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                  <span class="text-sm">{{ eventComments[event.id]?.count || 0 }}</span>
                </button>
                <button
                  @click="shareEvent(event)"
                  class="flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200"
                  title="Share Event"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                  </svg>
                </button>
              </div>

            </div>
          </div>
        </div>

        <!-- List View -->
        <div v-else class="space-y-4">
          <div 
            v-for="event in filteredEvents" 
            :key="event.id"
            class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 overflow-hidden"
          >
            <div class="flex flex-col md:flex-row">
              <!-- Event Image/Video -->
              <div class="relative w-full md:w-64 h-48 md:h-auto bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex-shrink-0">
                <div v-if="event.type === 'video' && event.video_url" class="absolute inset-0">
                  <iframe
                    :src="getYouTubeEmbedUrl(event.video_url)"
                    class="w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    loading="lazy"
                  ></iframe>
                </div>
                <div v-else class="absolute inset-0 flex items-center justify-center">
                  <div class="text-center">
                    <div class="w-16 h-16 bg-black dark:bg-white rounded-full flex items-center justify-center mx-auto shadow-lg">
                      <span class="text-3xl">{{ event.icon }}</span>
                    </div>
                  </div>
                </div>
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex flex-col gap-2">
                  <span :class="getEventBadgeClass(event.category)" class="px-2 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">
                    {{ getCategoryName(event.category) }}
                  </span>
                </div>
              </div>

              <!-- Event Content -->
              <div class="flex-1 p-6">
                <div class="flex items-start justify-between mb-3">
                  <div class="flex-1">
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-2">
                      <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ formatDate(event.event_date) }}</span>
                      </div>
                      <span v-if="event.event_time" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ formatTime(event.event_time) }}</span>
                      </span>
                      <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        <span>{{ event.location }}</span>
                      </div>
                    </div>
                    <h3 class="text-2xl font-bold text-black dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                      {{ event.title }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                      {{ event.description }}
                    </p>
                  </div>
                  <div class="ml-4 flex flex-col items-end gap-2">
                    <span :class="getSourceBadgeClass(event.source)" class="px-3 py-1 rounded-full text-xs font-medium">
                      {{ getSourceName(event.source) }}
                    </span>
                    <span v-if="event.is_featured" class="px-2 py-1 bg-yellow-500/20 text-yellow-800 dark:text-yellow-200 rounded-full text-xs">
                      ⭐ Featured
                    </span>
                  </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                  <button
                    @click="handleEventAction(event)"
                    :class="[
                      'px-6 py-2 rounded-lg font-medium transition-all duration-200',
                      isEventPast(event.event_date)
                        ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                        : 'bg-black dark:bg-white text-white dark:text-black hover:bg-gray-900 dark:hover:bg-gray-100'
                    ]"
                  >
                    {{ getEventActionText(event) }}
                  </button>
                  
                  <!-- Modern Interactive Actions -->
                  <button
                    @click="toggleEventLike(event)"
                    :class="[
                      'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
                      eventLikes[event.id]?.isLiked
                        ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    ]"
                  >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span class="text-sm">{{ eventLikes[event.id]?.count || 0 }}</span>
                  </button>
                  
                  <button
                    @click="toggleEventComments(event)"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="text-sm">{{ eventComments[event.id]?.count || 0 }}</span>
                  </button>
                  
                  <button
                    @click="shareEvent(event)"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                    title="Share Event"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                    </svg>
                  </button>
                  
                  <button
                    @click="addToCalendar(event)"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                    title="Add to Calendar"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </button>
                </div>

                <!-- Comments Section for List View -->
                <div v-if="eventComments[event.id]?.show" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                  <div class="space-y-3">
                    <!-- Comment Form -->
                    <div v-if="isAuthenticated" class="flex items-start gap-3">
                      <div class="w-8 h-8 bg-black dark:bg-white rounded-full flex items-center justify-center text-white dark:text-black font-bold text-sm flex-shrink-0">
                        {{ getInitials(authStore.currentUser?.name || authStore.currentUser?.email || 'U') }}
                      </div>
                      <div class="flex-1">
                        <textarea
                          v-model="eventComments[event.id].newComment"
                          placeholder="Write a comment..."
                          rows="2"
                          class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white resize-none text-sm"
                        ></textarea>
                        <button
                          @click="addEventComment(event)"
                          :disabled="!eventComments[event.id].newComment.trim()"
                          class="mt-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50 text-sm font-medium"
                        >
                          Post Comment
                        </button>
                      </div>
                    </div>
                    <div v-else class="text-center py-4">
                      <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Sign in to comment</p>
                      <button
                        @click="router.push('/signin')"
                        class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors text-sm font-medium"
                      >
                        Sign In
                      </button>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                      <div
                        v-for="comment in eventComments[event.id].comments"
                        :key="comment.id"
                        class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg"
                      >
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                          {{ getInitials(comment.user || 'U') }}
                        </div>
                        <div class="flex-1 min-w-0">
                          <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-sm text-black dark:text-white">{{ comment.user || 'Anonymous' }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatCommentDate(comment.date) }}</span>
                          </div>
                          <p class="text-sm text-gray-700 dark:text-gray-300">{{ comment.text }}</p>
                        </div>
                      </div>
                      <div v-if="eventComments[event.id].comments.length === 0" class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                        No comments yet. Be the first to comment!
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="!isLoading && filteredEvents.length === 0" class="text-center py-20">
          <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-black dark:text-white mb-2">No Events Found</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">No events match your current filters. Try adjusting your search criteria.</p>
          <button @click="clearFilters" class="px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors font-medium">
            Clear Filters
          </button>
        </div>
      </div>
    </section>

    <!-- Event Categories Info -->
    <section class="py-20 bg-white dark:bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl md:text-5xl font-bold mb-4">
            <span class="bg-gradient-to-r from-black to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
              Event Categories
            </span>
          </h2>
          <p class="text-xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto">
            Explore different types of events related to the Stargate Project and Cristal Intelligence ecosystem.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="category in eventCategories" 
            :key="category.id"
            @click="filterByCategory(category.id)"
            class="group bg-white dark:bg-gray-800 rounded-2xl p-8 border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer"
          >
            <div class="w-16 h-16 bg-black dark:bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
              <span class="text-3xl">{{ category.icon }}</span>
            </div>
            <h3 class="text-xl font-bold text-black dark:text-white mb-3 text-center">{{ category.name }}</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4 text-center text-sm">{{ category.description }}</p>
            <div class="text-center">
              <span class="text-sm font-semibold text-black dark:text-white">
                {{ getEventsCountByCategory(category.id) }} events
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter Subscription -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">
          <span class="bg-gradient-to-r from-black to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
            Stay Connected
          </span>
        </h2>
        <p class="text-xl text-gray-700 dark:text-gray-300 mb-8">
          Get notified about the latest events, updates, and announcements related to the Stargate Project and Cristal Intelligence.
        </p>
        
        <div class="max-w-md mx-auto">
          <div class="flex gap-3">
            <input 
              v-model="email"
              type="email" 
              placeholder="Enter your email address"
              class="flex-1 px-5 py-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"
            >
            <button 
              @click="subscribeToEvents"
              :disabled="!email || isSubscribing"
              class="px-8 py-4 bg-black dark:bg-white text-white dark:text-black rounded-xl font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubscribing ? 'Joining...' : 'Subscribe' }}
            </button>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">
            Join thousands of AI enthusiasts. No spam, just valuable insights.
          </p>
        </div>
      </div>
    </section>

    <!-- Comments Modal -->
    <div v-if="showCommentsModal && currentCommentsEvent" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click="closeCommentsModal">
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-3xl w-full shadow-2xl max-h-[90vh] flex flex-col" @click.stop>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
          <div class="flex-1">
            <h3 class="text-2xl font-bold text-black dark:text-white">Comments</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ currentCommentsEvent.title }}</p>
          </div>
          <button
            @click="closeCommentsModal"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors ml-4"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Comments List -->
        <div class="flex-1 overflow-y-auto space-y-4 mb-6 pr-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-700">
          <div v-if="commentsLoading" class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-2 border-black dark:border-white border-t-transparent"></div>
          </div>
          
          <div v-else-if="eventComments[currentCommentsEvent.id]?.comments && eventComments[currentCommentsEvent.id].comments.length > 0" class="space-y-4">
            <div
              v-for="comment in eventComments[currentCommentsEvent.id].comments"
              :key="comment.id"
              class="group flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-600"
            >
              <!-- Avatar -->
              <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-lg">
                {{ getInitials(comment.user || 'U') }}
              </div>
              
              <!-- Comment Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3 mb-2">
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-semibold text-sm text-black dark:text-white">{{ comment.user || 'Anonymous' }}</span>
                    <span v-if="comment.isPinned" class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded text-xs font-medium">
                      📌 Pinned
                    </span>
                    <span v-if="comment.isEdited" class="text-xs text-gray-500 dark:text-gray-400 italic">(edited)</span>
                  </div>
                  <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ formatCommentDate(comment.date) }}</span>
                </div>
                
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap break-words">{{ comment.text }}</p>
                
                <!-- Comment Actions -->
                <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                  <button
                    class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>{{ comment.likes || 0 }}</span>
                  </button>
                  <button
                    class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    <span>Reply</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
              </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium mb-1">No comments yet</p>
            <p class="text-sm text-gray-400 dark:text-gray-500">Be the first to share your thoughts!</p>
          </div>
        </div>

        <!-- Comment Form -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
          <div v-if="isAuthenticated" class="space-y-3">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 bg-black dark:bg-white rounded-full flex items-center justify-center text-white dark:text-black font-bold text-sm flex-shrink-0 shadow-lg">
                {{ getInitials(authStore.currentUser?.name || authStore.currentUser?.email || 'U') }}
              </div>
              <div class="flex-1">
                <textarea
                  v-model="newCommentText"
                  placeholder="Write a comment..."
                  rows="3"
                  class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white resize-none text-sm transition-all"
                  @keydown.ctrl.enter="addEventComment"
                  @keydown.meta.enter="addEventComment"
                ></textarea>
                <div class="flex items-center justify-between mt-2">
                  <p class="text-xs text-gray-500 dark:text-gray-400">Press Ctrl+Enter or Cmd+Enter to submit</p>
                  <button
                    @click="addEventComment"
                    :disabled="!newCommentText.trim() || isPostingComment"
                    class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-xl hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium flex items-center gap-2"
                  >
                    <div v-if="isPostingComment" class="animate-spin rounded-full h-4 w-4 border-2 border-current border-t-transparent"></div>
                    <span>{{ isPostingComment ? 'Posting...' : 'Post Comment' }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-6">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Sign in to join the conversation</p>
            <button
              @click="router.push('/signin')"
              class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-xl hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors text-sm font-medium"
            >
              Sign In
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Event Modal -->
    <Transition name="modal">
      <div v-if="showCreateEventModal" class="fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50 p-4" @click="closeCreateEventModal">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 max-w-4xl w-full shadow-2xl max-h-[90vh] overflow-y-auto" @click.stop>
          <!-- Header -->
          <div class="flex items-center justify-between mb-8 pb-6 border-b-2 border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
              </div>
              <div>
                <h3 class="text-3xl font-bold bg-gradient-to-r from-black to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">Create New Event</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Share your event with the Stargate community</p>
              </div>
            </div>
            <button
              @click="closeCreateEventModal"
              class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all duration-200 hover:rotate-90"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Form -->
          <form @submit.prevent="submitCreateEvent" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Title -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Event Title <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="createEventForm.title"
                  type="text"
                  required
                  :disabled="isCreatingEvent"
                  :class="[
                    'w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all text-lg',
                    createEventErrors.title ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'
                  ]"
                  placeholder="e.g., Stargate Project Infrastructure Launch"
                >
                <p v-if="createEventErrors.title" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ createEventErrors.title }}</p>
              </div>

              <!-- Category -->
              <div>
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Category <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="createEventForm.category"
                  required
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                >
                  <option value="stargate">🚀 Stargate Project</option>
                  <option value="cristal">💎 Cristal Intelligence</option>
                  <option value="conferences">🎤 Conferences</option>
                  <option value="meetings">🤝 Meetings</option>
                  <option value="announcements">📢 Announcements</option>
                </select>
              </div>

              <!-- Type -->
              <div>
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Event Type <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="createEventForm.type"
                  required
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                >
                  <option value="conference">Conference</option>
                  <option value="meeting">Meeting</option>
                  <option value="workshop">Workshop</option>
                  <option value="announcement">Announcement</option>
                  <option value="video">Video Event</option>
                </select>
              </div>

              <!-- Date -->
              <div>
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Event Date <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="createEventForm.event_date"
                  type="date"
                  required
                  :disabled="isCreatingEvent"
                  :min="new Date().toISOString().split('T')[0]"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                >
              </div>

              <!-- Time -->
              <div>
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Event Time
                </label>
                <input
                  v-model="createEventForm.event_time"
                  type="time"
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                >
              </div>

              <!-- Location -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Location <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="createEventForm.location"
                  type="text"
                  required
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                  placeholder="e.g., San Francisco, CA & Virtual"
                >
              </div>

              <!-- Organizer -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Organizer Name <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="createEventForm.organizer"
                  type="text"
                  required
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                  placeholder="e.g., Your Organization Name"
                >
              </div>

              <!-- Icon -->
              <div>
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Icon Emoji
                </label>
                <input
                  v-model="createEventForm.icon"
                  type="text"
                  maxlength="2"
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all text-center text-2xl"
                  placeholder="📅"
                >
              </div>

              <!-- Featured -->
              <div class="flex items-center justify-center">
                <label class="flex items-center gap-3 cursor-pointer p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border-2 border-gray-200 dark:border-gray-600 w-full">
                  <input
                    v-model="createEventForm.is_featured"
                    type="checkbox"
                    :disabled="isCreatingEvent"
                    class="w-6 h-6 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-black dark:focus:ring-white"
                  >
                  <div>
                    <span class="font-semibold text-black dark:text-white block">Feature this event</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Show prominently on the events page</span>
                  </div>
                </label>
              </div>

              <!-- Registration URL -->
              <div>
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Registration URL
                </label>
                <input
                  v-model="createEventForm.registration_url"
                  type="url"
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                  placeholder="https://..."
                >
              </div>

              <!-- Video URL -->
              <div>
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Video URL (YouTube)
                </label>
                <input
                  v-model="createEventForm.video_url"
                  type="url"
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
                  placeholder="https://youtube.com/..."
                >
              </div>

              <!-- Description -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                  Description <span class="text-red-500">*</span>
                </label>
                <textarea
                  v-model="createEventForm.description"
                  required
                  rows="6"
                  :disabled="isCreatingEvent"
                  class="w-full px-5 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white resize-none"
                  placeholder="Describe your event in detail..."
                ></textarea>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ createEventForm.description.length }}/5000 characters</p>
              </div>
            </div>

            <!-- Error Messages -->
            <div v-if="Object.keys(createEventErrors).length > 0" class="p-4 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-500/30 rounded-2xl">
              <p class="text-sm text-red-800 dark:text-red-300 font-semibold mb-2">Please fix the following errors:</p>
              <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400">
                <li v-for="(error, field) in createEventErrors" :key="field">{{ error }}</li>
              </ul>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-4 pt-6 border-t-2 border-gray-100 dark:border-gray-700">
              <button
                type="button"
                @click="closeCreateEventModal"
                :disabled="isCreatingEvent"
                class="flex-1 px-6 py-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 disabled:opacity-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="isCreatingEvent"
                class="flex-1 px-6 py-4 bg-gradient-to-r from-black to-gray-800 dark:from-white dark:to-gray-200 text-white dark:text-black rounded-2xl font-semibold hover:shadow-xl transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                <div v-if="isCreatingEvent" class="animate-spin rounded-full h-5 w-5 border-2 border-current border-t-transparent"></div>
                <span>{{ isCreatingEvent ? 'Creating Event...' : 'Create Event' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>

    <!-- Share Modal - Matching Community Style -->
    <div
      v-if="showShareModal && currentShareEvent"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeShareModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full mx-4" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-xl font-semibold text-black dark:text-white">Share Event</h3>
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
            <h4 class="text-lg font-medium text-black dark:text-white mb-2">{{ currentShareEvent.title }}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ currentShareEvent.organizer || 'Event' }}</p>
          </div>

          <!-- Share Options -->
          <div class="grid grid-cols-2 gap-3">
            <!-- Facebook -->
            <button
              @click="shareToPlatform('facebook')"
              class="flex items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
              <span>Facebook</span>
            </button>

            <!-- Twitter/X -->
            <button
              @click="shareToPlatform('twitter')"
              class="flex items-center justify-center p-4 bg-black hover:bg-gray-800 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
              <span>X (Twitter)</span>
            </button>

            <!-- LinkedIn -->
            <button
              @click="shareToPlatform('linkedin')"
              class="flex items-center justify-center p-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
              <span>LinkedIn</span>
            </button>

            <!-- WhatsApp -->
            <button
              @click="shareToPlatform('whatsapp')"
              class="flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
              <span>WhatsApp</span>
            </button>

            <!-- Telegram -->
            <button
              @click="shareToPlatform('telegram')"
              class="flex items-center justify-center p-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
              </svg>
              <span>Telegram</span>
            </button>

            <!-- Email -->
            <button
              @click="shareToPlatform('email')"
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
              @click="copyEventLink"
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

    <!-- Registration Modal -->
    <div v-if="showRegistrationModal && currentRegistrationEvent" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click="closeRegistrationModal">
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-2xl font-bold text-black dark:text-white">Register for Event</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ currentRegistrationEvent.title }}</p>
          </div>
          <button
            @click="closeRegistrationModal"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Event Info -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <span class="text-gray-600 dark:text-gray-400">Date:</span>
              <p class="font-semibold text-black dark:text-white">{{ formatDate(currentRegistrationEvent.event_date) }}</p>
            </div>
            <div v-if="currentRegistrationEvent.event_time">
              <span class="text-gray-600 dark:text-gray-400">Time:</span>
              <p class="font-semibold text-black dark:text-white">{{ formatTime(currentRegistrationEvent.event_time) }}</p>
            </div>
            <div class="col-span-2">
              <span class="text-gray-600 dark:text-gray-400">Location:</span>
              <p class="font-semibold text-black dark:text-white">{{ currentRegistrationEvent.location }}</p>
            </div>
          </div>
        </div>

        <!-- Registration Form -->
        <form @submit.prevent="submitRegistration" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Email Address <span class="text-red-500">*</span>
            </label>
            <input
              v-model="registrationForm.email"
              type="email"
              required
              :disabled="isRegistering"
              :class="[
                'w-full px-4 py-3 bg-white dark:bg-gray-700 border rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all',
                registrationErrors.email ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'
              ]"
              placeholder="your.email@example.com"
            >
            <p v-if="registrationErrors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ registrationErrors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Full Name
            </label>
            <input
              v-model="registrationForm.name"
              type="text"
              :disabled="isRegistering"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
              placeholder="John Doe"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Phone Number
            </label>
            <input
              v-model="registrationForm.phone"
              type="tel"
              :disabled="isRegistering"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
              placeholder="+1 (555) 123-4567"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Timezone
            </label>
            <select
              v-model="registrationForm.timezone"
              :disabled="isRegistering"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition-all"
            >
              <option v-for="tz in timezones" :key="tz" :value="tz">{{ tz }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">
              Additional Notes (Optional)
            </label>
            <textarea
              v-model="registrationForm.notes"
              rows="3"
              :disabled="isRegistering"
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white resize-none"
              placeholder="Any special requirements or questions..."
            ></textarea>
          </div>

          <!-- Notification Preferences -->
          <div class="space-y-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
            <p class="text-sm font-medium text-black dark:text-white mb-3">Notification Preferences</p>
            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="registrationForm.emailNotifications"
                type="checkbox"
                :disabled="isRegistering"
                class="w-5 h-5 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-black dark:focus:ring-white"
              >
              <span class="text-sm text-gray-700 dark:text-gray-300">Email notifications about event updates</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="registrationForm.smsNotifications"
                type="checkbox"
                :disabled="isRegistering"
                class="w-5 h-5 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-black dark:focus:ring-white"
              >
              <span class="text-sm text-gray-700 dark:text-gray-300">SMS reminders (if phone provided)</span>
            </label>
          </div>

          <!-- Error Messages -->
          <div v-if="Object.keys(registrationErrors).length > 0" class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500/30 rounded-xl">
            <p class="text-sm text-red-800 dark:text-red-300">Please fix the errors above to continue.</p>
          </div>

          <!-- Submit Button -->
          <div class="flex items-center gap-3 pt-4">
            <button
              type="button"
              @click="closeRegistrationModal"
              :disabled="isRegistering"
              class="flex-1 px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors disabled:opacity-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isRegistering || !registrationForm.email.trim()"
              class="flex-1 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-xl font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
              <div v-if="isRegistering" class="animate-spin rounded-full h-5 w-5 border-2 border-current border-t-transparent"></div>
              <span>{{ isRegistering ? 'Registering...' : 'Register Now' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modals (keep existing modals) -->
    <!-- Video Modal -->
    <div v-if="showVideoModal" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4" @click="closeVideoModal">
      <div class="bg-gray-900 rounded-xl p-4 max-w-6xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xl font-semibold text-white">Event Video</h3>
          <button 
            @click="closeVideoModal"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div class="relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
          <iframe
            v-if="currentVideoUrl"
            :src="getYouTubeEmbedUrl(currentVideoUrl)"
            class="absolute top-0 left-0 w-full h-full rounded-lg"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
          ></iframe>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { eventsApiService, type Event } from '../services/eventsApiService'
import { eventRegistrationService } from '../services/eventRegistrationService'
import { videoApiService } from '../services/videoApiService'

// Meta tags
useHead({
  title: 'Upcoming Events - Stargate.ci',
  meta: [
    { name: 'description', content: 'Stay informed about upcoming events, meetings, conferences, and important dates related to the Stargate Project and Cristal Intelligence.' }
  ]
})

// Auth and router
const authStore = useAuthStore()
const router = useRouter()

// Computed
const isAuthenticated = computed(() => authStore.isAuthenticated)

// View mode
const viewMode = ref<'grid' | 'list'>('grid')

// Reactive data
const selectedCategory = ref('all')
const selectedTimeframe = ref('upcoming')
const email = ref('')
const isSubscribing = ref(false)
const isLoading = ref(false)
const searchQuery = ref('')

// Video modal state
const showVideoModal = ref(false)
const currentVideoUrl = ref<string | null>(null)

// Event interactions state
const eventLikes = ref<Record<string, { isLiked: boolean; count: number }>>({})
const eventComments = ref<Record<string, { show: boolean; count: number; comments: Array<{ id: string; user: string; text: string; date: string }>; newComment: string }>>({})
const showShareModal = ref(false)
const currentShareEvent = ref<Event | null>(null)

// Registration modal state
const showRegistrationModal = ref(false)
const currentRegistrationEvent = ref<Event | null>(null)
const isRegistering = ref(false)
const registrationForm = ref({
  name: '',
  email: '',
  phone: '',
  timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
  notes: '',
  emailNotifications: true,
  smsNotifications: false
})
const registrationErrors = ref<Record<string, string>>({})
const isRegistered = ref<Record<string, boolean>>({})

// Comments modal state
const showCommentsModal = ref(false)
const currentCommentsEvent = ref<Event | null>(null)
const isPostingComment = ref(false)
const newCommentText = ref('')
const commentsLoading = ref(false)

// Share modal state
const linkCopied = ref(false)
const qrCodeUrl = ref<string | null>(null)

// Create Event modal state
const showCreateEventModal = ref(false)
const isCreatingEvent = ref(false)
const createEventForm = ref({
  title: '',
  description: '',
  category: 'stargate',
  type: 'conference',
  event_date: '',
  event_time: '',
  location: '',
  organizer: '',
  icon: '📅',
  registration_url: '',
  video_url: '',
  is_featured: false
})
const createEventErrors = ref<Record<string, string>>({})

// Event categories
const eventCategories = ref([
  {
    id: 'all',
    name: 'All Events',
    icon: '📅',
    description: 'All upcoming events and announcements'
  },
  {
    id: 'stargate',
    name: 'Stargate Project',
    icon: '🚀',
    description: 'Events related to the $500 billion AI infrastructure initiative'
  },
  {
    id: 'cristal',
    name: 'Cristal Intelligence',
    icon: '💎',
    description: 'Events about transparent AI and ethical AI development'
  },
  {
    id: 'conferences',
    name: 'Conferences',
    icon: '🎤',
    description: 'Major conferences and speaking engagements'
  },
  {
    id: 'meetings',
    name: 'Meetings',
    icon: '🤝',
    description: 'Partnership meetings and strategic discussions'
  },
  {
    id: 'announcements',
    name: 'Announcements',
    icon: '📢',
    description: 'Important announcements and milestone releases'
  }
])

// Events data
const events = ref<Event[]>([])

// Computed properties
const filteredEvents = computed(() => {
  let filtered = events.value
  
  // Filter by category
  if (selectedCategory.value !== 'all') {
    filtered = filtered.filter(event => event.category === selectedCategory.value)
  }

  // Filter by timeframe
  const now = new Date()
  const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())

  switch (selectedTimeframe.value) {
    case 'upcoming':
      filtered = filtered.filter(event => new Date(event.event_date) >= today)
      break
    case 'this-month':
      const thisMonthStart = new Date(now.getFullYear(), now.getMonth(), 1)
      const thisMonthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0)
      filtered = filtered.filter(event => {
        const eventDate = new Date(event.event_date)
        return eventDate >= thisMonthStart && eventDate <= thisMonthEnd
      })
      break
    case 'next-month':
      const nextMonthStart = new Date(now.getFullYear(), now.getMonth() + 1, 1)
      const nextMonthEnd = new Date(now.getFullYear(), now.getMonth() + 2, 0)
      filtered = filtered.filter(event => {
        const eventDate = new Date(event.event_date)
        return eventDate >= nextMonthStart && eventDate <= nextMonthEnd
      })
      break
  }

  // Filter by search query
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(event => 
      event.title.toLowerCase().includes(query) ||
      event.description.toLowerCase().includes(query) ||
      event.location.toLowerCase().includes(query) ||
      event.organizer.toLowerCase().includes(query)
    )
  }

  // Sort by date
  return filtered.sort((a, b) => new Date(a.event_date).getTime() - new Date(b.event_date).getTime())
})

// Methods
const handleSearchInput = () => {
  // Search happens automatically via computed property
}

const clearSearch = () => {
  searchQuery.value = ''
}

const filterByCategory = async (categoryId: string) => {
  selectedCategory.value = categoryId
  if (categoryId === 'all') {
    await loadEvents()
  } else {
    await loadEvents(categoryId)
  }
}

const filterByTimeframe = async () => {
  if (selectedTimeframe.value === 'upcoming') {
    await loadUpcomingEvents()
  } else {
    await loadEvents(selectedCategory.value === 'all' ? undefined : selectedCategory.value)
  }
}

const getCategoryName = (categoryId: string) => {
  const category = eventCategories.value.find(cat => cat.id === categoryId)
  return category ? category.name : categoryId
}

const getEventBadgeClass = (category: string) => {
  const classes = {
    stargate: 'bg-blue-500/90 text-white',
    cristal: 'bg-purple-500/90 text-white',
    conferences: 'bg-green-500/90 text-white',
    meetings: 'bg-orange-500/90 text-white',
    announcements: 'bg-red-500/90 text-white'
  }
  return classes[category as keyof typeof classes] || 'bg-gray-500/90 text-white'
}

const getSourceName = (source: string): string => {
  const sourceNames: Record<string, string> = {
    internal: 'Internal',
    openai: 'OpenAI',
    gemini: 'Gemini',
    cohere: 'Cohere',
    softbank: 'SoftBank',
    oracle: 'Oracle',
    mgx: 'MGX'
  }
  return sourceNames[source] || 'Unknown'
}

const getSourceBadgeClass = (source: string): string => {
  const sourceClasses: Record<string, string> = {
    internal: 'bg-blue-500/20 text-blue-300',
    openai: 'bg-green-500/20 text-green-300',
    gemini: 'bg-purple-500/20 text-purple-300',
    cohere: 'bg-orange-500/20 text-orange-300',
    softbank: 'bg-orange-500/20 text-orange-300',
    oracle: 'bg-red-500/20 text-red-300',
    mgx: 'bg-purple-500/20 text-purple-300'
  }
  return sourceClasses[source] || 'bg-gray-500/20 text-gray-300'
}

const getEventsCountByCategory = (categoryId: string) => {
  if (categoryId === 'all') return events.value.length
  return events.value.filter(event => event.category === categoryId).length
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatTime = (timeString: string): string => {
  try {
    const time = new Date(`2000-01-01T${timeString}`)
    return time.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    })
  } catch (error) {
    return timeString
  }
}

const isEventPast = (eventDate: string) => {
  const event = new Date(eventDate)
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  return event < today
}

// Timezones list
const timezones = ref([
  'America/New_York',
  'America/Chicago',
  'America/Denver',
  'America/Los_Angeles',
  'America/Phoenix',
  'America/Anchorage',
  'Pacific/Honolulu',
  'Europe/London',
  'Europe/Paris',
  'Europe/Berlin',
  'Europe/Rome',
  'Europe/Madrid',
  'Europe/Amsterdam',
  'Europe/Stockholm',
  'Europe/Zurich',
  'Asia/Tokyo',
  'Asia/Shanghai',
  'Asia/Hong_Kong',
  'Asia/Singapore',
  'Asia/Dubai',
  'Asia/Kolkata',
  'Australia/Sydney',
  'Australia/Melbourne',
  'Pacific/Auckland',
  'UTC'
])

const getEventActionText = (event: Event): string => {
  if (event.type === 'video' && event.video_url) {
    return 'Watch Video'
  }
  if (isRegistered.value[event.id]) {
    return '✓ Registered'
  }
  if (!isEventPast(event.event_date)) {
    return 'Register Now'
  }
  if (isEventPast(event.event_date)) {
    return 'View Details'
  }
  return 'Get Notified'
}

const handleEventAction = (event: Event) => {
  if (event.type === 'video' && event.video_url) {
    openVideo(event.video_url)
  } else if (!isEventPast(event.event_date)) {
    // Show registration modal for upcoming events
    openRegistrationModal(event)
  } else if (isEventPast(event.event_date)) {
    // Show event details for past events
    alert(`Event: ${event.title}\nDate: ${formatDate(event.event_date)}\nLocation: ${event.location}`)
  } else {
    scrollToSubscribe()
  }
}

const openRegistrationModal = async (event: Event) => {
  currentRegistrationEvent.value = event
  
  // Pre-fill form if user is authenticated
  if (isAuthenticated.value && authStore.currentUser) {
    registrationForm.value.email = authStore.currentUser.email || ''
    registrationForm.value.name = authStore.currentUser.name || ''
  }
  
  // Check if already registered
  if (isAuthenticated.value && authStore.currentUser?.email) {
    try {
      const eventId = typeof event.id === 'string' ? parseInt(event.id) : event.id
      if (!isNaN(eventId)) {
        const checkResponse = await eventRegistrationService.checkRegistration(
          eventId,
          authStore.currentUser.email
        )
        if (checkResponse.success && checkResponse.data?.is_registered) {
          isRegistered.value[event.id] = true
        }
      }
    } catch (error: any) {
      // Silently handle errors - event might not exist or user might not be registered
      if (error.message && !error.message.includes('Event not found')) {
        console.error('Error checking registration:', error)
      }
    }
  }
  
  showRegistrationModal.value = true
}

const closeRegistrationModal = () => {
  showRegistrationModal.value = false
  currentRegistrationEvent.value = null
  registrationForm.value = {
    name: '',
    email: '',
    phone: '',
    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    notes: '',
    emailNotifications: true,
    smsNotifications: false
  }
  registrationErrors.value = {}
}

const submitRegistration = async () => {
  if (!currentRegistrationEvent.value) return
  
  registrationErrors.value = {}
  
  // Validation
  if (!registrationForm.value.email.trim()) {
    registrationErrors.value.email = 'Email is required'
    return
  }
  
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(registrationForm.value.email)) {
    registrationErrors.value.email = 'Please enter a valid email address'
    return
  }
  
  isRegistering.value = true
  
  try {
    const response = await eventRegistrationService.registerForEvent({
      event_id: parseInt(currentRegistrationEvent.value.id),
      email: registrationForm.value.email.trim(),
      name: registrationForm.value.name.trim() || undefined,
      phone: registrationForm.value.phone.trim() || undefined,
      timezone: registrationForm.value.timezone,
      notes: registrationForm.value.notes.trim() || undefined,
      preferences: {
        email_notifications: registrationForm.value.emailNotifications,
        sms_notifications: registrationForm.value.smsNotifications
      }
    })
    
    if (response.success) {
      isRegistered.value[currentRegistrationEvent.value.id] = true
      showToast('Successfully registered for the event!', 'success')
      closeRegistrationModal()
      
      // Reload events to update registration status
      await loadEvents(selectedCategory.value === 'all' ? undefined : selectedCategory.value)
    } else {
      if (response.errors) {
        registrationErrors.value = response.errors
      }
      showToast(response.message || 'Registration failed', 'error')
    }
  } catch (error: any) {
    console.error('Registration error:', error)
    showToast(error.message || 'Failed to register for event', 'error')
  } finally {
    isRegistering.value = false
  }
}

const openVideo = (videoUrl: string) => {
  if (!authStore.isAuthenticated) {
    router.push('/signin')
    return
  }
  currentVideoUrl.value = videoUrl
  showVideoModal.value = true
}

const closeVideoModal = () => {
  showVideoModal.value = false
  currentVideoUrl.value = null
}

const getYouTubeEmbedUrl = (url: string): string => {
  if (!url) return ''
  
  let videoId = ''
  const watchMatch = url.match(/[?&]v=([^&]+)/)
  if (watchMatch) {
    videoId = watchMatch[1]
  } else {
    const shortMatch = url.match(/youtu\.be\/([^?]+)/)
    if (shortMatch) {
      videoId = shortMatch[1]
    }
  }
  
  if (!videoId) return url
  return `https://www.youtube.com/embed/${videoId}?autoplay=1`
}


const addToCalendar = (event: Event) => {
  try {
    // Parse event date and time safely
    let eventDateStr = event.event_date
    if (!eventDateStr) {
      showToast('Event date is missing', 'error')
      return
    }
    
    // Ensure date is in YYYY-MM-DD format
    if (eventDateStr.includes('T')) {
      eventDateStr = eventDateStr.split('T')[0]
    }
    
    // Parse time - handle different formats
    let timeStr = '00:00'
    if (event.event_time) {
      if (typeof event.event_time === 'string') {
        // If time is in format "HH:MM:SS" or "HH:MM"
        timeStr = event.event_time.split(':').slice(0, 2).join(':')
      } else if (event.event_time instanceof Date) {
        timeStr = event.event_time.toTimeString().slice(0, 5)
      }
    }
    
    // Create date objects
    const startDate = new Date(`${eventDateStr}T${timeStr}:00`)
    
    // Validate date
    if (isNaN(startDate.getTime())) {
      showToast('Invalid event date or time', 'error')
      return
    }
    
    // Set end date to 2 hours after start
    const endDate = new Date(startDate.getTime() + 2 * 60 * 60 * 1000)
    
    // Format dates for Google Calendar (YYYYMMDDTHHMMSSZ)
    const formatDateForCalendar = (date: Date) => {
      return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z'
    }
    
    let details = event.description || ''
    if (event.registration_url) {
      details += `\n\nRegistration: ${event.registration_url}`
    }
    
    const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${formatDateForCalendar(startDate)}/${formatDateForCalendar(endDate)}&details=${encodeURIComponent(details)}&location=${encodeURIComponent(event.location || '')}`
    
    window.open(calendarUrl, '_blank')
    showToast('Opening calendar...', 'success')
  } catch (error: any) {
    console.error('Error adding to calendar:', error)
    showToast('Failed to add event to calendar', 'error')
  }
}

const scrollToSubscribe = () => {
  const subscribeSection = document.querySelector('section:has(.max-w-md.mx-auto)')
  if (subscribeSection) {
    subscribeSection.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

const subscribeToEvents = async () => {
  if (!email.value) return
  
  isSubscribing.value = true
  
  try {
    await new Promise(resolve => setTimeout(resolve, 2000))
    alert('Successfully subscribed to event notifications!')
    email.value = ''
  } catch (error) {
    console.error('Event subscription failed:', error)
    alert('Failed to subscribe. Please try again.')
  } finally {
    isSubscribing.value = false
  }
}

const clearFilters = () => {
  selectedCategory.value = 'all'
  selectedTimeframe.value = 'upcoming'
  searchQuery.value = ''
}

const searchEvents = async (query: string) => {
  if (!query.trim()) {
    await loadEvents()
    return
  }
  
  isLoading.value = true
  try {
    const response = await eventsApiService.searchEvents(query, 10)
    if (response.success) {
      events.value = response.data
    }
  } catch (error) {
    console.error('Error searching events:', error)
  } finally {
    isLoading.value = false
  }
}

// Initialize event interactions
const initializeEventInteractions = (event: Event) => {
  if (!eventLikes.value[event.id]) {
    eventLikes.value[event.id] = { isLiked: false, count: 0 }
  }
  if (!eventComments.value[event.id]) {
    eventComments.value[event.id] = {
      show: false,
      count: 0,
      comments: [],
      newComment: ''
    }
  }
}

const toggleEventLike = async (event: Event) => {
  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }

  initializeEventInteractions(event)
  
  try {
    // Toggle like state
    eventLikes.value[event.id].isLiked = !eventLikes.value[event.id].isLiked
    eventLikes.value[event.id].count += eventLikes.value[event.id].isLiked ? 1 : -1
    
    // In a real app, you would call an API here
    // await eventApiService.toggleLike(event.id)
    
    // Show toast notification
    showToast(eventLikes.value[event.id].isLiked ? 'Event liked!' : 'Event unliked!', 'success')
  } catch (error) {
    console.error('Error toggling like:', error)
    // Revert on error
    eventLikes.value[event.id].isLiked = !eventLikes.value[event.id].isLiked
    eventLikes.value[event.id].count += eventLikes.value[event.id].isLiked ? 1 : -1
    showToast('Failed to update like', 'warning')
  }
}

const toggleEventComments = async (event: Event) => {
  await openCommentsModal(event)
}

const openCommentsModal = async (event: Event) => {
  currentCommentsEvent.value = event
  showCommentsModal.value = true
  
  // Load comments
  await loadEventComments(event)
}

const closeCommentsModal = () => {
  showCommentsModal.value = false
  currentCommentsEvent.value = null
  newCommentText.value = ''
}

const loadEventComments = async (event: Event) => {
  if (!event) return
  
  commentsLoading.value = true
  try {
    initializeEventInteractions(event)
    
    // Use videoApiService for comments (reusing existing infrastructure)
    const contentId = String(event.id)
    const commentsResponse = await videoApiService.getComments(contentId)
    
    if (commentsResponse.success) {
      eventComments.value[event.id] = {
        show: true,
        count: commentsResponse.data.length,
        comments: commentsResponse.data.map(comment => ({
          id: comment.id.toString(),
          user: comment.author_name || 'Anonymous',
          text: comment.content,
          date: comment.created_at,
          likes: comment.likes_count || 0,
          isLiked: false,
          isEdited: comment.is_edited || false,
          isPinned: comment.is_pinned || false
        })),
        newComment: ''
      }
    }
  } catch (error) {
    console.error('Error loading comments:', error)
  } finally {
    commentsLoading.value = false
  }
}

const addEventComment = async () => {
  if (!currentCommentsEvent.value) return
  
  const event = currentCommentsEvent.value
  
  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }

  const commentText = newCommentText.value.trim()
  
  if (!commentText) return

  isPostingComment.value = true
  
  try {
    initializeEventInteractions(event)
    
    // Use videoApiService for adding comments
    const contentId = String(event.id)
    const subscriberId = authStore.currentUser?.subscriber_id
    const sessionId = videoApiService.getSessionId()
    
    const response = await videoApiService.addComment(
      contentId,
      commentText,
      subscriberId,
      sessionId
    )
    
    if (response.success) {
      const newComment = {
        id: response.data.id.toString(),
        user: authStore.currentUser?.name || authStore.currentUser?.email || 'Anonymous',
        text: response.data.content,
        date: response.data.created_at,
        likes: 0,
        isLiked: false,
        isEdited: false,
        isPinned: false
      }
      
      if (!eventComments.value[event.id]) {
        eventComments.value[event.id] = {
          show: true,
          count: 0,
          comments: [],
          newComment: ''
        }
      }
      
      eventComments.value[event.id].comments.unshift(newComment)
      eventComments.value[event.id].count++
      newCommentText.value = ''
      
      showToast('Comment added successfully!', 'success')
    } else {
      showToast('Failed to add comment', 'error')
    }
  } catch (error) {
    console.error('Error adding comment:', error)
    showToast('Failed to add comment', 'error')
  } finally {
    isPostingComment.value = false
  }
}

const shareEvent = async (event: Event) => {
  currentShareEvent.value = event
  
  // Try native Web Share API first (mobile devices and modern browsers)
  if (navigator.share) {
    try {
      const shareData: ShareData = {
        title: event.title,
        text: event.description?.substring(0, 200) || event.title,
        url: getEventShareUrl(event)
      }
      
      // Check if can share
      if (navigator.canShare && !navigator.canShare(shareData)) {
        throw new Error('Cannot share this content')
      }
      
      await navigator.share(shareData)
      
      // Track successful native share
      await trackShareEvent(event.id, 'native_share')
      showToast('Event shared successfully!', 'success')
      return
    } catch (error: any) {
      // User cancelled or error occurred
      if (error.name === 'AbortError') {
        return // User cancelled, don't show modal
      }
      // Fall through to show custom modal
    }
  }
  
  // Fallback to custom share modal
  showShareModal.value = true
}

// Track share events for analytics
const trackShareEvent = async (eventId: string | number, platform: string) => {
  try {
    // Track share in backend (optional)
    // await videoApiService.addShare(String(eventId), platform)
    
    // Track in frontend analytics
    if (typeof window !== 'undefined' && (window as any).gtag) {
      (window as any).gtag('event', 'share', {
        method: platform,
        content_type: 'event',
        item_id: eventId
      })
    }
  } catch (error) {
    // Silently fail analytics
    console.debug('Share tracking failed:', error)
  }
}

const closeShareModal = () => {
  showShareModal.value = false
  currentShareEvent.value = null
  linkCopied.value = false
  qrCodeUrl.value = null
}

const getEventShareUrl = (event: Event): string => {
  return `${window.location.origin}/events#${event.id}`
}

// Check if native share API is available
const hasNativeShare = computed(() => {
  return typeof navigator !== 'undefined' && 'share' in navigator
})

const shareViaNative = async () => {
  if (!currentShareEvent.value) return
  
  if (typeof navigator === 'undefined' || !('share' in navigator)) {
    await copyEventLink()
    return
  }
  
  try {
    await navigator.share({
      title: currentShareEvent.value.title,
      text: `Check out this event: ${currentShareEvent.value.title}`,
      url: getEventShareUrl(currentShareEvent.value)
    })
    await trackShareEvent(currentShareEvent.value.id, 'native_share')
    showShareModal.value = false
  } catch (error) {
    // User cancelled or error occurred
    if ((error as Error).name !== 'AbortError') {
      await copyEventLink()
    }
  }
}

const shareToPlatform = async (platform: string) => {
  if (!currentShareEvent.value) {
    console.error('No event selected for sharing')
    return
  }
  
  const event = currentShareEvent.value
  const url = getEventShareUrl(event)
  const encodedUrl = encodeURIComponent(url)
  const title = encodeURIComponent(event.title)
  const cleanDescription = (event.description || event.title).substring(0, 200).replace(/\n/g, ' ').trim()
  const description = encodeURIComponent(cleanDescription)
  const hashtags = encodeURIComponent('StargateProject,AI,StargateCI')
  
  console.log('Sharing to platform:', platform, 'URL:', url)
  
  // Modern share URLs with better formatting
  const shareUrls: Record<string, { url: string; method: 'window' | 'redirect' | 'clipboard' }> = {
    facebook: {
      url: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${description}`,
      method: 'window'
    },
    twitter: {
      url: `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${title}&hashtags=${hashtags}`,
      method: 'window'
    },
    linkedin: {
      url: `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`,
      method: 'window'
    },
    whatsapp: {
      url: `https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`,
      method: 'window'
    },
    telegram: {
      url: `https://t.me/share/url?url=${encodedUrl}&text=${title}`,
      method: 'window'
    },
    instagram: {
      url: `https://www.instagram.com/`,
      method: 'clipboard' // Instagram doesn't support direct sharing, copy link instead
    },
    reddit: {
      url: `https://reddit.com/submit?url=${encodedUrl}&title=${title}`,
      method: 'window'
    },
    pinterest: {
      url: `https://pinterest.com/pin/create/button/?url=${encodedUrl}&description=${title}`,
      method: 'window'
    },
    discord: {
      url: `https://discord.com/channels/@me`,
      method: 'clipboard' // Discord doesn't support direct sharing, copy link instead
    },
    email: {
      url: `mailto:?subject=${title}&body=${description}%0A%0A${url}%0A%0AJoin us at Stargate.ci`,
      method: 'redirect'
    },
    sms: {
      url: `sms:?body=${title}%20${url}`,
      method: 'redirect'
    }
  }
  
  const shareConfig = shareUrls[platform]
  
  if (!shareConfig) {
    showToast('Platform not supported', 'warning')
    return
  }
  
  try {
    if (shareConfig.method === 'window') {
      console.log('Opening share window for', platform, 'with URL:', shareConfig.url)
      
      // Open in popup window with modern dimensions
      const width = 600
      const height = 700
      const left = Math.max(0, (window.screen.width - width) / 2)
      const top = Math.max(0, (window.screen.height - height) / 2)
      
      // Try to open popup - use unique window name to avoid conflicts
      const windowName = `share_${platform}_${Date.now()}`
      const shareWindow = window.open(
        shareConfig.url,
        windowName,
        `width=${width},height=${height},left=${left},top=${top},toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1`
      )
      
      console.log('Share window opened:', shareWindow ? 'success' : 'blocked')
      
      if (shareWindow) {
        // Focus the window
        shareWindow.focus()
        
        // Check if window was closed immediately (popup blocker)
        setTimeout(() => {
          if (shareWindow.closed) {
            // Popup was blocked, try direct navigation
            window.open(shareConfig.url, '_blank', 'noopener,noreferrer')
            showToast(`Opening ${platform} in new tab...`, 'info')
          }
        }, 100)
        
        // Track share
        await trackShareEvent(event.id, platform)
        showToast(`Opening ${platform}...`, 'success')
        
        // Close modal after a short delay
        setTimeout(() => {
          showShareModal.value = false
        }, 500)
      } else {
        // Popup blocked - try direct navigation in new tab
        console.warn('Popup blocked, trying direct navigation')
        const newTab = window.open(shareConfig.url, '_blank', 'noopener,noreferrer')
        if (newTab) {
          await trackShareEvent(event.id, platform)
          showToast(`Opening ${platform} in new tab...`, 'info')
          setTimeout(() => {
            showShareModal.value = false
          }, 500)
        } else {
          showToast('Please allow popups or open in new tab manually', 'warning')
        }
      }
    } else if (shareConfig.method === 'redirect') {
      // Direct redirect (email, SMS)
      window.location.href = shareConfig.url
      await trackShareEvent(event.id, platform)
      showShareModal.value = false
    } else if (shareConfig.method === 'clipboard') {
      // Copy to clipboard for platforms that don't support direct sharing
      await copyEventLink()
      await trackShareEvent(event.id, platform)
      showToast(`Link copied! Paste it in ${platform}`, 'success')
      setTimeout(() => {
        showShareModal.value = false
      }, 1500)
    }
  } catch (error: any) {
    console.error(`Error sharing to ${platform}:`, error)
    showToast(`Failed to share to ${platform}`, 'error')
  }
}

const copyEventLink = async () => {
  if (!currentShareEvent.value) return
  
  const url = getEventShareUrl(currentShareEvent.value)
  
  try {
    // Modern clipboard API
    if (navigator.clipboard && navigator.clipboard.writeText) {
      await navigator.clipboard.writeText(url)
    } else {
      // Fallback for older browsers
      const input = document.getElementById('share-url-input') as HTMLInputElement
      if (input) {
        input.select()
        input.setSelectionRange(0, 99999)
        document.execCommand('copy')
      }
    }
    
    linkCopied.value = true
    showToast('Link copied to clipboard!', 'success')
    
    // Reset copied state after 2 seconds
    setTimeout(() => {
      linkCopied.value = false
    }, 2000)
    
    // Track copy event (optional - for analytics)
    // await trackShareEvent(currentShareEvent.value.id, 'copy')
  } catch (error) {
    console.error('Failed to copy link:', error)
    showToast('Failed to copy link', 'error')
  }
}

const generateQRCode = () => {
  if (!currentShareEvent.value) return
  
  const url = getEventShareUrl(currentShareEvent.value)
  // Using a QR code API service with modern styling
  const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=20&data=${encodeURIComponent(url)}&color=000000&bgcolor=ffffff`
  
  qrCodeUrl.value = qrApiUrl
  showToast('QR Code generated!', 'success')
  
  // Track QR code generation (optional - for analytics)
  // await trackShareEvent(currentShareEvent.value.id, 'qr_code')
}

const formatCommentDate = (dateString: string): string => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return 'Just now'
  if (diffInHours < 24) return `${diffInHours}h ago`
  if (diffInHours < 168) return `${Math.floor(diffInHours / 24)}d ago`
  return date.toLocaleDateString()
}

const getInitials = (name: string): string => {
  if (!name) return 'U'
  const parts = name.split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

const showToast = (message: string, type: 'success' | 'error' | 'warning' = 'success') => {
  // Simple toast implementation
  const toast = document.createElement('div')
  toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 ${
    type === 'success' ? 'bg-green-600 text-white' :
    type === 'error' ? 'bg-red-600 text-white' :
    'bg-yellow-600 text-white'
  }`
  toast.textContent = message
  document.body.appendChild(toast)
  
  setTimeout(() => {
    toast.style.opacity = '0'
    setTimeout(() => document.body.removeChild(toast), 300)
  }, 3000)
}

const loadEvents = async (category?: string) => {
  isLoading.value = true
  try {
    const filters: Record<string, any> = {}
    
    if (category && category !== 'all') {
      filters.category = category
    }
    
    if (selectedTimeframe.value === 'upcoming') {
      filters.upcoming = true
    }
    
    const response = await eventsApiService.getAllEvents(15, filters)
    
    if (response.success && response.data.length > 0) {
      events.value = response.data
    } else {
      const fallbackResponse = await eventsApiService.generateEvents(category, 15)
      if (fallbackResponse.success) {
        events.value = fallbackResponse.data
      }
    }
  } catch (error) {
    console.error('Error loading events:', error)
    try {
      const fallbackResponse = await eventsApiService.generateEvents(category, 15)
      events.value = fallbackResponse.data
    } catch (fallbackError) {
      console.error('Fallback error:', fallbackError)
    }
  } finally {
    isLoading.value = false
  }
}

const loadUpcomingEvents = async () => {
  isLoading.value = true
  try {
    const response = await eventsApiService.getUpcomingEvents(10)
    if (response.success) {
      events.value = response.data
    }
  } catch (error) {
    console.error('Error loading upcoming events:', error)
  } finally {
    isLoading.value = false
  }
}

const refreshEvents = async () => {
  isLoading.value = true
  try {
    const syncResponse = await eventsApiService.syncEvents(true)
    await loadEvents(selectedCategory.value === 'all' ? undefined : selectedCategory.value)
  } catch (error) {
    console.error('Error refreshing events:', error)
    await loadEvents(selectedCategory.value === 'all' ? undefined : selectedCategory.value)
  } finally {
    isLoading.value = false
  }
}

// Initialize interactions for all events after loading
watch(() => events.value, (newEvents) => {
  newEvents.forEach(event => {
    initializeEventInteractions(event)
  })
}, { immediate: true })

// Create Event Functions
const openCreateEventModal = () => {
  if (!isAuthenticated.value) {
    router.push('/signin')
    return
  }
  
  // Pre-fill organizer name if available
  if (authStore.currentUser) {
    createEventForm.value.organizer = authStore.currentUser.name || authStore.currentUser.email || ''
  }
  
  // Set default date to tomorrow
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  createEventForm.value.event_date = tomorrow.toISOString().split('T')[0]
  
  showCreateEventModal.value = true
}

const closeCreateEventModal = () => {
  showCreateEventModal.value = false
  createEventForm.value = {
    title: '',
    description: '',
    category: 'stargate',
    type: 'conference',
    event_date: '',
    event_time: '',
    location: '',
    organizer: '',
    icon: '📅',
    registration_url: '',
    video_url: '',
    is_featured: false
  }
  createEventErrors.value = {}
}

const submitCreateEvent = async () => {
  createEventErrors.value = {}
  
  // Validation
  if (!createEventForm.value.title.trim()) {
    createEventErrors.value.title = 'Title is required'
    return
  }
  
  if (!createEventForm.value.description.trim()) {
    createEventErrors.value.description = 'Description is required'
    return
  }
  
  if (createEventForm.value.description.length > 5000) {
    createEventErrors.value.description = 'Description must be less than 5000 characters'
    return
  }
  
  if (!createEventForm.value.event_date) {
    createEventErrors.value.event_date = 'Event date is required'
    return
  }
  
  if (!createEventForm.value.location.trim()) {
    createEventErrors.value.location = 'Location is required'
    return
  }
  
  if (!createEventForm.value.organizer.trim()) {
    createEventErrors.value.organizer = 'Organizer name is required'
    return
  }
  
  isCreatingEvent.value = true
  
  try {
    const response = await eventsApiService.createEvent({
      title: createEventForm.value.title.trim(),
      description: createEventForm.value.description.trim(),
      category: createEventForm.value.category,
      type: createEventForm.value.type,
      event_date: createEventForm.value.event_date,
      event_time: createEventForm.value.event_time || undefined,
      location: createEventForm.value.location.trim(),
      organizer: createEventForm.value.organizer.trim(),
      icon: createEventForm.value.icon || '📅',
      registration_url: createEventForm.value.registration_url.trim() || undefined,
      video_url: createEventForm.value.video_url.trim() || undefined,
      is_featured: createEventForm.value.is_featured
    })
    
    if (response.success) {
      showToast('Event created successfully!', 'success')
      closeCreateEventModal()
      
      // Reload events to show the new one
      await loadEvents(selectedCategory.value === 'all' ? undefined : selectedCategory.value)
    } else {
      if (response.errors) {
        // Convert Laravel validation errors format
        Object.keys(response.errors).forEach(key => {
          const errors = response.errors![key]
          createEventErrors.value[key] = Array.isArray(errors) ? errors[0] : errors
        })
      }
      showToast(response.message || 'Failed to create event', 'error')
    }
  } catch (error: any) {
    console.error('Error creating event:', error)
    showToast(error.message || 'Failed to create event', 'error')
  } finally {
    isCreatingEvent.value = false
  }
}

// Initialize auth store
onMounted(() => {
  authStore.initialize()
  loadEvents()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Modal Transitions */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active > div,
.modal-leave-active > div {
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal-enter-from > div,
.modal-leave-to > div {
  transform: scale(0.9) translateY(-20px);
  opacity: 0;
}
</style>