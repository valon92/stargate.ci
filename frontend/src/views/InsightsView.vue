<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">{{ $t('insights.title') }}</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            {{ $t('insights.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Category Filter -->
    <section class="py-12 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
          <button
            v-for="category in categories"
            :key="category.key"
            @click="selectedCategory = category.key"
            class="px-6 py-3 rounded-lg font-medium transition-all duration-200"
            :class="selectedCategory === category.key 
              ? 'bg-gradient-to-r from-primary-500 to-secondary-500 text-white' 
              : 'bg-gray-700 text-gray-300 hover:bg-dark-600'"
          >
            {{ category.name }}
          </button>
        </div>
      </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article
            v-for="article in filteredArticles"
            :key="article.id"
            class="card group hover:scale-105 transition-transform duration-300"
          >
            <div class="aspect-w-16 aspect-h-9 mb-6">
              <div class="w-full h-48 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 rounded-lg flex items-center justify-center">
                <div class="text-4xl">{{ article.icon }}</div>
              </div>
            </div>
            
            <div class="flex items-center space-x-2 mb-4">
              <span class="px-3 py-1 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 text-primary-400 text-sm rounded-full">
                {{ article.category }}
              </span>
              <span class="text-gray-400 text-sm">{{ article.date }}</span>
            </div>
            
            <h2 class="text-xl font-semibold text-white mb-3 group-hover:text-primary-400 transition-colors duration-200">
              {{ article.title }}
            </h2>
            
            <p class="text-gray-400 mb-4 leading-relaxed">
              {{ article.excerpt }}
            </p>
            
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-500">{{ article.readTime }} min read</span>
              <RouterLink
                :to="`/insights/${article.slug}`"
                class="text-primary-400 hover:text-primary-300 font-medium transition-colors duration-200"
              >
                {{ $t('insights.readMore') }} →
              </RouterLink>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Stay Updated</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Subscribe to our newsletter for the latest insights on AI, technology, and innovation
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
          <input
            type="email"
            placeholder="Enter your email"
            class="flex-1 px-4 py-3 bg-gray-700 border border-dark-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
          <button class="btn-primary">
            Subscribe
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const selectedCategory = ref('all')

const categories = [
  { key: 'all', name: t('insights.categories.all') },
  { key: 'ai', name: t('insights.categories.ai') },
  { key: 'cloud', name: t('insights.categories.cloud') },
  { key: 'ethics', name: t('insights.categories.ethics') },
  { key: 'security', name: t('insights.categories.security') },
]

const articles = [
  {
    id: 1,
    title: "Understanding Artificial Intelligence: A Beginner's Guide",
    excerpt: "Explore the fundamentals of AI, from machine learning basics to advanced neural networks, and understand how these technologies are shaping our world.",
    category: "AI",
    date: "Dec 15, 2024",
    readTime: 8,
    slug: "understanding-artificial-intelligence-beginners-guide",
    icon: "🤖"
  },
  {
    id: 2,
    title: "The Future of Cloud Computing in AI Infrastructure",
    excerpt: "Discover how cloud computing is revolutionizing AI development and deployment, enabling scalable solutions for complex machine learning workloads.",
    category: "Cloud",
    date: "Dec 12, 2024",
    readTime: 6,
    slug: "future-cloud-computing-ai-infrastructure",
    icon: "☁️"
  },
  {
    id: 3,
    title: "Ethical AI: Building Responsible Technology",
    excerpt: "Learn about the importance of ethical considerations in AI development and how organizations are working to ensure AI benefits all of humanity.",
    category: "Ethics",
    date: "Dec 10, 2024",
    readTime: 10,
    slug: "ethical-ai-building-responsible-technology",
    icon: "⚖️"
  },
  {
    id: 4,
    title: "Data Security in the Age of AI",
    excerpt: "Understand the critical importance of data security and privacy protection in AI systems, and learn about best practices for secure AI deployment.",
    category: "Security",
    date: "Dec 8, 2024",
    readTime: 7,
    slug: "data-security-age-ai",
    icon: "🔒"
  },
  {
    id: 5,
    title: "Machine Learning Algorithms Explained",
    excerpt: "Dive deep into different types of machine learning algorithms, from supervised learning to reinforcement learning, and their real-world applications.",
    category: "AI",
    date: "Dec 5, 2024",
    readTime: 12,
    slug: "machine-learning-algorithms-explained",
    icon: "🧠"
  },
  {
    id: 6,
    title: "Edge Computing: Bringing AI Closer to Users",
    excerpt: "Explore how edge computing is enabling AI applications to run closer to users, reducing latency and improving performance in real-world scenarios.",
    category: "Cloud",
    date: "Dec 3, 2024",
    readTime: 9,
    slug: "edge-computing-bringing-ai-closer-users",
    icon: "⚡"
  }
]

const filteredArticles = computed(() => {
  if (selectedCategory.value === 'all') {
    return articles
  }
  return articles.filter(article => 
    article.category.toLowerCase() === selectedCategory.value
  )
})
</script>
