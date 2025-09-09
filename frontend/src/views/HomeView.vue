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

    <!-- Features Section -->
    <section class="py-24 bg-gray-800/50">
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

    <!-- Stats Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <div class="card">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">$100B+</div>
            <div class="text-gray-400">Project Investment</div>
          </div>
          <div class="card">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">3</div>
            <div class="text-gray-400">Leading Partners</div>
          </div>
          <div class="card">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">Global</div>
            <div class="text-gray-400">Impact Reach</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Join the Innovation Journey</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Stay informed about the latest developments in AI, cloud computing, and advanced technologies.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/insights" class="btn-primary">
            Explore Insights
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
