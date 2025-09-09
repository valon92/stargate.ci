<script setup lang="ts">
import { RouterView } from 'vue-router'
import Header from './components/layout/Header.vue'
import Footer from './components/layout/Footer.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-900">
    <Header />
    <main class="min-h-screen">
      <RouterView />
    </main>
    <Footer />
  </div>
</template>
