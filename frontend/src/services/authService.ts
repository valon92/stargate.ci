// Authentication Service for Admin Access
export interface AdminUser {
  id: string;
  username: string;
  email: string;
  role: 'admin' | 'super-admin';
  lastLogin?: string;
}

export interface LoginCredentials {
  username: string;
  password: string;
}

class AuthService {
  private readonly STORAGE_KEY = 'stargate_admin_auth';
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

  // Check if user is authenticated
  isAuthenticated(): boolean {
    const authData = this.getAuthData();
    return authData !== null && this.isTokenValid(authData);
  }

  // Get current user
  getCurrentUser(): AdminUser | null {
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
}

export const authService = new AuthService();
