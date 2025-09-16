<template>
  <div class="advanced-performance-view">
    <PerformanceDashboard />
  </div>
</template>

<script setup lang="ts">
import PerformanceDashboard from '../components/PerformanceDashboard.vue'
</script>

<style scoped>
.advanced-performance-view {
  @apply min-h-screen bg-gray-900;
}
</style>
