<template>
  <div class="interactive-component">
    <div class="mb-6">
      <h3 class="text-lg font-semibold text-white mb-4">{{ data.type.toUpperCase() }}</h3>
      <p class="text-gray-300 mb-4">{{ data.instructions }}</p>
    </div>

    <!-- Simulation Type -->
    <div v-if="data.type === 'simulation'" class="space-y-4">
      <div class="bg-gray-800 rounded-lg p-6">
        <h4 class="text-white font-medium mb-4">Simulation Controls</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-300 mb-2">Scenario</label>
            <select
              v-model="simulationConfig.scenario"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="pattern-recognition">Pattern Recognition</option>
              <option value="data-processing">Data Processing</option>
              <option value="ai-training">AI Training</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-300 mb-2">Complexity</label>
            <select
              v-model="simulationConfig.complexity"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="basic">Basic</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
          </div>
        </div>
        
        <div class="mt-4">
          <button
            @click="startSimulation"
            :disabled="isSimulationRunning"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
          >
            {{ isSimulationRunning ? 'Running...' : 'Start Simulation' }}
          </button>
        </div>

        <!-- Simulation Results -->
        <div v-if="simulationResults" class="mt-6 p-4 bg-gray-700 rounded-lg">
          <h5 class="text-white font-medium mb-2">Results</h5>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-300">Accuracy:</span>
              <span class="text-white">{{ simulationResults.accuracy }}%</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-300">Performance:</span>
              <span class="text-white">{{ simulationResults.performance }}ms</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-300">Status:</span>
              <span :class="simulationResults.success ? 'text-green-400' : 'text-red-400'">
                {{ simulationResults.success ? 'Success' : 'Failed' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Demo Type -->
    <div v-else-if="data.type === 'demo'" class="space-y-4">
      <div class="bg-gray-800 rounded-lg p-6">
        <h4 class="text-white font-medium mb-4">Interactive Demo</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm text-gray-300 mb-2">Input Data</label>
            <textarea
              v-model="demoInput"
              placeholder="Enter your data here..."
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
              rows="4"
            ></textarea>
          </div>
          
          <div class="flex space-x-3">
            <button
              @click="runDemo"
              :disabled="!demoInput.trim() || isDemoRunning"
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
            >
              {{ isDemoRunning ? 'Processing...' : 'Run Demo' }}
            </button>
            <button
              @click="clearDemo"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              Clear
            </button>
          </div>

          <!-- Demo Results -->
          <div v-if="demoResults" class="mt-4 p-4 bg-gray-700 rounded-lg">
            <h5 class="text-white font-medium mb-2">Output</h5>
            <pre class="text-gray-300 text-sm whitespace-pre-wrap">{{ demoResults }}</pre>
          </div>
        </div>
      </div>
    </div>

    <!-- Sandbox Type -->
    <div v-else-if="data.type === 'sandbox'" class="space-y-4">
      <div class="bg-gray-800 rounded-lg p-6">
        <h4 class="text-white font-medium mb-4">Code Sandbox</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm text-gray-300 mb-2">Template</label>
            <select
              v-model="sandboxTemplate"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="stargate-basic">Stargate Basic</option>
              <option value="cristal-intelligence">Cristal Intelligence</option>
              <option value="ai-integration">AI Integration</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm text-gray-300 mb-2">Code Editor</label>
            <textarea
              v-model="sandboxCode"
              placeholder="// Write your code here..."
              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm"
              rows="12"
            ></textarea>
          </div>
          
          <div class="flex space-x-3">
            <button
              @click="runSandbox"
              :disabled="!sandboxCode.trim() || isSandboxRunning"
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
            >
              {{ isSandboxRunning ? 'Running...' : 'Run Code' }}
            </button>
            <button
              @click="resetSandbox"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              Reset
            </button>
          </div>

          <!-- Sandbox Results -->
          <div v-if="sandboxResults" class="mt-4 p-4 bg-gray-700 rounded-lg">
            <h5 class="text-white font-medium mb-2">Output</h5>
            <pre class="text-gray-300 text-sm whitespace-pre-wrap">{{ sandboxResults }}</pre>
          </div>
        </div>
      </div>
    </div>

    <!-- Game Type -->
    <div v-else-if="data.type === 'game'" class="space-y-4">
      <div class="bg-gray-800 rounded-lg p-6">
        <h4 class="text-white font-medium mb-4">Interactive Game</h4>
        <div class="text-center">
          <div class="mb-4">
            <div class="text-2xl font-bold text-white mb-2">Score: {{ gameScore }}</div>
            <div class="text-gray-300">Level: {{ gameLevel }}</div>
          </div>
          
          <div class="grid grid-cols-3 gap-2 max-w-xs mx-auto mb-4">
            <button
              v-for="(cell, index) in gameGrid"
              :key="index"
              @click="makeMove(index)"
              :class="[
                'w-16 h-16 rounded-lg border-2 transition-colors',
                cell === 'X' ? 'bg-red-600 border-red-500' :
                cell === 'O' ? 'bg-blue-600 border-blue-500' :
                'bg-gray-700 border-gray-600 hover:bg-gray-600'
              ]"
            >
              <span class="text-2xl font-bold text-white">{{ cell }}</span>
            </button>
          </div>
          
          <div class="space-x-3">
            <button
              @click="startNewGame"
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              New Game
            </button>
            <button
              @click="resetGame"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              Reset
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Criteria -->
    <div class="mt-6 p-4 bg-gray-800 rounded-lg">
      <h4 class="text-white font-medium mb-3">Success Criteria</h4>
      <ul class="space-y-2">
        <li
          v-for="(criterion, index) in data.successCriteria"
          :key="index"
          class="flex items-center space-x-3"
        >
          <div
            :class="[
              'w-5 h-5 rounded-full flex items-center justify-center',
              successCriteriaMet[index]
                ? 'bg-green-500 text-white'
                : 'bg-gray-600 text-gray-300'
            ]"
          >
            <svg v-if="successCriteriaMet[index]" class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </div>
          <span class="text-gray-300">{{ criterion }}</span>
        </li>
      </ul>
    </div>

    <!-- Complete Button -->
    <div v-if="allCriteriaMet" class="mt-6 text-center">
      <button
        @click="markComplete"
        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
      >
        Mark as Complete
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { InteractiveData } from '../services/advancedContentService'

interface Props {
  data: InteractiveData
}

const props = defineProps<Props>()

const emit = defineEmits<{
  completed: []
}>()

// State
const simulationConfig = ref({
  scenario: 'pattern-recognition',
  complexity: 'basic'
})
const isSimulationRunning = ref(false)
const simulationResults = ref<any>(null)

const demoInput = ref('')
const isDemoRunning = ref(false)
const demoResults = ref('')

const sandboxTemplate = ref('stargate-basic')
const sandboxCode = ref('')
const isSandboxRunning = ref(false)
const sandboxResults = ref('')

const gameScore = ref(0)
const gameLevel = ref(1)
const gameGrid = ref(Array(9).fill(''))
const currentPlayer = ref('X')

const successCriteriaMet = ref<boolean[]>([])

// Computed
const allCriteriaMet = computed(() => {
  return successCriteriaMet.value.every(met => met)
})

// Methods
const startSimulation = async () => {
  isSimulationRunning.value = true
  
  // Simulate processing time
  await new Promise(resolve => setTimeout(resolve, 2000))
  
  // Generate mock results
  simulationResults.value = {
    accuracy: Math.floor(Math.random() * 30) + 70, // 70-100%
    performance: Math.floor(Math.random() * 500) + 100, // 100-600ms
    success: Math.random() > 0.3 // 70% success rate
  }
  
  isSimulationRunning.value = false
  
  // Check success criteria
  checkSuccessCriteria()
}

const runDemo = async () => {
  isDemoRunning.value = true
  
  // Simulate processing
  await new Promise(resolve => setTimeout(resolve, 1500))
  
  // Generate mock output
  demoResults.value = `Processing: ${demoInput.value}\nResult: ${demoInput.value.toUpperCase()}\nStatus: Success\nTimestamp: ${new Date().toISOString()}`
  
  isDemoRunning.value = false
  
  // Check success criteria
  checkSuccessCriteria()
}

const clearDemo = () => {
  demoInput.value = ''
  demoResults.value = ''
}

const runSandbox = async () => {
  isSandboxRunning.value = true
  
  // Simulate code execution
  await new Promise(resolve => setTimeout(resolve, 1000))
  
  // Generate mock output
  sandboxResults.value = `Code executed successfully!\nTemplate: ${sandboxTemplate.value}\nOutput: Hello from Stargate Sandbox!\nExecution time: ${Math.floor(Math.random() * 100) + 50}ms`
  
  isSandboxRunning.value = false
  
  // Check success criteria
  checkSuccessCriteria()
}

const resetSandbox = () => {
  sandboxCode.value = ''
  sandboxResults.value = ''
}

const makeMove = (index: number) => {
  if (gameGrid.value[index] !== '') return
  
  gameGrid.value[index] = currentPlayer.value
  
  // Check for win
  if (checkWin()) {
    gameScore.value += 10
    gameLevel.value++
    setTimeout(() => {
      startNewGame()
    }, 1000)
  } else if (gameGrid.value.every(cell => cell !== '')) {
    // Draw
    setTimeout(() => {
      startNewGame()
    }, 1000)
  } else {
    currentPlayer.value = currentPlayer.value === 'X' ? 'O' : 'X'
  }
  
  // Check success criteria
  checkSuccessCriteria()
}

const checkWin = (): boolean => {
  const winningCombinations = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
    [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
    [0, 4, 8], [2, 4, 6] // Diagonals
  ]
  
  return winningCombinations.some(combination => {
    const [a, b, c] = combination
    return gameGrid.value[a] && 
           gameGrid.value[a] === gameGrid.value[b] && 
           gameGrid.value[a] === gameGrid.value[c]
  })
}

const startNewGame = () => {
  gameGrid.value = Array(9).fill('')
  currentPlayer.value = 'X'
}

const resetGame = () => {
  gameScore.value = 0
  gameLevel.value = 1
  startNewGame()
}

const checkSuccessCriteria = () => {
  // Update success criteria based on current state
  successCriteriaMet.value = props.data.successCriteria.map((criterion, index) => {
    switch (props.data.type) {
      case 'simulation':
        return simulationResults.value?.success || false
      case 'demo':
        return demoResults.value.length > 0
      case 'sandbox':
        return sandboxResults.value.length > 0
      case 'game':
        return gameScore.value > 0
      default:
        return false
    }
  })
}

const markComplete = () => {
  emit('completed')
}

// Lifecycle
onMounted(() => {
  // Initialize success criteria
  successCriteriaMet.value = new Array(props.data.successCriteria.length).fill(false)
  
  // Initialize sandbox code based on template
  if (props.data.type === 'sandbox') {
    switch (sandboxTemplate.value) {
      case 'stargate-basic':
        sandboxCode.value = `// Stargate Basic Template
import { StargateAI } from '@stargate/ai-core';

const ai = new StargateAI({
  apiKey: 'your-api-key',
  model: 'stargate-v1'
});

// Your code here
console.log('Hello, Stargate!');`
        break
      case 'cristal-intelligence':
        sandboxCode.value = `// Cristal Intelligence Template
import { CristalIntelligence } from '@cristal/intelligence';

const cristal = new CristalIntelligence({
  config: 'advanced',
  mode: 'learning'
});

// Your code here
console.log('Cristal Intelligence activated!');`
        break
      case 'ai-integration':
        sandboxCode.value = `// AI Integration Template
import { AIIntegration } from '@stargate/ai-integration';

const integration = new AIIntegration({
  providers: ['openai', 'anthropic'],
  fallback: true
});

// Your code here
console.log('AI Integration ready!');`
        break
    }
  }
})
</script>
