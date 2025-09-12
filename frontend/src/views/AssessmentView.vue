<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Stargate Readiness Assessment</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Evaluate your organization's readiness for Stargate AI infrastructure and get personalized recommendations for implementation.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button 
              @click="startAssessment" 
              class="btn-primary text-lg px-8 py-4"
              v-if="!showAssessment"
            >
              Start Assessment
            </button>
            <RouterLink to="/partnership" class="btn-secondary text-lg px-8 py-4">
              Explore Partnerships
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Assessment Tool -->
    <section v-if="showAssessment" class="py-12">
      <StargateAssessment />
    </section>

    <!-- Benefits Section -->
    <section v-if="!showAssessment" class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Why Take the Assessment?</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Our comprehensive assessment helps you understand your organization's readiness for Stargate implementation.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📊</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Comprehensive Analysis</h3>
            <p class="text-gray-400">
              Evaluate your technical infrastructure, team capabilities, budget, and strategic goals to determine Stargate readiness.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🎯</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Personalized Recommendations</h3>
            <p class="text-gray-400">
              Get specific, actionable recommendations tailored to your organization's unique situation and requirements.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🚀</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Implementation Roadmap</h3>
            <p class="text-gray-400">
              Receive a detailed timeline and investment estimate for your Stargate implementation journey.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Assessment Process -->
    <section v-if="!showAssessment" class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Assessment Process</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Our assessment takes only 5-10 minutes and covers all essential aspects of Stargate readiness.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-white font-bold">1</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Company Info</h3>
            <p class="text-gray-400 text-sm">Size, industry, and current AI usage</p>
          </div>

          <div class="text-center">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-white font-bold">2</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Infrastructure</h3>
            <p class="text-gray-400 text-sm">Cloud setup, data processing, security</p>
          </div>

          <div class="text-center">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-white font-bold">3</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Resources</h3>
            <p class="text-gray-400 text-sm">Budget, team size, expertise level</p>
          </div>

          <div class="text-center">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-white font-bold">4</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Goals</h3>
            <p class="text-gray-400 text-sm">Objectives, timeline, ROI expectations</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section v-if="!showAssessment" class="py-24 bg-gray-900/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Ready to Assess Your Readiness?</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Take our comprehensive assessment and discover your path to Stargate implementation.
        </p>
        <button 
          @click="startAssessment" 
          class="btn-primary text-lg px-8 py-4"
        >
          Start Your Assessment
        </button>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'
import StargateAssessment from '../components/StargateAssessment.vue'

const showAssessment = ref(false)

const startAssessment = () => {
  showAssessment.value = true
  // Scroll to assessment
  setTimeout(() => {
    const assessmentElement = document.querySelector('.max-w-4xl')
    if (assessmentElement) {
      assessmentElement.scrollIntoView({ behavior: 'smooth' })
    }
  }, 100)
}

useHead({
  title: 'Stargate Readiness Assessment - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Evaluate your organization\'s readiness for Stargate AI infrastructure. Get personalized recommendations and implementation roadmap.'
    }
  ]
})
</script>
