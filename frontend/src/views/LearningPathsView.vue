<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Interactive Learning Paths</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Master Stargate AI infrastructure through structured, interactive learning experiences. 
            Choose your path based on your role, expertise level, and learning goals.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button 
              @click="scrollToLearningPaths" 
              class="btn-primary text-lg px-8 py-4"
            >
              Start Learning
            </button>
            <RouterLink to="/assessment" class="btn-secondary text-lg px-8 py-4">
              Take Readiness Assessment
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Learning Paths Component -->
    <section ref="learningPathsSection" class="py-12">
      <InteractiveLearningPaths />
    </section>

    <!-- Benefits Section -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Why Choose Our Learning Paths?</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Our interactive learning paths are designed to provide comprehensive, hands-on education about Stargate infrastructure.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🎯</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Role-Based Learning</h3>
            <p class="text-gray-400">
              Tailored learning paths for different roles: executives, developers, architects, and IT professionals.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📊</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Progress Tracking</h3>
            <p class="text-gray-400">
              Track your learning progress with detailed analytics and completion certificates.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🔄</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Interactive Content</h3>
            <p class="text-gray-400">
              Engage with videos, exercises, quizzes, and hands-on labs for comprehensive learning.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🏆</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Achievement System</h3>
            <p class="text-gray-400">
              Earn badges, certificates, and recognition as you complete learning milestones.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Learning Path Types -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Learning Path Types</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Choose from different types of learning experiences designed for various learning styles and objectives.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="card">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🚀</span>
            </div>
            <h3 class="text-xl font-bold mb-4 text-center">Fundamentals Path</h3>
            <p class="text-gray-400 mb-4">
              Perfect for beginners who want to understand Stargate basics and core concepts.
            </p>
            <ul class="text-sm text-gray-500 space-y-2">
              <li>• Introduction to Stargate</li>
              <li>• Architecture overview</li>
              <li>• Use cases and benefits</li>
              <li>• Basic implementation concepts</li>
            </ul>
          </div>

          <div class="card">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">⚙️</span>
            </div>
            <h3 class="text-xl font-bold mb-4 text-center">Technical Path</h3>
            <p class="text-gray-400 mb-4">
              For technical professionals who need to implement and integrate Stargate.
            </p>
            <ul class="text-sm text-gray-500 space-y-2">
              <li>• Integration planning</li>
              <li>• Security and compliance</li>
              <li>• Performance optimization</li>
              <li>• Troubleshooting and maintenance</li>
            </ul>
          </div>

          <div class="card">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🎯</span>
            </div>
            <h3 class="text-xl font-bold mb-4 text-center">Advanced Path</h3>
            <p class="text-gray-400 mb-4">
              For experts who want to master advanced Stargate optimization and architecture.
            </p>
            <ul class="text-sm text-gray-500 space-y-2">
              <li>• Advanced optimization techniques</li>
              <li>• Custom architecture design</li>
              <li>• Performance tuning</li>
              <li>• Best practices and patterns</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Learning Features -->
    <section class="py-24 bg-gray-900/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Interactive Learning Features</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Our learning platform offers a variety of interactive features to enhance your learning experience.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
          <div class="space-y-8">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">🎥</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Video Tutorials</h3>
                <p class="text-gray-400">
                  High-quality video content with expert instructors covering key concepts and practical examples.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">📚</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Reading Materials</h3>
                <p class="text-gray-400">
                  Comprehensive documentation, guides, and reference materials for deep learning.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">💡</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Interactive Exercises</h3>
                <p class="text-gray-400">
                  Hands-on exercises and practical scenarios to reinforce learning and test understanding.
                </p>
              </div>
            </div>
          </div>

          <div class="space-y-8">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">🧠</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Knowledge Quizzes</h3>
                <p class="text-gray-400">
                  Comprehensive quizzes to test your knowledge and ensure mastery of key concepts.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">📊</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Progress Analytics</h3>
                <p class="text-gray-400">
                  Detailed progress tracking with analytics to monitor your learning journey.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xl">🏆</span>
              </div>
              <div>
                <h3 class="text-lg font-bold mb-2">Achievement System</h3>
                <p class="text-gray-400">
                  Earn badges, certificates, and recognition as you complete learning milestones.
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
          <span class="gradient-text">Ready to Start Learning?</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Begin your journey to mastering Stargate AI infrastructure with our interactive learning paths.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button 
            @click="scrollToLearningPaths" 
            class="btn-primary text-lg px-8 py-4"
          >
            Start Learning Now
          </button>
          <RouterLink to="/contact" class="btn-secondary text-lg px-8 py-4">
            Get Learning Support
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
import InteractiveLearningPaths from '../components/InteractiveLearningPaths.vue'

const learningPathsSection = ref<HTMLElement | null>(null)

const scrollToLearningPaths = () => {
  if (learningPathsSection.value) {
    learningPathsSection.value.scrollIntoView({ behavior: 'smooth' })
  }
}

useHead({
  title: 'Interactive Learning Paths - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Master Stargate AI infrastructure through structured, interactive learning experiences. Choose your path based on your role and expertise level.'
    }
  ]
})
</script>
