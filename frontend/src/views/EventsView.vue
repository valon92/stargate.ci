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
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
          <select v-model="selectedTimeframe" @change="filterByTimeframe" class="bg-gray-800 border border-gray-600 text-white rounded-lg px-4 py-2">
            <option value="all">All Time</option>
            <option value="upcoming">Upcoming</option>
            <option value="this-month">This Month</option>
            <option value="next-month">Next Month</option>
          </select>
        </div>
      </div>
    </section>

    <!-- Events Grid -->
    <section class="py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
          <p class="mt-4 text-gray-400">Loading events...</p>
        </div>

        <!-- Events Grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="event in filteredEvents" 
            :key="event.id"
            class="card group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <!-- Event Image/Video -->
            <div class="relative overflow-hidden rounded-t-lg">
              <div v-if="event.type === 'video'" class="aspect-w-16 aspect-h-9">
                <iframe
                  :src="event.videoUrl"
                  :title="event.title"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  loading="lazy"
                ></iframe>
              </div>
              <div v-else class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-primary-900/20 to-secondary-900/20 flex items-center justify-center">
                <div class="text-center">
                  <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">{{ event.icon }}</span>
                  </div>
                  <h3 class="text-lg font-bold text-white">{{ event.title }}</h3>
                </div>
              </div>
              
              <!-- Event Badge -->
              <div class="absolute top-4 left-4">
                <span :class="getEventBadgeClass(event.category)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ getCategoryName(event.category) }}
                </span>
              </div>
              
              <!-- Event Type Badge -->
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-gray-800/80 text-white rounded-full text-xs font-medium">
                  {{ event.type }}
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
                  {{ formatDate(event.date) }}
                </div>
                <div v-if="event.time" class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  {{ event.time }}
                </div>
              </div>

              <h3 class="text-xl font-bold text-white mb-3 group-hover:text-primary-400 transition-colors">
                {{ event.title }}
              </h3>
              
              <p class="text-gray-400 mb-4 line-clamp-3">
                {{ event.description }}
              </p>

              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  {{ event.location }}
                </div>
                <div v-if="event.organizer" class="text-sm text-gray-400">
                  by {{ event.organizer }}
                </div>
              </div>

              <!-- Event Actions -->
              <div class="flex gap-3">
                <button 
                  v-if="event.type === 'video'"
                  @click="openVideo(event.videoUrl)"
                  class="flex-1 btn-primary text-sm"
                >
                  Watch Video
                </button>
                <button 
                  v-else-if="event.registrationUrl"
                  @click="openRegistration(event.registrationUrl)"
                  class="flex-1 btn-primary text-sm"
                >
                  Register Now
                </button>
                <button 
                  v-else
                  @click="viewEventDetails(event)"
                  class="flex-1 btn-outline text-sm"
                >
                  View Details
                </button>
                
                <button 
                  @click="addToCalendar(event)"
                  class="px-3 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                  title="Add to Calendar"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </button>
              </div>
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
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
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
          <span class="gradient-text">Stay Updated</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Get notified about upcoming events, important announcements, and key milestones in the Stargate Project and Cristal Intelligence development.
        </p>
        
        <div class="max-w-md mx-auto">
          <div class="flex gap-4">
            <input 
              v-model="email"
              type="email" 
              placeholder="Enter your email address"
              class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
            <button 
              @click="subscribeToEvents"
              :disabled="!email || isSubscribing"
              class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubscribing ? 'Subscribing...' : 'Subscribe' }}
            </button>
          </div>
          <p class="text-sm text-gray-400 mt-3">
            We respect your privacy. Unsubscribe at any time.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useHead } from '@vueuse/head'
import { eventsApiService, type Event } from '../services/eventsApiService'

// Meta tags
useHead({
  title: 'Upcoming Events - Stargate.ci',
  meta: [
    { name: 'description', content: 'Stay informed about upcoming events, meetings, conferences, and important dates related to the Stargate Project and Cristal Intelligence.' }
  ]
})

// Reactive data
const selectedCategory = ref('all')
const selectedTimeframe = ref('upcoming')
const email = ref('')
const isSubscribing = ref(false)
const isLoading = ref(false)
const searchQuery = ref('')

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

  // Filter by category
  if (selectedCategory.value !== 'all') {
    filtered = filtered.filter(event => event.category === selectedCategory.value)
  }

  // Filter by timeframe
  const now = new Date()
  const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())

  switch (selectedTimeframe.value) {
    case 'upcoming':
      filtered = filtered.filter(event => new Date(event.date) >= today)
      break
    case 'this-month':
      const thisMonthStart = new Date(now.getFullYear(), now.getMonth(), 1)
      const thisMonthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0)
      filtered = filtered.filter(event => {
        const eventDate = new Date(event.date)
        return eventDate >= thisMonthStart && eventDate <= thisMonthEnd
      })
      break
    case 'next-month':
      const nextMonthStart = new Date(now.getFullYear(), now.getMonth() + 1, 1)
      const nextMonthEnd = new Date(now.getFullYear(), now.getMonth() + 2, 0)
      filtered = filtered.filter(event => {
        const eventDate = new Date(event.date)
        return eventDate >= nextMonthStart && eventDate <= nextMonthEnd
      })
      break
  }

  // Sort by date
  return filtered.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())
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

const openVideo = (videoUrl: string) => {
  window.open(videoUrl, '_blank')
}

const openRegistration = (registrationUrl: string) => {
  window.open(registrationUrl, '_blank')
}

const viewEventDetails = (event: any) => {
  // In a real app, this would open a modal or navigate to event details
  alert(`Event Details:\n\n${event.title}\n\n${event.description}\n\nDate: ${formatDate(event.date)}\nTime: ${event.time}\nLocation: ${event.location}`)
}

const addToCalendar = (event: any) => {
  // Create calendar event URL
  const startDate = new Date(`${event.date}T${event.time || '00:00'}`)
  const endDate = new Date(startDate.getTime() + 2 * 60 * 60 * 1000) // 2 hours duration
  
  const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${startDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${endDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z&details=${encodeURIComponent(event.description)}&location=${encodeURIComponent(event.location)}`
  
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
    const response = await eventsApiService.generateEvents(category, 15)
    if (response.success) {
      events.value = response.events
      console.log('📅 Loaded events:', response.events.length)
    } else {
      console.error('Error loading events:', response.error)
    }
  } catch (error) {
    console.error('Error loading events:', error)
  } finally {
    isLoading.value = false
  }
}

// Load upcoming events
const loadUpcomingEvents = async () => {
  isLoading.value = true
  try {
    const response = await eventsApiService.getUpcomingEvents()
    if (response.success) {
      events.value = response.events
      console.log('📅 Loaded upcoming events:', response.events.length)
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
    const response = await eventsApiService.searchEvents(query, selectedCategory.value)
    if (response.success) {
      events.value = response.events
      console.log('🔍 Search results:', response.events.length)
    } else {
      console.error('Error searching events:', response.error)
    }
  } catch (error) {
    console.error('Error searching events:', error)
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

.aspect-w-16 {
  position: relative;
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
</style>
