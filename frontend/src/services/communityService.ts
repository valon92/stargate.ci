// Community Service for forum features, user profiles, and social interactions
import { backendApi } from './backendApi'
export interface UserProfile {
  id: string;
  username: string;
  email: string;
  displayName: string;
  bio: string;
  avatar: string;
  location: string;
  website: string;
  joinDate: string;
  lastActive: string;
  reputation: number;
  badges: Badge[];
  stats: UserStats;
  preferences: UserPreferences;
  socialLinks: SocialLinks;
}

export interface UserStats {
  posts: number;
  comments: number;
  likes: number;
  followers: number;
  following: number;
}

export interface UserPreferences {
  privacy: 'public' | 'private' | 'friends';
  notifications: boolean;
  emailUpdates: boolean;
}

export interface SocialLinks {
  twitter?: string;
  linkedin?: string;
  github?: string;
  website?: string;
}

export interface Badge {
  id: string;
  name: string;
  description: string;
  icon: string;
  earnedAt: string;
}

export interface ForumCategory {
  id: string;
  name: string;
  description: string;
  icon: string;
  color: string;
  order: number;
  postCount: number;
  moderators: string[];
}

export interface ForumPost {
  id: string;
  title: string;
  content: string;
  author: UserProfile;
  category: ForumCategory;
  tags: string[];
  createdAt: string;
  updatedAt: string;
  lastActivity: string;
  views: number;
  likes: number;
  comments: number;
  isPinned: boolean;
  isLocked: boolean;
  isSolved: boolean;
}

export interface ForumComment {
  id: string;
  content: string;
  author: UserProfile;
  post: ForumPost;
  parentId?: string;
  createdAt: string;
  updatedAt: string;
  likes: number;
}

export interface CommunityStats {
  totalUsers: number;
  totalPosts: number;
  totalComments: number;
  activeUsers: number;
  topContributors: UserProfile[];
  recentPosts: ForumPost[];
  popularTags: Array<{ tag: string; count: number }>;
}

class CommunityService {
  private readonly PROFILES_KEY = 'stargate_profiles';
  private readonly POSTS_KEY = 'stargate_posts';
  private readonly COMMENTS_KEY = 'stargate_comments';
  private readonly CATEGORIES_KEY = 'stargate_categories';

  // User Profile Management
  async createProfile(userData: Partial<UserProfile>): Promise<UserProfile> {
    const profile: UserProfile = {
      id: this.generateId(),
      username: userData.username || 'user',
      email: userData.email || '',
      displayName: userData.displayName || userData.username || 'User',
      bio: userData.bio || '',
      avatar: userData.avatar || this.generateAvatar(),
      location: userData.location || '',
      website: userData.website || '',
      joinDate: new Date().toISOString(),
      lastActive: new Date().toISOString(),
      reputation: 0,
      badges: [],
      stats: {
        posts: 0,
        comments: 0,
        likes: 0,
        followers: 0,
        following: 0
      },
      preferences: {
        privacy: 'public',
        notifications: true,
        emailUpdates: false
      },
      socialLinks: {}
    };

    const profiles = await this.getProfiles();
    profiles.push(profile);
    this.saveProfiles(profiles);

    return profile;
  }

  async updateProfile(userId: string, updates: Partial<UserProfile>): Promise<UserProfile | null> {
    const profiles = await this.getProfiles();
    const index = profiles.findIndex(p => p.id === userId);
    
    if (index === -1) return null;

    profiles[index] = {
      ...profiles[index],
      ...updates,
      lastActive: new Date().toISOString()
    };

    this.saveProfiles(profiles);
    return profiles[index];
  }

  async getProfile(userId: string): Promise<UserProfile | null> {
    const profiles = await this.getProfiles();
    return profiles.find(p => p.id === userId) || null;
  }

  async getProfileByUsername(username: string): Promise<UserProfile | null> {
    const profiles = await this.getProfiles();
    return profiles.find(p => p.username === username) || null;
  }

  // Forum Categories
  async getCategories(): Promise<ForumCategory[]> {
    try {
      const response = await backendApi.getCommunityCategories();
      if (response.status === 'success') {
        // Transform backend data to frontend interface
        return response.data.map((cat: any) => ({
          id: cat.id.toString(),
          name: cat.name,
          description: cat.description,
          icon: cat.icon || '💬',
          color: cat.color || '#3B82F6',
          order: cat.sort_order || 1,
          postCount: 0, // TODO: Add post count to backend
          moderators: [] // TODO: Add moderators to backend
        }));
      }
    } catch (error) {
      console.warn('Failed to fetch categories from API, using fallback:', error);
    }

    // Fallback to cached data or defaults
    const stored = localStorage.getItem(this.CATEGORIES_KEY);
    if (stored) {
      return JSON.parse(stored);
    }
    
    // Initialize with default categories
    return this.initializeDefaultCategories();
  }

  private initializeDefaultCategories(): ForumCategory[] {
    const defaultCategories: ForumCategory[] = [
      {
        id: '1',
        name: 'General Discussion',
        description: 'General discussions about Stargate.ci and related topics',
        icon: '💬',
        color: '#3B82F6',
        order: 1,
        postCount: 0,
        moderators: []
      },
      {
        id: '2',
        name: 'AI & Machine Learning',
        description: 'Discussions about AI, ML, and related technologies',
        icon: '🤖',
        color: '#10B981',
        order: 2,
        postCount: 0,
        moderators: []
      },
      {
        id: '3',
        name: 'Cloud Computing',
        description: 'Cloud platforms, services, and best practices',
        icon: '☁️',
        color: '#8B5CF6',
        order: 3,
        postCount: 0,
        moderators: []
      },
      {
        id: '4',
        name: 'Big Data',
        description: 'Data analytics, processing, and visualization',
        icon: '📊',
        color: '#F59E0B',
        order: 4,
        postCount: 0,
        moderators: []
      },
      {
        id: '5',
        name: 'Advanced Computing',
        description: 'Quantum computing, HPC, and advanced architectures',
        icon: '⚡',
        color: '#EF4444',
        order: 5,
        postCount: 0,
        moderators: []
      },
      {
        id: '6',
        name: 'Help & Support',
        description: 'Get help with using Stargate.ci platform',
        icon: '❓',
        color: '#6B7280',
        order: 6,
        postCount: 0,
        moderators: []
      }
    ];

    this.saveCategories(defaultCategories);
    return defaultCategories;
  }

  // Forum Posts
  async createPost(postData: Omit<ForumPost, 'id' | 'createdAt' | 'updatedAt' | 'lastActivity' | 'views' | 'likes' | 'comments'>): Promise<ForumPost> {
    const post: ForumPost = {
      ...postData,
      id: this.generateId(),
      views: 0,
      likes: 0,
      comments: 0,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
      lastActivity: new Date().toISOString()
    };

    const posts = await this.getPosts();
    posts.push(post);
    this.savePosts(posts);

    // Update category post count
    await this.updateCategoryPostCount(post.category.id, 1);

    // Update user stats
    await this.updateUserStats(post.author.id, 'posts', 1);

    return post;
  }

  async getPosts(categoryId?: string, limit?: number): Promise<ForumPost[]> {
    try {
        const response = await backendApi.getCommunityPosts({ 
          category: categoryId,
          search: limit?.toString() // Adjust this to a valid parameter
        });
      
      if (response.status === 'success') {
        const categories = await this.getCategories();
        
        // Transform backend data to frontend interface
        return response.data.data.map((post: any) => ({
          id: post.id.toString(),
          title: post.title,
          content: post.content,
          author: {
            id: post.author?.id?.toString() || 'unknown',
            username: post.author?.name || 'Unknown User',
            email: post.author?.email || '',
            displayName: post.author?.name || 'Unknown User',
            bio: '',
            avatar: '/api/placeholder/40/40',
            location: '',
            website: '',
            joinDate: post.author?.created_at || new Date().toISOString(),
            lastActive: new Date().toISOString(),
            reputation: 0,
            badges: [],
            stats: { posts: 0, comments: 0, likes: 0, followers: 0, following: 0 },
            preferences: { privacy: 'public' as const, notifications: true, emailUpdates: true },
            socialLinks: {}
          },
          category: categories.find(cat => cat.id === post.category_id?.toString()) || categories[0],
          views: post.view_count || 0,
          likes: post.like_count || 0,
          comments: post.comment_count || 0,
          tags: post.tags || [],
          isPinned: post.is_pinned === 1,
          isLocked: post.is_locked === 1,
          isSolved: false, // TODO: Add solved status to backend
          createdAt: post.created_at,
          updatedAt: post.updated_at,
          lastActivity: post.last_activity_at || post.updated_at
        }));
      }
    } catch (error) {
      console.warn('Failed to fetch posts from API, using fallback:', error);
    }

    // Fallback to localStorage
    const stored = localStorage.getItem(this.POSTS_KEY);
    let posts: ForumPost[] = stored ? JSON.parse(stored) : [];
    
    if (categoryId) {
      posts = posts.filter(p => p.category.id === categoryId);
    }

    // Sort by last activity (most recent first)
    posts.sort((a, b) => new Date(b.lastActivity).getTime() - new Date(a.lastActivity).getTime());

    if (limit) {
      posts = posts.slice(0, limit);
    }

    return posts;
  }

  async getPost(postId: string): Promise<ForumPost | null> {
    const stored = localStorage.getItem(this.POSTS_KEY);
    const posts: ForumPost[] = stored ? JSON.parse(stored) : [];
    return posts.find(p => p.id === postId) || null;
  }

  async updatePost(postId: string, updates: Partial<ForumPost>): Promise<ForumPost | null> {
    const stored = localStorage.getItem(this.POSTS_KEY);
    const posts: ForumPost[] = stored ? JSON.parse(stored) : [];
    const index = posts.findIndex(p => p.id === postId);
    
    if (index === -1) return null;

    posts[index] = {
      ...posts[index],
      ...updates,
      updatedAt: new Date().toISOString()
    };

    this.savePosts(posts);
    return posts[index];
  }

  async deletePost(postId: string): Promise<boolean> {
    const stored = localStorage.getItem(this.POSTS_KEY);
    const posts: ForumPost[] = stored ? JSON.parse(stored) : [];
    const post = posts.find(p => p.id === postId);
    
    if (!post) return false;

    const filteredPosts = posts.filter(p => p.id !== postId);
    this.savePosts(filteredPosts);

    // Update category post count
    await this.updateCategoryPostCount(post.category.id, -1);

    // Update user stats
    await this.updateUserStats(post.author.id, 'posts', -1);

    return true;
  }

  async likePost(postId: string, userId: string): Promise<boolean> {
    const post = await this.getPost(postId);
    if (!post) return false;

    // Check if user already liked
    const likeKey = `post_like_${postId}_${userId}`;
    const hasLiked = localStorage.getItem(likeKey);
    
    if (hasLiked) {
      // Unlike
      localStorage.removeItem(likeKey);
      await this.updatePost(postId, { likes: post.likes - 1 });
      await this.updateUserStats(post.author.id, 'likes', -1);
    } else {
      // Like
      localStorage.setItem(likeKey, 'true');
      await this.updatePost(postId, { likes: post.likes + 1 });
      await this.updateUserStats(post.author.id, 'likes', 1);
    }

    return true;
  }

  async viewPost(postId: string): Promise<void> {
    const post = await this.getPost(postId);
    if (post) {
      await this.updatePost(postId, { views: post.views + 1 });
    }
  }

  // Forum Comments
  async createComment(commentData: Omit<ForumComment, 'id' | 'createdAt' | 'updatedAt' | 'likes'>): Promise<ForumComment> {
    const comment: ForumComment = {
      ...commentData,
      id: this.generateId(),
      likes: 0,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    };

    const comments = await this.getComments();
    comments.push(comment);
    this.saveComments(comments);

    // Update post comment count
    await this.updatePost(comment.post.id, { 
      comments: comment.post.comments + 1,
      lastActivity: new Date().toISOString()
    });

    // Update user stats
    await this.updateUserStats(comment.author.id, 'comments', 1);

    return comment;
  }

  async getCommentsForPost(postId: string): Promise<ForumComment[]> {
    const storedComments = localStorage.getItem(this.COMMENTS_KEY);
    const comments: ForumComment[] = storedComments ? JSON.parse(storedComments) : [];
    return comments
      .filter((c: ForumComment) => c.post.id === postId)
      .sort((a: ForumComment, b: ForumComment) => new Date(a.createdAt).getTime() - new Date(b.createdAt).getTime());
  }

  async likeComment(commentId: string, userId: string): Promise<boolean> {
    const comments = await this.getComments();
    const comment = comments.find(c => c.id === commentId);
    
    if (!comment) return false;

    const likeKey = `comment_like_${commentId}_${userId}`;
    const hasLiked = localStorage.getItem(likeKey);
    
    if (hasLiked) {
      // Unlike
      localStorage.removeItem(likeKey);
      comment.likes -= 1;
    } else {
      // Like
      localStorage.setItem(likeKey, 'true');
      comment.likes += 1;
    }

    this.saveComments(comments);
    return true;
  }

  // Community Stats
  async getCommunityStats(): Promise<CommunityStats> {
    const profiles = await this.getProfiles();
    const storedPosts = localStorage.getItem(this.POSTS_KEY);
    const posts: ForumPost[] = storedPosts ? JSON.parse(storedPosts) : [];
    const comments = await this.getComments();

    // Calculate active users (active in last 7 days)
    const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000);
    const activeUsers = profiles.filter(p => new Date(p.lastActive) > weekAgo).length;

    // Top contributors (by reputation)
    const topContributors = [...profiles]
      .sort((a, b) => b.reputation - a.reputation)
      .slice(0, 10);

    // Recent posts
    const recentPosts = [...posts]
      .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
      .slice(0, 5);

    // Popular tags
    const tagCounts: Record<string, number> = {};
    posts.forEach(post => {
      post.tags.forEach(tag => {
        tagCounts[tag] = (tagCounts[tag] || 0) + 1;
      });
    });

    const popularTags = Object.entries(tagCounts)
      .map(([tag, count]) => ({ tag, count }))
      .sort((a, b) => b.count - a.count)
      .slice(0, 10);

    return {
      totalUsers: profiles.length,
      totalPosts: posts.length,
      totalComments: comments.length,
      activeUsers,
      topContributors,
      recentPosts,
      popularTags
    };
  }

  // Search
  async searchPosts(query: string, categoryId?: string): Promise<ForumPost[]> {
    const stored = localStorage.getItem(this.POSTS_KEY);
    let posts: ForumPost[] = stored ? JSON.parse(stored) : [];
    
    if (categoryId) {
      posts = posts.filter(p => p.category.id === categoryId);
    }

    const lowerQuery = query.toLowerCase();
    return posts.filter(post => 
      post.title.toLowerCase().includes(lowerQuery) ||
      post.content.toLowerCase().includes(lowerQuery) ||
      post.tags.some(tag => tag.toLowerCase().includes(lowerQuery))
    );
  }

  async searchUsers(query: string): Promise<UserProfile[]> {
    const profiles = await this.getProfiles();
    const lowerQuery = query.toLowerCase();
    
    return profiles.filter(profile =>
      profile.username.toLowerCase().includes(lowerQuery) ||
      profile.displayName.toLowerCase().includes(lowerQuery) ||
      profile.bio?.toLowerCase().includes(lowerQuery)
    );
  }

  // Badge System
  async awardBadge(userId: string, badge: Omit<Badge, 'earnedAt'>): Promise<void> {
    const profile = await this.getProfile(userId);
    if (!profile) return;

    const newBadge: Badge = {
      ...badge,
      earnedAt: new Date().toISOString()
    };

    // Check if user already has this badge
    if (profile.badges.some(b => b.id === badge.id)) return;

    profile.badges.push(newBadge);
    await this.updateProfile(userId, { badges: profile.badges });
  }

  // Helper Methods
  async getProfiles(): Promise<UserProfile[]> {
    const stored = localStorage.getItem(this.PROFILES_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  private saveProfiles(profiles: UserProfile[]): void {
    localStorage.setItem(this.PROFILES_KEY, JSON.stringify(profiles));
  }


  private savePosts(posts: ForumPost[]): void {
    localStorage.setItem(this.POSTS_KEY, JSON.stringify(posts));
  }

  async getComments(): Promise<ForumComment[]> {
    const stored = localStorage.getItem(this.COMMENTS_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  private saveComments(comments: ForumComment[]): void {
    localStorage.setItem(this.COMMENTS_KEY, JSON.stringify(comments));
  }


  private saveCategories(categories: ForumCategory[]): void {
    localStorage.setItem(this.CATEGORIES_KEY, JSON.stringify(categories));
  }

  private async updateCategoryPostCount(categoryId: string, delta: number = 1): Promise<void> {
    const categories = await this.getCategories();
    const category = categories.find(c => c.id === categoryId);
    
    if (category) {
      category.postCount += delta;
      this.saveCategories(categories);
    }
  }

  private async updateUserStats(userId: string, statType: keyof UserStats, increment: number): Promise<void> {
    const profile = await this.getProfile(userId);
    if (!profile) return;

    const currentValue = profile.stats[statType];
    await this.updateProfile(userId, {
      stats: {
        ...profile.stats,
        [statType]: currentValue + increment
      }
    });
  }

  private generateId(): string {
    return Math.random().toString(36).substr(2, 9);
  }

  private generateAvatar(): string {
    const colors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444', '#06B6D4'];
    const color = colors[Math.floor(Math.random() * colors.length)];
    return `https://ui-avatars.com/api/?name=User&background=${color.replace('#', '')}&color=fff&size=128`;
  }

  // Initialize sample data
  async initializeSampleData(): Promise<void> {
    const profiles = await this.getProfiles();
    if (profiles.length > 0) return; // Already initialized

    // Create sample users
    const sampleUsers = [
      {
        username: 'admin',
        email: 'admin@stargate.ci',
        displayName: 'Administrator',
        bio: 'Platform administrator and community manager',
        badges: []
      },
      {
        username: 'developer',
        email: 'dev@stargate.ci',
        displayName: 'Lead Developer',
        bio: 'Fullstack developer working on AI integration',
        badges: []
      },
      {
        username: 'researcher',
        email: 'research@stargate.ci',
        displayName: 'AI Researcher',
        bio: 'Research specialist in machine learning and AI',
        badges: []
      }
    ];

    for (const userData of sampleUsers) {
      await this.createProfile(userData);
    }

    // Create sample posts
    const categories = await this.getCategories();
    const users = await this.getProfiles();

    const samplePosts = [
      {
        title: 'Welcome to Stargate.ci Community!',
        content: 'Welcome everyone to our new community platform! This is where we can discuss AI, Cloud Computing, Big Data, and Advanced Computing topics.',
        author: users[0],
        isLocked: false,
        isSolved: false,
        category: categories[0],
        tags: ['welcome', 'community', 'introduction'],
        isPinned: true
      },
      {
        title: 'Getting Started with AI Integration',
        content: 'I\'m new to AI and would like to learn how to integrate AI capabilities into my applications. Any recommendations for beginners?',
        author: users[1],
        isLocked: false,
        isSolved: false,
        category: categories[1],
        tags: ['ai', 'beginner', 'integration', 'help'],
        isPinned: false
      },
      {
        title: 'Best Practices for Cloud Security',
        content: 'What are the current best practices for securing cloud infrastructure? I\'m particularly interested in multi-cloud environments.',
        author: users[2],
        isLocked: false,
        isSolved: false,
        category: categories[2],
        tags: ['cloud', 'security', 'best-practices', 'multi-cloud'],
        isPinned: false
      }
    ];

    for (const postData of samplePosts) {
      await this.createPost(postData);
    }
  }
}

export const communityService = new CommunityService();