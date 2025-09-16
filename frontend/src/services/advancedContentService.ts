/**
 * Advanced Content Service - Handles video content, interactive tutorials, and rich media
 * Provides video streaming, interactive content, progress tracking, and content analytics
 */

export interface VideoContent {
  id: string
  title: string
  description: string
  thumbnail: string
  videoUrl: string
  duration: number // in seconds
  quality: '360p' | '480p' | '720p' | '1080p' | '4K'
  format: 'mp4' | 'webm' | 'ogg'
  size: number // in bytes
  category: string
  tags: string[]
  author: string
  createdAt: string
  updatedAt: string
  views: number
  likes: number
  dislikes: number
  isPublic: boolean
  isFeatured: boolean
  transcript?: string
  subtitles?: SubtitleTrack[]
  chapters?: VideoChapter[]
}

export interface SubtitleTrack {
  id: string
  language: string
  label: string
  url: string
  isDefault: boolean
}

export interface VideoChapter {
  id: string
  title: string
  startTime: number // in seconds
  endTime: number // in seconds
  description?: string
}

export interface InteractiveTutorial {
  id: string
  title: string
  description: string
  thumbnail: string
  steps: TutorialStep[]
  estimatedTime: number // in minutes
  difficulty: 'beginner' | 'intermediate' | 'advanced'
  category: string
  tags: string[]
  author: string
  createdAt: string
  updatedAt: string
  completions: number
  rating: number
  isPublic: boolean
  isFeatured: boolean
  prerequisites?: string[]
  learningObjectives: string[]
}

export interface TutorialStep {
  id: string
  title: string
  content: string
  type: 'text' | 'video' | 'interactive' | 'quiz' | 'code' | 'image'
  order: number
  isRequired: boolean
  estimatedTime: number // in minutes
  isCompleted?: boolean
  contentData?: {
    videoUrl?: string
    codeBlock?: string
    imageUrl?: string
    quizData?: QuizData
    interactiveData?: InteractiveData
  }
  hints?: string[]
  resources?: TutorialResource[]
}

export interface QuizData {
  question: string
  options: string[]
  correctAnswer: number
  explanation: string
  points: number
}

export interface InteractiveData {
  type: 'simulation' | 'demo' | 'sandbox' | 'game'
  config: Record<string, any>
  instructions: string
  successCriteria: string[]
}

export interface TutorialResource {
  id: string
  title: string
  type: 'document' | 'link' | 'download' | 'video'
  url: string
  description?: string
}

export interface UserProgress {
  userId: string
  contentId: string
  contentType: 'video' | 'tutorial'
  progress: number // percentage 0-100
  currentStep?: number
  completedSteps: string[]
  timeSpent: number // in seconds
  lastAccessed: string
  completedAt?: string
  bookmarked: boolean
  notes?: string
  rating?: number
}

export interface ContentAnalytics {
  contentId: string
  contentType: 'video' | 'tutorial'
  views: number
  uniqueViews: number
  completions: number
  averageProgress: number
  averageTimeSpent: number
  dropOffPoints: DropOffPoint[]
  userEngagement: EngagementMetric[]
  deviceStats: DeviceStats
  geographicStats: GeographicStats
}

export interface DropOffPoint {
  timestamp: number // in seconds
  dropOffRate: number // percentage
  reason?: string
}

export interface EngagementMetric {
  metric: string
  value: number
  timestamp: string
}

export interface DeviceStats {
  desktop: number
  mobile: number
  tablet: number
}

export interface GeographicStats {
  country: string
  views: number
  completions: number
}

export interface ContentPlaylist {
  id: string
  title: string
  description: string
  thumbnail: string
  items: PlaylistItem[]
  author: string
  createdAt: string
  updatedAt: string
  isPublic: boolean
  isFeatured: boolean
  totalDuration: number
  totalItems: number
}

export interface PlaylistItem {
  id: string
  contentType: 'video' | 'tutorial'
  contentId: string
  order: number
  title: string
  duration: number
  thumbnail: string
}

class AdvancedContentService {
  private storageKey = 'stargate_advanced_content'
  private currentUser: any = null

  constructor() {
    this.initializeData()
  }

  private initializeData() {
    const existingData = localStorage.getItem(this.storageKey)
    if (!existingData) {
      const initialData = {
        videos: this.getSampleVideos(),
        tutorials: this.getSampleTutorials(),
        playlists: this.getSamplePlaylists(),
        userProgress: [],
        analytics: [],
        bookmarks: []
      }
      localStorage.setItem(this.storageKey, JSON.stringify(initialData))
    }
  }

  private getSampleVideos(): VideoContent[] {
    return [
      {
        id: 'video_1',
        title: 'Introduction to Stargate AI',
        description: 'Learn the fundamentals of Stargate AI technology and its applications in modern computing.',
        thumbnail: '/api/placeholder/640/360',
        videoUrl: '/api/videos/stargate-intro.mp4',
        duration: 1800, // 30 minutes
        quality: '1080p',
        format: 'mp4',
        size: 500000000, // 500MB
        category: 'AI Fundamentals',
        tags: ['AI', 'Stargate', 'Introduction', 'Technology'],
        author: 'Stargate Team',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
        views: 1250,
        likes: 89,
        dislikes: 3,
        isPublic: true,
        isFeatured: true,
        transcript: 'Welcome to Stargate AI. In this video, we will explore...',
        subtitles: [
          {
            id: 'sub_1',
            language: 'en',
            label: 'English',
            url: '/api/subtitles/stargate-intro-en.vtt',
            isDefault: true
          },
          {
            id: 'sub_2',
            language: 'es',
            label: 'Español',
            url: '/api/subtitles/stargate-intro-es.vtt',
            isDefault: false
          }
        ],
        chapters: [
          {
            id: 'ch_1',
            title: 'What is Stargate AI?',
            startTime: 0,
            endTime: 300,
            description: 'Introduction to Stargate AI concept'
          },
          {
            id: 'ch_2',
            title: 'Key Features',
            startTime: 300,
            endTime: 900,
            description: 'Exploring main features and capabilities'
          },
          {
            id: 'ch_3',
            title: 'Real-world Applications',
            startTime: 900,
            endTime: 1800,
            description: 'Practical applications and use cases'
          }
        ]
      },
      {
        id: 'video_2',
        title: 'Cristal Intelligence Deep Dive',
        description: 'Advanced concepts in Cristal Intelligence and its implementation in cloud computing.',
        thumbnail: '/api/placeholder/640/360',
        videoUrl: '/api/videos/cristal-intelligence.mp4',
        duration: 2400, // 40 minutes
        quality: '4K',
        format: 'mp4',
        size: 1200000000, // 1.2GB
        category: 'Advanced AI',
        tags: ['Cristal Intelligence', 'Cloud Computing', 'Advanced', 'AI'],
        author: 'Dr. Sarah Chen',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
        views: 890,
        likes: 67,
        dislikes: 2,
        isPublic: true,
        isFeatured: true,
        transcript: 'Cristal Intelligence represents a paradigm shift...',
        subtitles: [
          {
            id: 'sub_3',
            language: 'en',
            label: 'English',
            url: '/api/subtitles/cristal-intelligence-en.vtt',
            isDefault: true
          }
        ],
        chapters: [
          {
            id: 'ch_4',
            title: 'Cristal Computing Principles',
            startTime: 0,
            endTime: 600,
            description: 'Understanding cristal computing fundamentals'
          },
          {
            id: 'ch_5',
            title: 'Implementation Strategies',
            startTime: 600,
            endTime: 1500,
            description: 'How to implement cristal intelligence'
          },
          {
            id: 'ch_6',
            title: 'Performance Optimization',
            startTime: 1500,
            endTime: 2400,
            description: 'Optimizing cristal intelligence systems'
          }
        ]
      }
    ]
  }

  private getSampleTutorials(): InteractiveTutorial[] {
    return [
      {
        id: 'tutorial_1',
        title: 'Building Your First Stargate Application',
        description: 'Step-by-step guide to creating your first Stargate-powered application.',
        thumbnail: '/api/placeholder/640/360',
        steps: [
          {
            id: 'step_1',
            title: 'Setting Up Your Environment',
            content: 'First, you need to set up your development environment...',
            type: 'text',
            order: 1,
            isRequired: true,
            estimatedTime: 10,
            resources: [
              {
                id: 'res_1',
                title: 'Installation Guide',
                type: 'document',
                url: '/docs/installation',
                description: 'Complete installation instructions'
              }
            ]
          },
          {
            id: 'step_2',
            title: 'Creating Your First Project',
            content: 'Now let\'s create your first Stargate project...',
            type: 'interactive',
            order: 2,
            isRequired: true,
            estimatedTime: 15,
            contentData: {
              interactiveData: {
                type: 'sandbox',
                config: {
                  template: 'stargate-basic',
                  features: ['ai-integration', 'cloud-sync']
                },
                instructions: 'Create a new project using the Stargate template',
                successCriteria: ['Project created successfully', 'AI integration enabled']
              }
            },
            hints: [
              'Use the Stargate CLI to initialize your project',
              'Make sure to enable AI integration during setup'
            ]
          },
          {
            id: 'step_3',
            title: 'Understanding the Code Structure',
            content: 'Let\'s explore the generated code structure...',
            type: 'code',
            order: 3,
            isRequired: true,
            estimatedTime: 20,
            contentData: {
              codeBlock: `// main.js
import { StargateAI } from '@stargate/ai-core';

const ai = new StargateAI({
  apiKey: process.env.STARGATE_API_KEY,
  model: 'cristal-v1'
});

async function main() {
  const result = await ai.process({
    input: 'Hello, Stargate!',
    context: 'greeting'
  });
  
  console.log(result);
}

main().catch(console.error);`
            }
          },
          {
            id: 'step_4',
            title: 'Testing Your Application',
            content: 'Now let\'s test your application...',
            type: 'quiz',
            order: 4,
            isRequired: true,
            estimatedTime: 10,
            contentData: {
              quizData: {
                question: 'What is the main purpose of Stargate AI?',
                options: [
                  'To replace human intelligence',
                  'To enhance computing capabilities with AI',
                  'To create virtual reality experiences',
                  'To manage cloud infrastructure'
                ],
                correctAnswer: 1,
                explanation: 'Stargate AI is designed to enhance computing capabilities by integrating artificial intelligence into cloud infrastructure.',
                points: 10
              }
            }
          }
        ],
        estimatedTime: 55,
        difficulty: 'beginner',
        category: 'Development',
        tags: ['Stargate', 'Development', 'Tutorial', 'Beginner'],
        author: 'Stargate Team',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
        completions: 234,
        rating: 4.8,
        isPublic: true,
        isFeatured: true,
        prerequisites: ['Basic JavaScript knowledge', 'Node.js installed'],
        learningObjectives: [
          'Set up a Stargate development environment',
          'Create a basic Stargate application',
          'Understand Stargate AI integration',
          'Test and deploy Stargate applications'
        ]
      },
      {
        id: 'tutorial_2',
        title: 'Advanced Cristal Intelligence Patterns',
        description: 'Learn advanced patterns and techniques for implementing Cristal Intelligence.',
        thumbnail: '/api/placeholder/640/360',
        steps: [
          {
            id: 'step_5',
            title: 'Understanding Cristal Patterns',
            content: 'Cristal patterns are the foundation of Cristal Intelligence...',
            type: 'video',
            order: 1,
            isRequired: true,
            estimatedTime: 25,
            contentData: {
              videoUrl: '/api/videos/cristal-patterns.mp4'
            }
          },
          {
            id: 'step_6',
            title: 'Implementing Pattern Recognition',
            content: 'Let\'s implement pattern recognition in your application...',
            type: 'interactive',
            order: 2,
            isRequired: true,
            estimatedTime: 30,
            contentData: {
              interactiveData: {
                type: 'simulation',
                config: {
                  scenario: 'pattern-recognition',
                  complexity: 'advanced'
                },
                instructions: 'Implement pattern recognition algorithms',
                successCriteria: ['Patterns identified correctly', 'Performance optimized']
              }
            }
          }
        ],
        estimatedTime: 55,
        difficulty: 'advanced',
        category: 'Advanced AI',
        tags: ['Cristal Intelligence', 'Patterns', 'Advanced', 'AI'],
        author: 'Dr. Michael Rodriguez',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
        completions: 89,
        rating: 4.9,
        isPublic: true,
        isFeatured: false,
        prerequisites: ['Stargate AI fundamentals', 'Machine learning basics'],
        learningObjectives: [
          'Understand Cristal Intelligence patterns',
          'Implement pattern recognition systems',
          'Optimize Cristal Intelligence performance',
          'Apply advanced Cristal techniques'
        ]
      }
    ]
  }

  private getSamplePlaylists(): ContentPlaylist[] {
    return [
      {
        id: 'playlist_1',
        title: 'Stargate AI Complete Course',
        description: 'Complete course covering all aspects of Stargate AI technology.',
        thumbnail: '/api/placeholder/640/360',
        items: [
          {
            id: 'item_1',
            contentType: 'video',
            contentId: 'video_1',
            order: 1,
            title: 'Introduction to Stargate AI',
            duration: 1800,
            thumbnail: '/api/placeholder/320/180'
          },
          {
            id: 'item_2',
            contentType: 'tutorial',
            contentId: 'tutorial_1',
            order: 2,
            title: 'Building Your First Stargate Application',
            duration: 3300,
            thumbnail: '/api/placeholder/320/180'
          },
          {
            id: 'item_3',
            contentType: 'video',
            contentId: 'video_2',
            order: 3,
            title: 'Cristal Intelligence Deep Dive',
            duration: 2400,
            thumbnail: '/api/placeholder/320/180'
          }
        ],
        author: 'Stargate Team',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
        isPublic: true,
        isFeatured: true,
        totalDuration: 7500, // 2 hours 5 minutes
        totalItems: 3
      }
    ]
  }

  private getData() {
    const data = localStorage.getItem(this.storageKey)
    return data ? JSON.parse(data) : {
      videos: [],
      tutorials: [],
      playlists: [],
      userProgress: [],
      analytics: [],
      bookmarks: []
    }
  }

  private saveData(data: any) {
    localStorage.setItem(this.storageKey, JSON.stringify(data))
  }

  // Video Management
  async getVideos(category?: string, featured?: boolean): Promise<VideoContent[]> {
    const data = this.getData()
    let videos = data.videos

    if (category) {
      videos = videos.filter((video: VideoContent) => video.category === category)
    }

    if (featured !== undefined) {
      videos = videos.filter((video: VideoContent) => video.isFeatured === featured)
    }

    return videos.sort((a: VideoContent, b: VideoContent) => 
      new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
    )
  }

  async getVideo(videoId: string): Promise<VideoContent | null> {
    const data = this.getData()
    return data.videos.find((video: VideoContent) => video.id === videoId) || null
  }

  async addVideo(video: Omit<VideoContent, 'id' | 'createdAt' | 'updatedAt' | 'views' | 'likes' | 'dislikes'>): Promise<VideoContent> {
    const data = this.getData()
    const now = new Date().toISOString()
    
    const newVideo: VideoContent = {
      ...video,
      id: `video_${Date.now()}`,
      createdAt: now,
      updatedAt: now,
      views: 0,
      likes: 0,
      dislikes: 0
    }

    data.videos.push(newVideo)
    this.saveData(data)

    return newVideo
  }

  async updateVideo(videoId: string, updates: Partial<VideoContent>): Promise<VideoContent | null> {
    const data = this.getData()
    const index = data.videos.findIndex((video: VideoContent) => video.id === videoId)
    if (index > -1) {
      data.videos[index] = {
        ...data.videos[index],
        ...updates,
        updatedAt: new Date().toISOString()
      }
      this.saveData(data)
      return data.videos[index]
    }
    return null
  }

  async deleteVideo(videoId: string): Promise<boolean> {
    const data = this.getData()
    const index = data.videos.findIndex((video: VideoContent) => video.id === videoId)
    if (index > -1) {
      data.videos.splice(index, 1)
      this.saveData(data)
      return true
    }
    return false
  }

  // Tutorial Management
  async getTutorials(category?: string, difficulty?: string, featured?: boolean): Promise<InteractiveTutorial[]> {
    const data = this.getData()
    let tutorials = data.tutorials

    if (category) {
      tutorials = tutorials.filter((tutorial: InteractiveTutorial) => tutorial.category === category)
    }

    if (difficulty) {
      tutorials = tutorials.filter((tutorial: InteractiveTutorial) => tutorial.difficulty === difficulty)
    }

    if (featured !== undefined) {
      tutorials = tutorials.filter((tutorial: InteractiveTutorial) => tutorial.isFeatured === featured)
    }

    return tutorials.sort((a: InteractiveTutorial, b: InteractiveTutorial) => 
      new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
    )
  }

  async getTutorial(tutorialId: string): Promise<InteractiveTutorial | null> {
    const data = this.getData()
    return data.tutorials.find((tutorial: InteractiveTutorial) => tutorial.id === tutorialId) || null
  }

  async addTutorial(tutorial: Omit<InteractiveTutorial, 'id' | 'createdAt' | 'updatedAt' | 'completions' | 'rating'>): Promise<InteractiveTutorial> {
    const data = this.getData()
    const now = new Date().toISOString()
    
    const newTutorial: InteractiveTutorial = {
      ...tutorial,
      id: `tutorial_${Date.now()}`,
      createdAt: now,
      updatedAt: now,
      completions: 0,
      rating: 0
    }

    data.tutorials.push(newTutorial)
    this.saveData(data)

    return newTutorial
  }

  // Playlist Management
  async getPlaylists(featured?: boolean): Promise<ContentPlaylist[]> {
    const data = this.getData()
    let playlists = data.playlists

    if (featured !== undefined) {
      playlists = playlists.filter((playlist: ContentPlaylist) => playlist.isFeatured === featured)
    }

    return playlists.sort((a: ContentPlaylist, b: ContentPlaylist) => 
      new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
    )
  }

  async getPlaylist(playlistId: string): Promise<ContentPlaylist | null> {
    const data = this.getData()
    return data.playlists.find((playlist: ContentPlaylist) => playlist.id === playlistId) || null
  }

  // User Progress Tracking
  async getUserProgress(userId: string, contentType?: 'video' | 'tutorial'): Promise<UserProgress[]> {
    const data = this.getData()
    let progress = data.userProgress.filter((p: UserProgress) => p.userId === userId)

    if (contentType) {
      progress = progress.filter((p: UserProgress) => p.contentType === contentType)
    }

    return progress
  }

  async updateProgress(userId: string, contentId: string, contentType: 'video' | 'tutorial', progress: number, currentStep?: number): Promise<UserProgress> {
    const data = this.getData()
    const existingIndex = data.userProgress.findIndex((p: UserProgress) => 
      p.userId === userId && p.contentId === contentId
    )

    const progressData: UserProgress = {
      userId,
      contentId,
      contentType,
      progress,
      currentStep,
      completedSteps: [],
      timeSpent: 0,
      lastAccessed: new Date().toISOString(),
      bookmarked: false
    }

    if (existingIndex > -1) {
      data.userProgress[existingIndex] = {
        ...data.userProgress[existingIndex],
        progress,
        currentStep,
        lastAccessed: new Date().toISOString()
      }
    } else {
      data.userProgress.push(progressData)
    }

    this.saveData(data)
    return data.userProgress[existingIndex] || progressData
  }

  async completeContent(userId: string, contentId: string, contentType: 'video' | 'tutorial'): Promise<boolean> {
    const data = this.getData()
    const progressIndex = data.userProgress.findIndex((p: UserProgress) => 
      p.userId === userId && p.contentId === contentId
    )

    if (progressIndex > -1) {
      data.userProgress[progressIndex].progress = 100
      data.userProgress[progressIndex].completedAt = new Date().toISOString()
      this.saveData(data)
      return true
    }

    return false
  }

  // Analytics
  async getContentAnalytics(contentId: string): Promise<ContentAnalytics | null> {
    const data = this.getData()
    return data.analytics.find((analytics: ContentAnalytics) => analytics.contentId === contentId) || null
  }

  async recordView(contentId: string, contentType: 'video' | 'tutorial'): Promise<void> {
    const data = this.getData()
    
    // Update content views
    if (contentType === 'video') {
      const videoIndex = data.videos.findIndex((video: VideoContent) => video.id === contentId)
      if (videoIndex > -1) {
        data.videos[videoIndex].views++
      }
    } else if (contentType === 'tutorial') {
      // Tutorials don't have views, but we can track completions
    }

    this.saveData(data)
  }

  // Bookmarks
  async getBookmarks(userId: string): Promise<UserProgress[]> {
    const data = this.getData()
    return data.userProgress.filter((p: UserProgress) => p.userId === userId && p.bookmarked)
  }

  async toggleBookmark(userId: string, contentId: string): Promise<boolean> {
    const data = this.getData()
    const progressIndex = data.userProgress.findIndex((p: UserProgress) => 
      p.userId === userId && p.contentId === contentId
    )

    if (progressIndex > -1) {
      data.userProgress[progressIndex].bookmarked = !data.userProgress[progressIndex].bookmarked
      this.saveData(data)
      return data.userProgress[progressIndex].bookmarked
    }

    return false
  }

  // Search
  async searchContent(query: string, type?: 'video' | 'tutorial' | 'playlist'): Promise<{
    videos: VideoContent[]
    tutorials: InteractiveTutorial[]
    playlists: ContentPlaylist[]
  }> {
    const data = this.getData()
    const searchTerm = query.toLowerCase()

    const results = {
      videos: [] as VideoContent[],
      tutorials: [] as InteractiveTutorial[],
      playlists: [] as ContentPlaylist[]
    }

    if (!type || type === 'video') {
      results.videos = data.videos.filter((video: VideoContent) =>
        video.title.toLowerCase().includes(searchTerm) ||
        video.description.toLowerCase().includes(searchTerm) ||
        video.tags.some(tag => tag.toLowerCase().includes(searchTerm))
      )
    }

    if (!type || type === 'tutorial') {
      results.tutorials = data.tutorials.filter((tutorial: InteractiveTutorial) =>
        tutorial.title.toLowerCase().includes(searchTerm) ||
        tutorial.description.toLowerCase().includes(searchTerm) ||
        tutorial.tags.some(tag => tag.toLowerCase().includes(searchTerm))
      )
    }

    if (!type || type === 'playlist') {
      results.playlists = data.playlists.filter((playlist: ContentPlaylist) =>
        playlist.title.toLowerCase().includes(searchTerm) ||
        playlist.description.toLowerCase().includes(searchTerm)
      )
    }

    return results
  }

  // Utility Methods
  formatDuration(seconds: number): string {
    const hours = Math.floor(seconds / 3600)
    const minutes = Math.floor((seconds % 3600) / 60)
    const secs = seconds % 60

    if (hours > 0) {
      return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
    }
    return `${minutes}:${secs.toString().padStart(2, '0')}`
  }

  formatFileSize(bytes: number): string {
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    if (bytes === 0) return '0 Bytes'
    const i = Math.floor(Math.log(bytes) / Math.log(1024))
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i]
  }

  setCurrentUser(user: any) {
    this.currentUser = user
  }

  getCurrentUser() {
    return this.currentUser
  }
}

export const advancedContentService = new AdvancedContentService()
