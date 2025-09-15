/**
 * User Management Service - Handles role-based permissions and user management
 * Provides comprehensive user administration, role management, and permission control
 */

export interface UserRole {
  id: string
  name: string
  description: string
  permissions: Permission[]
  isSystemRole: boolean
  createdAt: string
  updatedAt: string
}

export interface Permission {
  id: string
  name: string
  resource: string
  action: string
  description: string
  category: 'user' | 'content' | 'billing' | 'analytics' | 'admin' | 'system'
}

export interface UserProfile {
  id: string
  email: string
  username: string
  firstName: string
  lastName: string
  avatar?: string
  roleId: string
  role?: UserRole
  isActive: boolean
  isVerified: boolean
  lastLoginAt?: string
  createdAt: string
  updatedAt: string
  preferences: UserPreferences
  subscription?: {
    planId: string
    status: string
  }
}

export interface UserPreferences {
  language: string
  timezone: string
  notifications: {
    email: boolean
    push: boolean
    sms: boolean
  }
  privacy: {
    profileVisibility: 'public' | 'private' | 'friends'
    showEmail: boolean
    showActivity: boolean
  }
  theme: 'light' | 'dark' | 'auto'
}

export interface UserActivity {
  id: string
  userId: string
  action: string
  resource: string
  resourceId?: string
  details?: Record<string, any>
  ipAddress?: string
  userAgent?: string
  createdAt: string
}

export interface Team {
  id: string
  name: string
  description: string
  ownerId: string
  members: TeamMember[]
  settings: TeamSettings
  createdAt: string
  updatedAt: string
}

export interface TeamMember {
  userId: string
  role: 'owner' | 'admin' | 'member' | 'viewer'
  joinedAt: string
  invitedBy?: string
}

export interface TeamSettings {
  allowMemberInvites: boolean
  requireApproval: boolean
  maxMembers: number
  defaultRole: 'admin' | 'member' | 'viewer'
}

export interface Invitation {
  id: string
  email: string
  roleId: string
  teamId?: string
  invitedBy: string
  status: 'pending' | 'accepted' | 'declined' | 'expired'
  expiresAt: string
  createdAt: string
}

class UserManagementService {
  private storageKey = 'stargate_user_management'
  private currentUser: any = null

  constructor() {
    this.initializeData()
  }

  private initializeData() {
    const existingData = localStorage.getItem(this.storageKey)
    if (!existingData) {
      const initialData = {
        users: [],
        roles: this.getDefaultRoles(),
        permissions: this.getDefaultPermissions(),
        activities: [],
        teams: [],
        invitations: []
      }
      localStorage.setItem(this.storageKey, JSON.stringify(initialData))
    }
  }

  private getDefaultPermissions(): Permission[] {
    return [
      // User Management
      { id: 'user.create', name: 'Create Users', resource: 'users', action: 'create', description: 'Create new user accounts', category: 'user' },
      { id: 'user.read', name: 'View Users', resource: 'users', action: 'read', description: 'View user profiles and information', category: 'user' },
      { id: 'user.update', name: 'Edit Users', resource: 'users', action: 'update', description: 'Edit user profiles and settings', category: 'user' },
      { id: 'user.delete', name: 'Delete Users', resource: 'users', action: 'delete', description: 'Delete user accounts', category: 'user' },
      { id: 'user.manage_roles', name: 'Manage User Roles', resource: 'users', action: 'manage_roles', description: 'Assign and modify user roles', category: 'user' },

      // Content Management
      { id: 'content.create', name: 'Create Content', resource: 'content', action: 'create', description: 'Create articles, tutorials, and other content', category: 'content' },
      { id: 'content.read', name: 'View Content', resource: 'content', action: 'read', description: 'View all content', category: 'content' },
      { id: 'content.update', name: 'Edit Content', resource: 'content', action: 'update', description: 'Edit existing content', category: 'content' },
      { id: 'content.delete', name: 'Delete Content', resource: 'content', action: 'delete', description: 'Delete content', category: 'content' },
      { id: 'content.publish', name: 'Publish Content', resource: 'content', action: 'publish', description: 'Publish content to public', category: 'content' },
      { id: 'content.moderate', name: 'Moderate Content', resource: 'content', action: 'moderate', description: 'Moderate user-generated content', category: 'content' },

      // Billing Management
      { id: 'billing.view', name: 'View Billing', resource: 'billing', action: 'read', description: 'View billing information', category: 'billing' },
      { id: 'billing.manage', name: 'Manage Billing', resource: 'billing', action: 'update', description: 'Manage billing and subscriptions', category: 'billing' },
      { id: 'billing.admin', name: 'Admin Billing', resource: 'billing', action: 'admin', description: 'Full billing administration', category: 'billing' },

      // Analytics
      { id: 'analytics.view', name: 'View Analytics', resource: 'analytics', action: 'read', description: 'View analytics and reports', category: 'analytics' },
      { id: 'analytics.export', name: 'Export Analytics', resource: 'analytics', action: 'export', description: 'Export analytics data', category: 'analytics' },

      // Admin
      { id: 'admin.system', name: 'System Administration', resource: 'system', action: 'admin', description: 'Full system administration', category: 'admin' },
      { id: 'admin.settings', name: 'Manage Settings', resource: 'settings', action: 'update', description: 'Manage system settings', category: 'admin' },
      { id: 'admin.logs', name: 'View Logs', resource: 'logs', action: 'read', description: 'View system logs', category: 'admin' },

      // Team Management
      { id: 'team.create', name: 'Create Teams', resource: 'teams', action: 'create', description: 'Create new teams', category: 'user' },
      { id: 'team.manage', name: 'Manage Teams', resource: 'teams', action: 'update', description: 'Manage team settings and members', category: 'user' },
      { id: 'team.invite', name: 'Invite Members', resource: 'teams', action: 'invite', description: 'Invite users to teams', category: 'user' }
    ]
  }

  private getDefaultRoles(): UserRole[] {
    const permissions = this.getDefaultPermissions()
    
    return [
      {
        id: 'super_admin',
        name: 'Super Administrator',
        description: 'Full system access with all permissions',
        permissions: permissions,
        isSystemRole: true,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      },
      {
        id: 'admin',
        name: 'Administrator',
        description: 'Administrative access to most system features',
        permissions: permissions.filter(p => !['admin.system', 'admin.logs'].includes(p.id)),
        isSystemRole: true,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      },
      {
        id: 'moderator',
        name: 'Moderator',
        description: 'Content moderation and user management',
        permissions: permissions.filter(p => 
          ['user.read', 'user.update', 'content.read', 'content.update', 'content.moderate', 'analytics.view'].includes(p.id)
        ),
        isSystemRole: true,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      },
      {
        id: 'content_creator',
        name: 'Content Creator',
        description: 'Create and manage content',
        permissions: permissions.filter(p => 
          ['content.create', 'content.read', 'content.update', 'content.publish', 'analytics.view'].includes(p.id)
        ),
        isSystemRole: true,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      },
      {
        id: 'premium_user',
        name: 'Premium User',
        description: 'Premium features and enhanced access',
        permissions: permissions.filter(p => 
          ['content.read', 'content.create', 'billing.view', 'analytics.view', 'team.create', 'team.manage', 'team.invite'].includes(p.id)
        ),
        isSystemRole: true,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      },
      {
        id: 'user',
        name: 'User',
        description: 'Basic user access',
        permissions: permissions.filter(p => 
          ['content.read', 'billing.view'].includes(p.id)
        ),
        isSystemRole: true,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      }
    ]
  }

  private getData() {
    const data = localStorage.getItem(this.storageKey)
    return data ? JSON.parse(data) : {
      users: [],
      roles: this.getDefaultRoles(),
      permissions: this.getDefaultPermissions(),
      activities: [],
      teams: [],
      invitations: []
    }
  }

  private saveData(data: any) {
    localStorage.setItem(this.storageKey, JSON.stringify(data))
  }

  // User Management
  async getUsers(): Promise<UserProfile[]> {
    const data = this.getData()
    return data.users.map((user: UserProfile) => ({
      ...user,
      role: data.roles.find((role: UserRole) => role.id === user.roleId)
    }))
  }

  async getUser(userId: string): Promise<UserProfile | null> {
    const data = this.getData()
    const user = data.users.find((u: UserProfile) => u.id === userId)
    if (user) {
      return {
        ...user,
        role: data.roles.find((role: UserRole) => role.id === user.roleId)
      }
    }
    return null
  }

  async createUser(userData: Omit<UserProfile, 'id' | 'createdAt' | 'updatedAt'>): Promise<UserProfile> {
    const data = this.getData()
    const now = new Date().toISOString()
    
    const newUser: UserProfile = {
      ...userData,
      id: `user_${Date.now()}`,
      createdAt: now,
      updatedAt: now
    }

    data.users.push(newUser)
    this.saveData(data)

    // Log activity
    await this.logActivity(newUser.id, 'user.create', 'users', newUser.id, { createdBy: this.currentUser?.id })

    return newUser
  }

  async updateUser(userId: string, updates: Partial<UserProfile>): Promise<UserProfile | null> {
    const data = this.getData()
    const index = data.users.findIndex((u: UserProfile) => u.id === userId)
    if (index > -1) {
      const oldUser = { ...data.users[index] }
      data.users[index] = {
        ...data.users[index],
        ...updates,
        updatedAt: new Date().toISOString()
      }
      this.saveData(data)

      // Log activity
      await this.logActivity(userId, 'user.update', 'users', userId, { 
        updatedBy: this.currentUser?.id,
        changes: Object.keys(updates)
      })

      return data.users[index]
    }
    return null
  }

  async deleteUser(userId: string): Promise<boolean> {
    const data = this.getData()
    const index = data.users.findIndex((u: UserProfile) => u.id === userId)
    if (index > -1) {
      data.users.splice(index, 1)
      this.saveData(data)

      // Log activity
      await this.logActivity(userId, 'user.delete', 'users', userId, { deletedBy: this.currentUser?.id })

      return true
    }
    return false
  }

  async updateUserRole(userId: string, roleId: string): Promise<boolean> {
    const data = this.getData()
    const user = data.users.find((u: UserProfile) => u.id === userId)
    if (user) {
      user.roleId = roleId
      user.updatedAt = new Date().toISOString()
      this.saveData(data)

      // Log activity
      await this.logActivity(userId, 'user.manage_roles', 'users', userId, { 
        updatedBy: this.currentUser?.id,
        newRole: roleId,
        oldRole: user.roleId
      })

      return true
    }
    return false
  }

  // Role Management
  async getRoles(): Promise<UserRole[]> {
    const data = this.getData()
    return data.roles
  }

  async getRole(roleId: string): Promise<UserRole | null> {
    const data = this.getData()
    return data.roles.find((role: UserRole) => role.id === roleId) || null
  }

  async createRole(roleData: Omit<UserRole, 'id' | 'createdAt' | 'updatedAt'>): Promise<UserRole> {
    const data = this.getData()
    const now = new Date().toISOString()
    
    const newRole: UserRole = {
      ...roleData,
      id: `role_${Date.now()}`,
      createdAt: now,
      updatedAt: now
    }

    data.roles.push(newRole)
    this.saveData(data)

    return newRole
  }

  async updateRole(roleId: string, updates: Partial<UserRole>): Promise<UserRole | null> {
    const data = this.getData()
    const index = data.roles.findIndex((role: UserRole) => role.id === roleId)
    if (index > -1) {
      data.roles[index] = {
        ...data.roles[index],
        ...updates,
        updatedAt: new Date().toISOString()
      }
      this.saveData(data)
      return data.roles[index]
    }
    return null
  }

  async deleteRole(roleId: string): Promise<boolean> {
    const data = this.getData()
    const role = data.roles.find((r: UserRole) => r.id === roleId)
    if (role && role.isSystemRole) {
      return false // Cannot delete system roles
    }

    const index = data.roles.findIndex((role: UserRole) => role.id === roleId)
    if (index > -1) {
      data.roles.splice(index, 1)
      this.saveData(data)
      return true
    }
    return false
  }

  // Permission Management
  async getPermissions(): Promise<Permission[]> {
    const data = this.getData()
    return data.permissions
  }

  async getPermissionsByCategory(category: string): Promise<Permission[]> {
    const data = this.getData()
    return data.permissions.filter((p: Permission) => p.category === category)
  }

  // Team Management
  async getTeams(): Promise<Team[]> {
    const data = this.getData()
    return data.teams
  }

  async createTeam(teamData: Omit<Team, 'id' | 'createdAt' | 'updatedAt'>): Promise<Team> {
    const data = this.getData()
    const now = new Date().toISOString()
    
    const newTeam: Team = {
      ...teamData,
      id: `team_${Date.now()}`,
      createdAt: now,
      updatedAt: now
    }

    data.teams.push(newTeam)
    this.saveData(data)

    return newTeam
  }

  async inviteUser(email: string, roleId: string, teamId?: string): Promise<Invitation> {
    const data = this.getData()
    const now = new Date()
    const expiresAt = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000) // 7 days

    const invitation: Invitation = {
      id: `inv_${Date.now()}`,
      email,
      roleId,
      teamId,
      invitedBy: this.currentUser?.id || 'system',
      status: 'pending',
      expiresAt: expiresAt.toISOString(),
      createdAt: now.toISOString()
    }

    data.invitations.push(invitation)
    this.saveData(data)

    return invitation
  }

  // Activity Logging
  async logActivity(userId: string, action: string, resource: string, resourceId?: string, details?: Record<string, any>): Promise<void> {
    const data = this.getData()
    
    const activity: UserActivity = {
      id: `activity_${Date.now()}`,
      userId,
      action,
      resource,
      resourceId,
      details,
      ipAddress: '127.0.0.1', // Simulated
      userAgent: navigator.userAgent,
      createdAt: new Date().toISOString()
    }

    data.activities.push(activity)
    this.saveData(data)
  }

  async getUserActivities(userId: string, limit: number = 50): Promise<UserActivity[]> {
    const data = this.getData()
    return data.activities
      .filter((activity: UserActivity) => activity.userId === userId)
      .sort((a: UserActivity, b: UserActivity) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
      .slice(0, limit)
  }

  async getAllActivities(limit: number = 100): Promise<UserActivity[]> {
    const data = this.getData()
    return data.activities
      .sort((a: UserActivity, b: UserActivity) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
      .slice(0, limit)
  }

  // Permission Checking
  async hasPermission(userId: string, permissionId: string): Promise<boolean> {
    const user = await this.getUser(userId)
    if (!user || !user.role) return false

    return user.role.permissions.some(p => p.id === permissionId)
  }

  async hasAnyPermission(userId: string, permissionIds: string[]): Promise<boolean> {
    const user = await this.getUser(userId)
    if (!user || !user.role) return false

    return permissionIds.some(permissionId => 
      user.role!.permissions.some(p => p.id === permissionId)
    )
  }

  async hasAllPermissions(userId: string, permissionIds: string[]): Promise<boolean> {
    const user = await this.getUser(userId)
    if (!user || !user.role) return false

    return permissionIds.every(permissionId => 
      user.role!.permissions.some(p => p.id === permissionId)
    )
  }

  // Statistics
  async getUserStats(): Promise<{
    totalUsers: number
    activeUsers: number
    usersByRole: Record<string, number>
    recentSignups: number
  }> {
    const data = this.getData()
    const users = data.users
    const now = new Date()
    const last30Days = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000)

    const usersByRole: Record<string, number> = {}
    data.roles.forEach((role: UserRole) => {
      usersByRole[role.name] = users.filter((user: UserProfile) => user.roleId === role.id).length
    })

    const recentSignups = users.filter((user: UserProfile) => 
      new Date(user.createdAt) >= last30Days
    ).length

    return {
      totalUsers: users.length,
      activeUsers: users.filter((user: UserProfile) => user.isActive).length,
      usersByRole,
      recentSignups
    }
  }

  // Utility methods
  setCurrentUser(user: any) {
    this.currentUser = user
  }

  getCurrentUser() {
    return this.currentUser
  }
}

export const userManagementService = new UserManagementService()
