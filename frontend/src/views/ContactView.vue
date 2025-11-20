<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Contact stargate.ci Platform</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Connect with our platform team for inquiries about Stargate Project and Cristal Intelligence opportunities. We facilitate connections between companies and official project teams while maintaining legal compliance.
          </p>
        </div>
      </div>
    </section>


    <!-- Contact Form Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Platform Contact Form</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Get in touch with our platform team for inquiries about Stargate and Cristal Intelligence opportunities
          </p>
        </div>
        
        <div class="card">
          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                  Full Name *
                </label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  required
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  placeholder="Your full name"
                />
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                  Email Address *
                </label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  required
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  placeholder="your.email@company.com"
                />
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="company" class="block text-sm font-medium text-gray-300 mb-2">
                  Company Name
                </label>
                <input
                  type="text"
                  id="company"
                  v-model="form.company"
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  placeholder="Your company name"
                />
              </div>
              <div>
                <label for="projectType" class="block text-sm font-medium text-gray-300 mb-2">
                  Project Interest
                </label>
                <select
                  id="projectType"
                  v-model="form.projectType"
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                  <option value="">Select project type</option>
                  <option value="stargate">Stargate AI Infrastructure</option>
                  <option value="cristal">Cristal Intelligence Platform</option>
                  <option value="quantum">Quantum Cloud Computing</option>
                  <option value="general">General Inquiry</option>
                </select>
              </div>
            </div>
            
            <div>
              <label for="subject" class="block text-sm font-medium text-gray-300 mb-2">
                Subject *
              </label>
              <input
                type="text"
                id="subject"
                v-model="form.subject"
                required
                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                placeholder="Brief description of your inquiry"
              />
            </div>
            
            <div>
              <label for="message" class="block text-sm font-medium text-gray-300 mb-2">
                Message
              </label>
              <textarea
                id="message"
                v-model="form.message"
                required
                rows="6"
                class="w-full px-4 py-3 bg-gray-700 border border-dark-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                placeholder="Tell us about your project or questions..."
              ></textarea>
            </div>
            
            <div class="text-center">
              <button
                type="submit"
                :disabled="isSubmitting"
                class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="isSubmitting" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Loading...
                </span>
                <span v-else>Send Message</span>
              </button>
            </div>
            
            <div v-if="submitMessage" class="text-center">
              <div v-if="submitSuccess" class="text-green-400">
                Message sent successfully! We'll get back to you soon.
              </div>
              <div v-else class="text-red-400">
                Failed to send message. Please try again.
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- Additional Resources -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Platform Resources</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Explore our platform resources and connect with official project teams
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card text-center">
            <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Platform Insights</h3>
            <p class="text-gray-400 mb-4">
              Comprehensive analysis and insights about Stargate Project and Cristal Intelligence
            </p>
            <RouterLink to="/insights" class="text-primary-400 hover:text-primary-300 transition-colors duration-200">
              Read Analysis
            </RouterLink>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">News & Updates</h3>
            <p class="text-gray-400 mb-4">
              Stay updated with the latest news about Stargate Project and Cristal Intelligence
            </p>
            <RouterLink to="/news" class="text-primary-400 hover:text-primary-300 transition-colors duration-200">
              View Latest News
            </RouterLink>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Platform FAQ</h3>
            <p class="text-gray-400 mb-4">
              Find answers to common questions about our platform and services
            </p>
            <RouterLink to="/faq" class="text-primary-400 hover:text-primary-300 transition-colors duration-200">
              View FAQ
            </RouterLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useHead } from '@vueuse/head'
import { apiClient } from '@/services/apiClient'


const form = ref({
  name: '',
  email: '',
  company: '',
  projectType: '',
  subject: '',
  message: ''
})

// Check for pre-filled project information
onMounted(() => {
  const selectedProject = localStorage.getItem('selectedProject')
  if (selectedProject) {
    try {
      const project = JSON.parse(selectedProject)
      form.value.subject = `Inquiry about ${project.title}`
      form.value.message = project.message
      form.value.projectType = project.type || ''
      // Clear the stored project info
      localStorage.removeItem('selectedProject')
    } catch (error) {
      console.error('Error parsing selected project:', error)
    }
  }
})

const isSubmitting = ref(false)
const submitMessage = ref('')
const submitSuccess = ref(false)

const submitForm = async () => {
  isSubmitting.value = true
  submitMessage.value = ''
  
  try {
    // Send data to backend API
    const response = await apiClient.post('/api/v1/contact', {
      name: form.value.name,
      email: form.value.email,
      subject: form.value.subject,
      message: form.value.message
    })
    
    console.log('Contact form response:', response)
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      company: '',
      projectType: '',
      subject: '',
      message: ''
    }
    
    submitSuccess.value = true
    submitMessage.value = response.message || 'Thank you for your message! We\'ll get back to you soon.'
  } catch (error: any) {
    console.error('Contact form error:', error)
    submitSuccess.value = false
    submitMessage.value = error.message || 'There was an error submitting your inquiry. Please try again or contact us directly.'
  } finally {
    isSubmitting.value = false
  }
}

useHead({
  title: 'Contact - stargate.ci Platform',
  meta: [
    {
      name: 'description',
      content: 'Contact stargate.ci platform team for inquiries about Stargate Project and Cristal Intelligence opportunities. We facilitate connections between companies and official project teams.'
    }
  ]
})
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-6 hover:border-primary-500/50 transition-all duration-300;
}

.btn-primary {
  @apply bg-black text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-900 transition-all duration-300;
}

.gradient-text {
  @apply text-white;
}
</style>
