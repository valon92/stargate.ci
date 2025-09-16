<template>
  <div class="external-integrations-view">
    <IntegrationDashboard />
  </div>
</template>

<script setup lang="ts">
import IntegrationDashboard from '../components/IntegrationDashboard.vue'
</script>

<style scoped>
.external-integrations-view {
  @apply min-h-screen bg-gray-900;
}
</style>
