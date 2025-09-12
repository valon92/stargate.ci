<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        <span class="gradient-text">Stargate Project Templates</span>
      </h2>
      <p class="text-xl text-gray-300 max-w-3xl mx-auto">
        Jumpstart your Stargate implementation with ready-to-use project templates. 
        Choose from industry-specific templates and customize them for your needs.
      </p>
    </div>

    <!-- Filter and Search -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="flex flex-wrap gap-2">
          <button 
            v-for="category in categories" 
            :key="category.id"
            @click="selectedCategory = category.id"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
            :class="selectedCategory === category.id 
              ? 'bg-primary-600 text-white' 
              : 'bg-gray-700 text-gray-300 hover:bg-gray-600'"
          >
            {{ category.name }}
          </button>
        </div>
        <div class="relative">
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Search templates..."
            class="w-64 px-4 py-2 pl-10 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          >
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div 
        v-for="template in filteredTemplates" 
        :key="template.id"
        class="card group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
      >
        <!-- Template Image/Icon -->
        <div class="relative mb-6">
          <div class="w-full h-48 bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg flex items-center justify-center">
            <div class="text-6xl">{{ template.icon }}</div>
          </div>
          <div class="absolute top-4 right-4">
            <span class="px-3 py-1 bg-primary-600 text-white text-xs font-medium rounded-full">
              {{ template.category }}
            </span>
          </div>
        </div>

        <!-- Template Info -->
        <div class="mb-6">
          <h3 class="text-xl font-bold text-white mb-2">{{ template.name }}</h3>
          <p class="text-gray-400 text-sm mb-4">{{ template.description }}</p>
          
          <!-- Template Features -->
          <div class="space-y-2 mb-4">
            <div v-for="feature in template.features" :key="feature" class="flex items-center text-sm text-gray-300">
              <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              {{ feature }}
            </div>
          </div>

          <!-- Template Stats -->
          <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
            <div class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ template.estimatedTime }}
            </div>
            <div class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              {{ template.difficulty }}
            </div>
            <div class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              {{ template.teamSize }}
            </div>
          </div>
        </div>

        <!-- Template Actions -->
        <div class="flex flex-col space-y-3">
          <button 
            @click="previewTemplate(template)"
            class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors"
          >
            Preview Template
          </button>
          <button 
            @click="downloadTemplate(template)"
            class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
          >
            Download Template
          </button>
        </div>
      </div>
    </div>

    <!-- Template Preview Modal -->
    <div v-if="selectedTemplate" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-900 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <!-- Modal Header -->
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-2xl font-bold text-white">{{ selectedTemplate.name }}</h3>
              <p class="text-gray-400">{{ selectedTemplate.category }} Template</p>
            </div>
            <button 
              @click="selectedTemplate = null"
              class="text-gray-400 hover:text-white transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Template Preview Content -->
          <div class="space-y-6">
            <!-- Template Overview -->
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Overview</h4>
              <p class="text-gray-300">{{ selectedTemplate.description }}</p>
            </div>

            <!-- Template Features -->
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Features</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div v-for="feature in selectedTemplate.features" :key="feature" class="flex items-center text-gray-300">
                  <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  {{ feature }}
                </div>
              </div>
            </div>

            <!-- Template Architecture -->
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Architecture</h4>
              <div class="bg-gray-800 rounded-lg p-4">
                <div class="text-sm text-gray-300 whitespace-pre-line">{{ selectedTemplate.architecture }}</div>
              </div>
            </div>

            <!-- Implementation Steps -->
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Implementation Steps</h4>
              <div class="space-y-3">
                <div v-for="(step, index) in selectedTemplate.implementationSteps" :key="index" class="flex items-start">
                  <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3 flex-shrink-0">
                    {{ index + 1 }}
                  </div>
                  <div class="text-gray-300">{{ step }}</div>
                </div>
              </div>
            </div>

            <!-- Template Requirements -->
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Requirements</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <h5 class="font-medium text-white mb-2">Technical Requirements</h5>
                  <ul class="text-sm text-gray-300 space-y-1">
                    <li v-for="req in selectedTemplate.requirements.technical" :key="req">• {{ req }}</li>
                  </ul>
                </div>
                <div>
                  <h5 class="font-medium text-white mb-2">Team Requirements</h5>
                  <ul class="text-sm text-gray-300 space-y-1">
                    <li v-for="req in selectedTemplate.requirements.team" :key="req">• {{ req }}</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Template Stats -->
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Project Details</h4>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary-400">{{ selectedTemplate.estimatedTime }}</div>
                  <div class="text-sm text-gray-400">Estimated Time</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary-400">{{ selectedTemplate.difficulty }}</div>
                  <div class="text-sm text-gray-400">Difficulty</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary-400">{{ selectedTemplate.teamSize }}</div>
                  <div class="text-sm text-gray-400">Team Size</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary-400">{{ selectedTemplate.budget }}</div>
                  <div class="text-sm text-gray-400">Budget Range</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Actions -->
          <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-700">
            <button 
              @click="selectedTemplate = null"
              class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors"
            >
              Close
            </button>
            <button 
              @click="downloadTemplate(selectedTemplate)"
              class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              Download Template
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

// Type definitions
interface Template {
  id: string
  name: string
  description: string
  icon: string
  category: string
  difficulty: string
  estimatedTime: string
  teamSize: string
  budget: string
  features: string[]
  architecture: string
  implementationSteps: string[]
  requirements: {
    technical: string[]
    team: string[]
  }
}

interface Category {
  id: string
  name: string
}

// Reactive data
const selectedCategory = ref('all')
const searchQuery = ref('')
const selectedTemplate = ref<Template | null>(null)

// Categories
const categories = ref<Category[]>([
  { id: 'all', name: 'All Templates' },
  { id: 'ai-ml', name: 'AI & Machine Learning' },
  { id: 'data-analytics', name: 'Data Analytics' },
  { id: 'cloud-infrastructure', name: 'Cloud Infrastructure' },
  { id: 'enterprise', name: 'Enterprise Solutions' },
  { id: 'startup', name: 'Startup Solutions' }
])

// Templates data
const templates = ref<Template[]>([
  {
    id: 'ai-chatbot-template',
    name: 'AI Chatbot Template',
    description: 'Complete AI chatbot implementation using Stargate infrastructure with natural language processing capabilities.',
    icon: '🤖',
    category: 'AI & Machine Learning',
    difficulty: 'Intermediate',
    estimatedTime: '2-3 weeks',
    teamSize: '3-5 developers',
    budget: '$50K - $100K',
    features: [
      'Natural Language Processing',
      'Multi-language Support',
      'Real-time Chat Interface',
      'Integration APIs',
      'Analytics Dashboard',
      'Scalable Architecture'
    ],
    architecture: `Frontend: React/Vue.js
Backend: Node.js/Python
AI Engine: OpenAI GPT Integration
Database: PostgreSQL/MongoDB
Cache: Redis
Message Queue: RabbitMQ
API Gateway: Express.js/FastAPI
Monitoring: Prometheus + Grafana`,
    implementationSteps: [
      'Set up Stargate infrastructure and configure AI services',
      'Implement backend API with authentication and rate limiting',
      'Create frontend chat interface with real-time messaging',
      'Integrate OpenAI API for natural language processing',
      'Implement conversation memory and context management',
      'Add analytics and monitoring dashboards',
      'Deploy and configure production environment'
    ],
    requirements: {
      technical: [
        'Stargate AI infrastructure access',
        'OpenAI API key',
        'Node.js/Python development environment',
        'Database setup (PostgreSQL/MongoDB)',
        'Redis for caching',
        'Docker for containerization'
      ],
      team: [
        'Full-stack developer (2-3)',
        'AI/ML engineer (1)',
        'DevOps engineer (1)',
        'UI/UX designer (1)'
      ]
    }
  },
  {
    id: 'data-pipeline-template',
    name: 'Data Pipeline Template',
    description: 'End-to-end data processing pipeline for big data analytics using Stargate computing power.',
    icon: '📊',
    category: 'Data Analytics',
    difficulty: 'Advanced',
    estimatedTime: '4-6 weeks',
    teamSize: '5-8 developers',
    budget: '$100K - $200K',
    features: [
      'Real-time Data Processing',
      'ETL/ELT Pipelines',
      'Data Warehousing',
      'Machine Learning Integration',
      'Data Visualization',
      'Automated Monitoring'
    ],
    architecture: `Data Sources: APIs, Databases, Files
Ingestion: Apache Kafka/Spark
Processing: Apache Spark/Flink
Storage: Data Lake (S3/HDFS)
Warehouse: Snowflake/BigQuery
Analytics: Jupyter Notebooks
Visualization: Tableau/PowerBI
Orchestration: Apache Airflow`,
    implementationSteps: [
      'Design data architecture and select appropriate tools',
      'Set up data ingestion pipelines with Kafka/Spark',
      'Implement data processing workflows with Spark/Flink',
      'Create data warehousing solution with proper schemas',
      'Build analytics and machine learning models',
      'Develop data visualization dashboards',
      'Implement monitoring and alerting systems',
      'Deploy and optimize for production scale'
    ],
    requirements: {
      technical: [
        'Stargate computing infrastructure',
        'Apache Kafka/Spark setup',
        'Data warehouse solution',
        'Python/R for analytics',
        'Docker and Kubernetes',
        'Monitoring tools (Prometheus, Grafana)'
      ],
      team: [
        'Data engineer (2-3)',
        'Data scientist (2)',
        'DevOps engineer (1)',
        'Analytics engineer (1)',
        'Data architect (1)'
      ]
    }
  },
  {
    id: 'cloud-migration-template',
    name: 'Cloud Migration Template',
    description: 'Complete cloud migration strategy and implementation using Stargate cloud infrastructure.',
    icon: '☁️',
    category: 'Cloud Infrastructure',
    difficulty: 'Advanced',
    estimatedTime: '6-8 weeks',
    teamSize: '6-10 developers',
    budget: '$150K - $300K',
    features: [
      'Legacy System Migration',
      'Cloud-native Architecture',
      'Microservices Implementation',
      'Container Orchestration',
      'Auto-scaling',
      'Disaster Recovery'
    ],
    architecture: `Legacy Systems: Monolithic Applications
Migration Strategy: Strangler Fig Pattern
Cloud Platform: Stargate Cloud Infrastructure
Containers: Docker + Kubernetes
API Gateway: Kong/AWS API Gateway
Service Mesh: Istio
Monitoring: Prometheus + Grafana
CI/CD: GitLab CI/Jenkins`,
    implementationSteps: [
      'Assess current infrastructure and create migration plan',
      'Set up Stargate cloud infrastructure and networking',
      'Containerize existing applications with Docker',
      'Implement microservices architecture and API gateway',
      'Set up Kubernetes orchestration and service mesh',
      'Migrate data and implement backup strategies',
      'Configure monitoring, logging, and alerting',
      'Implement CI/CD pipelines and testing strategies',
      'Perform load testing and optimization',
      'Execute production migration with rollback plans'
    ],
    requirements: {
      technical: [
        'Stargate cloud infrastructure access',
        'Docker and Kubernetes expertise',
        'Microservices architecture knowledge',
        'CI/CD pipeline tools',
        'Monitoring and logging solutions',
        'Database migration tools'
      ],
      team: [
        'Cloud architect (1)',
        'DevOps engineers (2-3)',
        'Backend developers (3-4)',
        'Database administrator (1)',
        'Security engineer (1)',
        'Project manager (1)'
      ]
    }
  },
  {
    id: 'enterprise-ai-template',
    name: 'Enterprise AI Platform',
    description: 'Comprehensive AI platform for enterprise use cases with advanced security and compliance.',
    icon: '🏢',
    category: 'Enterprise Solutions',
    difficulty: 'Expert',
    estimatedTime: '8-12 weeks',
    teamSize: '8-12 developers',
    budget: '$300K - $500K',
    features: [
      'Multi-tenant Architecture',
      'Advanced Security & Compliance',
      'AI Model Management',
      'Enterprise Integration',
      'Audit & Compliance Logging',
      'High Availability'
    ],
    architecture: `Frontend: React/Angular Enterprise UI
Backend: Microservices (Node.js/Python)
AI Platform: TensorFlow/PyTorch
Database: PostgreSQL + Redis
Message Queue: Apache Kafka
API Gateway: Kong/AWS API Gateway
Security: OAuth2/JWT + RBAC
Monitoring: ELK Stack + Prometheus`,
    implementationSteps: [
      'Design enterprise architecture with security requirements',
      'Implement multi-tenant data isolation and access controls',
      'Set up AI model training and deployment pipelines',
      'Create enterprise integration APIs and connectors',
      'Implement comprehensive security and compliance features',
      'Build admin dashboards and user management systems',
      'Set up monitoring, logging, and audit trails',
      'Implement disaster recovery and backup strategies',
      'Conduct security audits and penetration testing',
      'Deploy with enterprise-grade monitoring and support'
    ],
    requirements: {
      technical: [
        'Stargate enterprise infrastructure',
        'Enterprise security frameworks',
        'AI/ML platform expertise',
        'Microservices architecture',
        'Compliance tools (SOC2, GDPR)',
        'Enterprise monitoring solutions'
      ],
      team: [
        'Enterprise architect (1)',
        'AI/ML engineers (3-4)',
        'Security engineers (2)',
        'Backend developers (4-5)',
        'DevOps engineers (2)',
        'Compliance specialist (1)',
        'Project manager (1)'
      ]
    }
  },
  {
    id: 'startup-mvp-template',
    name: 'Startup MVP Template',
    description: 'Rapid MVP development template for startups using Stargate infrastructure.',
    icon: '🚀',
    category: 'Startup Solutions',
    difficulty: 'Beginner',
    estimatedTime: '2-4 weeks',
    teamSize: '2-4 developers',
    budget: '$20K - $50K',
    features: [
      'Rapid Prototyping',
      'Basic AI Integration',
      'Scalable Backend',
      'Modern Frontend',
      'Analytics Dashboard',
      'Quick Deployment'
    ],
    architecture: `Frontend: React/Next.js
Backend: Node.js/Express
Database: MongoDB/PostgreSQL
AI: OpenAI API Integration
Authentication: Auth0/Firebase
Hosting: Vercel/Netlify
Database: MongoDB Atlas
Monitoring: Basic Analytics`,
    implementationSteps: [
      'Set up development environment and project structure',
      'Implement basic backend API with authentication',
      'Create responsive frontend with modern UI components',
      'Integrate AI services using OpenAI API',
      'Set up database and implement basic CRUD operations',
      'Add analytics and user tracking',
      'Deploy to production with basic monitoring',
      'Implement feedback collection and iteration system'
    ],
    requirements: {
      technical: [
        'Stargate basic infrastructure',
        'Node.js/React development skills',
        'Database setup (MongoDB/PostgreSQL)',
        'OpenAI API access',
        'Basic deployment knowledge',
        'Version control (Git)'
      ],
      team: [
        'Full-stack developer (1-2)',
        'Frontend developer (1)',
        'Product manager (1)',
        'Designer (1) - optional'
      ]
    }
  },
  {
    id: 'iot-analytics-template',
    name: 'IoT Analytics Template',
    description: 'IoT data collection and analytics platform using Stargate edge computing capabilities.',
    icon: '🌐',
    category: 'Data Analytics',
    difficulty: 'Intermediate',
    estimatedTime: '3-5 weeks',
    teamSize: '4-6 developers',
    budget: '$75K - $150K',
    features: [
      'IoT Device Integration',
      'Real-time Data Streaming',
      'Edge Computing',
      'Predictive Analytics',
      'Alert System',
      'Data Visualization'
    ],
    architecture: `IoT Devices: Sensors, Actuators
Edge Computing: Stargate Edge Nodes
Streaming: Apache Kafka/MQTT
Processing: Apache Spark Streaming
Storage: InfluxDB/Time Series DB
Analytics: Python/R + Jupyter
Visualization: Grafana/Dashboard
Cloud: Stargate Cloud Infrastructure`,
    implementationSteps: [
      'Set up IoT device connectivity and data collection',
      'Implement edge computing nodes with Stargate infrastructure',
      'Create real-time data streaming pipelines',
      'Build time-series database for IoT data storage',
      'Develop predictive analytics models',
      'Create real-time monitoring dashboards',
      'Implement alerting and notification systems',
      'Deploy and optimize for edge computing performance'
    ],
    requirements: {
      technical: [
        'Stargate edge computing infrastructure',
        'IoT protocols (MQTT, CoAP)',
        'Time-series database (InfluxDB)',
        'Streaming data processing',
        'Edge computing frameworks',
        'Real-time analytics tools'
      ],
      team: [
        'IoT engineer (1-2)',
        'Data engineer (1-2)',
        'Backend developer (1)',
        'DevOps engineer (1)',
        'Data scientist (1)'
      ]
    }
  }
])

// Computed properties
const filteredTemplates = computed(() => {
  let filtered = templates.value

  // Filter by category
  if (selectedCategory.value !== 'all') {
    const category = categories.value.find(c => c.id === selectedCategory.value)
    if (category) {
      filtered = filtered.filter(template => template.category === category.name)
    }
  }

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(template => 
      template.name.toLowerCase().includes(query) ||
      template.description.toLowerCase().includes(query) ||
      template.features.some(feature => feature.toLowerCase().includes(query))
    )
  }

  return filtered
})

// Methods
const previewTemplate = (template: Template) => {
  selectedTemplate.value = template
}

const downloadTemplate = (template: Template) => {
  // Create a mock download functionality
  const templateData = {
    name: template.name,
    description: template.description,
    architecture: template.architecture,
    implementationSteps: template.implementationSteps,
    requirements: template.requirements,
    features: template.features,
    metadata: {
      difficulty: template.difficulty,
      estimatedTime: template.estimatedTime,
      teamSize: template.teamSize,
      budget: template.budget
    }
  }

  // Create and download JSON file
  const dataStr = JSON.stringify(templateData, null, 2)
  const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr)
  
  const exportFileDefaultName = `${template.id}-template.json`
  
  const linkElement = document.createElement('a')
  linkElement.setAttribute('href', dataUri)
  linkElement.setAttribute('download', exportFileDefaultName)
  linkElement.click()

  // Show success message
  alert(`Template "${template.name}" downloaded successfully!`)
}
</script>
