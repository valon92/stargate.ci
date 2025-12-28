<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import Header from './components/layout/Header.vue'
import Footer from './components/layout/Footer.vue'
import VoiceControl from './components/VoiceControl.vue'
import ScrollToTop from './components/ScrollToTop.vue'
import { useTheme } from './composables/useTheme'

// Initialize theme system
const { initTheme } = useTheme()

onMounted(() => {
  initTheme()
})
</script>

<template>
  <div class="min-h-screen bg-gray-900 dark:bg-gray-900 bg-white transition-colors duration-300">
    <!-- Main Navigation -->
    <Header />
    
    <!-- Main Content -->
    <main class="min-h-screen">
      <RouterView />
    </main>
    
    <!-- Footer -->
    <Footer />
    
    <!-- Voice Control -->
    <VoiceControl />
    
    <!-- Scroll to Top Button -->
    <ScrollToTop />
  </div>
</template>
