<template>
  <div class="email-test-panel">
    <div class="panel-header">
      <h3>Email Notification Test Panel</h3>
      <p>Test email notifications for subscribers</p>
    </div>
    
    <div class="stats-section">
      <h4>Subscription Statistics</h4>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-number">{{ stats.total }}</div>
          <div class="stat-label">Total Subscribers</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{ stats.active }}</div>
          <div class="stat-label">Active Subscribers</div>
        </div>
      </div>
      
      <div class="interests-stats">
        <h5>Subscribers by Interest:</h5>
        <div class="interest-tags">
          <span 
            v-for="(count, interest) in stats.byInterest" 
            :key="interest"
            class="interest-tag"
          >
            {{ formatInterest(interest) }}: {{ count }}
          </span>
        </div>
      </div>
    </div>
    
    <div class="test-section">
      <h4>Send Test Notifications</h4>
      
      <div class="form-group">
        <label>Notification Type:</label>
        <select v-model="testNotification.type">
          <option value="newsletter">Newsletter</option>
          <option value="update">Update</option>
          <option value="announcement">Announcement</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>Subject:</label>
        <input v-model="testNotification.subject" type="text" placeholder="Enter email subject">
      </div>
      
      <div class="form-group">
        <label>Content:</label>
        <textarea v-model="testNotification.content" rows="4" placeholder="Enter email content"></textarea>
      </div>
      
      <div v-if="testNotification.type === 'update'" class="form-group">
        <label>Target Interests:</label>
        <div class="checkbox-group">
          <label><input type="checkbox" value="stargate" v-model="testNotification.interests"> Stargate Project</label>
          <label><input type="checkbox" value="cristal" v-model="testNotification.interests"> Cristal Intelligence</label>
          <label><input type="checkbox" value="ai-ethics" v-model="testNotification.interests"> AI Ethics</label>
          <label><input type="checkbox" value="research" v-model="testNotification.interests"> Research</label>
        </div>
      </div>
      
      <button @click="sendTestNotification" :disabled="isSending" class="send-button">
        {{ isSending ? 'Sending...' : 'Send Test Notification' }}
      </button>
    </div>
    
    <div v-if="lastResult" class="result-section">
      <h4>Last Result:</h4>
      <div class="result-message" :class="lastResult.success ? 'success' : 'error'">
        {{ lastResult.message }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { emailNotificationService } from '../services/emailNotificationService'

// State
const stats = ref({
  total: 0,
  active: 0,
  byInterest: {} as Record<string, number>,
  byRole: {} as Record<string, number>
})

const isSending = ref(false)
const lastResult = ref<{ success: boolean; message: string } | null>(null)

const testNotification = reactive({
  type: 'newsletter',
  subject: '',
  content: '',
  interests: [] as string[]
})

// Methods
const loadStats = () => {
  stats.value = emailNotificationService.getSubscriptionStats()
}

const formatInterest = (interest: string): string => {
  const interestMap: Record<string, string> = {
    'stargate': 'Stargate Project',
    'cristal': 'Cristal Intelligence',
    'ai-ethics': 'AI Ethics',
    'research': 'Research'
  }
  return interestMap[interest] || interest
}

const sendTestNotification = async () => {
  if (!testNotification.subject || !testNotification.content) {
    lastResult.value = {
      success: false,
      message: 'Please fill in subject and content'
    }
    return
  }
  
  isSending.value = true
  lastResult.value = null
  
  try {
    if (testNotification.type === 'newsletter') {
      await emailNotificationService.sendNewsletter(
        testNotification.subject,
        testNotification.content
      )
      lastResult.value = {
        success: true,
        message: 'Newsletter sent to all active subscribers!'
      }
    } else if (testNotification.type === 'update') {
      if (testNotification.interests.length === 0) {
        lastResult.value = {
          success: false,
          message: 'Please select at least one interest for update notifications'
        }
        return
      }
      
      await emailNotificationService.sendUpdateNotification(
        testNotification.interests,
        testNotification.subject,
        testNotification.content
      )
      lastResult.value = {
        success: true,
        message: `Update notification sent to subscribers interested in: ${testNotification.interests.map(formatInterest).join(', ')}`
      }
    }
    
    // Reload stats
    loadStats()
    
  } catch (error) {
    lastResult.value = {
      success: false,
      message: 'Failed to send notification: ' + (error as Error).message
    }
  } finally {
    isSending.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadStats()
})
</script>

<style scoped>
.email-test-panel {
  background: #1a1a1a;
  border: 1px solid #333;
  border-radius: 10px;
  padding: 20px;
  margin: 20px 0;
  color: white;
}

.panel-header {
  text-align: center;
  margin-bottom: 30px;
}

.panel-header h3 {
  color: #4f46e5;
  margin-bottom: 10px;
}

.stats-section {
  margin-bottom: 30px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}

.stat-card {
  background: #2a2a2a;
  padding: 15px;
  border-radius: 8px;
  text-align: center;
}

.stat-number {
  font-size: 24px;
  font-weight: bold;
  color: #4f46e5;
}

.stat-label {
  font-size: 14px;
  color: #9ca3af;
}

.interests-stats h5 {
  color: #4f46e5;
  margin-bottom: 10px;
}

.interest-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.interest-tag {
  background: #4f46e5;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
}

.test-section {
  margin-bottom: 30px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  color: #e5e7eb;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #333;
  border-radius: 5px;
  background: #2a2a2a;
  color: white;
}

.checkbox-group {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 10px;
}

.checkbox-group label {
  display: flex;
  align-items: center;
  margin-bottom: 0;
}

.checkbox-group input {
  width: auto;
  margin-right: 8px;
}

.send-button {
  background: #4f46e5;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
}

.send-button:hover:not(:disabled) {
  background: #4338ca;
}

.send-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.result-section {
  margin-top: 20px;
}

.result-message {
  padding: 15px;
  border-radius: 8px;
  font-weight: bold;
}

.result-message.success {
  background: #065f46;
  color: #10b981;
}

.result-message.error {
  background: #7f1d1d;
  color: #f87171;
}
</style>
