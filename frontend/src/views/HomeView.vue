<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6">
            <span class="gradient-text">Stargate.ci</span>
          </h1>
          <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
            Your Gateway to the Future of AI
          </p>
          <p class="text-lg text-gray-400 mb-8 max-w-4xl mx-auto leading-relaxed">
            Explore the revolutionary Stargate Project and Cristal Intelligence through comprehensive educational resources, insights, and community discussions. Learn about the $500 billion AI infrastructure initiative that's reshaping the future of artificial intelligence.
          </p>
          <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-4 max-w-4xl mx-auto mb-8">
            <p class="text-yellow-200 text-sm">
              <strong>Legal Notice:</strong> This is an independent educational platform. We are not affiliated with OpenAI, SoftBank, or Arm. 
              We provide information only and do not charge fees or receive commissions. 
              <RouterLink to="/legal-disclaimer" class="text-yellow-400 hover:text-yellow-300 underline">Read full legal disclaimer</RouterLink>
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <RouterLink to="/about" class="btn-primary">
              Learn More
            </RouterLink>
            <RouterLink to="/insights" class="btn-secondary">
              Read More
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Educational Video Section -->
    <section class="py-24 bg-gradient-to-br from-primary-900/20 to-secondary-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Welcome to Stargate.ci</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
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
            <div class="p-4">
              <h3 class="text-xl font-semibold text-white mb-2">Stargate Project - Introduction Video</h3>
              <p class="text-gray-400 text-sm mb-4">An introduction video that explains the scope and vision of the Stargate Project and Cristal Intelligence, providing users with a comprehensive understanding of these revolutionary AI initiatives.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0">
              <InteractiveContent
                content-id="stargate-intro-video"
                content-type="video"
                :initial-likes="Math.floor(Math.random() * 200) + 50"
                :initial-comments="[]"
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
            <div class="p-4">
              <h3 class="text-xl font-semibold text-white mb-2">Stargate Project - Deep Dive Analysis</h3>
              <p class="text-gray-400 text-sm mb-4">A comprehensive deep dive analysis of the Stargate Project, exploring the technical aspects, partnerships, and future implications of this groundbreaking AI infrastructure initiative.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0">
              <InteractiveContent
                content-id="stargate-deep-dive-video"
                content-type="video"
                :initial-likes="Math.floor(Math.random() * 200) + 50"
                :initial-comments="[]"
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
            <div class="p-4">
              <h3 class="text-xl font-semibold text-white mb-2">Stargate Project - Technical Implementation</h3>
              <p class="text-gray-400 text-sm mb-4">A detailed technical implementation guide for the Stargate Project, covering infrastructure requirements, deployment strategies, and integration approaches for organizations looking to leverage this advanced AI technology.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0">
              <InteractiveContent
                content-id="stargate-technical-video"
                content-type="video"
                :initial-likes="Math.floor(Math.random() * 200) + 50"
                :initial-comments="[]"
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
            <div class="p-4">
              <h3 class="text-xl font-semibold text-white mb-2">Cristal Intelligence - Advanced AI Paradigm</h3>
              <p class="text-gray-400 text-sm mb-4">An in-depth exploration of Cristal Intelligence, the revolutionary AI paradigm that emphasizes transparency, interpretability, and ethical alignment in artificial intelligence systems.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0">
              <InteractiveContent
                content-id="cristal-intelligence-video"
                content-type="video"
                :initial-likes="Math.floor(Math.random() * 200) + 50"
                :initial-comments="[]"
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
            <div class="p-4">
              <h3 class="text-xl font-semibold text-white mb-2">Future of AI - Industry Impact & Applications</h3>
              <p class="text-gray-400 text-sm mb-4">A comprehensive analysis of how the Stargate Project and Cristal Intelligence will transform industries, examining real-world applications, market implications, and the future landscape of artificial intelligence.</p>
            </div>
            
            <!-- Interactive Content -->
            <div class="p-4 pt-0">
              <InteractiveContent
                content-id="future-ai-video"
                content-type="video"
                :initial-likes="Math.floor(Math.random() * 200) + 50"
                :initial-comments="[]"
              />
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Community Members Section -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Our Community</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Join our growing community of innovators, researchers, and technology enthusiasts.
          </p>
          
          <!-- Community Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-8">
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
              <div class="text-3xl font-bold text-primary-400 mb-2">{{ totalMembers.toLocaleString() }}</div>
              <div class="text-gray-300">Total Members</div>
            </div>
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
              <div class="text-3xl font-bold text-secondary-400 mb-2">{{ activeMembersThisMonth.toLocaleString() }}</div>
              <div class="text-gray-300">Active This Month</div>
            </div>
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
              <div class="text-3xl font-bold text-green-400 mb-2">{{ totalCountries }}</div>
              <div class="text-gray-300">Countries</div>
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
              <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:shadow-lg">
                <span class="text-white font-bold text-lg">{{ member.username.charAt(0).toUpperCase() }}</span>
              </div>
              <div class="text-sm font-medium text-white truncate">{{ member.username }}</div>
              <div class="text-xs text-gray-400">{{ member.role || 'Member' }}</div>
            </div>
          </div>
        </div>

        <!-- Member Spotlight -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-8">
          <h3 class="text-2xl font-bold text-center mb-8">
            <span class="gradient-text">Member Spotlight</span>
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div 
              v-for="member in displayedMembers.slice(0, 3)" 
              :key="member.id"
              class="text-center"
            >
              <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-2xl">{{ member.username.charAt(0).toUpperCase() }}</span>
              </div>
              <h4 class="text-lg font-bold text-white mb-2">{{ member.username }}</h4>
              <p class="text-sm text-gray-400 mb-3">{{ member.role || 'Community Member' }}</p>
              <div v-if="member.organization" class="text-xs text-gray-500 mb-2">{{ member.organization }}</div>
              <div class="text-xs text-gray-500">Joined {{ formatDate(member.subscribedAt) }}</div>
            </div>
          </div>
        </div>

        <div class="text-center mt-12">
          <p class="text-gray-400 mb-6">
            Join over <span class="text-primary-400 font-bold">1,000,000</span> members worldwide
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
              v-if="totalMembers > displayedMembers.length"
            >
              View More Members
            </button>
          </div>
        </div>

        <div v-if="displayedMembers.length === 0" class="text-center py-12">
          <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Be the First to Join!</h3>
          <p class="text-gray-400 mb-6">Join our growing community of innovators and be among the first to receive updates about Stargate Project and Cristal Intelligence.</p>
          <RouterLink to="/subscribe" class="btn-primary">
            Subscribe Now
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Domain Explanation Section -->
    <section class="py-24 bg-gray-800/50">
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
                <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-xl">S</span>
                </div>
                <h3 class="text-2xl font-bold text-white">Stargate</h3>
              </div>
              <p class="text-gray-300 leading-relaxed">The revolutionary $500 billion AI infrastructure project by OpenAI, SoftBank, and Arm that's reshaping the future of artificial intelligence.</p>
            </div>
            
            <div class="card group hover:scale-105 transition-transform duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-xl">.ci</span>
                </div>
                <h3 class="text-2xl font-bold text-white">Cristal Intelligence</h3>
              </div>
              <p class="text-gray-300 leading-relaxed">The next paradigm in AI development, emphasizing transparency, interpretability, and ethical alignment in artificial intelligence systems.</p>
            </div>
          </div>
          
          <div class="card bg-gradient-to-br from-primary-900/20 to-secondary-900/20 border border-primary-500/30">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Our Mission</h3>
              <p class="text-gray-300 leading-relaxed">To provide independent, educational information about the Stargate project and Cristal Intelligence, helping individuals and organizations understand these groundbreaking developments in AI infrastructure.</p>
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
import { emailNotificationService } from '../services/emailNotificationService'


// Members data
const allMembers = ref<any[]>([])
const displayedMembers = ref<any[]>([])
const membersPerPage = 6
const currentPage = ref(1)


// Computed properties
const totalMembers = computed(() => allMembers.value.length)

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
const loadMembers = () => {
  // Load members from email notification service
  allMembers.value = emailNotificationService.getSubscribers()
  console.log('Loading members from email service:', allMembers.value)
  updateDisplayedMembers()
}

const updateDisplayedMembers = () => {
  const startIndex = 0
  const endIndex = currentPage.value * membersPerPage
  displayedMembers.value = allMembers.value.slice(startIndex, endIndex)
  console.log('Updated displayed members:', displayedMembers.value)
}

const loadMoreMembers = () => {
  currentPage.value++
  updateDisplayedMembers()
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
  @apply bg-gray-800 rounded-lg overflow-hidden border border-gray-700;
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
  @apply bg-gray-800/50 border border-gray-700 rounded-lg overflow-hidden;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-300;
}

.btn-outline {
  @apply border border-gray-600 text-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-700 hover:border-gray-500 transition-all duration-200;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
