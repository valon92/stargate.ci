<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white dark:bg-gradient-to-br dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10 dark:from-primary-900/10 dark:to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6">
            <span class="gradient-text">Stargate.ci</span>
          </h1>
          <p class="text-xl md:text-2xl text-black dark:text-gray-300 mb-8 max-w-3xl mx-auto font-medium">
            Your Gateway to the Future of AI
          </p>
          <p class="text-lg text-gray-700 dark:text-gray-400 mb-8 max-w-4xl mx-auto leading-relaxed">
            Explore the revolutionary Stargate Project and Cristal Intelligence through comprehensive educational resources, insights, and community discussions. Learn about the $500 billion AI infrastructure initiative that's reshaping the future of artificial intelligence.
          </p>
          <div class="bg-yellow-900/20 dark:bg-yellow-900/20 bg-yellow-50 border border-yellow-500/30 dark:border-yellow-500/30 border-yellow-300 rounded-lg p-4 max-w-4xl mx-auto mb-8">
            <p class="text-yellow-200 dark:text-yellow-200 text-yellow-900 text-sm">
              <strong>Legal Notice:</strong> This is an independent educational platform. We are not affiliated with OpenAI, SoftBank, or Arm. 
              We provide information only and do not charge fees or receive commissions. 
              <RouterLink to="/legal-disclaimer" class="text-yellow-400 dark:text-yellow-400 text-yellow-700 hover:text-yellow-300 dark:hover:text-yellow-300 hover:text-yellow-800 underline">Read full legal disclaimer</RouterLink>
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Educational Video Section -->
    <section class="py-24 bg-white dark:bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Welcome to Stargate.ci</span>
          </h2>
          <p class="text-xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto">
            Watch these comprehensive educational videos to understand the scope, vision, technical aspects, implementation strategies, advanced AI paradigms, and future industry impact of the Stargate Project and Cristal Intelligence.
          </p>
        </div>

        <div class="space-y-8">
          <div class="youtube-video-container">
            <div class="aspect-w-16 aspect-h-9">
              <iframe
                src="https://www.youtube.com/embed/GhIJs4zbH0o?rel=0&modestbranding=1"
                title="Stargate Project - Introduction Video"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                loading="lazy"
              ></iframe>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800">
              <h3 class="text-xl font-semibold text-black dark:text-white mb-2">Stargate Project - Introduction Video</h3>
              <p class="text-gray-700 dark:text-gray-400 text-sm mb-4">An introduction video that explains the scope and vision of the Stargate Project and Cristal Intelligence, providing users with a comprehensive understanding of these revolutionary AI initiatives.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0 bg-white dark:bg-gray-800">
              <InteractiveContent
                content-id="stargate-intro-video"
                content-type="video"
              />
            </div>
          </div>
          
          <div class="youtube-video-container">
            <div class="aspect-w-16 aspect-h-9">
              <iframe
                src="https://www.youtube.com/embed/JPcxiWOOj2E?rel=0&modestbranding=1"
                title="Stargate Project - Deep Dive Analysis"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                loading="lazy"
              ></iframe>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800">
              <h3 class="text-xl font-semibold text-black dark:text-white mb-2">Stargate Project - Deep Dive Analysis</h3>
              <p class="text-gray-700 dark:text-gray-400 text-sm mb-4">A comprehensive deep dive analysis of the Stargate Project, exploring the technical aspects, partnerships, and future implications of this groundbreaking AI infrastructure initiative.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0 bg-white dark:bg-gray-800">
              <InteractiveContent
                content-id="stargate-deep-dive-video"
                content-type="video"
              />
            </div>
          </div>
          
          <div class="youtube-video-container">
            <div class="aspect-w-16 aspect-h-9">
              <iframe
                src="https://www.youtube.com/embed/1oYSZ-naJVo?rel=0&modestbranding=1"
                title="Stargate Project - Technical Implementation"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                loading="lazy"
              ></iframe>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800">
              <h3 class="text-xl font-semibold text-black dark:text-white mb-2">Stargate Project - Technical Implementation</h3>
              <p class="text-gray-700 dark:text-gray-400 text-sm mb-4">A detailed technical implementation guide for the Stargate Project, covering infrastructure requirements, deployment strategies, and integration approaches for organizations looking to leverage this advanced AI technology.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0 bg-white dark:bg-gray-800">
              <InteractiveContent
                content-id="stargate-technical-video"
                content-type="video"
              />
            </div>
          </div>
          
          <div class="youtube-video-container">
            <div class="aspect-w-16 aspect-h-9">
              <iframe
                src="https://www.youtube.com/embed/DHiX4_jetjs?rel=0&modestbranding=1"
                title="Cristal Intelligence - Advanced AI Paradigm"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                loading="lazy"
              ></iframe>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800">
              <h3 class="text-xl font-semibold text-black dark:text-white mb-2">Cristal Intelligence - Advanced AI Paradigm</h3>
              <p class="text-gray-700 dark:text-gray-400 text-sm mb-4">An in-depth exploration of Cristal Intelligence, the revolutionary AI paradigm that emphasizes transparency, interpretability, and ethical alignment in artificial intelligence systems.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0 bg-white dark:bg-gray-800">
              <InteractiveContent
                content-id="cristal-intelligence-video"
                content-type="video"
              />
            </div>
          </div>
          
          <div class="youtube-video-container">
            <div class="aspect-w-16 aspect-h-9">
              <iframe
                src="https://www.youtube.com/embed/po3TG3oSsSI?rel=0&modestbranding=1"
                title="Future of AI - Industry Impact & Applications"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                loading="lazy"
              ></iframe>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800">
              <h3 class="text-xl font-semibold text-black dark:text-white mb-2">Future of AI - Industry Impact & Applications</h3>
              <p class="text-gray-700 dark:text-gray-400 text-sm mb-4">A comprehensive analysis of how the Stargate Project and Cristal Intelligence will transform industries, examining real-world applications, market implications, and the future landscape of artificial intelligence.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0 bg-white dark:bg-gray-800">
              <InteractiveContent
                content-id="future-ai-video"
                content-type="video"
              />
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Community Members Section -->
    <section class="py-24 bg-gray-800/30 dark:bg-gray-800/30 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Our Community</span>
          </h2>
          <p class="text-xl text-gray-300 dark:text-gray-300 text-gray-700 max-w-3xl mx-auto mb-8">
            Join our growing community of innovators, researchers, and technology enthusiasts.
          </p>
          
          <!-- Community Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-8">
            <div class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
              <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">{{ totalMembers.toLocaleString() }}</div>
              <div class="text-gray-700 dark:text-gray-300">Total Members</div>
            </div>
            <div class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
              <div class="text-3xl font-bold text-secondary-600 dark:text-secondary-400 mb-2">{{ activeMembersThisMonth.toLocaleString() }}</div>
              <div class="text-gray-700 dark:text-gray-300">Active This Month</div>
            </div>
            <div class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
              <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">{{ totalCountries }}</div>
              <div class="text-gray-700 dark:text-gray-300">Countries</div>
            </div>
          </div>
        </div>

        <!-- Recent Members -->
        <div class="mb-12">
          <h3 class="text-2xl font-bold text-center mb-8">
            <span class="gradient-text">Recent Members</span>
          </h3>
          
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div 
              v-for="member in displayedMembers.slice(0, 12)" 
              :key="member.id"
              class="text-center group hover:scale-105 transition-transform duration-200"
            >
              <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-3 group-hover:shadow-lg">
                <span class="text-white font-bold text-lg">{{ member.username.charAt(0).toUpperCase() }}</span>
              </div>
              <div class="text-sm font-medium text-white dark:text-white text-gray-900 truncate">{{ member.username }}</div>
              <div class="text-xs text-gray-400 dark:text-gray-400 text-gray-600">{{ member.role || 'Member' }}</div>
            </div>
          </div>
        </div>


        <div class="text-center mt-12">
          <p class="text-gray-400 mb-6">
            Join over <span class="text-primary-400 font-bold">({{ totalMembers.toLocaleString() }})</span> members worldwide
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <RouterLink 
              to="/subscribe"
              class="btn-primary"
            >
              Join Our Community
            </RouterLink>
            <button 
              @click="loadMoreMembers"
              class="btn-outline"
              v-if="hasMoreMembers && displayedMembers.length > 0 && displayedMembers.length < totalMembers"
              :disabled="isLoadingMore"
            >
              <span v-if="isLoadingMore">Loading...</span>
              <span v-else>Load More Members</span>
            </button>
          </div>
        </div>

        <div v-if="displayedMembers.length === 0" class="text-center py-12">
          <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white dark:text-white text-gray-900 mb-2">Be the First to Join!</h3>
          <p class="text-gray-400 dark:text-gray-400 text-gray-600 mb-6">Join our growing community of innovators and be among the first to receive updates about Stargate Project and Cristal Intelligence.</p>
          <RouterLink to="/subscribe" class="btn-primary">
            Subscribe Now
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Domain Explanation Section -->
    <section class="py-24 bg-gray-50 dark:bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Understanding Our Domain</span>
          </h2>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="space-y-8">
            <div class="card group hover:scale-105 transition-transform duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-xl">S</span>
                </div>
                <h3 class="text-2xl font-bold text-black dark:text-white">Stargate</h3>
              </div>
              <p class="text-gray-700 dark:text-white leading-relaxed">The revolutionary $500 billion AI infrastructure project by OpenAI, SoftBank, and Arm that's reshaping the future of artificial intelligence.</p>
            </div>
            
            <div class="card group hover:scale-105 transition-transform duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-xl">.ci</span>
                </div>
                <h3 class="text-2xl font-bold text-black dark:text-white">Cristal Intelligence</h3>
              </div>
              <p class="text-gray-700 dark:text-white leading-relaxed">The next paradigm in AI development, emphasizing transparency, interpretability, and ethical alignment in artificial intelligence systems.</p>
            </div>
          </div>
          
          <div class="card">
            <div class="text-center">
              <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-black dark:text-white mb-4">Our Mission</h3>
              <p class="text-gray-700 dark:text-white leading-relaxed">To provide independent, educational information about the Stargate project and Cristal Intelligence, helping individuals and organizations understand these groundbreaking developments in AI infrastructure.</p>
            </div>
          </div>
        </div>
      </div>
    </section>









  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'
import InteractiveContent from '../components/InteractiveContent.vue'
import { videoApiService } from '../services/videoApiService'
// Email notification service removed - using localStorage for subscribers


// Members data
const allMembers = ref<any[]>([])
const displayedMembers = ref<any[]>([])
const membersPerPage = 10
const currentPage = ref(1)
const isLoadingMore = ref(false)
const hasMoreMembers = ref(true)
const totalMembersCount = ref(0)


// Computed properties
const totalMembers = computed(() => totalMembersCount.value || allMembers.value.length)

// Community statistics
const activeMembersThisMonth = computed(() => {
  const thisMonth = new Date()
  const startOfMonth = new Date(thisMonth.getFullYear(), thisMonth.getMonth(), 1)
  return allMembers.value.filter(member => {
    const memberDate = new Date(member.subscribedAt)
    return memberDate >= startOfMonth
  }).length
})

const totalCountries = computed(() => {
  const countries = new Set()
  allMembers.value.forEach(member => {
    if (member.country) {
      countries.add(member.country)
    }
  })
  return countries.size || 1 // Default to 1 if no countries found
})

// Methods
const loadMembers = async (page = 1, append = false) => {
  try {
    // Load subscribers from API with pagination
    const response = await videoApiService.getSubscribers()
    console.log('🔍 DEBUG - API Response:', response)
    if (response.success) {
      if (append) {
        // Append new members to existing list
        allMembers.value = [...allMembers.value, ...response.data]
      } else {
        // Replace with new members (initial load)
        allMembers.value = response.data
        // Store total count from API pagination
        totalMembersCount.value = response.pagination?.total || response.data.length
      }
      console.log('✅ Loading members from API:', allMembers.value.length, 'loaded,', totalMembersCount.value, 'total members')
      
      // Check if there are more members to load
      if (!append) {
        // Initial load - check if there are more pages
        hasMoreMembers.value = response.data.length === membersPerPage && totalMembersCount.value > membersPerPage
      } else {
        // Loading more - check if we got a full page
        hasMoreMembers.value = response.data.length === membersPerPage
      }
    } else {
      console.error('❌ API returned success: false')
      // Fallback to localStorage if API fails
      const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
      allMembers.value = subscribers
      totalMembersCount.value = subscribers.length
      hasMoreMembers.value = false
      console.log('⚠️ Loading members from localStorage (fallback):', allMembers.value.length, 'members')
    }
  } catch (error: any) {
    // Only log non-404 errors (404 is expected if subscribers endpoint doesn't exist)
    if (error?.status !== 404 && error?.response?.status !== 404) {
      console.error('❌ Error loading members from API:', error)
    }
    // Fallback to localStorage if API fails
    const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
    allMembers.value = subscribers
    totalMembersCount.value = subscribers.length
    hasMoreMembers.value = false
    if (error?.status !== 404 && error?.response?.status !== 404) {
      console.log('⚠️ Loading members from localStorage (fallback):', allMembers.value.length, 'members')
    }
  }
  updateDisplayedMembers()
}

const updateDisplayedMembers = () => {
  // Show only the first 10 members initially, then more as we load them
  const endIndex = currentPage.value * membersPerPage
  displayedMembers.value = allMembers.value.slice(0, endIndex)
  console.log('Updated displayed members:', displayedMembers.value.length, 'out of', allMembers.value.length, 'total')
}

const loadMoreMembers = async () => {
  if (isLoadingMore.value || !hasMoreMembers.value) return
  
  isLoadingMore.value = true
  currentPage.value++
  
  try {
    // Load more members from API
    await loadMembers(currentPage.value, true)
  } catch (error) {
    console.error('Error loading more members:', error)
    // Reset page if error occurs
    currentPage.value--
  } finally {
    isLoadingMore.value = false
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatInterest = (interest: string) => {
  const interestMap: Record<string, string> = {
    'stargate': 'Stargate Project',
    'cristal': 'Cristal Intelligence',
    'ai-ethics': 'AI Ethics',
    'research': 'Research'
  }
  return interestMap[interest] || interest
}

// Lifecycle
onMounted(() => {
  loadMembers()
})

useHead({
  title: 'Stargate Cristal Intelligence',
  meta: [
    {
      name: 'description',
      content: 'Where Stargate meets Cristal Intelligence. Guiding companies toward ethical AI implementation while preserving intellectual property rights.'
    }
  ]
})
</script>

<style scoped>
.youtube-video-container {
  background-color: rgba(31, 41, 55, 1);
  border-radius: 0.75rem;
  overflow: hidden;
  border: 1px solid rgba(55, 65, 81, 1);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
}

.dark .youtube-video-container {
  background-color: rgba(31, 41, 55, 1);
  border: 1px solid rgba(55, 65, 81, 1);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
}

:not(.dark) .youtube-video-container {
  background-color: rgba(255, 255, 255, 1);
  border: 1px solid rgba(229, 231, 235, 1);
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.youtube-video-container:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.dark .youtube-video-container:hover {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
}
.aspect-w-16 {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
}
.aspect-w-16 iframe {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}

.card {
  border-radius: 0.5rem;
  overflow: hidden;
}

.dark .card {
  background-color: rgb(0, 0, 0) !important;
  border: 1px solid rgba(55, 65, 81, 1);
}

:not(.dark) .card {
  background-color: rgba(255, 255, 255, 1);
  border: 1px solid rgba(229, 231, 235, 1);
}

.btn-primary {
  background: black;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 500;
  transition: all 0.3s;
}
.btn-primary:hover {
  background: rgb(17, 24, 39);
}

.btn-outline {
  border: 1px solid rgba(75, 85, 99, 1);
  color: rgba(209, 213, 219, 1);
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-weight: 500;
  transition: all 0.2s;
}
.btn-outline:hover {
  background-color: rgba(55, 65, 81, 1);
  border-color: rgba(107, 114, 128, 1);
}

.gradient-text {
  @apply text-white dark:text-white text-black;
}
</style>
