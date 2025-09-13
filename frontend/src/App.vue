<script setup lang="ts">
import { RouterView } from 'vue-router'
import MetaHeader from './components/layout/MetaHeader.vue'
import Header from './components/layout/Header.vue'
import Footer from './components/layout/Footer.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Meta Header - Top level info -->
    <MetaHeader />
    
    <!-- Main Navigation -->
    <Header />
    
    <!-- Main Content -->
    <main class="min-h-screen">
      <RouterView />
    </main>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>
