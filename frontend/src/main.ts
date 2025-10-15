import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createHead } from '@vueuse/head'

import App from './App.vue'
import router from './router'
import { analyticsService } from './services/analyticsService'
import { searchService } from './services/searchService'

const app = createApp(App)
const head = createHead()

app.use(createPinia())
app.use(router)
app.use(head)

// Initialize services
analyticsService.initialize()
searchService.initialize()

app.mount('#app')
