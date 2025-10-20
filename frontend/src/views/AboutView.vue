<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">About stargate.ci</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            The bridge between companies and the revolutionary Stargate Project and Cristal Intelligence initiatives. We facilitate, integrate, and preserve authorship while guiding organizations toward ethical AI implementation.
          </p>
        </div>
      </div>
    </section>

    <!-- Domain Meaning Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Domain Meaning</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Understanding the significance behind our domain name and its connection to cutting-edge AI technologies
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="card">
            <div class="p-6">
              <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-white mb-4 text-center">stargate</h3>
              <div class="p-4 bg-primary-500/10 rounded-lg border border-primary-500/20">
                <p class="text-gray-300 text-sm leading-relaxed">
                  The revolutionary project from OpenAI, SoftBank, and ARM - a $500B investment in next-generation AI computing infrastructure. This massive undertaking represents the future of artificial intelligence and computing power.
                </p>
              </div>
            </div>
          </div>
          
          <div class="card">
            <div class="p-6">
              <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-white mb-4 text-center">.ci</h3>
              <div class="p-4 bg-secondary-500/10 rounded-lg border border-secondary-500/20">
                <p class="text-gray-300 text-sm leading-relaxed">
                  Cristal Intelligence - Advanced Enterprise AI that securely integrates systems and data, customized for each company. This represents the next evolution in AI technology with enhanced transparency and ethical implementation.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Legal Disclaimer Section -->
    <section class="py-24 bg-gradient-to-r from-yellow-900/20 to-orange-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card border-yellow-500/20">
          <div class="flex items-start space-x-4">
            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold mb-4 text-white">Legal Disclaimer</h3>
              <p class="text-gray-300 leading-relaxed mb-4">
                stargate.ci is an independent platform that serves as a bridge between companies and official Stargate and Cristal Intelligence projects. We are not affiliated with, endorsed by, or sponsored by OpenAI, SoftBank, ARM, or any other official project partners.
              </p>
              <div class="space-y-2 text-sm text-gray-400">
                <p>• We provide information from publicly available official sources</p>
                <p>• We facilitate connections but do not guarantee project acceptance</p>
                <p>• All trademarks and copyrights belong to their respective owners</p>
                <p>• We operate as a non-profit educational platform</p>
                <p>• We respect all intellectual property rights and legal requirements</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { useHead } from '@vueuse/head'

useHead({
  title: 'About stargate.ci - Domain Meaning & Legal Disclaimer',
  meta: [
    {
      name: 'description',
      content: 'Learn about stargate.ci domain meaning and legal disclaimer. Understanding the significance behind our domain name and our legal position.'
    }
  ]
})
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-6 hover:border-primary-500/50 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>