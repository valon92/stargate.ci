<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Dashboard Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-white mb-2">
            <span class="gradient-text">Welcome to Your Dashboard</span>
          </h1>
          <p class="text-gray-300">
            Track your progress, discover new opportunities, and stay updated with Stargate developments.
          </p>
        </div>
        <div class="flex items-center space-x-4">
          <div class="text-right">
            <p class="text-sm text-gray-400">Last active</p>
            <p class="text-sm text-white">{{ lastActive }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
            <span class="text-xl">👤</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="card">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-4">
            <span class="text-xl">📊</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-white">{{ userStats.assessmentScore }}</p>
            <p class="text-sm text-gray-400">Readiness Score</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
            <span class="text-xl">🎓</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-white">{{ userStats.completedModules }}</p>
            <p class="text-sm text-gray-400">Modules Completed</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
            <span class="text-xl">🏆</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-white">{{ userStats.achievements }}</p>
            <p class="text-sm text-gray-400">Achievements</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mr-4">
            <span class="text-xl">📈</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-white">{{ userStats.learningStreak }}</p>
            <p class="text-sm text-gray-400">Day Streak</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left Column -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Learning Progress -->
        <div class="card">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Learning Progress</h2>
            <RouterLink to="/learning" class="text-primary-400 hover:text-primary-300 text-sm">
              View All →
            </RouterLink>
          </div>
          
          <div class="space-y-4">
            <div v-for="path in learningProgress" :key="path.id" class="border border-gray-700 rounded-lg p-4">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                  <span class="text-2xl mr-3">{{ path.icon }}</span>
                  <div>
                    <h3 class="font-semibold text-white">{{ path.title }}</h3>
                    <p class="text-sm text-gray-400">{{ path.difficulty }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm text-gray-400">{{ path.completedModules }}/{{ path.totalModules }}</p>
                  <div class="w-20 bg-gray-700 rounded-full h-2 mt-1">
                    <div 
                      class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full transition-all duration-300"
                      :style="{ width: `${(path.completedModules / path.totalModules) * 100}%` }"
                    ></div>
                  </div>
                </div>
              </div>
              <div class="flex justify-between text-sm text-gray-400">
                <span>{{ path.duration }}</span>
                <span>{{ path.estimatedTime }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
          <h2 class="text-xl font-bold text-white mb-6">Recent Activity</h2>
          <div class="space-y-4">
            <div v-for="activity in recentActivity" :key="activity.id" class="flex items-start space-x-3">
              <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                   :class="getActivityIconClass(activity.type)">
                <span class="text-sm">{{ getActivityIcon(activity.type) }}</span>
              </div>
              <div class="flex-grow">
                <p class="text-white text-sm">{{ activity.description }}</p>
                <p class="text-gray-400 text-xs">{{ activity.timestamp }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recommended Actions -->
        <div class="card">
          <h2 class="text-xl font-bold text-white mb-6">Recommended Actions</h2>
          <div class="space-y-4">
            <div v-for="action in recommendedActions" :key="action.id" class="border border-gray-700 rounded-lg p-4">
              <div class="flex items-start justify-between">
                <div class="flex-grow">
                  <h3 class="font-semibold text-white mb-2">{{ action.title }}</h3>
                  <p class="text-gray-400 text-sm mb-3">{{ action.description }}</p>
                  <div class="flex items-center space-x-4 text-sm">
                    <span class="text-gray-500">{{ action.priority }}</span>
                    <span class="text-gray-500">{{ action.estimatedTime }}</span>
                  </div>
                </div>
                <div class="flex-shrink-0 ml-4">
                  <button 
                    @click="executeAction(action)"
                    class="btn-primary text-sm px-4 py-2"
                  >
                    {{ action.buttonText }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-8">
        <!-- Quick Actions -->
        <div class="card">
          <h2 class="text-xl font-bold text-white mb-6">Quick Actions</h2>
          <div class="space-y-3">
            <RouterLink 
              to="/assessment" 
              class="flex items-center p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors"
            >
              <span class="text-xl mr-3">📋</span>
              <span class="text-white">Take Assessment</span>
            </RouterLink>
            <RouterLink 
              to="/learning" 
              class="flex items-center p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors"
            >
              <span class="text-xl mr-3">🎓</span>
              <span class="text-white">Continue Learning</span>
            </RouterLink>
            <RouterLink 
              to="/partnership" 
              class="flex items-center p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors"
            >
              <span class="text-xl mr-3">🤝</span>
              <span class="text-white">Explore Partnerships</span>
            </RouterLink>
            <RouterLink 
              to="/insights" 
              class="flex items-center p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors"
            >
              <span class="text-xl mr-3">💡</span>
              <span class="text-white">Read Insights</span>
            </RouterLink>
          </div>
        </div>

        <!-- Achievements -->
        <div class="card">
          <h2 class="text-xl font-bold text-white mb-6">Recent Achievements</h2>
          <div class="space-y-4">
            <div v-for="achievement in recentAchievements" :key="achievement.id" class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center">
                <span class="text-lg">🏆</span>
              </div>
              <div>
                <h3 class="font-semibold text-white text-sm">{{ achievement.title }}</h3>
                <p class="text-gray-400 text-xs">{{ achievement.description }}</p>
                <p class="text-gray-500 text-xs">{{ achievement.date }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Stargate News -->
        <div class="card">
          <h2 class="text-xl font-bold text-white mb-6">Latest Stargate News</h2>
          <div class="space-y-4">
            <div v-for="news in stargateNews" :key="news.id" class="border-b border-gray-700 pb-4 last:border-b-0">
              <h3 class="font-semibold text-white text-sm mb-2">{{ news.title }}</h3>
              <p class="text-gray-400 text-xs mb-2">{{ news.summary }}</p>
              <div class="flex items-center justify-between">
                <span class="text-gray-500 text-xs">{{ news.date }}</span>
                <a :href="news.link" target="_blank" class="text-primary-400 hover:text-primary-300 text-xs">
                  Read More →
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Learning Goals -->
        <div class="card">
          <h2 class="text-xl font-bold text-white mb-6">Learning Goals</h2>
          <div class="space-y-4">
            <div v-for="goal in learningGoals" :key="goal.id" class="border border-gray-700 rounded-lg p-4">
              <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-white text-sm">{{ goal.title }}</h3>
                <span class="text-xs text-gray-400">{{ goal.progress }}%</span>
              </div>
              <div class="w-full bg-gray-700 rounded-full h-2 mb-2">
                <div 
                  class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full transition-all duration-300"
                  :style="{ width: `${goal.progress}%` }"
                ></div>
              </div>
              <p class="text-gray-400 text-xs">{{ goal.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'

// Type definitions
interface UserStats {
  assessmentScore: number
  completedModules: number
  achievements: number
  learningStreak: number
}

interface LearningProgress {
  id: string
  title: string
  icon: string
  difficulty: string
  duration: string
  estimatedTime: string
  completedModules: number
  totalModules: number
}

interface Activity {
  id: string
  type: 'assessment' | 'learning' | 'achievement' | 'partnership'
  description: string
  timestamp: string
}

interface RecommendedAction {
  id: string
  title: string
  description: string
  priority: string
  estimatedTime: string
  buttonText: string
  action: string
}

interface Achievement {
  id: string
  title: string
  description: string
  date: string
}

interface NewsItem {
  id: string
  title: string
  summary: string
  date: string
  link: string
}

interface LearningGoal {
  id: string
  title: string
  description: string
  progress: number
}

// Reactive data
const lastActive = ref('2 hours ago')
const userStats = ref<UserStats>({
  assessmentScore: 0,
  completedModules: 0,
  achievements: 0,
  learningStreak: 0
})

const learningProgress = ref<LearningProgress[]>([])
const recentActivity = ref<Activity[]>([])
const recommendedActions = ref<RecommendedAction[]>([])
const recentAchievements = ref<Achievement[]>([])
const stargateNews = ref<NewsItem[]>([])
const learningGoals = ref<LearningGoal[]>([])

// Methods
const getActivityIcon = (type: string) => {
  const icons = {
    assessment: '📋',
    learning: '🎓',
    achievement: '🏆',
    partnership: '🤝'
  }
  return icons[type as keyof typeof icons] || '📝'
}

const getActivityIconClass = (type: string) => {
  const classes = {
    assessment: 'bg-blue-500',
    learning: 'bg-green-500',
    achievement: 'bg-yellow-500',
    partnership: 'bg-purple-500'
  }
  return classes[type as keyof typeof classes] || 'bg-gray-500'
}

const executeAction = (action: RecommendedAction) => {
  // Handle action execution based on action.action
  switch (action.action) {
    case 'assessment':
      window.location.href = '/assessment'
      break
    case 'learning':
      window.location.href = '/learning'
      break
    case 'partnership':
      window.location.href = '/partnership'
      break
    case 'insights':
      window.location.href = '/insights'
      break
    default:
      console.log('Action:', action.action)
  }
}

const loadUserData = () => {
  // Load user stats from localStorage or API
  const savedStats = localStorage.getItem('userStats')
  if (savedStats) {
    userStats.value = JSON.parse(savedStats)
  } else {
    // Initialize with default values
    userStats.value = {
      assessmentScore: 0,
      completedModules: 0,
      achievements: 0,
      learningStreak: 0
    }
  }

  // Load learning progress
  const savedProgress = localStorage.getItem('learningPathsProgress')
  if (savedProgress) {
    const progress = JSON.parse(savedProgress)
    learningProgress.value = [
      {
        id: 'stargate-fundamentals',
        title: 'Stargate Fundamentals',
        icon: '🚀',
        difficulty: 'Beginner',
        duration: '2-3 weeks',
        estimatedTime: '8-12 hours',
        completedModules: progress.moduleStates ? Object.values(progress.moduleStates).filter((m: any) => m.completed).length : 0,
        totalModules: 4
      },
      {
        id: 'technical-implementation',
        title: 'Technical Implementation',
        icon: '⚙️',
        difficulty: 'Intermediate',
        duration: '3-4 weeks',
        estimatedTime: '12-16 hours',
        completedModules: 0,
        totalModules: 2
      },
      {
        id: 'advanced-optimization',
        title: 'Advanced Optimization',
        icon: '🎯',
        difficulty: 'Advanced',
        duration: '4-5 weeks',
        estimatedTime: '16-20 hours',
        completedModules: 0,
        totalModules: 1
      }
    ]
  }

  // Load recent activity
  recentActivity.value = [
    {
      id: '1',
      type: 'learning',
      description: 'Completed "Introduction to Stargate" module',
      timestamp: '2 hours ago'
    },
    {
      id: '2',
      type: 'assessment',
      description: 'Took Stargate Readiness Assessment',
      timestamp: '1 day ago'
    },
    {
      id: '3',
      type: 'achievement',
      description: 'Earned "First Steps" achievement',
      timestamp: '2 days ago'
    },
    {
      id: '4',
      type: 'partnership',
      description: 'Explored partnership opportunities',
      timestamp: '3 days ago'
    }
  ]

  // Load recommended actions
  recommendedActions.value = [
    {
      id: '1',
      title: 'Complete Your Assessment',
      description: 'Take the Stargate Readiness Assessment to get personalized recommendations.',
      priority: 'High',
      estimatedTime: '10 min',
      buttonText: 'Start Assessment',
      action: 'assessment'
    },
    {
      id: '2',
      title: 'Continue Learning Path',
      description: 'Resume your Stargate Fundamentals learning path.',
      priority: 'Medium',
      estimatedTime: '30 min',
      buttonText: 'Continue Learning',
      action: 'learning'
    },
    {
      id: '3',
      title: 'Explore Partnerships',
      description: 'Discover partnership opportunities with Stargate providers.',
      priority: 'Low',
      estimatedTime: '15 min',
      buttonText: 'Explore',
      action: 'partnership'
    }
  ]

  // Load recent achievements
  recentAchievements.value = [
    {
      id: '1',
      title: 'First Steps',
      description: 'Completed your first learning module',
      date: '2 days ago'
    },
    {
      id: '2',
      title: 'Assessment Taker',
      description: 'Completed the readiness assessment',
      date: '1 week ago'
    }
  ]

  // Load Stargate news
  stargateNews.value = [
    {
      id: '1',
      title: 'Stargate Project Update',
      summary: 'Latest developments in the Stargate AI infrastructure project.',
      date: '2 days ago',
      link: 'https://openai.com/index/announcing-the-stargate-project/'
    },
    {
      id: '2',
      title: 'SoftBank Partnership',
      summary: 'SoftBank announces new initiatives in AI infrastructure.',
      date: '1 week ago',
      link: 'https://group.softbank/en/news/press/20250203_0'
    }
  ]

  // Load learning goals
  learningGoals.value = [
    {
      id: '1',
      title: 'Complete Fundamentals Path',
      description: 'Finish all modules in the Stargate Fundamentals learning path',
      progress: 25
    },
    {
      id: '2',
      title: 'Achieve 80% Readiness Score',
      description: 'Improve your Stargate readiness assessment score',
      progress: 60
    },
    {
      id: '3',
      title: 'Explore 5 Partnerships',
      description: 'Research and evaluate partnership opportunities',
      progress: 40
    }
  ]
}

// Lifecycle
onMounted(() => {
  loadUserData()
})
</script>
