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
              <a
                href="https://youtu.be/GhIJs4zbH0o" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group"
              >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                Watch on YouTube
              </a>
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
              <a
                href="https://youtu.be/JPcxiWOOj2E" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group"
              >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                Watch on YouTube
              </a>
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
              <a
                href="https://youtu.be/1oYSZ-naJVo" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group"
              >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                Watch on YouTube
              </a>
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
              <a
                href="https://youtu.be/DHiX4_jetjs" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group"
              >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                Watch on YouTube
              </a>
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
              <a
                href="https://youtu.be/po3TG3oSsSI" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group"
              >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                Watch on YouTube
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Email Test Panel (for development) -->
    <section v-if="showTestPanel" class="py-12 bg-gray-900/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <EmailTestPanel />
      </div>
    </section>

    <!-- Community Members Section -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Our Community Members</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Join thousands of innovators, researchers, and technology enthusiasts who are staying updated with the latest developments in Stargate Project and Cristal Intelligence.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="member in displayedMembers" 
            :key="member.id"
            class="card group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="p-6">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-lg">{{ member.username.charAt(0).toUpperCase() }}</span>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">{{ member.username }}</h3>
                  <p class="text-sm text-gray-400">{{ member.role || 'Community Member' }}</p>
                </div>
              </div>
              
              <div class="space-y-2 mb-4">
                <div v-if="member.fullName" class="flex items-center text-sm text-gray-300">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  {{ member.fullName }}
                </div>
                <div v-if="member.organization" class="flex items-center text-sm text-gray-300">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                  {{ member.organization }}
                </div>
                <div class="flex items-center text-sm text-gray-300">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  Joined {{ formatDate(member.subscribedAt) }}
                </div>
              </div>

              <div v-if="member.interests && member.interests.length > 0" class="mb-4">
                <h4 class="text-sm font-medium text-gray-300 mb-2">Interests:</h4>
                <div class="flex flex-wrap gap-1">
                  <span 
                    v-for="interest in member.interests.slice(0, 3)" 
                    :key="interest"
                    class="px-2 py-1 bg-primary-500/20 text-primary-400 text-xs rounded-full"
                  >
                    {{ formatInterest(interest) }}
                  </span>
                  <span 
                    v-if="member.interests.length > 3"
                    class="px-2 py-1 bg-gray-600/20 text-gray-400 text-xs rounded-full"
                  >
                    +{{ member.interests.length - 3 }} more
                  </span>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">Member #{{ member.id }}</span>
                <div class="flex items-center text-xs text-green-400">
                  <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                  Active
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="totalMembers > displayedMembers.length" class="text-center mt-12">
          <p class="text-gray-400 mb-4">
            Showing {{ displayedMembers.length }} of {{ totalMembers }} community members
          </p>
          <button 
            @click="loadMoreMembers"
            class="btn-outline"
          >
            Load More Members
          </button>
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









    <!-- Platform Mission Section -->
    <section class="py-24 bg-gradient-to-br from-primary-900/30 to-secondary-900/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">stargate.ci Platform Mission</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          {{ platformMission }}
        </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                  <RouterLink to="/dashboard" class="btn-primary">
                    Go to Dashboard
                  </RouterLink>
                  <RouterLink to="/templates" class="btn-secondary">
                    Browse Templates
                  </RouterLink>
                  <RouterLink to="/assessment" class="btn-outline">
                    Take Assessment
                  </RouterLink>
                </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'
import { emailNotificationService } from '../services/emailNotificationService'
import EmailTestPanel from '../components/EmailTestPanel.vue'

// Platform mission
const platformMission = 'Our mission is to provide independent, educational information about the Stargate project and Cristal Intelligence, helping individuals and organizations understand these groundbreaking developments in AI infrastructure.'

// Members data
const allMembers = ref<any[]>([])
const displayedMembers = ref<any[]>([])
const membersPerPage = 6
const currentPage = ref(1)

// Test panel (show in development)
const showTestPanel = ref(true) // Set to false in production

// Computed properties
const totalMembers = computed(() => allMembers.value.length)

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
