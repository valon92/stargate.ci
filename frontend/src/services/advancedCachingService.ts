// Advanced Caching Service for Stargate.ci
import { ref, computed } from 'vue'

export interface CacheEntry {
  key: string
  value: any
  type: 'api' | 'static' | 'dynamic' | 'user' | 'session'
  ttl: number
  createdAt: string
  expiresAt: string
  accessCount: number
  lastAccessed: string
  size: number
  tags: string[]
  priority: 'low' | 'medium' | 'high' | 'critical'
  dependencies?: string[]
}

export interface CacheConfig {
  maxSize: number
  maxEntries: number
  defaultTtl: number
  cleanupInterval: number
  compressionEnabled: boolean
  encryptionEnabled: boolean
  strategies: {
    api: 'cache-first' | 'network-first' | 'stale-while-revalidate'
    static: 'cache-first' | 'network-first'
    dynamic: 'network-first' | 'stale-while-revalidate'
    user: 'cache-first' | 'network-first'
    session: 'memory-only' | 'persistent'
  }
  invalidation: {
    enabled: boolean
    strategies: ('time-based' | 'dependency-based' | 'tag-based' | 'manual')[]
    maxAge: number
  }
  analytics: {
    enabled: boolean
    trackHits: boolean
    trackMisses: boolean
    trackSize: boolean
  }
}

export interface CacheStats {
  totalEntries: number
  totalSize: number
  hitRate: number
  missRate: number
  averageAccessTime: number
  evictionCount: number
  compressionRatio: number
  memoryUsage: number
  topKeys: Array<{
    key: string
    accessCount: number
    size: number
    lastAccessed: string
  }>
  typeDistribution: Array<{
    type: string
    count: number
    size: number
    hitRate: number
  }>
  performanceMetrics: {
    averageGetTime: number
    averageSetTime: number
    averageDeleteTime: number
    cacheEfficiency: number
  }
}

export interface CacheInvalidationRule {
  id: string
  name: string
  type: 'time-based' | 'dependency-based' | 'tag-based' | 'pattern-based'
  pattern: string
  ttl?: number
  dependencies?: string[]
  tags?: string[]
  enabled: boolean
  lastTriggered?: string
  triggerCount: number
}

class AdvancedCachingService {
  private cache = ref<Map<string, CacheEntry>>(new Map())
  private config = ref<CacheConfig>({
    maxSize: 50 * 1024 * 1024, // 50MB
    maxEntries: 10000,
    defaultTtl: 3600000, // 1 hour
    cleanupInterval: 300000, // 5 minutes
    compressionEnabled: true,
    encryptionEnabled: false,
    strategies: {
      api: 'stale-while-revalidate',
      static: 'cache-first',
      dynamic: 'network-first',
      user: 'cache-first',
      session: 'memory-only'
    },
    invalidation: {
      enabled: true,
      strategies: ['time-based', 'dependency-based', 'tag-based'],
      maxAge: 86400000 // 24 hours
    },
    analytics: {
      enabled: true,
      trackHits: true,
      trackMisses: true,
      trackSize: true
    }
  })
  private invalidationRules = ref<CacheInvalidationRule[]>([])
  private stats = ref<CacheStats>({
    totalEntries: 0,
    totalSize: 0,
    hitRate: 0,
    missRate: 0,
    averageAccessTime: 0,
    evictionCount: 0,
    compressionRatio: 0,
    memoryUsage: 0,
    topKeys: [],
    typeDistribution: [],
    performanceMetrics: {
      averageGetTime: 0,
      averageSetTime: 0,
      averageDeleteTime: 0,
      cacheEfficiency: 0
    }
  })

  private hits = 0
  private misses = 0
  private cleanupTimer: number | null = null

  constructor() {
    this.initializeCache()
    this.startCleanupTimer()
    this.loadMockData()
  }

  // Core Cache Operations
  async get<T = any>(key: string, type: CacheEntry['type'] = 'dynamic'): Promise<T | null> {
    const startTime = performance.now()
    
    try {
      const entry = this.cache.value.get(key)
      
      if (!entry) {
        this.misses++
        this.updateStats()
        return null
      }

      // Check if expired
      if (new Date() > new Date(entry.expiresAt)) {
        this.cache.value.delete(key)
        this.misses++
        this.updateStats()
        return null
      }

      // Update access statistics
      entry.accessCount++
      entry.lastAccessed = new Date().toISOString()
      
      this.hits++
      this.updateStats()
      
      const accessTime = performance.now() - startTime
      this.updatePerformanceMetrics('get', accessTime)
      
      return entry.value as T
    } catch (error) {
      console.error('Cache get error:', error)
      this.misses++
      this.updateStats()
      return null
    }
  }

  async set<T = any>(
    key: string,
    value: T,
    options: {
      ttl?: number
      type?: CacheEntry['type']
      tags?: string[]
      priority?: CacheEntry['priority']
      dependencies?: string[]
    } = {}
  ): Promise<boolean> {
    const startTime = performance.now()
    
    try {
      const {
        ttl = this.config.value.defaultTtl,
        type = 'dynamic',
        tags = [],
        priority = 'medium',
        dependencies = []
      } = options

      const serializedValue = JSON.stringify(value)
      const size = new Blob([serializedValue]).size
      
      // Check size limits
      if (size > this.config.value.maxSize) {
        console.warn('Cache entry too large:', key, size)
        return false
      }

      if (this.cache.value.size >= this.config.value.maxEntries) {
        await this.evictEntries()
      }

      const now = new Date()
      const expiresAt = new Date(now.getTime() + ttl)

      const entry: CacheEntry = {
        key,
        value,
        type,
        ttl,
        createdAt: now.toISOString(),
        expiresAt: expiresAt.toISOString(),
        accessCount: 0,
        lastAccessed: now.toISOString(),
        size,
        tags,
        priority,
        dependencies
      }

      this.cache.value.set(key, entry)
      this.updateStats()
      
      const setTime = performance.now() - startTime
      this.updatePerformanceMetrics('set', setTime)
      
      return true
    } catch (error) {
      console.error('Cache set error:', error)
      return false
    }
  }

  async delete(key: string): Promise<boolean> {
    const startTime = performance.now()
    
    try {
      const deleted = this.cache.value.delete(key)
      this.updateStats()
      
      const deleteTime = performance.now() - startTime
      this.updatePerformanceMetrics('delete', deleteTime)
      
      return deleted
    } catch (error) {
      console.error('Cache delete error:', error)
      return false
    }
  }

  async clear(type?: CacheEntry['type']): Promise<number> {
    try {
      let deletedCount = 0
      
      if (type) {
        for (const [key, entry] of this.cache.value.entries()) {
          if (entry.type === type) {
            this.cache.value.delete(key)
            deletedCount++
          }
        }
      } else {
        deletedCount = this.cache.value.size
        this.cache.value.clear()
      }
      
      this.updateStats()
      return deletedCount
    } catch (error) {
      console.error('Cache clear error:', error)
      return 0
    }
  }

  // Advanced Operations
  async invalidateByTag(tag: string): Promise<number> {
    try {
      let invalidatedCount = 0
      
      for (const [key, entry] of this.cache.value.entries()) {
        if (entry.tags.includes(tag)) {
          this.cache.value.delete(key)
          invalidatedCount++
        }
      }
      
      this.updateStats()
      return invalidatedCount
    } catch (error) {
      console.error('Cache invalidation error:', error)
      return 0
    }
  }

  async invalidateByPattern(pattern: string): Promise<number> {
    try {
      let invalidatedCount = 0
      const regex = new RegExp(pattern)
      
      for (const [key] of this.cache.value.entries()) {
        if (regex.test(key)) {
          this.cache.value.delete(key)
          invalidatedCount++
        }
      }
      
      this.updateStats()
      return invalidatedCount
    } catch (error) {
      console.error('Cache pattern invalidation error:', error)
      return 0
    }
  }

  async invalidateByDependency(dependency: string): Promise<number> {
    try {
      let invalidatedCount = 0
      
      for (const [key, entry] of this.cache.value.entries()) {
        if (entry.dependencies?.includes(dependency)) {
          this.cache.value.delete(key)
          invalidatedCount++
        }
      }
      
      this.updateStats()
      return invalidatedCount
    } catch (error) {
      console.error('Cache dependency invalidation error:', error)
      return 0
    }
  }

  // Cache Warming
  async warmCache(keys: string[], fetcher: (key: string) => Promise<any>): Promise<number> {
    try {
      let warmedCount = 0
      
      for (const key of keys) {
        if (!this.cache.value.has(key)) {
          try {
            const value = await fetcher(key)
            await this.set(key, value, { type: 'api' })
            warmedCount++
          } catch (error) {
            console.error(`Failed to warm cache for key ${key}:`, error)
          }
        }
      }
      
      return warmedCount
    } catch (error) {
      console.error('Cache warming error:', error)
      return 0
    }
  }

  // Configuration Management
  async getConfig(): Promise<CacheConfig> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve({ ...this.config.value })
      }, 300)
    })
  }

  async updateConfig(updates: Partial<CacheConfig>): Promise<CacheConfig> {
    return new Promise(resolve => {
      setTimeout(() => {
        this.config.value = { ...this.config.value, ...updates }
        this.saveState()
        resolve({ ...this.config.value })
      }, 500)
    })
  }

  // Analytics and Stats
  async getStats(): Promise<CacheStats> {
    return new Promise(resolve => {
      setTimeout(() => {
        this.updateStats()
        resolve({ ...this.stats.value })
      }, 300)
    })
  }

  // Invalidation Rules Management
  async getInvalidationRules(): Promise<CacheInvalidationRule[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve([...this.invalidationRules.value])
      }, 300)
    })
  }

  async createInvalidationRule(rule: Omit<CacheInvalidationRule, 'id' | 'triggerCount'>): Promise<CacheInvalidationRule> {
    return new Promise(resolve => {
      setTimeout(() => {
        const newRule: CacheInvalidationRule = {
          ...rule,
          id: `rule_${Date.now()}`,
          triggerCount: 0
        }
        
        this.invalidationRules.value.unshift(newRule)
        this.saveState()
        resolve(newRule)
      }, 500)
    })
  }

  async triggerInvalidationRule(ruleId: string): Promise<number> {
    return new Promise(resolve => {
      setTimeout(() => {
        const rule = this.invalidationRules.value.find(r => r.id === ruleId)
        if (!rule) {
          resolve(0)
          return
        }

        let invalidatedCount = 0
        
        switch (rule.type) {
          case 'time-based':
            // Invalidate entries older than TTL
            for (const [key, entry] of this.cache.value.entries()) {
              if (new Date() > new Date(entry.expiresAt)) {
                this.cache.value.delete(key)
                invalidatedCount++
              }
            }
            break
            
          case 'tag-based':
            if (rule.tags) {
              for (const tag of rule.tags) {
                invalidatedCount += this.invalidateByTagSync(tag)
              }
            }
            break
            
          case 'pattern-based':
            invalidatedCount = this.invalidateByPatternSync(rule.pattern)
            break
            
          case 'dependency-based':
            if (rule.dependencies) {
              for (const dep of rule.dependencies) {
                invalidatedCount += this.invalidateByDependencySync(dep)
              }
            }
            break
        }

        rule.triggerCount++
        rule.lastTriggered = new Date().toISOString()
        this.saveState()
        
        resolve(invalidatedCount)
      }, 1000)
    })
  }

  // Helper Methods
  private invalidateByTagSync(tag: string): number {
    let invalidatedCount = 0
    
    for (const [key, entry] of this.cache.value.entries()) {
      if (entry.tags.includes(tag)) {
        this.cache.value.delete(key)
        invalidatedCount++
      }
    }
    
    this.updateStats()
    return invalidatedCount
  }

  private invalidateByPatternSync(pattern: string): number {
    let invalidatedCount = 0
    const regex = new RegExp(pattern)
    
    for (const [key] of this.cache.value.entries()) {
      if (regex.test(key)) {
        this.cache.value.delete(key)
        invalidatedCount++
      }
    }
    
    this.updateStats()
    return invalidatedCount
  }

  private invalidateByDependencySync(dependency: string): number {
    let invalidatedCount = 0
    
    for (const [key, entry] of this.cache.value.entries()) {
      if (entry.dependencies?.includes(dependency)) {
        this.cache.value.delete(key)
        invalidatedCount++
      }
    }
    
    this.updateStats()
    return invalidatedCount
  }

  private async evictEntries(): Promise<void> {
    const entries = Array.from(this.cache.value.entries())
    
    // Sort by priority and last access time
    entries.sort(([, a], [, b]) => {
      const priorityOrder = { critical: 4, high: 3, medium: 2, low: 1 }
      const aPriority = priorityOrder[a.priority]
      const bPriority = priorityOrder[b.priority]
      
      if (aPriority !== bPriority) {
        return bPriority - aPriority
      }
      
      return new Date(a.lastAccessed).getTime() - new Date(b.lastAccessed).getTime()
    })

    // Remove 10% of entries (lowest priority and oldest)
    const toRemove = Math.ceil(entries.length * 0.1)
    for (let i = 0; i < toRemove; i++) {
      this.cache.value.delete(entries[i][0])
    }
    
    this.stats.value.evictionCount += toRemove
  }

  private updateStats(): void {
    const entries = Array.from(this.cache.value.values())
    
    this.stats.value.totalEntries = entries.length
    this.stats.value.totalSize = entries.reduce((sum, entry) => sum + entry.size, 0)
    
    const totalRequests = this.hits + this.misses
    this.stats.value.hitRate = totalRequests > 0 ? (this.hits / totalRequests) * 100 : 0
    this.stats.value.missRate = totalRequests > 0 ? (this.misses / totalRequests) * 100 : 0
    
    // Calculate type distribution
    const typeStats = entries.reduce((acc, entry) => {
      if (!acc[entry.type]) {
        acc[entry.type] = { count: 0, size: 0, hits: 0 }
      }
      acc[entry.type].count++
      acc[entry.type].size += entry.size
      acc[entry.type].hits += entry.accessCount
      return acc
    }, {} as Record<string, { count: number; size: number; hits: number }>)
    
    this.stats.value.typeDistribution = Object.entries(typeStats).map(([type, stats]) => ({
      type,
      count: stats.count,
      size: stats.size,
      hitRate: stats.hits > 0 ? (stats.hits / (stats.hits + stats.count)) * 100 : 0
    }))
    
    // Top keys by access count
    this.stats.value.topKeys = entries
      .sort((a, b) => b.accessCount - a.accessCount)
      .slice(0, 10)
      .map(entry => ({
        key: entry.key,
        accessCount: entry.accessCount,
        size: entry.size,
        lastAccessed: entry.lastAccessed
      }))
  }

  private updatePerformanceMetrics(operation: 'get' | 'set' | 'delete', time: number): void {
    const metrics = this.stats.value.performanceMetrics
    
    switch (operation) {
      case 'get':
        metrics.averageGetTime = (metrics.averageGetTime + time) / 2
        break
      case 'set':
        metrics.averageSetTime = (metrics.averageSetTime + time) / 2
        break
      case 'delete':
        metrics.averageDeleteTime = (metrics.averageDeleteTime + time) / 2
        break
    }
    
    metrics.cacheEfficiency = this.stats.value.hitRate
  }

  private startCleanupTimer(): void {
    this.cleanupTimer = window.setInterval(() => {
      this.cleanupExpiredEntries()
    }, this.config.value.cleanupInterval)
  }

  private cleanupExpiredEntries(): void {
    const now = new Date()
    let cleanedCount = 0
    
    for (const [key, entry] of this.cache.value.entries()) {
      if (now > new Date(entry.expiresAt)) {
        this.cache.value.delete(key)
        cleanedCount++
      }
    }
    
    if (cleanedCount > 0) {
      this.updateStats()
    }
  }

  private initializeCache(): void {
    // Initialize cache with default configuration
    this.cache.value = new Map()
  }

  // Data Persistence
  private saveState(): void {
    try {
      const cacheData = Array.from(this.cache.value.entries())
      localStorage.setItem('advanced-cache', JSON.stringify(cacheData))
      localStorage.setItem('cache-config', JSON.stringify(this.config.value))
      localStorage.setItem('cache-invalidation-rules', JSON.stringify(this.invalidationRules.value))
      localStorage.setItem('cache-stats', JSON.stringify(this.stats.value))
    } catch (error) {
      console.error('Failed to save cache state:', error)
    }
  }

  private loadState(): void {
    try {
      const cacheData = localStorage.getItem('advanced-cache')
      if (cacheData) {
        const entries = JSON.parse(cacheData)
        this.cache.value = new Map(entries)
      }
      
      const config = localStorage.getItem('cache-config')
      if (config) {
        this.config.value = JSON.parse(config)
      }
      
      const rules = localStorage.getItem('cache-invalidation-rules')
      if (rules) {
        this.invalidationRules.value = JSON.parse(rules)
      }
      
      const stats = localStorage.getItem('cache-stats')
      if (stats) {
        this.stats.value = JSON.parse(stats)
      }
    } catch (error) {
      console.error('Failed to load cache state:', error)
    }
  }

  private loadMockData(): void {
    // Load existing data first
    this.loadState()
    
    // Add mock invalidation rules if none exist
    if (this.invalidationRules.value.length === 0) {
      const mockRules: CacheInvalidationRule[] = [
        {
          id: 'rule_1',
          name: 'API Cache Cleanup',
          type: 'time-based',
          pattern: 'api:*',
          ttl: 3600000, // 1 hour
          enabled: true,
          triggerCount: 0
        },
        {
          id: 'rule_2',
          name: 'User Data Invalidation',
          type: 'tag-based',
          pattern: 'user:*',
          tags: ['user-data'],
          enabled: true,
          triggerCount: 0
        },
        {
          id: 'rule_3',
          name: 'Static Asset Cleanup',
          type: 'pattern-based',
          pattern: 'static:.*\\.(css|js|png|jpg|gif)$',
          enabled: true,
          triggerCount: 0
        }
      ]
      
      this.invalidationRules.value = mockRules
    }
    
    this.saveState()
  }

  // Cleanup
  destroy(): void {
    if (this.cleanupTimer) {
      clearInterval(this.cleanupTimer)
    }
  }
}

export const advancedCachingService = new AdvancedCachingService()
