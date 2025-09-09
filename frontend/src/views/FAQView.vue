<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">{{ $t('faq.title') }}</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            {{ $t('faq.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
          <div v-for="(faq, index) in faqs" :key="index" class="card">
            <button
              @click="toggleFAQ(index)"
              class="w-full text-left flex items-center justify-between focus:outline-none"
            >
              <h3 class="text-lg font-semibold text-white pr-4">{{ faq.question }}</h3>
              <div class="flex-shrink-0">
                <svg
                  class="w-5 h-5 text-gray-400 transition-transform duration-200"
                  :class="{ 'rotate-180': openFAQs.includes(index) }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </button>
            <div
              v-if="openFAQs.includes(index)"
              class="mt-4 pt-4 border-t border-gray-700"
            >
              <p class="text-gray-300 leading-relaxed">{{ faq.answer }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Additional Resources -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Still Have Questions?</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Explore our resources or get in touch with our community
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Read Our Insights</h3>
            <p class="text-gray-400 mb-6">
              Explore in-depth articles about AI, technology, and innovation
            </p>
            <RouterLink to="/insights" class="btn-secondary">
              Browse Articles
            </RouterLink>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Join the Discussion</h3>
            <p class="text-gray-400 mb-6">
              Connect with our community and participate in discussions
            </p>
            <a href="#" class="btn-secondary">
              Join Community
            </a>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Contact Us</h3>
            <p class="text-gray-400 mb-6">
              Get in touch directly with our team for specific questions
            </p>
            <RouterLink to="/contact" class="btn-secondary">
              Contact Us
            </RouterLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const openFAQs = ref<number[]>([])

const toggleFAQ = (index: number) => {
  const isOpen = openFAQs.value.includes(index)
  if (isOpen) {
    openFAQs.value = openFAQs.value.filter(i => i !== index)
  } else {
    openFAQs.value.push(index)
  }
}

const faqs = [
  {
    question: "What is the Stargate project?",
    answer: "The Stargate project is a large-scale AI infrastructure initiative involving SoftBank, OpenAI, and Arm. It represents a significant investment in advanced computing capabilities and AI research, aimed at pushing the boundaries of artificial intelligence and machine learning technologies."
  },
  {
    question: "What is Crystal Intelligence?",
    answer: "Crystal Intelligence is an AI company that's part of the Stargate project ecosystem. It focuses on developing cutting-edge artificial intelligence solutions and contributes to the overall technological advancement of the project."
  },
  {
    question: "Is this the official Stargate project website?",
    answer: "No, this is an independent educational platform. We are not officially affiliated with the Stargate project, SoftBank, OpenAI, or Arm. Our mission is to provide educational content and information based on publicly available sources about these technologies and organizations."
  },
  {
    question: "What technologies are involved in Stargate?",
    answer: "The Stargate project involves several key technologies including artificial intelligence, machine learning, cloud computing, advanced data processing, and high-performance computing infrastructure. These technologies work together to create a comprehensive AI ecosystem."
  },
  {
    question: "How can I learn more about AI and these technologies?",
    answer: "You can explore our Insights section for educational articles, read about our partners and their contributions, or check out our Services section to understand the different technologies involved. We also recommend following official sources and academic publications for the most up-to-date information."
  },
  {
    question: "What is the investment scale of the Stargate project?",
    answer: "While specific details are not publicly disclosed, the Stargate project represents one of the largest investments in AI infrastructure, with estimates suggesting investments in the range of $100 billion or more. This reflects the scale and ambition of the project."
  },
  {
    question: "How does this project impact global technology?",
    answer: "The Stargate project has the potential to significantly advance AI capabilities globally. By combining the expertise of leading technology companies, it aims to accelerate AI research and development, potentially leading to breakthroughs in various fields including healthcare, finance, and scientific research."
  },
  {
    question: "Are there any ethical considerations discussed?",
    answer: "Yes, ethical considerations are an important part of AI development. Organizations like OpenAI have publicly committed to developing AI safely and ensuring it benefits all of humanity. We discuss these topics in our educational content to promote awareness of responsible AI development."
  }
]
</script>
