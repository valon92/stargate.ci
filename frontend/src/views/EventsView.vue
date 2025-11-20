<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Upcoming Events</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Stay informed about upcoming events, meetings, conferences, and important dates related to the Stargate Project and Cristal Intelligence.
          </p>
        </div>
      </div>
    </section>

    <!-- Events Filter -->
    <section class="py-16 bg-gray-800/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">
        <!-- Search Bar -->
        <div class="mb-8">
          <div class="max-w-2xl mx-auto">
            <div class="relative">
              <input
                v-model="searchQuery"
                @keyup.enter="searchEvents(searchQuery)"
                type="text"
                placeholder="Search events..."
                class="w-full px-4 py-3 pl-12 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              <button
                @click="searchEvents(searchQuery)"
                :disabled="isLoading"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="!isLoading" class="h-5 w-5 text-gray-400 hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <div v-else class="animate-spin rounded-full h-5 w-5 border-b-2 border-primary-500"></div>
              </button>
            </div>
          </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-12">
          <div class="flex flex-wrap gap-2">
            <button 
              v-for="category in eventCategories" 
              :key="category.id"
              @click="filterByCategory(category.id)"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-all duration-200',
                selectedCategory === category.id 
                  ? 'bg-primary-500 text-white' 
                  : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
              ]"
            >
              {{ category.name }}
            </button>
          </div>
          <div class="flex gap-2">
            <select v-model="selectedTimeframe" @change="filterByTimeframe" class="bg-gray-800 border border-gray-600 text-white rounded-lg px-4 py-2">
              <option value="all">All Time</option>
              <option value="upcoming">Upcoming</option>
              <option value="this-month">This Month</option>
              <option value="next-month">Next Month</option>
            </select>
            <button
              @click="refreshEvents"
              :disabled="isLoading"
              class="px-4 py-2 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2"
            >
              <svg v-if="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              <div v-else class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
              {{ isLoading ? 'Searching...' : 'Search Real Events' }}
            </button>
            
            
            
          </div>
        </div>
      </div>
    </section>

    <!-- Events Grid -->
    <section class="py-24">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">
        <!-- API Events Notice -->
        <div v-if="!isLoading && events.length > 0" class="mb-8 p-4 bg-blue-900/20 border border-blue-500/30 rounded-lg">
          <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-blue-300 font-medium">Live Events from External APIs</h3>
              <p class="text-blue-200/80 text-sm">These events are fetched from external APIs including OpenAI, SoftBank, Oracle, and MGX. Data is automatically synced and updated.</p>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
          <p class="mt-4 text-gray-400">Searching for real events...</p>
        </div>

        <!-- Events Display - Single Card Style (like videos on home page) -->
        <div v-else class="space-y-8">
          <div 
            v-for="event in filteredEvents" 
            :key="event.id"
            class="event-container group hover:scale-[1.02] transition-all duration-300"
          >
            <!-- Event Video/Content -->
            <div class="relative overflow-hidden rounded-lg">
              <div v-if="event.type === 'video'" class="aspect-w-16 aspect-h-9">
                <iframe
                  :src="event.video_url"
                  :title="event.title"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  loading="lazy"
                ></iframe>
              </div>
              <div v-else class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-primary-900/20 to-secondary-900/20 flex items-center justify-center">
                <div class="text-center">
                  <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">{{ event.icon }}</span>
                  </div>
                  <h3 class="text-2xl font-bold text-white">{{ event.title }}</h3>
                </div>
              </div>
              
              <!-- Event Badge -->
              <div class="absolute top-4 left-4">
                <span :class="getEventBadgeClass(event.category)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ getCategoryName(event.category) }}
                </span>
              </div>
              
              <!-- Event Source Badge -->
              <div class="absolute top-4 right-4 flex items-center gap-2">
                <span class="px-3 py-1 bg-gray-800/80 text-white rounded-full text-xs font-medium">
                  {{ event.type }}
                </span>
                <span class="px-2 py-1 text-xs font-medium rounded-full"
                      :class="getSourceBadgeClass(event.source)">
                  {{ getSourceName(event.source) }}
                </span>
                <span v-if="event.is_featured" class="px-2 py-1 text-xs font-medium bg-yellow-500/20 text-yellow-300 rounded-full">
                  ⭐
                </span>
              </div>
            </div>

            <!-- Event Content -->
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  {{ formatDate(event.event_date) }}
                </div>
                <div v-if="event.event_time" class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  {{ formatTime(event.event_time) }}
                </div>
              </div>

              <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-primary-400 transition-colors">
                {{ event.title }}
              </h3>
              
              <p class="text-gray-400 mb-6 text-lg leading-relaxed">
                {{ event.description }}
              </p>

              <div class="flex items-center justify-between mb-6">
                <div class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  {{ event.location }}
                </div>
                <div v-if="event.organizer" class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  {{ event.organizer }}
                </div>
              </div>

              <!-- Event Actions -->
              <div class="flex gap-3 mb-6">
                <!-- Video Events: Only show Watch Video button -->
                <button 
                  v-if="event.type === 'video' && event.video_url"
                  @click="openVideo(event.video_url)"
                  class="flex-1 btn-primary"
                >
                  Watch Video
                </button>
                
                <!-- Events with vendor registration URL: Show Register Now (opens vendor URL) -->
                <button 
                  v-else-if="event.registration_url && !isEventPast(event.event_date)"
                  @click="() => event.registration_url && openUrl(event.registration_url)"
                  class="flex-1 btn-primary"
                >
                  Register Now
                </button>
                
                <!-- Past Events: Show Watch Recording -->
                <button 
                  v-else-if="isEventPast(event.event_date)"
                  @click="openPastEvent(event)"
                  class="flex-1 btn-primary"
                >
                  Watch Recording
                </button>
                
                <!-- Events without registration URL (not video): Show Subscribe button -->
                <button 
                  v-else
                  @click="scrollToSubscribe"
                  class="flex-1 btn-primary"
                >
                  Get Notified
                </button>
              </div>

              <!-- Interactive Content -->
              <InteractiveContent
                :content-id="event.id"
                content-type="event"
                :initial-likes="0"
                :initial-comments="[]"
              />
            </div>
          </div>
        </div>

        <!-- No Events Message -->
        <div v-if="!isLoading && filteredEvents.length === 0" class="text-center py-12">
          <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">No Events Found</h3>
          <p class="text-gray-400 mb-6">No events match your current filters. Try adjusting your search criteria.</p>
          <button @click="clearFilters" class="btn-primary">
            Clear Filters
          </button>
        </div>
      </div>
    </section>

    <!-- Event Categories Info -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Event Categories</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Explore different types of events related to the Stargate Project and Cristal Intelligence ecosystem.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="category in eventCategories" 
            :key="category.id"
            class="card text-center group hover:scale-105 transition-transform duration-300"
          >
            <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">{{ category.icon }}</span>
            </div>
            <h3 class="text-xl font-bold mb-4">{{ category.name }}</h3>
            <p class="text-gray-400 mb-4">{{ category.description }}</p>
            <div class="text-sm text-gray-500">
              {{ getEventsCountByCategory(category.id) }} events
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter Subscription -->
    <section class="py-24">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Join Our Community</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Stay informed about the latest developments in AI infrastructure, Stargate Project updates, and Cristal Intelligence breakthroughs. Be part of the future of artificial intelligence.
        </p>
        
        <div class="max-w-md mx-auto">
          <div class="flex gap-4">
            <input 
              v-model="email"
              type="email" 
              placeholder="Your email address"
              class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
            <button 
              @click="subscribeToEvents"
              :disabled="!email || isSubscribing"
              class="px-6 py-3 bg-black text-white rounded-lg font-medium hover:bg-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubscribing ? 'Joining...' : 'Join Now' }}
            </button>
          </div>
          <p class="text-sm text-gray-400 mt-3">
            Join thousands of AI enthusiasts. No spam, just valuable insights.
          </p>
        </div>
      </div>
    </section>

    <!-- Auth Modal (Login/Register) -->
    <div v-if="showAuthModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="showAuthModal = false">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-white">
            {{ authModalMode === 'login' ? 'Sign In Required' : 'Create Account' }}
          </h3>
          <button 
            @click="showAuthModal = false"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="mb-6">
          <p class="text-gray-300 mb-4">
            {{ authModalMode === 'login' 
              ? 'You need to sign in to watch this event video.' 
              : 'Create a free account to access event videos and stay updated.' }}
          </p>
        </div>

        <div class="flex gap-3">
          <button 
            @click="showAuthModal = false"
            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="switchAuthMode"
            class="flex-1 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors"
          >
            {{ authModalMode === 'login' ? 'Sign Up Instead' : 'Sign In Instead' }}
          </button>
          <button 
            @click="authModalMode === 'login' ? goToSignIn() : goToSignUp()"
            class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            {{ authModalMode === 'login' ? 'Sign In' : 'Sign Up' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Video Modal -->
    <div v-if="showVideoModal" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50" @click="closeVideoModal">
      <div class="bg-gray-900 rounded-lg p-4 max-w-6xl w-full mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
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
            class="absolute top-0 left-0 w-full h-full"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
          ></iframe>
        </div>
        
        <div class="mt-4 text-center">
          <a 
            :href="currentVideoUrl || '#'"
            target="_blank"
            rel="noopener noreferrer"
            class="text-primary-400 hover:text-primary-300 underline"
          >
            Watch on YouTube
          </a>
        </div>
      </div>
    </div>

    <!-- Past Event Modal -->
    <div v-if="showPastEventModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="showPastEventModal = false">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-white">Event Recording</h3>
          <button 
            @click="showPastEventModal = false"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-4">
          <div class="p-4 bg-blue-500/20 border border-blue-500/30 rounded-lg">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
              <div>
                <h4 class="text-blue-300 font-medium">{{ currentPastEvent?.title }}</h4>
                <p class="text-blue-200/80 text-sm">This event has already passed on {{ formatDate(currentPastEvent?.event_date) }}</p>
              </div>
            </div>
          </div>

          <div class="p-4 bg-gray-700 rounded-lg">
            <p class="text-gray-300 text-sm mb-3">This event has already passed on {{ formatDate(currentPastEvent?.event_date) }}.</p>
            <div class="text-xs text-gray-400 space-y-1">
              <p><strong>Event Details:</strong></p>
              <p>📅 Date: {{ formatDate(currentPastEvent?.event_date) }}</p>
              <p>🕐 Time: {{ currentPastEvent?.event_time ? formatTime(currentPastEvent.event_time) : 'TBD' }}</p>
              <p>📍 Location: {{ currentPastEvent?.location }}</p>
              <p>👤 Organizer: {{ currentPastEvent?.organizer }}</p>
              <p>🏷️ Category: {{ currentPastEvent?.category }}</p>
              <p>📝 Type: {{ currentPastEvent?.type }}</p>
            </div>
          </div>

          <div class="flex gap-3">
            <button 
              @click="showPastEventModal = false"
              class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
              Close
            </button>
            <button 
              @click="addToCalendar(currentPastEvent); showPastEventModal = false"
              class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
              Add to Calendar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Registration Modal -->
    <div v-if="showRegistrationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="showRegistrationModal = false">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-white">Registration Information</h3>
          <button 
            @click="showRegistrationModal = false"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-4">
          <div class="p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
              <div>
                <h4 class="text-yellow-300 font-medium">Demo Registration URL</h4>
                <p class="text-yellow-200/80 text-sm">This is a demonstration URL. In a real application, this would redirect to the actual event registration page.</p>
              </div>
            </div>
          </div>

          <div class="p-4 bg-gray-700 rounded-lg">
            <p class="text-gray-300 text-sm mb-2">Registration URL:</p>
            <code class="text-blue-300 text-xs break-all">{{ currentRegistrationUrl }}</code>
          </div>

          <div class="flex gap-3">
            <button 
              @click="showRegistrationModal = false"
              class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
              Close
            </button>
            <button 
              @click="() => { openUrl(currentRegistrationUrl); showRegistrationModal = false }"
              class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
              Open Anyway
            </button>
          </div>
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
import InteractiveContent from '../components/InteractiveContent.vue'

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

// Initialize auth store
onMounted(() => {
  authStore.initialize()
  
  // Check if there's a pending video URL from sessionStorage
  const savedVideoUrl = sessionStorage.getItem('pending_video_url')
  if (savedVideoUrl && authStore.isAuthenticated) {
    // User is authenticated and has pending video, show it in modal
    currentVideoUrl.value = savedVideoUrl
    showVideoModal.value = true
    sessionStorage.removeItem('pending_video_url')
    pendingVideoUrl.value = null
  } else if (savedVideoUrl) {
    // Video URL exists but user is not authenticated, restore it
    pendingVideoUrl.value = savedVideoUrl
  }
  
  // Listen for auth changes
  window.addEventListener('auth-changed', () => {
    if (authStore.isAuthenticated && pendingVideoUrl.value) {
      handleAuthSuccess()
      sessionStorage.removeItem('pending_video_url')
    }
  })
})

// Watch for auth changes
watch(() => authStore.isAuthenticated, (isAuthenticated) => {
  if (isAuthenticated && pendingVideoUrl.value && showAuthModal.value) {
    handleAuthSuccess()
  }
})

// Reactive data
const selectedCategory = ref('all')
const selectedTimeframe = ref('upcoming')
const email = ref('')
const isSubscribing = ref(false)
const isLoading = ref(false)
const searchQuery = ref('')

// Registration modal state
const showRegistrationModal = ref(false)
const currentRegistrationUrl = ref('')

// Past event modal state
const showPastEventModal = ref(false)
const currentPastEvent = ref<any>(null)

// Auth modal state
const showAuthModal = ref(false)
const pendingVideoUrl = ref<string | null>(null)
const authModalMode = ref<'login' | 'register'>('login')

// Helper functions
const openUrl = (url: string) => {
  if (typeof window !== 'undefined') {
    window.open(url, '_blank')
  }
}

// Video modal state
const showVideoModal = ref(false)
const currentVideoUrl = ref<string | null>(null)


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

// Events data - start empty, will be populated by API
const events = ref<Event[]>([])

// Computed properties
const filteredEvents = computed(() => {
  let filtered = events.value
  
  console.log('🔍 FilteredEvents computed - Input events:', events.value.length)
  console.log('🔍 FilteredEvents computed - Events:', events.value.map(e => e.title))

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

  // Sort by date
  const sorted = filtered.sort((a, b) => new Date(a.event_date).getTime() - new Date(b.event_date).getTime())
  
  console.log('🔍 FilteredEvents computed - Output events:', sorted.length)
  console.log('🔍 FilteredEvents computed - Output titles:', sorted.map(e => e.title))
  
  return sorted
})

// Methods
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
    stargate: 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    cristal: 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    conferences: 'bg-green-500/20 text-green-400 border border-green-500/30',
    meetings: 'bg-orange-500/20 text-orange-400 border border-orange-500/30',
    announcements: 'bg-red-500/20 text-red-400 border border-red-500/30'
  }
  return classes[category as keyof typeof classes] || 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
}

// Get source name
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

// Get source badge class
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
    month: 'long',
    day: 'numeric'
  })
}

// Format time
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

// Check if event is in the past
const isEventPast = (eventDate: string) => {
  const event = new Date(eventDate)
  const today = new Date()
  today.setHours(0, 0, 0, 0) // Reset time to start of day
  return event < today
}

const openPastEvent = (event: any) => {
  // Check if event has video URL
  if (event.video_url) {
    window.open(event.video_url, '_blank')
    return
  }
  
  // If no video URL, show modal with options
  showPastEventModal.value = true
  currentPastEvent.value = event
}

const openVideo = (videoUrl: string) => {
  // Check if user is authenticated
  if (!authStore.isAuthenticated) {
    // User is not authenticated, show auth modal
    pendingVideoUrl.value = videoUrl
    showAuthModal.value = true
    authModalMode.value = 'login'
    return
  }
  
  // User is authenticated, show video in modal
  currentVideoUrl.value = videoUrl
  showVideoModal.value = true
}

const handleAuthSuccess = () => {
  // After successful authentication, show video in modal
  if (pendingVideoUrl.value) {
    currentVideoUrl.value = pendingVideoUrl.value
    showVideoModal.value = true
    pendingVideoUrl.value = null
  }
  showAuthModal.value = false
}

const closeVideoModal = () => {
  showVideoModal.value = false
  currentVideoUrl.value = null
}

const getYouTubeEmbedUrl = (url: string): string => {
  if (!url) return ''
  
  // Extract video ID from YouTube URL
  // Supports formats:
  // - https://www.youtube.com/watch?v=VIDEO_ID
  // - https://youtube.com/watch?v=VIDEO_ID
  // - https://youtu.be/VIDEO_ID
  // - youtube.com/watch?v=VIDEO_ID
  
  let videoId = ''
  
  // Check for watch?v= format
  const watchMatch = url.match(/[?&]v=([^&]+)/)
  if (watchMatch) {
    videoId = watchMatch[1]
  } else {
    // Check for youtu.be format
    const shortMatch = url.match(/youtu\.be\/([^?]+)/)
    if (shortMatch) {
      videoId = shortMatch[1]
    } else {
      // Try to extract from URL path
      const pathMatch = url.match(/\/([a-zA-Z0-9_-]{11})/)
      if (pathMatch) {
        videoId = pathMatch[1]
      }
    }
  }
  
  if (!videoId) {
    // If we can't extract video ID, return original URL (will open in new tab)
    return url
  }
  
  return `https://www.youtube.com/embed/${videoId}?autoplay=1`
}

const switchAuthMode = () => {
  // Save pending video URL to sessionStorage
  if (pendingVideoUrl.value) {
    sessionStorage.setItem('pending_video_url', pendingVideoUrl.value)
  }
  
  // Save current page URL and scroll position
  sessionStorage.setItem('return_url', window.location.pathname + window.location.search)
  sessionStorage.setItem('return_scroll', window.scrollY.toString())
  
  // Redirect to appropriate page
  if (authModalMode.value === 'login') {
    // Currently showing login, redirect to signup
    router.push('/signup')
  } else {
    // Currently showing register, redirect to signin
    router.push('/signin')
  }
  
  showAuthModal.value = false
}

const goToSignIn = () => {
  // Save pending video URL to sessionStorage
  if (pendingVideoUrl.value) {
    sessionStorage.setItem('pending_video_url', pendingVideoUrl.value)
  }
  
  // Save current page URL and scroll position
  sessionStorage.setItem('return_url', window.location.pathname + window.location.search)
  sessionStorage.setItem('return_scroll', window.scrollY.toString())
  
  showAuthModal.value = false
  router.push('/signin')
}

const goToSignUp = () => {
  // Save pending video URL to sessionStorage
  if (pendingVideoUrl.value) {
    sessionStorage.setItem('pending_video_url', pendingVideoUrl.value)
  }
  
  // Save current page URL and scroll position
  sessionStorage.setItem('return_url', window.location.pathname + window.location.search)
  sessionStorage.setItem('return_scroll', window.scrollY.toString())
  
  showAuthModal.value = false
  router.push('/signup')
}

const scrollToSubscribe = () => {
  const subscribeSection = document.querySelector('section:has(.max-w-md.mx-auto)')
  if (subscribeSection) {
    subscribeSection.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else {
    // Fallback: scroll to bottom
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' })
  }
}



const addToCalendar = (event: any) => {
  // Create calendar event URL
  const startDate = new Date(`${event.event_date}T${event.event_time || '00:00'}`)
  const endDate = new Date(startDate.getTime() + 2 * 60 * 60 * 1000) // 2 hours duration
  
  // Include registration URL in details if available
  let details = event.description
  if (event.registration_url) {
    details += `\n\nRegistration: ${event.registration_url}`
  }
  
  const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${startDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${endDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z&details=${encodeURIComponent(details)}&location=${encodeURIComponent(event.location)}`

  window.open(calendarUrl, '_blank')
}

const subscribeToEvents = async () => {
  if (!email.value) return
  
  isSubscribing.value = true
  
  try {
    // Simulate API call
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
}

// Load events from API
const loadEvents = async (category?: string) => {
  isLoading.value = true
  try {
    const filters = {
      category: category === 'all' ? undefined : category,
      upcoming: selectedTimeframe.value === 'upcoming' ? true : undefined
    }
    
    console.log('🔍 Loading events with filters:', filters)
    console.log('🔍 Selected timeframe:', selectedTimeframe.value)
    
    // Try to get events from real API first
    const response = await eventsApiService.getAllEvents(15, filters)
    
    console.log('🔍 API Response:', response)
    
    if (response.success && response.data.length > 0) {
      events.value = response.data
      console.log('✅ Loaded real events from API:', response.data.length)
      console.log('✅ Events:', response.data.map(e => e.title))
      console.log('✅ API Sources:', response.meta?.sources)
      console.log('✅ Events array after assignment:', events.value)
      console.log('✅ Events length after assignment:', events.value.length)
    } else {
      console.log('⚠️ No events from API, using fallback')
      // Fallback to generated events
      const fallbackResponse = await eventsApiService.generateEvents(category, 15)
      if (fallbackResponse.success) {
        events.value = fallbackResponse.data
        console.log('📅 Loaded fallback events:', fallbackResponse.data.length)
      } else {
        console.error('Error loading events:', fallbackResponse.error)
      }
    }
  } catch (error) {
    console.error('Error loading events:', error)
    // Final fallback to static data
    try {
      const fallbackResponse = await eventsApiService.generateEvents(category, 15)
      events.value = fallbackResponse.data
      console.log('📅 Using static fallback events:', fallbackResponse.data.length)
    } catch (fallbackError) {
      console.error('Fallback error:', fallbackError)
    }
  } finally {
    isLoading.value = false
  }
}

// Load upcoming events
const loadUpcomingEvents = async () => {
  isLoading.value = true
  try {
    const response = await eventsApiService.getUpcomingEvents(10)
    if (response.success) {
      events.value = response.data
      console.log('📅 Loaded upcoming events from API:', response.data.length)
    } else {
      console.error('Error loading upcoming events:', response.error)
    }
  } catch (error) {
    console.error('Error loading upcoming events:', error)
  } finally {
    isLoading.value = false
  }
}

// Search events
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
      console.log('🔍 Search results from API:', response.data.length)
    } else {
      console.error('Error searching events:', response.error)
    }
  } catch (error) {
    console.error('Error searching events:', error)
  } finally {
    isLoading.value = false
  }
}

// Refresh events from external APIs
const refreshEvents = async () => {
  isLoading.value = true
  try {
    // Sync events from external APIs
    const syncResponse = await eventsApiService.syncEvents(true)
    console.log('🔄 Events sync completed:', syncResponse.data?.total_synced || 0)
    
    // Reload events after sync
    await loadEvents(selectedCategory.value === 'all' ? undefined : selectedCategory.value)
    
    console.log('🔄 Refreshed events from external APIs')
  } catch (error) {
    console.error('Error refreshing events:', error)
    // Fallback to regular load
    await loadEvents(selectedCategory.value === 'all' ? undefined : selectedCategory.value)
  } finally {
    isLoading.value = false
  }
}




// Lifecycle
onMounted(async () => {
  await loadEvents()
})
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.event-container {
  background-color: rgba(31, 41, 55, 1);
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid rgba(55, 65, 81, 1);
}

.aspect-w-16 {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-h-9 {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.aspect-w-16 iframe {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}
</style>
