<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">{{ $t('services.title') }}</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            {{ $t('services.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Services Grid -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <!-- AI Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">{{ $t('services.ai.title') }}</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('services.ai.description') }}
            </p>
            <div class="space-y-3">
              <div v-for="feature in $t('services.ai.features')" :key="feature" class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">{{ feature }}</span>
              </div>
            </div>
          </div>

          <!-- Cloud Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">{{ $t('services.cloud.title') }}</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('services.cloud.description') }}
            </p>
            <div class="space-y-3">
              <div v-for="feature in $t('services.cloud.features')" :key="feature" class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">{{ feature }}</span>
              </div>
            </div>
          </div>

          <!-- Security Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">{{ $t('services.security.title') }}</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('services.security.description') }}
            </p>
            <div class="space-y-3">
              <div v-for="feature in $t('services.security.features')" :key="feature" class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">{{ feature }}</span>
              </div>
            </div>
          </div>

          <!-- Data Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">{{ $t('services.data.title') }}</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('services.data.description') }}
            </p>
            <div class="space-y-3">
              <div v-for="feature in $t('services.data.features')" :key="feature" class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">{{ feature }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Technology Stack</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            The foundational technologies powering the Stargate project
          </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div v-for="tech in technologies" :key="tech.name" class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <div class="text-2xl">{{ tech.icon }}</div>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">{{ tech.name }}</h3>
            <p class="text-sm text-gray-400">{{ tech.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Use Cases -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Real-World Applications</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            How these technologies are transforming industries and solving complex challenges
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Healthcare</h3>
            <p class="text-gray-400">
              AI-powered diagnostics, drug discovery, and personalized treatment plans revolutionizing patient care.
            </p>
          </div>
          
          <div class="card">
            <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Finance</h3>
            <p class="text-gray-400">
              Advanced fraud detection, algorithmic trading, and risk assessment transforming financial services.
            </p>
          </div>
          
          <div class="card">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Energy</h3>
            <p class="text-gray-400">
              Smart grid optimization, renewable energy forecasting, and efficient resource management.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Ready to Explore?</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Dive deeper into these technologies and their applications in our insights section.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/insights" class="btn-primary">
            Read Insights
          </RouterLink>
          <RouterLink to="/contact" class="btn-secondary">
            Get in Touch
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const technologies = [
  { name: 'Machine Learning', icon: '🤖', description: 'Advanced algorithms' },
  { name: 'Neural Networks', icon: '🧠', description: 'Deep learning models' },
  { name: 'Cloud Infrastructure', icon: '☁️', description: 'Scalable computing' },
  { name: 'Data Analytics', icon: '📊', description: 'Insight generation' },
  { name: 'Edge Computing', icon: '⚡', description: 'Distributed processing' },
  { name: 'Quantum Computing', icon: '🔬', description: 'Next-gen processing' },
  { name: 'Blockchain', icon: '⛓️', description: 'Secure transactions' },
  { name: 'IoT', icon: '🌐', description: 'Connected devices' },
]
</script>
