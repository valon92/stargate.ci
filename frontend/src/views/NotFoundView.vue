<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-900">
    <div class="text-center">
      <div class="mb-8">
        <h1 class="text-9xl font-bold gradient-text">404</h1>
      </div>
      
      <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
        Page Not Found
      </h2>
      
      <p class="text-xl text-gray-300 mb-8 max-w-md mx-auto">
        The page you're looking for doesn't exist or has been moved.
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <RouterLink to="/" class="btn-primary">
          Go Home
        </RouterLink>
        <button @click="$router.go(-1)" class="btn-secondary">
          Go Back
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>
