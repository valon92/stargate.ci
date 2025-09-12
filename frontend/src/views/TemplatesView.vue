<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Project Templates</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Accelerate your Stargate implementation with ready-to-use project templates. 
            Choose from industry-specific solutions and customize them for your needs.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button 
              @click="scrollToTemplates" 
              class="btn-primary text-lg px-8 py-4"
            >
              Browse Templates
            </button>
            <RouterLink to="/dashboard" class="btn-secondary text-lg px-8 py-4">
              Go to Dashboard
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Templates Component -->
    <section ref="templatesSection" class="py-12">
      <ProjectTemplates />
    </section>

    <!-- Template Categories -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Template Categories</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Choose from different categories of templates designed for various use cases and industries.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🤖</span>
            </div>
            <h3 class="text-xl font-bold mb-4">AI & Machine Learning</h3>
            <p class="text-gray-400 mb-4">
              Templates for AI chatbots, machine learning pipelines, and intelligent automation solutions.
            </p>
            <ul class="text-sm text-gray-500 space-y-1">
              <li>• AI Chatbot Template</li>
              <li>• ML Pipeline Template</li>
              <li>• Computer Vision Template</li>
            </ul>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📊</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Data Analytics</h3>
            <p class="text-gray-400 mb-4">
              Templates for data processing, analytics dashboards, and business intelligence solutions.
            </p>
            <ul class="text-sm text-gray-500 space-y-1">
              <li>• Data Pipeline Template</li>
              <li>• IoT Analytics Template</li>
              <li>• Real-time Analytics Template</li>
            </ul>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">☁️</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Cloud Infrastructure</h3>
            <p class="text-gray-400 mb-4">
              Templates for cloud migration, microservices, and scalable infrastructure solutions.
            </p>
            <ul class="text-sm text-gray-500 space-y-1">
              <li>• Cloud Migration Template</li>
              <li>• Microservices Template</li>
              <li>• Serverless Template</li>
            </ul>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🏢</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Enterprise Solutions</h3>
            <p class="text-gray-400 mb-4">
              Templates for enterprise-grade applications with security, compliance, and scalability.
            </p>
            <ul class="text-sm text-gray-500 space-y-1">
              <li>• Enterprise AI Platform</li>
              <li>• Multi-tenant SaaS Template</li>
              <li>• Compliance Template</li>
            </ul>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🚀</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Startup Solutions</h3>
            <p class="text-gray-400 mb-4">
              Templates for rapid MVP development and startup-friendly solutions.
            </p>
            <ul class="text-sm text-gray-500 space-y-1">
              <li>• Startup MVP Template</li>
              <li>• Rapid Prototype Template</li>
              <li>• Growth Template</li>
            </ul>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🌐</span>
            </div>
            <h3 class="text-xl font-bold mb-4">IoT & Edge Computing</h3>
            <p class="text-gray-400 mb-4">
              Templates for IoT applications, edge computing, and connected device solutions.
            </p>
            <ul class="text-sm text-gray-500 space-y-1">
              <li>• IoT Analytics Template</li>
              <li>• Edge Computing Template</li>
              <li>• Smart City Template</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Template Benefits -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Why Use Project Templates?</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Our templates are designed to accelerate your development process and ensure best practices.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">⚡</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Faster Development</h3>
            <p class="text-gray-400">
              Reduce development time by 60-80% with pre-built, tested components and architectures.
            </p>
          </div>

          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">✅</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Best Practices</h3>
            <p class="text-gray-400">
              Built-in security, scalability, and performance optimizations following industry standards.
            </p>
          </div>

          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🔧</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Easy Customization</h3>
            <p class="text-gray-400">
              Fully customizable templates that adapt to your specific requirements and use cases.
            </p>
          </div>

          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📚</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Complete Documentation</h3>
            <p class="text-gray-400">
              Detailed implementation guides, architecture diagrams, and step-by-step instructions.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Template Features -->
    <section class="py-24 bg-gray-900/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Template Features</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Each template comes with comprehensive features and documentation to ensure successful implementation.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
          <div class="space-y-8">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">🏗️</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Complete Architecture</h3>
                <p class="text-gray-400">
                  Pre-designed system architecture with detailed diagrams and component specifications.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">📋</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Implementation Steps</h3>
                <p class="text-gray-400">
                  Step-by-step implementation guide with code examples and best practices.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">🔒</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Security & Compliance</h3>
                <p class="text-gray-400">
                  Built-in security measures and compliance features for enterprise requirements.
                </p>
              </div>
            </div>
          </div>

          <div class="space-y-8">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">📊</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Monitoring & Analytics</h3>
                <p class="text-gray-400">
                  Integrated monitoring, logging, and analytics for production-ready applications.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">🚀</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Deployment Ready</h3>
                <p class="text-gray-400">
                  Pre-configured deployment scripts and CI/CD pipelines for easy production deployment.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">🔄</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Scalability</h3>
                <p class="text-gray-400">
                  Designed for horizontal and vertical scaling to handle growing user demands.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/30 to-secondary-900/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Ready to Get Started?</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Browse our collection of project templates and find the perfect solution for your Stargate implementation.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button 
            @click="scrollToTemplates" 
            class="btn-primary text-lg px-8 py-4"
          >
            Browse Templates
          </button>
          <RouterLink to="/contact" class="btn-secondary text-lg px-8 py-4">
            Get Custom Template
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'
import ProjectTemplates from '../components/ProjectTemplates.vue'

const templatesSection = ref<HTMLElement | null>(null)

const scrollToTemplates = () => {
  if (templatesSection.value) {
    templatesSection.value.scrollIntoView({ behavior: 'smooth' })
  }
}

useHead({
  title: 'Project Templates - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Accelerate your Stargate implementation with ready-to-use project templates. Choose from industry-specific solutions and customize them for your needs.'
    }
  ]
})
</script>
