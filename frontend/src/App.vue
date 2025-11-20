<script setup lang="ts">
import { RouterView } from 'vue-router'
import Header from './components/layout/Header.vue'
import Footer from './components/layout/Footer.vue'
import VoiceControl from './components/VoiceControl.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-900">
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
  </div>
</template>
