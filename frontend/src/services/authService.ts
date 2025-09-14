// Authentication Service for Admin Access
export interface AdminUser {
  id: string;
  username: string;
  email: string;
  role: 'admin' | 'super-admin';
  lastLogin?: string;
}

export interface User {
  id: string;
  username: string;
  email: string;
  firstName?: string;
  lastName?: string;
  company?: string;
  role: 'user' | 'admin' | 'super-admin';
  createdAt: string;
  lastLogin?: string;
  preferences?: UserPreferences;
}

export interface UserPreferences {
  language: string;
  theme: 'light' | 'dark';
  notifications: boolean;
  emailUpdates: boolean;
}

export interface LoginCredentials {
  username: string;
  password: string;
}

export interface RegisterCredentials {
  username: string;
  email: string;
  password: string;
  firstName?: string;
  lastName?: string;
  company?: string;
}

class AuthService {
  private readonly STORAGE_KEY = 'stargate_admin_auth';
  private readonly USER_STORAGE_KEY = 'stargate_user_auth';
  private readonly USERS_STORAGE_KEY = 'stargate_users';
  
  private readonly ADMIN_CREDENTIALS = {
    username: 'admin',
    password: 'stargate2025!', // In production, this should be hashed
    email: 'admin@stargate.ci',
    role: 'admin' as const
  };

  private readonly SUPER_ADMIN_CREDENTIALS = {
    username: 'superadmin',
    password: 'cristal2025!', // In production, this should be hashed
    email: 'superadmin@stargate.ci',
    role: 'super-admin' as const
  };

  private readonly DEMO_USER_CREDENTIALS = {
    username: 'demo',
    password: 'demo123', // Demo credentials for testing
    email: 'demo@stargate.ci',
    role: 'user' as const
  };

  // Check if user is authenticated
  isAuthenticated(): boolean {
    const authData = this.getAuthData();
    return authData !== null && this.isTokenValid(authData);
  }

  // Get current admin user
  getCurrentAdminUser(): AdminUser | null {
    const authData = this.getAuthData();
    if (!authData || !this.isTokenValid(authData)) {
      return null;
    }
    return authData.user;
  }

  // Login function
  async login(credentials: LoginCredentials): Promise<{ success: boolean; user?: AdminUser; error?: string }> {
    try {
      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 1000));

      // Check credentials
      let user: AdminUser | null = null;
      
      if (credentials.username === this.ADMIN_CREDENTIALS.username && 
          credentials.password === this.ADMIN_CREDENTIALS.password) {
        user = {
          id: '1',
          username: this.ADMIN_CREDENTIALS.username,
          email: this.ADMIN_CREDENTIALS.email,
          role: this.ADMIN_CREDENTIALS.role,
          lastLogin: new Date().toISOString()
        };
      } else if (credentials.username === this.SUPER_ADMIN_CREDENTIALS.username && 
                 credentials.password === this.SUPER_ADMIN_CREDENTIALS.password) {
        user = {
          id: '2',
          username: this.SUPER_ADMIN_CREDENTIALS.username,
          email: this.SUPER_ADMIN_CREDENTIALS.email,
          role: this.SUPER_ADMIN_CREDENTIALS.role,
          lastLogin: new Date().toISOString()
        };
      }

      if (user) {
        // Generate a simple token (in production, use JWT)
        const token = this.generateToken(user);
        const authData = {
          user,
          token,
          expiresAt: Date.now() + (24 * 60 * 60 * 1000) // 24 hours
        };

        this.setAuthData(authData);
        return { success: true, user };
      } else {
        return { success: false, error: 'Invalid username or password' };
      }
    } catch (error) {
      return { success: false, error: 'Login failed. Please try again.' };
    }
  }

  // Logout function
  logout(): void {
    localStorage.removeItem(this.STORAGE_KEY);
  }

  // Check if user has specific role
  hasRole(role: 'admin' | 'super-admin'): boolean {
    const user = this.getCurrentUser();
    if (!user) return false;
    
    if (role === 'admin') {
      return user.role === 'admin' || user.role === 'super-admin';
    }
    
    return user.role === 'super-admin';
  }

  // Get auth data from localStorage
  private getAuthData(): any {
    try {
      const data = localStorage.getItem(this.STORAGE_KEY);
      return data ? JSON.parse(data) : null;
    } catch {
      return null;
    }
  }

  // Set auth data to localStorage
  private setAuthData(authData: any): void {
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(authData));
  }

  // Check if token is valid
  private isTokenValid(authData: any): boolean {
    if (!authData || !authData.expiresAt) return false;
    return Date.now() < authData.expiresAt;
  }

  // Generate a simple token (in production, use proper JWT)
  private generateToken(user: AdminUser): string {
    const payload = {
      userId: user.id,
      username: user.username,
      role: user.role,
      timestamp: Date.now()
    };
    
    // Simple base64 encoding (in production, use proper JWT)
    return btoa(JSON.stringify(payload));
  }

  // Verify token (in production, use proper JWT verification)
  private verifyToken(token: string): boolean {
    try {
      const payload = JSON.parse(atob(token));
      return payload && payload.userId && payload.role;
    } catch {
      return false;
    }
  }

  // Get session info
  getSessionInfo(): { user: AdminUser; expiresIn: number } | null {
    const authData = this.getAuthData();
    if (!authData || !this.isTokenValid(authData)) {
      return null;
    }

    const expiresIn = Math.max(0, authData.expiresAt - Date.now());
    return {
      user: authData.user,
      expiresIn
    };
  }

  // Refresh session
  refreshSession(): boolean {
    const authData = this.getAuthData();
    if (!authData || !this.isTokenValid(authData)) {
      return false;
    }

    // Extend session by 24 hours
    authData.expiresAt = Date.now() + (24 * 60 * 60 * 1000);
    this.setAuthData(authData);
    return true;
  }

  // User Management Methods
  async registerUser(credentials: RegisterCredentials): Promise<{ success: boolean; user?: User; error?: string }> {
    try {
      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 1000));

      // Check if user already exists
      const existingUsers = this.getStoredUsers();
      const existingUser = existingUsers.find(u => u.email === credentials.email || u.username === credentials.username);
      
      if (existingUser) {
        return { success: false, error: 'User with this email or username already exists' };
      }

      // Create new user
      const newUser: User = {
        id: Date.now().toString(),
        username: credentials.username,
        email: credentials.email,
        firstName: credentials.firstName,
        lastName: credentials.lastName,
        company: credentials.company,
        role: 'user',
        createdAt: new Date().toISOString(),
        preferences: {
          language: 'en',
          theme: 'dark',
          notifications: true,
          emailUpdates: true
        }
      };

      // Store user
      existingUsers.push(newUser);
      this.setStoredUsers(existingUsers);

      return { success: true, user: newUser };
    } catch (error) {
      return { success: false, error: 'Registration failed. Please try again.' };
    }
  }

  async loginUser(credentials: LoginCredentials): Promise<{ success: boolean; user?: User; error?: string }> {
    try {
      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 1000));

      // Check demo credentials first
      if (credentials.username === this.DEMO_USER_CREDENTIALS.username && 
          credentials.password === this.DEMO_USER_CREDENTIALS.password) {
        const demoUser: User = {
          id: 'demo-user-1',
          username: this.DEMO_USER_CREDENTIALS.username,
          email: this.DEMO_USER_CREDENTIALS.email,
          role: this.DEMO_USER_CREDENTIALS.role,
          createdAt: new Date().toISOString(),
          lastLogin: new Date().toISOString(),
          preferences: {
            language: 'en',
            theme: 'dark',
            notifications: true,
            emailUpdates: false
          }
        };

        // Create user session
        const token = this.generateUserToken(demoUser);
        const authData = {
          user: demoUser,
          token,
          expiresAt: Date.now() + (24 * 60 * 60 * 1000) // 24 hours
        };

        this.setUserAuthData(authData);
        return { success: true, user: demoUser };
      }

      // Check registered users
      const users = this.getStoredUsers();
      const user = users.find(u => 
        (u.username === credentials.username || u.email === credentials.username) &&
        this.validatePassword(credentials.password, u.username) // Simple validation for demo
      );

      if (user) {
        // Update last login
        user.lastLogin = new Date().toISOString();
        this.updateStoredUser(user);

        // Create user session
        const token = this.generateUserToken(user);
        const authData = {
          user,
          token,
          expiresAt: Date.now() + (24 * 60 * 60 * 1000) // 24 hours
        };

        this.setUserAuthData(authData);
        return { success: true, user };
      } else {
        return { success: false, error: 'Invalid username or password' };
      }
    } catch (error) {
      return { success: false, error: 'Login failed. Please try again.' };
    }
  }

  isUserAuthenticated(): boolean {
    const authData = this.getUserAuthData();
    return authData !== null && this.isUserTokenValid(authData);
  }

  getCurrentUser(): User | null {
    const authData = this.getUserAuthData();
    if (!authData || !this.isUserTokenValid(authData)) {
      return null;
    }
    return authData.user;
  }

  logoutUser(): void {
    localStorage.removeItem(this.USER_STORAGE_KEY);
  }

  // Helper methods for user management
  private getStoredUsers(): User[] {
    try {
      const stored = localStorage.getItem(this.USERS_STORAGE_KEY);
      return stored ? JSON.parse(stored) : [];
    } catch {
      return [];
    }
  }

  private setStoredUsers(users: User[]): void {
    localStorage.setItem(this.USERS_STORAGE_KEY, JSON.stringify(users));
  }

  private updateStoredUser(updatedUser: User): void {
    const users = this.getStoredUsers();
    const index = users.findIndex(u => u.id === updatedUser.id);
    if (index !== -1) {
      users[index] = updatedUser;
      this.setStoredUsers(users);
    }
  }

  private getUserAuthData(): { user: User; token: string; expiresAt: number } | null {
    try {
      const stored = localStorage.getItem(this.USER_STORAGE_KEY);
      return stored ? JSON.parse(stored) : null;
    } catch {
      return null;
    }
  }

  private setUserAuthData(authData: { user: User; token: string; expiresAt: number }): void {
    localStorage.setItem(this.USER_STORAGE_KEY, JSON.stringify(authData));
  }

  private isUserTokenValid(authData: { user: User; token: string; expiresAt: number }): boolean {
    return Date.now() < authData.expiresAt && this.validateUserToken(authData.token);
  }

  private generateUserToken(user: User): string {
    const payload = {
      userId: user.id,
      username: user.username,
      role: user.role,
      iat: Date.now()
    };
    return btoa(JSON.stringify(payload));
  }

  private validateUserToken(token: string): boolean {
    try {
      const payload = JSON.parse(atob(token));
      return payload && payload.userId && payload.role;
    } catch {
      return false;
    }
  }

  private validatePassword(password: string, username: string): boolean {
    // Simple validation for demo - in production, use proper password hashing
    return password.length >= 6;
  }

  // Get all users (admin only)
  getAllUsers(): User[] {
    return this.getStoredUsers();
  }

  // Update user preferences
  updateUserPreferences(userId: string, preferences: Partial<UserPreferences>): boolean {
    const users = this.getStoredUsers();
    const user = users.find(u => u.id === userId);
    if (user && user.preferences) {
      user.preferences = { 
        language: user.preferences.language,
        theme: user.preferences.theme,
        notifications: user.preferences.notifications,
        emailUpdates: user.preferences.emailUpdates,
        ...preferences 
      };
      this.updateStoredUser(user);
      return true;
    }
    return false;
  }
}

export const authService = new AuthService();
