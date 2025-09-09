<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">{{ $t('partners.title') }}</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            {{ $t('partners.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Partners Grid -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <!-- SoftBank -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center mr-6">
                <span class="text-2xl font-bold text-white">SB</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-white">{{ $t('partners.softbank.name') }}</h2>
                <p class="text-primary-400">Technology Investment Leader</p>
              </div>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('partners.softbank.description') }}
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">$100B+ Vision Fund investments</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Global technology ecosystem</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">AI and advanced computing focus</span>
              </div>
            </div>
          </div>

          <!-- OpenAI -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-20 h-20 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-2xl flex items-center justify-center mr-6">
                <span class="text-2xl font-bold text-white">AI</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-white">{{ $t('partners.openai.name') }}</h2>
                <p class="text-secondary-400">AI Research Pioneer</p>
              </div>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('partners.openai.description') }}
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">GPT and ChatGPT development</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Safe AI development practices</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Open research and collaboration</span>
              </div>
            </div>
          </div>

          <!-- Arm -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center mr-6">
                <span class="text-2xl font-bold text-white">A</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-white">{{ $t('partners.arms.name') }}</h2>
                <p class="text-primary-400">Semiconductor Innovation</p>
              </div>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('partners.arms.description') }}
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">ARM architecture design</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Energy-efficient processors</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Mobile and IoT solutions</span>
              </div>
            </div>
          </div>

          <!-- Crystal Intelligence -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-20 h-20 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-2xl flex items-center justify-center mr-6">
                <span class="text-2xl font-bold text-white">CI</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-white">{{ $t('partners.crystal.name') }}</h2>
                <p class="text-secondary-400">AI Innovation Hub</p>
              </div>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              {{ $t('partners.crystal.description') }}
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Advanced AI research</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Cutting-edge solutions</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Innovation partnerships</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Partnership Benefits -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Partnership Synergies</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            How these leading organizations combine their expertise to drive innovation
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Investment & Resources</h3>
            <p class="text-gray-400">
              SoftBank's Vision Fund provides the capital and strategic support needed for large-scale AI infrastructure development.
            </p>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">AI Research & Development</h3>
            <p class="text-gray-400">
              OpenAI and Crystal Intelligence bring cutting-edge AI research and development capabilities to the partnership.
            </p>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Hardware Foundation</h3>
            <p class="text-gray-400">
              Arm provides the energy-efficient processor architecture that powers the advanced computing infrastructure.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Global Impact -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Global Impact</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            The combined expertise of these partners creates unprecedented opportunities for technological advancement
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">$100B+</div>
            <div class="text-gray-400">Total Investment</div>
          </div>
          <div class="text-center">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">190+</div>
            <div class="text-gray-400">Countries Reached</div>
          </div>
          <div class="text-center">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">1B+</div>
            <div class="text-gray-400">Devices Powered</div>
          </div>
          <div class="text-center">
            <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">Global</div>
            <div class="text-gray-400">Innovation Network</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Learn More About Our Partners</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Explore how these organizations are shaping the future of technology and AI.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/insights" class="btn-primary">
            Read Insights
          </RouterLink>
          <RouterLink to="/services" class="btn-secondary">
            Explore Technologies
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
</script>
