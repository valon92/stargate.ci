<template>
  <div class="interactive-tutorial bg-gray-900 rounded-xl overflow-hidden shadow-lg">
    <!-- Tutorial Header -->
    <div class="bg-gray-800 p-6 border-b border-gray-700">
      <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
          <h1 class="text-2xl font-bold text-white mb-2">{{ tutorial.title }}</h1>
          <p class="text-gray-300 mb-4">{{ tutorial.description }}</p>
          
          <!-- Tutorial Stats -->
          <div class="flex items-center space-x-6 text-sm text-gray-400">
            <div class="flex items-center space-x-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              <span>{{ tutorial.rating.toFixed(1) }} rating</span>
            </div>
            <div class="flex items-center space-x-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
              <span>{{ tutorial.completions }} completions</span>
            </div>
            <div class="flex items-center space-x-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
              <span>{{ tutorial.estimatedTime }} min</span>
            </div>
            <div class="flex items-center space-x-1">
              <span class="px-2 py-1 bg-primary-500/20 text-primary-300 rounded text-xs">
                {{ tutorial.difficulty }}
              </span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-3">
          <button
            @click="toggleBookmark"
            :class="[
              'flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors',
              isBookmarked
                ? 'bg-primary-600 text-white'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
            ]"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
            </svg>
            <span>{{ isBookmarked ? 'Bookmarked' : 'Bookmark' }}</span>
          </button>

          <button
            @click="startTutorial"
            :disabled="isStarted"
            class="px-6 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg hover:from-primary-600 hover:to-secondary-600 disabled:opacity-50 transition-all duration-200 font-medium"
          >
            {{ isStarted ? 'In Progress' : 'Start Tutorial' }}
          </button>
        </div>
      </div>

      <!-- Progress Bar -->
      <div v-if="isStarted" class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm text-gray-300">Progress</span>
          <span class="text-sm text-gray-300">{{ Math.round(progress) }}%</span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-2">
          <div
            class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full transition-all duration-300"
            :style="{ width: progress + '%' }"
          ></div>
        </div>
      </div>

      <!-- Tags -->
      <div class="flex flex-wrap gap-2">
        <span
          v-for="tag in tutorial.tags"
          :key="tag"
          class="px-2 py-1 bg-gray-700 text-gray-300 rounded text-xs"
        >
          #{{ tag }}
        </span>
      </div>
    </div>

    <!-- Tutorial Content -->
    <div v-if="isStarted" class="flex">
      <!-- Steps Sidebar -->
      <div class="w-80 bg-gray-800 border-r border-gray-700 p-4">
        <h3 class="text-lg font-semibold text-white mb-4">Steps</h3>
        <div class="space-y-2">
          <button
            v-for="(step, index) in tutorial.steps"
            :key="step.id"
            @click="goToStep(index)"
            :class="[
              'w-full text-left p-3 rounded-lg transition-colors',
              currentStepIndex === index
                ? 'bg-primary-600 text-white'
                : step.isCompleted
                ? 'bg-green-600/20 text-green-300'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
            ]"
          >
            <div class="flex items-center space-x-3">
              <div class="flex-shrink-0">
                <div
                  :class="[
                    'w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold',
                    step.isCompleted
                      ? 'bg-green-500 text-white'
                      : currentStepIndex === index
                      ? 'bg-white text-primary-600'
                      : 'bg-gray-600 text-gray-300'
                  ]"
                >
                  <svg v-if="step.isCompleted" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  <span v-else>{{ index + 1 }}</span>
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium truncate">{{ step.title }}</div>
                <div class="text-xs opacity-75">{{ step.estimatedTime }} min</div>
              </div>
            </div>
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-1 p-6">
        <div v-if="currentStep" class="max-w-4xl">
          <!-- Step Header -->
          <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold text-white">{{ currentStep.title }}</h2>
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-400">{{ currentStep.estimatedTime }} min</span>
                <span
                  v-if="currentStep.isRequired"
                  class="px-2 py-1 bg-red-500/20 text-red-300 rounded text-xs"
                >
                  Required
                </span>
              </div>
            </div>
          </div>

          <!-- Step Content -->
          <div class="space-y-6">
            <!-- Text Content -->
            <div v-if="currentStep.type === 'text'" class="prose prose-invert max-w-none">
              <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ currentStep.content }}</div>
            </div>

            <!-- Video Content -->
            <div v-else-if="currentStep.type === 'video'" class="space-y-4">
              <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ currentStep.content }}</div>
              <div v-if="currentStep.contentData?.videoUrl" class="bg-black rounded-lg overflow-hidden">
                <video
                  :src="currentStep.contentData.videoUrl"
                  controls
                  class="w-full h-auto"
                >
                  Your browser does not support the video tag.
                </video>
              </div>
            </div>

            <!-- Code Content -->
            <div v-else-if="currentStep.type === 'code'" class="space-y-4">
              <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ currentStep.content }}</div>
              <div v-if="currentStep.contentData?.codeBlock" class="bg-gray-800 rounded-lg p-4">
                <pre class="text-gray-300 text-sm overflow-x-auto"><code>{{ currentStep.contentData.codeBlock }}</code></pre>
              </div>
            </div>

            <!-- Quiz Content -->
            <div v-else-if="currentStep.type === 'quiz'" class="space-y-4">
              <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ currentStep.content }}</div>
              <div v-if="currentStep.contentData?.quizData" class="bg-gray-800 rounded-lg p-6">
                <QuizComponent
                  :quiz="currentStep.contentData.quizData"
                  @answered="onQuizAnswered"
                />
              </div>
            </div>

            <!-- Interactive Content -->
            <div v-else-if="currentStep.type === 'interactive'" class="space-y-4">
              <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ currentStep.content }}</div>
              <div v-if="currentStep.contentData?.interactiveData" class="bg-gray-800 rounded-lg p-6">
                <InteractiveComponent
                  :data="currentStep.contentData.interactiveData"
                  @completed="onInteractiveCompleted"
                />
              </div>
            </div>

            <!-- Image Content -->
            <div v-else-if="currentStep.type === 'image'" class="space-y-4">
              <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ currentStep.content }}</div>
              <div v-if="currentStep.contentData?.imageUrl" class="text-center">
                <img
                  :src="currentStep.contentData.imageUrl"
                  :alt="currentStep.title"
                  class="max-w-full h-auto rounded-lg shadow-lg"
                />
              </div>
            </div>

            <!-- Hints -->
            <div v-if="currentStep.hints && currentStep.hints.length > 0" class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4">
              <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                </svg>
                <div>
                  <h4 class="text-blue-400 font-medium mb-2">Hints</h4>
                  <ul class="text-blue-300 text-sm space-y-1">
                    <li v-for="(hint, index) in currentStep.hints" :key="index">• {{ hint }}</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Resources -->
            <div v-if="currentStep.resources && currentStep.resources.length > 0" class="bg-gray-800 rounded-lg p-4">
              <h4 class="text-white font-medium mb-3">Resources</h4>
              <div class="space-y-2">
                <a
                  v-for="resource in currentStep.resources"
                  :key="resource.id"
                  :href="resource.url"
                  target="_blank"
                  class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors"
                >
                  <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-white font-medium">{{ resource.title }}</div>
                    <div v-if="resource.description" class="text-gray-400 text-sm">{{ resource.description }}</div>
                  </div>
                </a>
              </div>
            </div>
          </div>

          <!-- Step Navigation -->
          <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-700">
            <button
              @click="previousStep"
              :disabled="currentStepIndex === 0"
              class="flex items-center space-x-2 px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
              </svg>
              <span>Previous</span>
            </button>

            <div class="flex items-center space-x-3">
              <button
                @click="markStepComplete"
                :disabled="currentStep.isCompleted"
                :class="[
                  'flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors',
                  currentStep.isCompleted
                    ? 'bg-green-600 text-white'
                    : 'bg-primary-600 text-white hover:bg-primary-700'
                ]"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                <span>{{ currentStep.isCompleted ? 'Completed' : 'Mark Complete' }}</span>
              </button>

              <button
                @click="nextStep"
                :disabled="currentStepIndex === tutorial.steps.length - 1"
                class="flex items-center space-x-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                <span>Next</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M4 11v2h12.17l-5.59 5.59L12 20l8-8-8-8-1.41 1.41L16.17 11H4z"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tutorial Overview (when not started) -->
    <div v-else class="p-6">
      <div class="max-w-4xl">
        <!-- Learning Objectives -->
        <div v-if="tutorial.learningObjectives && tutorial.learningObjectives.length > 0" class="mb-8">
          <h3 class="text-lg font-semibold text-white mb-4">Learning Objectives</h3>
          <ul class="space-y-2">
            <li
              v-for="objective in tutorial.learningObjectives"
              :key="objective"
              class="flex items-start space-x-3"
            >
              <svg class="w-5 h-5 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
              <span class="text-gray-300">{{ objective }}</span>
            </li>
          </ul>
        </div>

        <!-- Prerequisites -->
        <div v-if="tutorial.prerequisites && tutorial.prerequisites.length > 0" class="mb-8">
          <h3 class="text-lg font-semibold text-white mb-4">Prerequisites</h3>
          <ul class="space-y-2">
            <li
              v-for="prerequisite in tutorial.prerequisites"
              :key="prerequisite"
              class="flex items-start space-x-3"
            >
              <svg class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              <span class="text-gray-300">{{ prerequisite }}</span>
            </li>
          </ul>
        </div>

        <!-- Steps Overview -->
        <div class="mb-8">
          <h3 class="text-lg font-semibold text-white mb-4">Tutorial Steps</h3>
          <div class="space-y-3">
            <div
              v-for="(step, index) in tutorial.steps"
              :key="step.id"
              class="flex items-center space-x-4 p-4 bg-gray-800 rounded-lg"
            >
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                  {{ index + 1 }}
                </div>
              </div>
              <div class="flex-1">
                <h4 class="text-white font-medium">{{ step.title }}</h4>
                <p class="text-gray-400 text-sm">{{ step.estimatedTime }} min • {{ step.type }}</p>
              </div>
              <div class="flex-shrink-0">
                <span
                  :class="[
                    'px-2 py-1 rounded text-xs',
                    step.isRequired
                      ? 'bg-red-500/20 text-red-300'
                      : 'bg-gray-600 text-gray-300'
                  ]"
                >
                  {{ step.isRequired ? 'Required' : 'Optional' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { advancedContentService, type InteractiveTutorial, type TutorialStep } from '../services/advancedContentService'
import QuizComponent from './QuizComponent.vue'
import InteractiveComponent from './InteractiveComponent.vue'

interface Props {
  tutorial: InteractiveTutorial
}

const props = defineProps<Props>()

const emit = defineEmits<{
  completed: []
  progress: [progress: number]
}>()

// State
const isStarted = ref(false)
const currentStepIndex = ref(0)
const isBookmarked = ref(false)
const completedSteps = ref<string[]>([])

// Computed
const currentStep = computed(() => {
  return props.tutorial.steps[currentStepIndex.value]
})

const progress = computed(() => {
  if (props.tutorial.steps.length === 0) return 0
  return (completedSteps.value.length / props.tutorial.steps.length) * 100
})

// Methods
const startTutorial = () => {
  isStarted.value = true
  currentStepIndex.value = 0
}

const goToStep = (index: number) => {
  currentStepIndex.value = index
}

const nextStep = () => {
  if (currentStepIndex.value < props.tutorial.steps.length - 1) {
    currentStepIndex.value++
  }
}

const previousStep = () => {
  if (currentStepIndex.value > 0) {
    currentStepIndex.value--
  }
}

const markStepComplete = () => {
  const step = currentStep.value
  if (!completedSteps.value.includes(step.id)) {
    completedSteps.value.push(step.id)
    step.isCompleted = true
    
    // Update progress
    emit('progress', progress.value)
    
    // Check if tutorial is completed
    if (completedSteps.value.length === props.tutorial.steps.length) {
      completeTutorial()
    }
  }
}

const completeTutorial = () => {
  emit('completed')
  
  // Mark as completed in service
  if (advancedContentService.getCurrentUser()) {
    advancedContentService.completeContent(
      advancedContentService.getCurrentUser().id,
      props.tutorial.id,
      'tutorial'
    )
  }
}

const onQuizAnswered = (correct: boolean) => {
  if (correct) {
    markStepComplete()
  }
}

const onInteractiveCompleted = () => {
  markStepComplete()
}

const toggleBookmark = async () => {
  if (advancedContentService.getCurrentUser()) {
    const bookmarked = await advancedContentService.toggleBookmark(
      advancedContentService.getCurrentUser().id,
      props.tutorial.id
    )
    isBookmarked.value = bookmarked
  }
}

// Lifecycle
onMounted(() => {
  // Check bookmark status
  if (advancedContentService.getCurrentUser()) {
    advancedContentService.getBookmarks(advancedContentService.getCurrentUser().id)
      .then(bookmarks => {
        isBookmarked.value = bookmarks.some(b => b.contentId === props.tutorial.id)
      })
  }
})
</script>
