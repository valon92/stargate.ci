<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">
            <span class="gradient-text">Your Stargate Dashboard</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Track your progress, discover opportunities, and stay connected with the Stargate ecosystem.
          </p>
        </div>
      </div>
    </section>

    <!-- Dashboard Component -->
    <section class="py-8">
      <UserDashboard />
    </section>

    <!-- Dashboard Features -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Dashboard Features</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Your personalized command center for everything Stargate-related.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📊</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Progress Tracking</h3>
            <p class="text-gray-400">
              Monitor your learning progress, assessment scores, and achievement milestones in real-time.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🎯</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Personalized Recommendations</h3>
            <p class="text-gray-400">
              Get tailored suggestions for learning paths, partnerships, and next steps based on your profile.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📈</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Analytics & Insights</h3>
            <p class="text-gray-400">
              View detailed analytics about your engagement, learning patterns, and growth trajectory.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🏆</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Achievement System</h3>
            <p class="text-gray-400">
              Earn badges, certificates, and recognition as you progress through your Stargate journey.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📰</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Latest News</h3>
            <p class="text-gray-400">
              Stay updated with the latest Stargate developments, announcements, and industry news.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🎯</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Goal Setting</h3>
            <p class="text-gray-400">
              Set and track personal learning goals, milestones, and objectives for your Stargate journey.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Access -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Quick Access</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Jump directly to the tools and resources you need most.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <RouterLink 
            to="/assessment" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">📋</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Readiness Assessment</h3>
            <p class="text-gray-400 text-sm">Evaluate your Stargate readiness</p>
          </RouterLink>

          <RouterLink 
            to="/learning" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🎓</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Learning Paths</h3>
            <p class="text-gray-400 text-sm">Interactive learning experiences</p>
          </RouterLink>

          <RouterLink 
            to="/partnership" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🤝</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Partnerships</h3>
            <p class="text-gray-400 text-sm">Explore collaboration opportunities</p>
          </RouterLink>

          <RouterLink 
            to="/insights" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">💡</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Insights</h3>
            <p class="text-gray-400 text-sm">Latest insights and analysis</p>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gray-900/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Ready to Get Started?</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Your dashboard is your gateway to the Stargate ecosystem. Start exploring and building your future with AI infrastructure.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/assessment" class="btn-primary text-lg px-8 py-4">
            Take Assessment
          </RouterLink>
          <RouterLink to="/learning" class="btn-secondary text-lg px-8 py-4">
            Start Learning
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'
import UserDashboard from '../components/UserDashboard.vue'

useHead({
  title: 'User Dashboard - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Your personalized Stargate dashboard. Track progress, discover opportunities, and stay connected with the Stargate ecosystem.'
    }
  ]
})
</script>
