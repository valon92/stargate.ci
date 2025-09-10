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
          <p class="text-lg text-gray-400 mb-12 max-w-4xl mx-auto leading-relaxed">
            {{ $t('home.description') }}
          </p>
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
                <h3 class="text-2xl font-bold text-white">Crystal Intelligence</h3>
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

    <!-- Features Section -->
    <section class="py-24 bg-gray-900/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">{{ $t('home.features.title') }}</span>
          </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(feature, key) in features" :key="key" class="card group hover:scale-105 transition-transform duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-4">
              <component :is="feature.icon" class="w-6 h-6 text-white" />
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">{{ $t(`home.features.${key}.title`) }}</h3>
            <p class="text-gray-400">{{ $t(`home.features.${key}.description`) }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Crystal Intelligence Vision Section -->
    <section class="py-24 bg-gradient-to-br from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Crystal Intelligence Vision</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Pioneering the future where technology meets transparency, ethics, and crystalline clarity
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Transparency</h3>
            <p class="text-gray-400">Crystal-clear processes and ethical AI implementation</p>
          </div>
          
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Security</h3>
            <p class="text-gray-400">Fortress-like protection for intellectual property and data</p>
          </div>
          
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Innovation</h3>
            <p class="text-gray-400">Lightning-fast advancement in crystalline computing</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
          <div class="card">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">∞</div>
            <div class="text-gray-400">Crystal Clarity</div>
          </div>
          <div class="card">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">100%</div>
            <div class="text-gray-400">Ethical AI</div>
          </div>
          <div class="card">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">.ci</div>
            <div class="text-gray-400">Crystal Intelligence</div>
          </div>
          <div class="card">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">⚡</div>
            <div class="text-gray-400">Lightning Fast</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-primary-900/30 to-secondary-900/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Join the Crystal Intelligence Revolution</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Be part of the future where Stargate meets Crystal Intelligence. Guide your company toward ethical AI implementation while preserving intellectual property rights.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/contact" class="btn-primary">
            Start Your Crystal Journey
          </RouterLink>
          <RouterLink to="/insights" class="btn-secondary">
            Explore Crystal Intelligence
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

// Feature icons (using simple SVG components)
const features = {
  ai: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  },
  cloud: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  },
  data: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  },
  computing: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  }
}
</script>
