<template>
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        <span class="gradient-text">Interactive Learning Paths</span>
      </h2>
      <p class="text-xl text-gray-300 max-w-3xl mx-auto">
        Master Stargate AI infrastructure through structured, interactive learning experiences tailored to your role and expertise level.
      </p>
    </div>

    <!-- Learning Path Selection -->
    <div v-if="!selectedPath" class="mb-12">
      <h3 class="text-2xl font-bold mb-8 text-center">Choose Your Learning Path</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="path in learningPaths" 
          :key="path.id"
          @click="selectPath(path)"
          class="card cursor-pointer group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
        >
          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-3xl">{{ path.icon }}</span>
            </div>
            <h4 class="text-xl font-bold mb-3">{{ path.title }}</h4>
            <p class="text-gray-400 mb-4">{{ path.description }}</p>
            <div class="flex justify-between text-sm text-gray-500 mb-4">
              <span>{{ path.duration }}</span>
              <span>{{ path.difficulty }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
              <span>{{ path.modules.length }} modules</span>
              <span>{{ path.estimatedTime }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Selected Learning Path -->
    <div v-if="selectedPath && !currentModule" class="mb-12">
      <div class="card">
        <div class="flex items-center justify-between mb-6">
          <button 
            @click="goBackToPaths"
            class="flex items-center text-primary-400 hover:text-primary-300 transition-colors"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Learning Paths
          </button>
          <div class="text-sm text-gray-400">
            Progress: {{ completedModules }}/{{ selectedPath.modules.length }}
          </div>
        </div>

        <div class="text-center mb-8">
          <div class="w-24 h-24 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl">{{ selectedPath.icon }}</span>
          </div>
          <h3 class="text-3xl font-bold mb-4">{{ selectedPath.title }}</h3>
          <p class="text-xl text-gray-300 max-w-2xl mx-auto mb-6">{{ selectedPath.description }}</p>
          
          <!-- Progress Bar -->
          <div class="w-full bg-gray-700 rounded-full h-3 mb-6">
            <div 
              class="bg-gradient-to-r from-primary-500 to-secondary-500 h-3 rounded-full transition-all duration-500"
              :style="{ width: `${(completedModules / selectedPath.modules.length) * 100}%` }"
            ></div>
          </div>
          
          <div class="flex justify-center gap-4 text-sm text-gray-400">
            <span>{{ selectedPath.duration }}</span>
            <span>•</span>
            <span>{{ selectedPath.difficulty }}</span>
            <span>•</span>
            <span>{{ selectedPath.estimatedTime }}</span>
          </div>
        </div>

        <!-- Modules List -->
        <div class="space-y-4">
          <h4 class="text-xl font-bold mb-6">Learning Modules</h4>
          <div 
            v-for="(module, index) in selectedPath.modules" 
            :key="module.id"
            @click="startModule(module, index)"
            class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors group"
            :class="{ 'opacity-50': !module.unlocked }"
          >
            <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center mr-4"
                 :class="module.completed ? 'bg-green-600' : module.unlocked ? 'bg-primary-600' : 'bg-gray-600'">
              <span v-if="module.completed" class="text-white text-xl">✓</span>
              <span v-else class="text-white font-bold">{{ index + 1 }}</span>
            </div>
            <div class="flex-grow">
              <h5 class="font-semibold text-lg">{{ module.title }}</h5>
              <p class="text-gray-400 text-sm">{{ module.description }}</p>
              <div class="flex justify-between items-center mt-2">
                <span class="text-xs text-gray-500">{{ module.duration }}</span>
                <span class="text-xs text-gray-500">{{ module.type }}</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Current Module -->
    <div v-if="currentModule" class="mb-12">
      <div class="card">
        <div class="flex items-center justify-between mb-6">
          <button 
            @click="goBackToPath"
            class="flex items-center text-primary-400 hover:text-primary-300 transition-colors"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to {{ selectedPath?.title }}
          </button>
          <div class="text-sm text-gray-400">
            Module {{ currentModuleIndex + 1 }} of {{ selectedPath?.modules.length }}
          </div>
        </div>

        <div class="mb-8">
          <h3 class="text-3xl font-bold mb-4">{{ currentModule.title }}</h3>
          <p class="text-xl text-gray-300 mb-6">{{ currentModule.description }}</p>
          
          <!-- Module Progress -->
          <div class="w-full bg-gray-700 rounded-full h-2 mb-6">
            <div 
              class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full transition-all duration-300"
              :style="{ width: `${moduleProgress}%` }"
            ></div>
          </div>
        </div>

        <!-- Module Content -->
        <div class="space-y-8">
          <!-- Video/Interactive Content -->
          <div v-if="currentModule.type === 'Video'" class="bg-gray-800 rounded-lg p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-3xl">🎥</span>
            </div>
            <h4 class="text-xl font-bold mb-4">Video Tutorial</h4>
            <p class="text-gray-400 mb-6">{{ currentModule.content.videoDescription }}</p>
            <button 
              @click="markContentComplete('video')"
              class="btn-primary"
              :disabled="currentModule.completedContent.includes('video')"
            >
              {{ currentModule.completedContent.includes('video') ? 'Video Watched' : 'Mark Video as Watched' }}
            </button>
          </div>

          <!-- Reading Material -->
          <div v-if="currentModule.type === 'Reading'" class="bg-gray-800 rounded-lg p-8">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-3xl">📚</span>
            </div>
            <h4 class="text-xl font-bold mb-4 text-center">Reading Material</h4>
            <div class="prose prose-invert max-w-none">
              <div v-html="currentModule.content.readingContent"></div>
            </div>
            <div class="text-center mt-6">
              <button 
                @click="markContentComplete('reading')"
                class="btn-primary"
                :disabled="currentModule.completedContent.includes('reading')"
              >
                {{ currentModule.completedContent.includes('reading') ? 'Reading Completed' : 'Mark Reading as Complete' }}
              </button>
            </div>
          </div>

          <!-- Interactive Exercise -->
          <div v-if="currentModule.type === 'Exercise'" class="bg-gray-800 rounded-lg p-8">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-3xl">💡</span>
            </div>
            <h4 class="text-xl font-bold mb-4 text-center">Interactive Exercise</h4>
            <div class="space-y-6">
              <div v-for="(question, index) in currentModule.content.questions" :key="index" class="border border-gray-600 rounded-lg p-6">
                <h5 class="font-semibold mb-4">{{ question.question }}</h5>
                <div class="space-y-3">
                  <label 
                    v-for="(option, optionIndex) in question.options" 
                    :key="optionIndex"
                    class="flex items-center cursor-pointer"
                  >
                    <input 
                      type="radio" 
                      :name="`question-${index}`"
                      :value="optionIndex"
                      v-model="exerciseAnswers[index]"
                      class="mr-3 text-primary-500"
                    >
                    <span>{{ option }}</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="text-center mt-6">
              <button 
                @click="submitExercise"
                class="btn-primary"
                :disabled="!isExerciseComplete"
              >
                Submit Exercise
              </button>
            </div>
          </div>

          <!-- Quiz -->
          <div v-if="currentModule.type === 'Quiz'" class="bg-gray-800 rounded-lg p-8">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-3xl">🧠</span>
            </div>
            <h4 class="text-xl font-bold mb-4 text-center">Knowledge Quiz</h4>
            <div class="space-y-6">
              <div v-for="(question, index) in currentModule.content.questions" :key="index" class="border border-gray-600 rounded-lg p-6">
                <h5 class="font-semibold mb-4">{{ question.question }}</h5>
                <div class="space-y-3">
                  <label 
                    v-for="(option, optionIndex) in question.options" 
                    :key="optionIndex"
                    class="flex items-center cursor-pointer"
                  >
                    <input 
                      type="radio" 
                      :name="`quiz-${index}`"
                      :value="optionIndex"
                      v-model="quizAnswers[index]"
                      class="mr-3 text-primary-500"
                    >
                    <span>{{ option }}</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="text-center mt-6">
              <button 
                @click="submitQuiz"
                class="btn-primary"
                :disabled="!isQuizComplete"
              >
                Submit Quiz
              </button>
            </div>
          </div>
        </div>

        <!-- Module Completion -->
        <div v-if="isModuleComplete" class="mt-8 p-6 bg-green-900/20 border border-green-500/30 rounded-lg">
          <div class="text-center">
            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🎉</span>
            </div>
            <h4 class="text-xl font-bold mb-2">Module Completed!</h4>
            <p class="text-gray-300 mb-4">Great job! You've successfully completed this module.</p>
            <div class="flex justify-center gap-4">
              <button 
                @click="completeModule"
                class="btn-primary"
              >
                Mark Module as Complete
              </button>
              <button 
                @click="goToNextModule"
                class="btn-secondary"
                v-if="currentModuleIndex < (selectedPath?.modules.length || 0) - 1"
              >
                Next Module
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Learning Path Completion -->
    <div v-if="selectedPath && isPathComplete" class="mb-12">
      <div class="card text-center">
        <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
          <span class="text-4xl">🏆</span>
        </div>
        <h3 class="text-3xl font-bold mb-4">Congratulations!</h3>
        <p class="text-xl text-gray-300 mb-6">You've successfully completed the {{ selectedPath.title }} learning path!</p>
        <div class="flex justify-center gap-4">
          <button @click="goBackToPaths" class="btn-primary">
            Explore More Learning Paths
          </button>
          <RouterLink to="/assessment" class="btn-secondary">
            Take Readiness Assessment
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { RouterLink } from 'vue-router'

// Type definitions
interface ModuleContent {
  videoDescription?: string
  readingContent?: string
  questions?: Array<{
    question: string
    options: string[]
    correct: number
  }>
}

interface LearningModule {
  id: string
  title: string
  description: string
  type: 'Video' | 'Reading' | 'Exercise' | 'Quiz'
  duration: string
  unlocked: boolean
  completed: boolean
  completedContent: string[]
  content: ModuleContent
}

interface LearningPath {
  id: string
  title: string
  description: string
  icon: string
  duration: string
  difficulty: string
  estimatedTime: string
  modules: LearningModule[]
}

// Learning Paths Data
const learningPaths = ref<LearningPath[]>([
  {
    id: 'stargate-fundamentals',
    title: 'Stargate Fundamentals',
    description: 'Learn the basics of Stargate AI infrastructure, its components, and core concepts.',
    icon: '🚀',
    duration: '2-3 weeks',
    difficulty: 'Beginner',
    estimatedTime: '8-12 hours',
    modules: [
      {
        id: 'intro-stargate',
        title: 'Introduction to Stargate',
        description: 'Understanding what Stargate is and why it matters for AI infrastructure.',
        type: 'Video' as const,
        duration: '30 min',
        unlocked: true,
        completed: false,
        completedContent: [],
        content: {
          videoDescription: 'Learn about the Stargate project, its goals, and how it revolutionizes AI infrastructure.',
          readingContent: `
            <h3>What is Stargate?</h3>
            <p>Stargate is a revolutionary AI infrastructure project developed by OpenAI, SoftBank, and Arm. It represents the next generation of AI computing infrastructure designed to handle massive-scale AI workloads.</p>
            
            <h3>Key Features:</h3>
            <ul>
              <li>Massive-scale AI computing power</li>
              <li>Advanced data processing capabilities</li>
              <li>Integrated cloud infrastructure</li>
              <li>Enterprise-grade security and compliance</li>
            </ul>
            
            <h3>Why Stargate Matters:</h3>
            <p>Stargate enables organizations to leverage cutting-edge AI capabilities without the complexity of building and maintaining their own infrastructure. It provides a scalable, secure, and efficient platform for AI innovation.</p>
          `
        }
      },
      {
        id: 'architecture-overview',
        title: 'Stargate Architecture',
        description: 'Deep dive into the technical architecture and components of Stargate.',
        type: 'Reading' as const,
        duration: '45 min',
        unlocked: false,
        completed: false,
        completedContent: [],
        content: {
          readingContent: `
            <h3>Stargate Architecture Overview</h3>
            <p>Stargate's architecture is built on a distributed, cloud-native foundation that enables massive scalability and performance.</p>
            
            <h3>Core Components:</h3>
            <ul>
              <li><strong>Compute Layer:</strong> High-performance AI processing units</li>
              <li><strong>Storage Layer:</strong> Distributed data storage and management</li>
              <li><strong>Network Layer:</strong> High-speed interconnects and data transfer</li>
              <li><strong>Security Layer:</strong> Enterprise-grade security and compliance</li>
            </ul>
            
            <h3>Key Technologies:</h3>
            <ul>
              <li>Advanced AI chips and processors</li>
              <li>Distributed computing frameworks</li>
              <li>Cloud-native orchestration</li>
              <li>Real-time data processing</li>
            </ul>
          `
        }
      },
      {
        id: 'use-cases',
        title: 'Stargate Use Cases',
        description: 'Explore real-world applications and use cases for Stargate infrastructure.',
        type: 'Exercise' as const,
        duration: '60 min',
        unlocked: false,
        completed: false,
        completedContent: [],
        content: {
          questions: [
            {
              question: 'Which of the following is NOT a primary use case for Stargate?',
              options: [
                'Large-scale AI model training',
                'Real-time data processing',
                'Traditional web hosting',
                'Advanced analytics and insights'
              ],
              correct: 2
            },
            {
              question: 'What type of organizations would benefit most from Stargate?',
              options: [
                'Small local businesses',
                'Enterprises with AI initiatives',
                'Individual developers',
                'Non-profit organizations'
              ],
              correct: 1
            }
          ]
        }
      },
      {
        id: 'fundamentals-quiz',
        title: 'Fundamentals Quiz',
        description: 'Test your understanding of Stargate fundamentals.',
        type: 'Quiz' as const,
        duration: '30 min',
        unlocked: false,
        completed: false,
        completedContent: [],
        content: {
          questions: [
            {
              question: 'Who are the main partners in the Stargate project?',
              options: [
                'Google, Microsoft, Amazon',
                'OpenAI, SoftBank, Arm',
                'Apple, Tesla, NVIDIA',
                'Meta, IBM, Intel'
              ],
              correct: 1
            },
            {
              question: 'What is the primary goal of Stargate?',
              options: [
                'To replace all existing cloud services',
                'To provide massive-scale AI infrastructure',
                'To create a new programming language',
                'To develop consumer AI applications'
              ],
              correct: 1
            }
          ]
        }
      }
    ]
  },
  {
    id: 'technical-implementation',
    title: 'Technical Implementation',
    description: 'Learn how to implement and integrate Stargate into your existing infrastructure.',
    icon: '⚙️',
    duration: '3-4 weeks',
    difficulty: 'Intermediate',
    estimatedTime: '12-16 hours',
    modules: [
      {
        id: 'integration-planning',
        title: 'Integration Planning',
        description: 'Plan your Stargate integration strategy and roadmap.',
        type: 'Video' as const,
        duration: '45 min',
        unlocked: true,
        completed: false,
        completedContent: [],
        content: {
          videoDescription: 'Learn how to plan and strategize your Stargate integration project.',
          readingContent: `
            <h3>Integration Planning Process</h3>
            <p>Successful Stargate integration requires careful planning and strategic thinking.</p>
            
            <h3>Key Planning Steps:</h3>
            <ol>
              <li>Assess current infrastructure</li>
              <li>Define integration goals</li>
              <li>Create implementation timeline</li>
              <li>Identify resource requirements</li>
              <li>Plan for testing and validation</li>
            </ol>
          `
        }
      },
      {
        id: 'security-considerations',
        title: 'Security & Compliance',
        description: 'Understand security requirements and compliance considerations.',
        type: 'Reading' as const,
        duration: '60 min',
        unlocked: false,
        completed: false,
        completedContent: [],
        content: {
          readingContent: `
            <h3>Security Framework</h3>
            <p>Stargate implements enterprise-grade security measures to protect your data and infrastructure.</p>
            
            <h3>Security Features:</h3>
            <ul>
              <li>End-to-end encryption</li>
              <li>Multi-factor authentication</li>
              <li>Role-based access control</li>
              <li>Audit logging and monitoring</li>
            </ul>
            
            <h3>Compliance Standards:</h3>
            <ul>
              <li>GDPR compliance</li>
              <li>SOC 2 Type II</li>
              <li>ISO 27001</li>
              <li>HIPAA (where applicable)</li>
            </ul>
          `
        }
      }
    ]
  },
  {
    id: 'advanced-optimization',
    title: 'Advanced Optimization',
    description: 'Master advanced techniques for optimizing Stargate performance and efficiency.',
    icon: '🎯',
    duration: '4-5 weeks',
    difficulty: 'Advanced',
    estimatedTime: '16-20 hours',
    modules: [
      {
        id: 'performance-tuning',
        title: 'Performance Tuning',
        description: 'Learn advanced techniques for optimizing Stargate performance.',
        type: 'Video' as const,
        duration: '60 min',
        unlocked: true,
        completed: false,
        completedContent: [],
        content: {
          videoDescription: 'Master the art of performance tuning for Stargate infrastructure.',
          readingContent: `
            <h3>Performance Optimization Strategies</h3>
            <p>Optimizing Stargate performance requires understanding of various tuning parameters and techniques.</p>
            
            <h3>Key Areas:</h3>
            <ul>
              <li>Compute resource allocation</li>
              <li>Data pipeline optimization</li>
              <li>Network configuration tuning</li>
              <li>Storage performance optimization</li>
            </ul>
          `
        }
      }
    ]
  }
])

// State Management
const selectedPath = ref<LearningPath | null>(null)
const currentModule = ref<LearningModule | null>(null)
const currentModuleIndex = ref(0)
const exerciseAnswers = ref<Record<number, number>>({})
const quizAnswers = ref<Record<number, number>>({})

// Computed Properties
const completedModules = computed(() => {
  if (!selectedPath.value) return 0
  return selectedPath.value.modules.filter(module => module.completed).length
})

const moduleProgress = computed(() => {
  if (!currentModule.value) return 0
  const totalContent = getTotalContentCount(currentModule.value)
  const completedContent = currentModule.value.completedContent.length
  return totalContent > 0 ? (completedContent / totalContent) * 100 : 0
})

const isModuleComplete = computed(() => {
  if (!currentModule.value) return false
  const totalContent = getTotalContentCount(currentModule.value)
  return currentModule.value.completedContent.length >= totalContent
})

const isPathComplete = computed(() => {
  if (!selectedPath.value) return false
  return selectedPath.value.modules.every(module => module.completed)
})

const isExerciseComplete = computed(() => {
  if (!currentModule.value || currentModule.value.type !== 'Exercise') return false
  const questions = currentModule.value.content.questions || []
  return questions.every((_, index) => exerciseAnswers.value[index] !== undefined)
})

const isQuizComplete = computed(() => {
  if (!currentModule.value || currentModule.value.type !== 'Quiz') return false
  const questions = currentModule.value.content.questions || []
  return questions.every((_, index) => quizAnswers.value[index] !== undefined)
})

// Methods
const selectPath = (path: LearningPath) => {
  selectedPath.value = { ...path }
  // Reset module states
  selectedPath.value.modules.forEach(module => {
    module.completedContent = []
  })
}

const goBackToPaths = () => {
  selectedPath.value = null
  currentModule.value = null
  currentModuleIndex.value = 0
}

const goBackToPath = () => {
  currentModule.value = null
  currentModuleIndex.value = 0
}

const startModule = (module: LearningModule, index: number) => {
  if (!module.unlocked) return
  currentModule.value = { ...module }
  currentModuleIndex.value = index
  exerciseAnswers.value = {}
  quizAnswers.value = {}
}

const markContentComplete = (contentType: string) => {
  if (!currentModule.value?.completedContent.includes(contentType)) {
    currentModule.value?.completedContent.push(contentType)
  }
}

const submitExercise = () => {
  if (!isExerciseComplete.value || !currentModule.value) return
  
  // Check answers and provide feedback
  let correctAnswers = 0
  const questions = currentModule.value.content.questions || []
  questions.forEach((question, index) => {
    if (exerciseAnswers.value[index] === question.correct) {
      correctAnswers++
    }
  })
  
  const score = (correctAnswers / questions.length) * 100
  
  if (score >= 70) {
    markContentComplete('exercise')
  } else {
    alert(`You scored ${score.toFixed(0)}%. Please review the material and try again.`)
  }
}

const submitQuiz = () => {
  if (!isQuizComplete.value || !currentModule.value) return
  
  // Check answers and provide feedback
  let correctAnswers = 0
  const questions = currentModule.value.content.questions || []
  questions.forEach((question, index) => {
    if (quizAnswers.value[index] === question.correct) {
      correctAnswers++
    }
  })
  
  const score = (correctAnswers / questions.length) * 100
  
  if (score >= 80) {
    markContentComplete('quiz')
  } else {
    alert(`You scored ${score.toFixed(0)}%. You need at least 80% to pass. Please review and try again.`)
  }
}

const completeModule = () => {
  if (!isModuleComplete.value || !currentModule.value || !selectedPath.value) return
  
  currentModule.value.completed = true
  selectedPath.value.modules[currentModuleIndex.value].completed = true
  
  // Unlock next module
  if (currentModuleIndex.value < selectedPath.value.modules.length - 1) {
    selectedPath.value.modules[currentModuleIndex.value + 1].unlocked = true
  }
  
  goBackToPath()
}

const goToNextModule = () => {
  if (!selectedPath.value || currentModuleIndex.value < selectedPath.value.modules.length - 1) {
    const nextModule = selectedPath.value?.modules[currentModuleIndex.value + 1]
    if (nextModule?.unlocked) {
      startModule(nextModule, currentModuleIndex.value + 1)
    }
  }
}

const getTotalContentCount = (module: LearningModule) => {
  switch (module.type) {
    case 'Video':
      return 1
    case 'Reading':
      return 1
    case 'Exercise':
      return 1
    case 'Quiz':
      return 1
    default:
      return 1
  }
}

// Load saved progress
onMounted(() => {
  const savedProgress = localStorage.getItem('learningPathsProgress')
  if (savedProgress) {
    try {
      const progress = JSON.parse(savedProgress)
      // Restore progress state
      if (progress.selectedPathId) {
        const path = learningPaths.value.find(p => p.id === progress.selectedPathId)
        if (path) {
          selectedPath.value = { ...path }
          // Restore module states
          if (progress.moduleStates && selectedPath.value) {
            selectedPath.value.modules.forEach((module, index) => {
              if (progress.moduleStates[module.id]) {
                Object.assign(module, progress.moduleStates[module.id])
              }
            })
          }
        }
      }
    } catch (error) {
      console.error('Error loading learning progress:', error)
    }
  }
})

// Save progress
const saveProgress = () => {
  if (selectedPath.value) {
    const progress = {
      selectedPathId: selectedPath.value.id,
      moduleStates: {} as Record<string, any>
    }
    
    selectedPath.value.modules.forEach(module => {
      progress.moduleStates[module.id] = {
        completed: module.completed,
        unlocked: module.unlocked,
        completedContent: module.completedContent
      }
    })
    
    localStorage.setItem('learningPathsProgress', JSON.stringify(progress))
  }
}

// Watch for changes and save progress
watch([selectedPath, completedModules], () => {
  saveProgress()
}, { deep: true })
</script>
