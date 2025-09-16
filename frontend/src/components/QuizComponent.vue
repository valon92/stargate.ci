<template>
  <div class="quiz-component">
    <div class="mb-6">
      <h3 class="text-lg font-semibold text-white mb-4">{{ quiz.question }}</h3>
      <div class="space-y-3">
        <label
          v-for="(option, index) in quiz.options"
          :key="index"
          :class="[
            'flex items-center space-x-3 p-4 rounded-lg cursor-pointer transition-colors',
            selectedAnswer === index
              ? 'bg-primary-600 text-white'
              : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
          ]"
        >
          <input
            type="radio"
            :value="index"
            v-model="selectedAnswer"
            class="sr-only"
          />
          <div
            :class="[
              'w-5 h-5 rounded-full border-2 flex items-center justify-center',
              selectedAnswer === index
                ? 'border-white'
                : 'border-gray-400'
            ]"
          >
            <div
              v-if="selectedAnswer === index"
              class="w-2 h-2 bg-white rounded-full"
            ></div>
          </div>
          <span class="flex-1">{{ option }}</span>
        </label>
      </div>
    </div>

    <div class="flex items-center justify-between">
      <button
        @click="submitAnswer"
        :disabled="selectedAnswer === null || isSubmitted"
        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
      >
        {{ isSubmitted ? 'Submitted' : 'Submit Answer' }}
      </button>

      <div v-if="isSubmitted" class="flex items-center space-x-2">
        <span
          :class="[
            'px-3 py-1 rounded-full text-sm font-medium',
            isCorrect
              ? 'bg-green-600 text-white'
              : 'bg-red-600 text-white'
          ]"
        >
          {{ isCorrect ? 'Correct!' : 'Incorrect' }}
        </span>
        <span class="text-sm text-gray-400">
          {{ quiz.points }} points
        </span>
      </div>
    </div>

    <!-- Explanation -->
    <div v-if="isSubmitted" class="mt-6 p-4 bg-gray-800 rounded-lg">
      <h4 class="text-white font-medium mb-2">Explanation</h4>
      <p class="text-gray-300">{{ quiz.explanation }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { QuizData } from '../services/advancedContentService'

interface Props {
  quiz: QuizData
}

const props = defineProps<Props>()

const emit = defineEmits<{
  answered: [correct: boolean]
}>()

// State
const selectedAnswer = ref<number | null>(null)
const isSubmitted = ref(false)
const isCorrect = ref(false)

// Methods
const submitAnswer = () => {
  if (selectedAnswer.value === null) return

  isSubmitted.value = true
  isCorrect.value = selectedAnswer.value === props.quiz.correctAnswer

  emit('answered', isCorrect.value)
}
</script>
