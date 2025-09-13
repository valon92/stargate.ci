<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6">
            <span class="gradient-text">{{ $t('home.title') }}</span>
          </h1>
          <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
            {{ $t('home.subtitle') }}
          </p>
          <p class="text-lg text-gray-400 mb-8 max-w-4xl mx-auto leading-relaxed">
            {{ $t('home.description') }}
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
              {{ $t('home.cta') }}
            </RouterLink>
            <RouterLink to="/insights" class="btn-secondary">
              {{ $t('common.readMore') }}
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Domain Explanation Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">{{ $t('home.domainExplanation.title') }}</span>
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
              <p class="text-gray-300 leading-relaxed">{{ $t('home.domainExplanation.stargate') }}</p>
            </div>
            
            <div class="card group hover:scale-105 transition-transform duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-xl">.ci</span>
                </div>
                <h3 class="text-2xl font-bold text-white">Cristal Intelligence</h3>
              </div>
              <p class="text-gray-300 leading-relaxed">{{ $t('home.domainExplanation.ci') }}</p>
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
              <p class="text-gray-300 leading-relaxed">{{ $t('home.domainExplanation.mission') }}</p>
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
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useHead } from '@vueuse/head'

const { t } = useI18n()

// Platform mission
const platformMission = 'Our mission is to provide independent, educational information about the Stargate project and Cristal Intelligence, helping individuals and organizations understand these groundbreaking developments in AI infrastructure.'

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
