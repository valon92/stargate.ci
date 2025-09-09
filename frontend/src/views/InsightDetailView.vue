<template>
  <div class="min-h-screen">
    <!-- Article Header -->
    <section class="relative overflow-hidden bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <div class="flex items-center justify-center space-x-2 mb-6">
            <span class="px-3 py-1 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 text-primary-400 text-sm rounded-full">
              {{ article.category }}
            </span>
            <span class="text-gray-400 text-sm">{{ article.date }}</span>
          </div>
          
          <h1 class="text-4xl md:text-5xl font-bold mb-6">
            <span class="gradient-text">{{ article.title }}</span>
          </h1>
          
          <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
            {{ article.excerpt }}
          </p>
          
          <div class="flex items-center justify-center space-x-6 text-gray-400">
            <span>{{ article.readTime }} min read</span>
            <span>•</span>
            <span>{{ article.author }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Article Content -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card">
          <div class="prose prose-invert max-w-none">
            <div class="aspect-w-16 aspect-h-9 mb-8">
              <div class="w-full h-64 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 rounded-lg flex items-center justify-center">
                <div class="text-6xl">{{ article.icon }}</div>
              </div>
            </div>
            
            <div v-html="article.content"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Related Articles -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Related Articles</span>
          </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <article
            v-for="relatedArticle in relatedArticles"
            :key="relatedArticle.id"
            class="card group hover:scale-105 transition-transform duration-300"
          >
            <div class="aspect-w-16 aspect-h-9 mb-6">
              <div class="w-full h-32 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 rounded-lg flex items-center justify-center">
                <div class="text-3xl">{{ relatedArticle.icon }}</div>
              </div>
            </div>
            
            <div class="flex items-center space-x-2 mb-4">
              <span class="px-3 py-1 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 text-primary-400 text-sm rounded-full">
                {{ relatedArticle.category }}
              </span>
              <span class="text-gray-400 text-sm">{{ relatedArticle.date }}</span>
            </div>
            
            <h3 class="text-lg font-semibold text-white mb-3 group-hover:text-primary-400 transition-colors duration-200">
              {{ relatedArticle.title }}
            </h3>
            
            <p class="text-gray-400 mb-4 text-sm">
              {{ relatedArticle.excerpt }}
            </p>
            
            <RouterLink
              :to="`/insights/${relatedArticle.slug}`"
              class="text-primary-400 hover:text-primary-300 font-medium transition-colors duration-200 text-sm"
            >
              Read More →
            </RouterLink>
          </article>
        </div>
      </div>
    </section>

    <!-- Back to Insights -->
    <section class="py-12 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <RouterLink to="/insights" class="btn-secondary">
          ← Back to All Insights
        </RouterLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink, useRoute } from 'vue-router'

const route = useRoute()

// Mock article data - in a real app, this would come from an API
const article = computed(() => ({
  id: 1,
  title: "Understanding Artificial Intelligence: A Beginner's Guide",
  excerpt: "Explore the fundamentals of AI, from machine learning basics to advanced neural networks, and understand how these technologies are shaping our world.",
  category: "AI",
  date: "Dec 15, 2024",
  readTime: 8,
  slug: "understanding-artificial-intelligence-beginners-guide",
  icon: "🤖",
  author: "Stargate.ci Team",
  content: `
    <h2>Introduction to Artificial Intelligence</h2>
    <p>Artificial Intelligence (AI) represents one of the most transformative technologies of our time. From simple rule-based systems to complex neural networks, AI has evolved dramatically over the past few decades.</p>
    
    <h3>What is Artificial Intelligence?</h3>
    <p>AI refers to the simulation of human intelligence in machines that are programmed to think and learn like humans. These systems can perform tasks that typically require human intelligence, such as visual perception, speech recognition, decision-making, and language translation.</p>
    
    <h3>Types of AI</h3>
    <p>There are several types of AI systems:</p>
    <ul>
      <li><strong>Narrow AI:</strong> Designed for specific tasks, like image recognition or language translation</li>
      <li><strong>General AI:</strong> Hypothetical AI that can understand, learn, and apply knowledge across different domains</li>
      <li><strong>Superintelligence:</strong> AI that surpasses human intelligence in all areas</li>
    </ul>
    
    <h3>Machine Learning Fundamentals</h3>
    <p>Machine learning is a subset of AI that enables computers to learn and improve from experience without being explicitly programmed. It uses algorithms to identify patterns in data and make predictions or decisions.</p>
    
    <h3>Real-World Applications</h3>
    <p>AI is already transforming various industries:</p>
    <ul>
      <li>Healthcare: Medical diagnosis and drug discovery</li>
      <li>Finance: Fraud detection and algorithmic trading</li>
      <li>Transportation: Autonomous vehicles and traffic optimization</li>
      <li>Entertainment: Content recommendation and game AI</li>
    </ul>
    
    <h3>Ethical Considerations</h3>
    <p>As AI becomes more powerful, it's crucial to consider the ethical implications. Issues such as bias, privacy, job displacement, and decision-making transparency need to be addressed to ensure AI benefits all of humanity.</p>
    
    <h3>Conclusion</h3>
    <p>Understanding AI is essential in our increasingly digital world. As these technologies continue to evolve, staying informed about their capabilities, limitations, and implications will help us navigate the future effectively.</p>
  `
}))

const relatedArticles = [
  {
    id: 2,
    title: "The Future of Cloud Computing in AI Infrastructure",
    excerpt: "Discover how cloud computing is revolutionizing AI development and deployment.",
    category: "Cloud",
    date: "Dec 12, 2024",
    readTime: 6,
    slug: "future-cloud-computing-ai-infrastructure",
    icon: "☁️"
  },
  {
    id: 3,
    title: "Ethical AI: Building Responsible Technology",
    excerpt: "Learn about the importance of ethical considerations in AI development.",
    category: "Ethics",
    date: "Dec 10, 2024",
    readTime: 10,
    slug: "ethical-ai-building-responsible-technology",
    icon: "⚖️"
  },
  {
    id: 5,
    title: "Machine Learning Algorithms Explained",
    excerpt: "Dive deep into different types of machine learning algorithms and their applications.",
    category: "AI",
    date: "Dec 5, 2024",
    readTime: 12,
    slug: "machine-learning-algorithms-explained",
    icon: "🧠"
  }
]
</script>
