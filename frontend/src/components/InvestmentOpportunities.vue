<template>
  <div class="investment-opportunities">
    <!-- Header -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        <span class="gradient-text">Investment Opportunities</span>
      </h2>
      <p class="text-xl text-gray-300 max-w-3xl mx-auto">
        Connect with official Stargate and Cristal Intelligence projects. Find the perfect investment opportunity for your organization.
      </p>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
      <p class="text-gray-400 mt-4">Loading investment opportunities...</p>
    </div>

    <!-- Opportunities Grid -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
      <div 
        v-for="opportunity in opportunities" 
        :key="opportunity.id"
        class="card group hover:scale-105 transition-transform duration-300"
      >
        <!-- Header -->
        <div class="mb-6">
          <h3 class="text-xl font-bold text-white mb-2">{{ opportunity.title }}</h3>
          <div class="flex items-center justify-between mb-4">
            <span class="px-3 py-1 bg-primary-500/20 text-primary-400 rounded-full text-sm font-medium">
              {{ opportunity.investmentRange }}
            </span>
            <span class="text-sm text-gray-400">{{ opportunity.timeline }}</span>
          </div>
        </div>

        <!-- Description -->
        <p class="text-gray-300 mb-6 leading-relaxed">{{ opportunity.description }}</p>

        <!-- Requirements -->
        <div class="mb-6">
          <h4 class="text-sm font-semibold text-white mb-3">Requirements</h4>
          <ul class="space-y-2">
            <li v-for="requirement in opportunity.requirements.slice(0, 3)" :key="requirement" class="flex items-start">
              <span class="w-2 h-2 bg-secondary-400 rounded-full mr-3 mt-2 flex-shrink-0"></span>
              <span class="text-gray-300 text-sm">{{ requirement }}</span>
            </li>
            <li v-if="opportunity.requirements.length > 3" class="text-gray-400 text-sm">
              +{{ opportunity.requirements.length - 3 }} more requirements
            </li>
          </ul>
        </div>

        <!-- Benefits -->
        <div class="mb-6">
          <h4 class="text-sm font-semibold text-white mb-3">Key Benefits</h4>
          <ul class="space-y-2">
            <li v-for="benefit in opportunity.benefits.slice(0, 3)" :key="benefit" class="flex items-start">
              <span class="w-2 h-2 bg-accent-400 rounded-full mr-3 mt-2 flex-shrink-0"></span>
              <span class="text-gray-300 text-sm">{{ benefit }}</span>
            </li>
            <li v-if="opportunity.benefits.length > 3" class="text-gray-400 text-sm">
              +{{ opportunity.benefits.length - 3 }} more benefits
            </li>
          </ul>
        </div>

        <!-- Actions -->
        <div class="space-y-3">
          <button 
            @click="openOpportunityModal(opportunity)"
            class="w-full btn-primary"
          >
            Learn More
          </button>
          <button 
            @click="connectToOpportunity(opportunity)"
            class="w-full btn-secondary"
          >
            Connect Now
          </button>
        </div>

        <!-- Source -->
        <div class="mt-4 pt-4 border-t border-gray-700">
          <p class="text-xs text-gray-500">
            Source: <a :href="opportunity.officialSource" target="_blank" class="text-primary-400 hover:text-primary-300 underline">
              Official Project
            </a>
          </p>
        </div>
      </div>
    </div>

    <!-- Opportunity Modal -->
    <div v-if="selectedOpportunity" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" @click="closeModal">
      <div class="bg-gray-900 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="p-6">
          <!-- Modal Header -->
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-white">{{ selectedOpportunity.title }}</h3>
            <button @click="closeModal" class="text-gray-400 hover:text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Investment Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="p-4 bg-gray-800/50 rounded-lg">
              <h4 class="font-semibold text-primary-400 mb-2">Investment Range</h4>
              <p class="text-white text-lg">{{ selectedOpportunity.investmentRange }}</p>
            </div>
            <div class="p-4 bg-gray-800/50 rounded-lg">
              <h4 class="font-semibold text-secondary-400 mb-2">Timeline</h4>
              <p class="text-white text-lg">{{ selectedOpportunity.timeline }}</p>
            </div>
          </div>

          <!-- Full Description -->
          <div class="mb-6">
            <h4 class="font-semibold text-white mb-3">Description</h4>
            <p class="text-gray-300 leading-relaxed">{{ selectedOpportunity.description }}</p>
          </div>

          <!-- All Requirements -->
          <div class="mb-6">
            <h4 class="font-semibold text-white mb-3">Requirements</h4>
            <ul class="space-y-2">
              <li v-for="requirement in selectedOpportunity.requirements" :key="requirement" class="flex items-start">
                <span class="w-2 h-2 bg-secondary-400 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                <span class="text-gray-300">{{ requirement }}</span>
              </li>
            </ul>
          </div>

          <!-- All Benefits -->
          <div class="mb-6">
            <h4 class="font-semibold text-white mb-3">Benefits</h4>
            <ul class="space-y-2">
              <li v-for="benefit in selectedOpportunity.benefits" :key="benefit" class="flex items-start">
                <span class="w-2 h-2 bg-accent-400 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                <span class="text-gray-300">{{ benefit }}</span>
              </li>
            </ul>
          </div>

          <!-- Contact Information -->
          <div class="mb-6 p-4 bg-gray-800/50 rounded-lg">
            <h4 class="font-semibold text-white mb-3">Contact Information</h4>
            <p class="text-gray-300 mb-2">
              <strong>Email:</strong> 
              <a :href="`mailto:${selectedOpportunity.contactInfo}`" class="text-primary-400 hover:text-primary-300 underline">
                {{ selectedOpportunity.contactInfo }}
              </a>
            </p>
            <p class="text-gray-300">
              <strong>Official Source:</strong> 
              <a :href="selectedOpportunity.officialSource" target="_blank" class="text-primary-400 hover:text-primary-300 underline">
                Visit Official Project
              </a>
            </p>
          </div>

          <!-- Actions -->
          <div class="flex flex-col sm:flex-row gap-4">
            <button 
              @click="connectToOpportunity(selectedOpportunity)"
              class="btn-primary flex-1"
            >
              Connect to This Opportunity
            </button>
            <button 
              @click="closeModal"
              class="btn-secondary flex-1"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { stargateApi, type InvestmentOpportunity } from '../services/stargateApi'

const router = useRouter()

// Reactive data
const opportunities = ref<InvestmentOpportunity[]>([])
const selectedOpportunity = ref<InvestmentOpportunity | null>(null)
const isLoading = ref(true)

// Load opportunities on mount
onMounted(async () => {
  try {
    const data = await stargateApi.getInvestmentOpportunities()
    opportunities.value = data
  } catch (error) {
    console.error('Error loading investment opportunities:', error)
  } finally {
    isLoading.value = false
  }
})

// Open opportunity modal
const openOpportunityModal = (opportunity: InvestmentOpportunity) => {
  selectedOpportunity.value = opportunity
}

// Close modal
const closeModal = () => {
  selectedOpportunity.value = null
}

// Connect to opportunity
const connectToOpportunity = (opportunity: InvestmentOpportunity) => {
  // Store opportunity info for contact form
  localStorage.setItem('selectedOpportunity', JSON.stringify({
    title: opportunity.title,
    investmentRange: opportunity.investmentRange,
    contactInfo: opportunity.contactInfo,
    officialSource: opportunity.officialSource,
    message: `I'm interested in learning more about the ${opportunity.title} investment opportunity. Please provide detailed information about the investment process, requirements, and next steps.`
  }))
  
  // Navigate to contact page
  router.push('/contact')
}
</script>

<style scoped>
.investment-opportunities {
  @apply py-12;
}

.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-6 hover:border-primary-500/50 transition-all duration-300;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-300;
}

.btn-secondary {
  @apply bg-gray-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
