// Connection Service
// Facilitates connections between users and official Stargate/Cristal Intelligence projects
// Core functionality of stargate.ci platform

import { stargateApi, type InvestmentOpportunity } from './stargateApi'

export interface UserProfile {
  id?: string;
  name: string;
  email: string;
  company: string;
  companySize: 'startup' | 'small' | 'medium' | 'large' | 'enterprise';
  industry: string;
  investmentCapacity: 'low' | 'medium' | 'high' | 'enterprise';
  interests: string[];
  location: string;
  phone?: string;
  website?: string;
  message?: string;
  preferredContact: 'email' | 'phone' | 'both';
  createdAt?: Date;
}

export interface ConnectionRequest {
  id: string;
  userProfile: UserProfile;
  opportunityId: string;
  opportunity: InvestmentOpportunity;
  status: 'pending' | 'reviewed' | 'contacted' | 'in-progress' | 'completed' | 'declined';
  message: string;
  requestedAt: Date;
  lastUpdated: Date;
  notes?: string;
}

export interface ProjectMatch {
  opportunity: InvestmentOpportunity;
  matchScore: number;
  reasons: string[];
  recommendations: string[];
}

export class ConnectionService {
  private static instance: ConnectionService;
  private connections: ConnectionRequest[] = [];
  private userProfiles: UserProfile[] = [];

  public static getInstance(): ConnectionService {
    if (!ConnectionService.instance) {
      ConnectionService.instance = new ConnectionService();
    }
    return ConnectionService.instance;
  }

  // Create user profile
  public async createUserProfile(profile: UserProfile): Promise<UserProfile> {
    const newProfile: UserProfile = {
      ...profile,
      id: this.generateId(),
      createdAt: new Date()
    };
    
    this.userProfiles.push(newProfile);
    return newProfile;
  }

  // Get user profile by ID
  public async getUserProfile(id: string): Promise<UserProfile | null> {
    return this.userProfiles.find(profile => profile.id === id) || null;
  }

  // Update user profile
  public async updateUserProfile(id: string, updates: Partial<UserProfile>): Promise<UserProfile | null> {
    const index = this.userProfiles.findIndex(profile => profile.id === id);
    if (index === -1) return null;

    this.userProfiles[index] = { ...this.userProfiles[index], ...updates };
    return this.userProfiles[index];
  }

  // Find matching opportunities for user
  public async findMatchingOpportunities(userProfile: UserProfile): Promise<ProjectMatch[]> {
    const opportunities = await stargateApi.getInvestmentOpportunities();
    const matches: ProjectMatch[] = [];

    for (const opportunity of opportunities) {
      const matchScore = this.calculateMatchScore(userProfile, opportunity);
      
      if (matchScore > 0.3) { // Only include matches with 30%+ compatibility
        matches.push({
          opportunity,
          matchScore,
          reasons: this.getMatchReasons(userProfile, opportunity),
          recommendations: this.getRecommendations(userProfile, opportunity)
        });
      }
    }

    return matches.sort((a, b) => b.matchScore - a.matchScore);
  }

  // Calculate match score between user and opportunity
  private calculateMatchScore(userProfile: UserProfile, opportunity: InvestmentOpportunity): number {
    let score = 0;
    let factors = 0;

    // Investment capacity match (40% weight)
    const capacityMatch = this.matchInvestmentCapacity(userProfile.investmentCapacity, opportunity.investmentRange);
    score += capacityMatch ? 0.4 : 0;
    factors++;

    // Company size match (30% weight)
    const sizeMatch = this.matchCompanySize(userProfile.companySize, opportunity.requirements);
    score += sizeMatch ? 0.3 : 0;
    factors++;

    // Industry relevance (20% weight)
    const industryMatch = this.matchIndustry(userProfile.industry, opportunity.benefits);
    score += industryMatch ? 0.2 : 0;
    factors++;

    // Interest alignment (10% weight)
    const interestMatch = this.matchInterests(userProfile.interests, opportunity.benefits);
    score += interestMatch ? 0.1 : 0;
    factors++;

    return factors > 0 ? score : 0;
  }

  // Helper methods for matching
  private matchInvestmentCapacity(capacity: string, range: string): boolean {
    const capacityMap = {
      'low': ['$100K', '$500K', '$1M'],
      'medium': ['$1M', '$5M', '$10M'],
      'high': ['$10M', '$50M', '$100M'],
      'enterprise': ['$100M', '$500M', '$1B']
    };
    
    const userRanges = capacityMap[capacity as keyof typeof capacityMap] || [];
    return userRanges.some(userRange => range.includes(userRange));
  }

  private matchCompanySize(size: string, requirements: string[]): boolean {
    const sizeMap = {
      'startup': ['startup', 'small', 'minimum'],
      'small': ['small', 'medium', 'minimum'],
      'medium': ['medium', 'large', 'enterprise'],
      'large': ['large', 'enterprise'],
      'enterprise': ['enterprise', 'large']
    };
    
    const userSizes = sizeMap[size as keyof typeof sizeMap] || [];
    return requirements.some(req => 
      userSizes.some(size => req.toLowerCase().includes(size))
    );
  }

  private matchIndustry(industry: string, benefits: string[]): boolean {
    const industryKeywords = industry.toLowerCase().split(' ');
    return benefits.some(benefit => 
      industryKeywords.some(keyword => 
        benefit.toLowerCase().includes(keyword)
      )
    );
  }

  private matchInterests(interests: string[], benefits: string[]): boolean {
    return interests.some(interest => 
      benefits.some(benefit => 
        benefit.toLowerCase().includes(interest.toLowerCase()) ||
        interest.toLowerCase().includes(benefit.toLowerCase())
      )
    );
  }

  // Get match reasons
  private getMatchReasons(userProfile: UserProfile, opportunity: InvestmentOpportunity): string[] {
    const reasons: string[] = [];

    if (this.matchInvestmentCapacity(userProfile.investmentCapacity, opportunity.investmentRange)) {
      reasons.push(`Investment capacity aligns with ${opportunity.investmentRange} range`);
    }

    if (this.matchCompanySize(userProfile.companySize, opportunity.requirements)) {
      reasons.push(`Company size (${userProfile.companySize}) matches opportunity requirements`);
    }

    if (this.matchIndustry(userProfile.industry, opportunity.benefits)) {
      reasons.push(`Industry (${userProfile.industry}) is relevant to this opportunity`);
    }

    if (this.matchInterests(userProfile.interests, opportunity.benefits)) {
      reasons.push('Your interests align with the opportunity benefits');
    }

    return reasons;
  }

  // Get recommendations
  private getRecommendations(userProfile: UserProfile, opportunity: InvestmentOpportunity): string[] {
    const recommendations: string[] = [];

    if (!this.matchInvestmentCapacity(userProfile.investmentCapacity, opportunity.investmentRange)) {
      recommendations.push('Consider increasing investment capacity or exploring smaller opportunities');
    }

    if (!this.matchCompanySize(userProfile.companySize, opportunity.requirements)) {
      recommendations.push('Consider company growth or partnership opportunities');
    }

    recommendations.push('Review all requirements carefully before proceeding');
    recommendations.push('Prepare detailed business case and financial projections');
    recommendations.push('Ensure compliance with all regulatory requirements');

    return recommendations;
  }

  // Create connection request
  public async createConnectionRequest(
    userProfile: UserProfile, 
    opportunityId: string, 
    message: string
  ): Promise<ConnectionRequest> {
    const opportunity = await stargateApi.getInvestmentOpportunity(opportunityId);
    if (!opportunity) {
      throw new Error('Opportunity not found');
    }

    const connectionRequest: ConnectionRequest = {
      id: this.generateId(),
      userProfile,
      opportunityId,
      opportunity,
      status: 'pending',
      message,
      requestedAt: new Date(),
      lastUpdated: new Date()
    };

    this.connections.push(connectionRequest);
    return connectionRequest;
  }

  // Get connection requests
  public async getConnectionRequests(): Promise<ConnectionRequest[]> {
    return this.connections;
  }

  // Get connection request by ID
  public async getConnectionRequest(id: string): Promise<ConnectionRequest | null> {
    return this.connections.find(conn => conn.id === id) || null;
  }

  // Update connection request status
  public async updateConnectionStatus(
    id: string, 
    status: ConnectionRequest['status'], 
    notes?: string
  ): Promise<ConnectionRequest | null> {
    const index = this.connections.findIndex(conn => conn.id === id);
    if (index === -1) return null;

    this.connections[index] = {
      ...this.connections[index],
      status,
      notes,
      lastUpdated: new Date()
    };

    return this.connections[index];
  }

  // Get platform statistics
  public async getPlatformStats(): Promise<{
    totalUsers: number;
    totalConnections: number;
    activeOpportunities: number;
    successRate: number;
  }> {
    const totalUsers = this.userProfiles.length;
    const totalConnections = this.connections.length;
    const activeOpportunities = (await stargateApi.getInvestmentOpportunities()).length;
    const successfulConnections = this.connections.filter(conn => 
      ['in-progress', 'completed'].includes(conn.status)
    ).length;
    const successRate = totalConnections > 0 ? (successfulConnections / totalConnections) * 100 : 0;

    return {
      totalUsers,
      totalConnections,
      activeOpportunities,
      successRate
    };
  }

  // Generate unique ID
  private generateId(): string {
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
  }

  // Export user data for official project contact
  public async exportUserDataForProject(connectionId: string): Promise<{
    userInfo: UserProfile;
    opportunity: InvestmentOpportunity;
    message: string;
    platformInfo: {
      source: 'stargate.ci';
      timestamp: Date;
      connectionId: string;
    };
  }> {
    const connection = await this.getConnectionRequest(connectionId);
    if (!connection) {
      throw new Error('Connection not found');
    }

    return {
      userInfo: connection.userProfile,
      opportunity: connection.opportunity,
      message: connection.message,
      platformInfo: {
        source: 'stargate.ci',
        timestamp: new Date(),
        connectionId: connection.id
      }
    };
  }
}

// Export singleton instance
export const connectionService = ConnectionService.getInstance();
