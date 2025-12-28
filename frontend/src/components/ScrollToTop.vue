<template>
  <button
    v-if="isVisible"
    @click="scrollToTop"
    class="fixed bottom-24 right-8 z-40 bg-black dark:bg-white text-white dark:text-black p-4 rounded-full shadow-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500"
    aria-label="Scroll to top"
    title="Scroll to top"
  >
    <svg
      class="w-6 h-6"
      fill="none"
      stroke="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M5 10l7-7m0 0l7 7m-7-7v18"
      />
    </svg>
  </button>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const isVisible = ref(false)
const scrollThreshold = 300 // Show button when scrolled down 300px

const handleScroll = () => {
  const scrollPosition = window.pageYOffset || document.documentElement.scrollTop
  const windowHeight = window.innerHeight
  const documentHeight = document.documentElement.scrollHeight
  
  // Show button if scrolled down more than threshold OR near bottom of page
  const isNearBottom = scrollPosition + windowHeight >= documentHeight - 100
  isVisible.value = scrollPosition > scrollThreshold || isNearBottom
}

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  handleScroll() // Check initial state
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
/* Smooth fade in/out animation */
button {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

