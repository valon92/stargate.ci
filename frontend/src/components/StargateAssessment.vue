<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        <span class="gradient-text">Stargate Readiness Assessment</span>
      </h2>
      <p class="text-xl text-gray-300 max-w-3xl mx-auto">
        Evaluate your organization's readiness for Stargate AI infrastructure and get personalized recommendations.
      </p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
      <div class="flex justify-between text-sm text-gray-400 mb-2">
        <span>Progress</span>
        <span>{{ currentStep }} of {{ totalSteps }}</span>
      </div>
      <div class="w-full bg-gray-700 rounded-full h-2">
        <div 
          class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full transition-all duration-300"
          :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
        ></div>
      </div>
    </div>

    <!-- Assessment Steps -->
    <div v-if="!assessmentComplete" class="space-y-8">
      <!-- Step 1: Company Information -->
      <div v-if="currentStep === 1" class="card">
        <h3 class="text-2xl font-bold mb-6">Company Information</h3>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Company Size</label>
            <select v-model="assessment.companySize" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select company size</option>
              <option value="startup">Startup (1-10 employees)</option>
              <option value="small">Small (11-50 employees)</option>
              <option value="medium">Medium (51-200 employees)</option>
              <option value="large">Large (201-1000 employees)</option>
              <option value="enterprise">Enterprise (1000+ employees)</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Industry</label>
            <select v-model="assessment.industry" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select industry</option>
              <option value="technology">Technology</option>
              <option value="finance">Finance</option>
              <option value="healthcare">Healthcare</option>
              <option value="manufacturing">Manufacturing</option>
              <option value="retail">Retail</option>
              <option value="education">Education</option>
              <option value="government">Government</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Current AI/ML Usage</label>
            <select v-model="assessment.currentAIUsage" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select current usage</option>
              <option value="none">No AI/ML usage</option>
              <option value="basic">Basic AI tools (chatbots, simple automation)</option>
              <option value="moderate">Moderate AI usage (ML models, data analysis)</option>
              <option value="advanced">Advanced AI implementation (custom models, deep learning)</option>
              <option value="expert">Expert level (AI research, cutting-edge applications)</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Step 2: Technical Infrastructure -->
      <div v-if="currentStep === 2" class="card">
        <h3 class="text-2xl font-bold mb-6">Technical Infrastructure</h3>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Cloud Infrastructure</label>
            <div class="space-y-3">
              <label v-for="option in cloudOptions" :key="option.value" class="flex items-center">
                <input 
                  type="radio" 
                  v-model="assessment.cloudInfrastructure" 
                  :value="option.value"
                  class="mr-3 text-primary-500 focus:ring-primary-500"
                >
                <span class="text-gray-300">{{ option.label }}</span>
              </label>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Data Processing Capacity</label>
            <select v-model="assessment.dataProcessing" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select capacity</option>
              <option value="low">Low (GB scale)</option>
              <option value="medium">Medium (TB scale)</option>
              <option value="high">High (PB scale)</option>
              <option value="enterprise">Enterprise (EB scale)</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Security & Compliance</label>
            <div class="space-y-3">
              <label v-for="option in securityOptions" :key="option.value" class="flex items-center">
                <input 
                  type="checkbox" 
                  v-model="assessment.securityCompliance" 
                  :value="option.value"
                  class="mr-3 text-primary-500 focus:ring-primary-500"
                >
                <span class="text-gray-300">{{ option.label }}</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 3: Budget & Resources -->
      <div v-if="currentStep === 3" class="card">
        <h3 class="text-2xl font-bold mb-6">Budget & Resources</h3>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Annual IT Budget</label>
            <select v-model="assessment.itBudget" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select budget range</option>
              <option value="under-100k">Under $100K</option>
              <option value="100k-500k">$100K - $500K</option>
              <option value="500k-1m">$500K - $1M</option>
              <option value="1m-5m">$1M - $5M</option>
              <option value="5m-10m">$5M - $10M</option>
              <option value="over-10m">Over $10M</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Technical Team Size</label>
            <select v-model="assessment.teamSize" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select team size</option>
              <option value="1-2">1-2 developers</option>
              <option value="3-5">3-5 developers</option>
              <option value="6-10">6-10 developers</option>
              <option value="11-25">11-25 developers</option>
              <option value="26-50">26-50 developers</option>
              <option value="over-50">Over 50 developers</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">AI/ML Expertise Level</label>
            <select v-model="assessment.aiExpertise" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select expertise level</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
              <option value="expert">Expert</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Step 4: Goals & Timeline -->
      <div v-if="currentStep === 4" class="card">
        <h3 class="text-2xl font-bold mb-6">Goals & Timeline</h3>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Primary Goals</label>
            <div class="space-y-3">
              <label v-for="option in goalOptions" :key="option.value" class="flex items-center">
                <input 
                  type="checkbox" 
                  v-model="assessment.primaryGoals" 
                  :value="option.value"
                  class="mr-3 text-primary-500 focus:ring-primary-500"
                >
                <span class="text-gray-300">{{ option.label }}</span>
              </label>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Implementation Timeline</label>
            <select v-model="assessment.timeline" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select timeline</option>
              <option value="immediate">Immediate (0-3 months)</option>
              <option value="short">Short term (3-6 months)</option>
              <option value="medium">Medium term (6-12 months)</option>
              <option value="long">Long term (1-2 years)</option>
              <option value="exploratory">Exploratory (2+ years)</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Expected ROI Timeline</label>
            <select v-model="assessment.roiTimeline" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select ROI timeline</option>
              <option value="immediate">Immediate (0-6 months)</option>
              <option value="short">Short term (6-12 months)</option>
              <option value="medium">Medium term (1-2 years)</option>
              <option value="long">Long term (2+ years)</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="flex justify-between">
        <button 
          @click="previousStep" 
          :disabled="currentStep === 1"
          class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Previous
        </button>
        <button 
          @click="nextStep" 
          :disabled="!canProceed"
          class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg hover:from-primary-600 hover:to-secondary-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
        >
          {{ currentStep === totalSteps ? 'Get Assessment' : 'Next' }}
        </button>
      </div>
    </div>

    <!-- Results -->
    <div v-if="assessmentComplete" class="card">
      <div class="text-center mb-8">
        <h3 class="text-3xl font-bold mb-4">
          <span class="gradient-text">Your Stargate Readiness Assessment</span>
        </h3>
        <div class="text-6xl mb-4">{{ readinessScore.emoji }}</div>
        <h4 class="text-2xl font-bold text-gray-300 mb-2">{{ readinessScore.title }}</h4>
        <p class="text-lg text-gray-400">{{ readinessScore.description }}</p>
      </div>

      <!-- Score Breakdown -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gray-800/50 rounded-lg p-6">
          <h5 class="text-lg font-semibold text-gray-300 mb-4">Readiness Score</h5>
          <div class="text-4xl font-bold text-primary-400 mb-2">{{ totalScore }}/100</div>
          <div class="w-full bg-gray-700 rounded-full h-3">
            <div 
              class="bg-gradient-to-r from-primary-500 to-secondary-500 h-3 rounded-full transition-all duration-500"
              :style="{ width: `${totalScore}%` }"
            ></div>
          </div>
        </div>

        <div class="bg-gray-800/50 rounded-lg p-6">
          <h5 class="text-lg font-semibold text-gray-300 mb-4">Recommended Timeline</h5>
          <div class="text-2xl font-bold text-secondary-400 mb-2">{{ recommendations.timeline }}</div>
          <p class="text-gray-400">{{ recommendations.timelineDescription }}</p>
        </div>
      </div>

      <!-- Recommendations -->
      <div class="space-y-6">
        <h5 class="text-xl font-semibold text-gray-300">Recommendations</h5>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-gray-800/30 rounded-lg p-6">
            <h6 class="text-lg font-semibold text-primary-400 mb-3">Immediate Actions</h6>
            <ul class="space-y-2 text-gray-300">
              <li v-for="action in recommendations.immediateActions" :key="action" class="flex items-start">
                <span class="text-primary-500 mr-2">•</span>
                {{ action }}
              </li>
            </ul>
          </div>

          <div class="bg-gray-800/30 rounded-lg p-6">
            <h6 class="text-lg font-semibold text-secondary-400 mb-3">Next Steps</h6>
            <ul class="space-y-2 text-gray-300">
              <li v-for="step in recommendations.nextSteps" :key="step" class="flex items-start">
                <span class="text-secondary-500 mr-2">•</span>
                {{ step }}
              </li>
            </ul>
          </div>
        </div>

        <div class="bg-gray-800/30 rounded-lg p-6">
          <h6 class="text-lg font-semibold text-gray-300 mb-3">Investment Estimate</h6>
          <div class="text-2xl font-bold text-gray-300 mb-2">{{ recommendations.investmentRange }}</div>
          <p class="text-gray-400">{{ recommendations.investmentDescription }}</p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
        <RouterLink to="/partnership" class="btn-primary">
          Explore Partnership Opportunities
        </RouterLink>
        <RouterLink to="/contact" class="btn-secondary">
          Get Expert Consultation
        </RouterLink>
        <button @click="resetAssessment" class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
          Take Assessment Again
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'

// Assessment data
const assessment = ref({
  companySize: '',
  industry: '',
  currentAIUsage: '',
  cloudInfrastructure: '',
  dataProcessing: '',
  securityCompliance: [] as string[],
  itBudget: '',
  teamSize: '',
  aiExpertise: '',
  primaryGoals: [] as string[],
  timeline: '',
  roiTimeline: ''
})

// Assessment state
const currentStep = ref(1)
const totalSteps = 4
const assessmentComplete = ref(false)

// Options
const cloudOptions = [
  { value: 'none', label: 'No cloud infrastructure' },
  { value: 'basic', label: 'Basic cloud services (AWS, Azure, GCP)' },
  { value: 'advanced', label: 'Advanced cloud with AI/ML services' },
  { value: 'hybrid', label: 'Hybrid cloud/on-premises' },
  { value: 'multi-cloud', label: 'Multi-cloud strategy' }
]

const securityOptions = [
  { value: 'soc2', label: 'SOC 2 Compliance' },
  { value: 'iso27001', label: 'ISO 27001' },
  { value: 'gdpr', label: 'GDPR Compliance' },
  { value: 'hipaa', label: 'HIPAA Compliance' },
  { value: 'pci', label: 'PCI DSS' },
  { value: 'custom', label: 'Custom security framework' }
]

const goalOptions = [
  { value: 'automation', label: 'Process automation' },
  { value: 'analytics', label: 'Advanced analytics' },
  { value: 'ai-products', label: 'AI-powered products' },
  { value: 'cost-reduction', label: 'Cost reduction' },
  { value: 'innovation', label: 'Innovation leadership' },
  { value: 'scalability', label: 'Scalability improvement' }
]

// Computed properties
const canProceed = computed(() => {
  switch (currentStep.value) {
    case 1:
      return assessment.value.companySize && assessment.value.industry && assessment.value.currentAIUsage
    case 2:
      return assessment.value.cloudInfrastructure && assessment.value.dataProcessing
    case 3:
      return assessment.value.itBudget && assessment.value.teamSize && assessment.value.aiExpertise
    case 4:
      return assessment.value.primaryGoals.length > 0 && assessment.value.timeline && assessment.value.roiTimeline
    default:
      return false
  }
})

// Scoring logic
const totalScore = computed(() => {
  let score = 0
  
  // Company size scoring (0-20 points)
  const sizeScores = {
    'startup': 5,
    'small': 10,
    'medium': 15,
    'large': 18,
    'enterprise': 20
  }
  score += sizeScores[assessment.value.companySize as keyof typeof sizeScores] || 0
  
  // AI usage scoring (0-20 points)
  const aiScores = {
    'none': 0,
    'basic': 5,
    'moderate': 10,
    'advanced': 15,
    'expert': 20
  }
  score += aiScores[assessment.value.currentAIUsage as keyof typeof aiScores] || 0
  
  // Cloud infrastructure scoring (0-20 points)
  const cloudScores = {
    'none': 0,
    'basic': 8,
    'advanced': 15,
    'hybrid': 12,
    'multi-cloud': 18
  }
  score += cloudScores[assessment.value.cloudInfrastructure as keyof typeof cloudScores] || 0
  
  // Data processing scoring (0-15 points)
  const dataScores = {
    'low': 3,
    'medium': 8,
    'high': 12,
    'enterprise': 15
  }
  score += dataScores[assessment.value.dataProcessing as keyof typeof dataScores] || 0
  
  // Security scoring (0-10 points)
  score += Math.min(assessment.value.securityCompliance.length * 2, 10)
  
  // Team size scoring (0-15 points)
  const teamScores = {
    '1-2': 3,
    '3-5': 6,
    '6-10': 9,
    '11-25': 12,
    '26-50': 14,
    'over-50': 15
  }
  score += teamScores[assessment.value.teamSize as keyof typeof teamScores] || 0
  
  return Math.min(score, 100)
})

const readinessScore = computed(() => {
  const score = totalScore.value
  
  if (score >= 80) {
    return {
      emoji: '🚀',
      title: 'Ready for Stargate',
      description: 'Your organization is well-prepared for Stargate implementation. You have the infrastructure, expertise, and resources needed for success.'
    }
  } else if (score >= 60) {
    return {
      emoji: '⚡',
      title: 'Almost Ready',
      description: 'You\'re close to being ready for Stargate. A few strategic improvements will get you there.'
    }
  } else if (score >= 40) {
    return {
      emoji: '🛠️',
      title: 'Needs Preparation',
      description: 'Some groundwork is needed before implementing Stargate. Focus on building your foundation first.'
    }
  } else {
    return {
      emoji: '📚',
      title: 'Start with Basics',
      description: 'Begin with fundamental AI/ML concepts and infrastructure before considering Stargate.'
    }
  }
})

const recommendations = computed(() => {
  const score = totalScore.value
  
  if (score >= 80) {
    return {
      timeline: '3-6 months',
      timelineDescription: 'You can start Stargate implementation immediately with proper planning.',
      immediateActions: [
        'Contact Stargate partners for detailed consultation',
        'Set up pilot project with limited scope',
        'Establish dedicated Stargate team',
        'Review and update security protocols'
      ],
      nextSteps: [
        'Develop comprehensive implementation roadmap',
        'Secure necessary budget approvals',
        'Begin infrastructure scaling',
        'Plan team training and certification'
      ],
      investmentRange: '$500K - $2M+',
      investmentDescription: 'Investment depends on scale and specific requirements. Contact us for detailed estimates.'
    }
  } else if (score >= 60) {
    return {
      timeline: '6-12 months',
      timelineDescription: 'Focus on building missing capabilities before Stargate implementation.',
      immediateActions: [
        'Strengthen cloud infrastructure',
        'Hire or train AI/ML specialists',
        'Implement security frameworks',
        'Start with smaller AI projects'
      ],
      nextSteps: [
        'Develop AI/ML expertise in-house',
        'Build robust data processing capabilities',
        'Establish governance frameworks',
        'Plan gradual Stargate adoption'
      ],
      investmentRange: '$200K - $1M',
      investmentDescription: 'Lower initial investment to build foundation, then scale up for Stargate.'
    }
  } else if (score >= 40) {
    return {
      timeline: '12-18 months',
      timelineDescription: 'Significant preparation needed before considering Stargate.',
      immediateActions: [
        'Start with basic AI/ML education',
        'Implement cloud infrastructure',
        'Build data management capabilities',
        'Establish security protocols'
      ],
      nextSteps: [
        'Hire experienced AI/ML team',
        'Develop data strategy and governance',
        'Start with pilot AI projects',
        'Build technical foundation'
      ],
      investmentRange: '$100K - $500K',
      investmentDescription: 'Focus on building foundation first, then consider Stargate in future phases.'
    }
  } else {
    return {
      timeline: '18+ months',
      timelineDescription: 'Extensive preparation required before Stargate consideration.',
      immediateActions: [
        'Begin AI/ML education and training',
        'Assess current technical capabilities',
        'Develop IT strategy and roadmap',
        'Start with basic automation tools'
      ],
      nextSteps: [
        'Build technical team and capabilities',
        'Implement basic cloud infrastructure',
        'Develop data management practices',
        'Start with simple AI applications'
      ],
      investmentRange: '$50K - $200K',
      investmentDescription: 'Start with foundational investments, gradually building toward Stargate readiness.'
    }
  }
})

// Methods
const nextStep = () => {
  if (currentStep.value < totalSteps) {
    currentStep.value++
  } else {
    assessmentComplete.value = true
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const resetAssessment = () => {
  assessment.value = {
    companySize: '',
    industry: '',
    currentAIUsage: '',
    cloudInfrastructure: '',
    dataProcessing: '',
    securityCompliance: [],
    itBudget: '',
    teamSize: '',
    aiExpertise: '',
    primaryGoals: [],
    timeline: '',
    roiTimeline: ''
  }
  currentStep.value = 1
  assessmentComplete.value = false
}
</script>
