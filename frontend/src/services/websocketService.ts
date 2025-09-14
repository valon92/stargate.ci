// WebSocket Service for Real-time Data Integration
export interface WebSocketMessage {
  type: string;
  data: any;
  timestamp: string;
}

export interface NotificationData {
  id: string;
  title: string;
  message: string;
  type: 'info' | 'success' | 'warning' | 'error';
  read: boolean;
  created_at: string;
}

export interface LiveUpdateData {
  type: 'article' | 'faq' | 'user' | 'system';
  action: 'created' | 'updated' | 'deleted';
  data: any;
  timestamp: string;
}

class WebSocketService {
  private ws: WebSocket | null = null;
  private reconnectAttempts = 0;
  private maxReconnectAttempts = 5;
  private reconnectInterval = 3000;
  private isConnected = false;
  private listeners: Map<string, Function[]> = new Map();
  private heartbeatInterval: number | null = null;

  constructor() {
    this.connect();
  }

  private connect(): void {
    try {
      // For development, we'll simulate WebSocket with polling
      // In production, this would connect to a real WebSocket server
      this.simulateConnection();
    } catch (error) {
      console.error('WebSocket connection failed:', error);
      this.scheduleReconnect();
    }
  }

  private simulateConnection(): void {
    // Simulate WebSocket connection for development
    this.isConnected = true;
    console.log('WebSocket service initialized (simulated)');
    
    // Start heartbeat
    this.startHeartbeat();
    
    // Simulate some initial data
    this.simulateInitialData();
  }

  private startHeartbeat(): void {
    this.heartbeatInterval = window.setInterval(() => {
      if (this.isConnected) {
        this.emit('heartbeat', { timestamp: new Date().toISOString() });
      }
    }, 30000); // Every 30 seconds
  }

  private simulateInitialData(): void {
    // Simulate some initial notifications and updates
    setTimeout(() => {
      this.emit('notification', {
        id: '1',
        title: 'Welcome to Stargate.ci',
        message: 'Real-time updates are now active',
        type: 'info',
        read: false,
        created_at: new Date().toISOString()
      });
    }, 1000);

    // Simulate live updates every 30 seconds
    setInterval(() => {
      if (this.isConnected) {
        this.simulateLiveUpdate();
      }
    }, 30000);
  }

  private simulateLiveUpdate(): void {
    const updateTypes = ['article', 'faq', 'user', 'system'];
    const actions = ['created', 'updated', 'deleted'];
    
    const randomType = updateTypes[Math.floor(Math.random() * updateTypes.length)];
    const randomAction = actions[Math.floor(Math.random() * actions.length)];
    
    this.emit('live_update', {
      type: randomType,
      action: randomAction,
      data: {
        id: Math.random().toString(36).substr(2, 9),
        title: `Sample ${randomType} ${randomAction}`,
        timestamp: new Date().toISOString()
      },
      timestamp: new Date().toISOString()
    });
  }

  private scheduleReconnect(): void {
    if (this.reconnectAttempts < this.maxReconnectAttempts) {
      this.reconnectAttempts++;
      setTimeout(() => {
        console.log(`Attempting to reconnect... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
        this.connect();
      }, this.reconnectInterval);
    } else {
      console.error('Max reconnection attempts reached');
    }
  }

  public on(event: string, callback: Function): void {
    if (!this.listeners.has(event)) {
      this.listeners.set(event, []);
    }
    this.listeners.get(event)!.push(callback);
  }

  public off(event: string, callback: Function): void {
    const eventListeners = this.listeners.get(event);
    if (eventListeners) {
      const index = eventListeners.indexOf(callback);
      if (index > -1) {
        eventListeners.splice(index, 1);
      }
    }
  }

  private emit(event: string, data: any): void {
    const eventListeners = this.listeners.get(event);
    if (eventListeners) {
      eventListeners.forEach(callback => {
        try {
          callback(data);
        } catch (error) {
          console.error(`Error in WebSocket event listener for ${event}:`, error);
        }
      });
    }
  }

  public send(message: WebSocketMessage): void {
    if (this.isConnected) {
      // In a real WebSocket implementation, this would send the message
      console.log('WebSocket message sent:', message);
    } else {
      console.warn('WebSocket not connected, message not sent:', message);
    }
  }

  public disconnect(): void {
    this.isConnected = false;
    
    if (this.heartbeatInterval) {
      clearInterval(this.heartbeatInterval);
      this.heartbeatInterval = null;
    }
    
    if (this.ws) {
      this.ws.close();
      this.ws = null;
    }
    
    console.log('WebSocket disconnected');
  }

  public getConnectionStatus(): boolean {
    return this.isConnected;
  }

  // Public methods for specific real-time features
  public subscribeToNotifications(callback: (notification: NotificationData) => void): void {
    this.on('notification', callback);
  }

  public subscribeToLiveUpdates(callback: (update: LiveUpdateData) => void): void {
    this.on('live_update', callback);
  }

  public subscribeToUserActivity(callback: (activity: any) => void): void {
    this.on('user_activity', callback);
  }

  public subscribeToSystemStatus(callback: (status: any) => void): void {
    this.on('system_status', callback);
  }

  // Simulate sending a notification
  public sendNotification(notification: Omit<NotificationData, 'id' | 'created_at'>): void {
    const fullNotification: NotificationData = {
      ...notification,
      id: Math.random().toString(36).substr(2, 9),
      created_at: new Date().toISOString()
    };
    
    this.emit('notification', fullNotification);
  }

  // Simulate user activity
  public trackUserActivity(activity: string, data?: any): void {
    this.emit('user_activity', {
      activity,
      data,
      timestamp: new Date().toISOString()
    });
  }
}

// Create singleton instance
export const websocketService = new WebSocketService();

// Auto-cleanup on page unload
window.addEventListener('beforeunload', () => {
  websocketService.disconnect();
});
