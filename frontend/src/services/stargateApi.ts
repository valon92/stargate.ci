// Stargate API Service
// Integrates with official Stargate Project and Cristal Intelligence data sources

export interface StargateProjectData {
  totalInvestment: string;
  initialDeploy: string;
  projectTimeline: string;
  location: string;
  jobs: string;
  partners: number;
  description: string;
  source: string;
  lastUpdated: string;
}

export interface CristalIntelligenceData {
  name: string;
  description: string;
  partnership: {
    openai: string;
    softbank: string;
    arm: string;
    investment: string;
  };
  features: string[];
  source: string;
  lastUpdated: string;
}

export interface PartnerInfo {
  name: string;
  role: string;
  description: string;
  website: string;
  logo: string;
}

// Official Stargate Project Data
export const stargateProjectData: StargateProjectData = {
  totalInvestment: "$500B",
  initialDeploy: "$100B",
  projectTimeline: "4 Years",
  location: "Starting in Texas",
  jobs: "100K+ American Jobs",
  partners: 7,
  description: "Stargate Project will invest $500 billion over the next four years, with an immediate deployment of $100 billion, starting from Texas to build next-generation AI computing infrastructure, advancing American leadership in AI and generating massive economic benefits.",
  source: "https://stargateprojects.net/",
  lastUpdated: "2025-01-11"
};

// Official Cristal Intelligence Data from SoftBank Partnership
export const cristalIntelligenceData: CristalIntelligenceData = {
  name: "Cristal Intelligence",
  description: "Advanced Enterprise AI that securely integrates the systems and data of individual enterprises in a way that is customized specifically for each company.",
  partnership: {
    openai: "Lead Operational Partner - Leading operational responsibility and AI development",
    softbank: "Lead Financial Partner - Leading financial responsibility under Chairman Masayoshi Son",
    arm: "Technology Partner - Advanced computing architecture provider",
    investment: "$3 billion US annually"
  },
  features: [
    "Secure enterprise data integration",
    "Customized AI solutions for each company",
    "AI agents for knowledge work automation",
    "Advanced reasoning capabilities",
    "Independent task execution",
    "Financial report generation",
    "Document drafting",
    "Customer inquiry management"
  ],
  source: "https://group.softbank/en/news/press/20250203_0",
  lastUpdated: "2025-02-03"
};

// Official Partners Information
export const officialPartners: PartnerInfo[] = [
  {
    name: "OpenAI",
    role: "Lead Operational Partner",
    description: "Leading operational responsibility and AI development",
    website: "https://openai.com",
    logo: "/assets/logos/openai-logo.svg"
  },
  {
    name: "SoftBank",
    role: "Lead Financial Partner", 
    description: "Leading financial responsibility under Chairman Masayoshi Son",
    website: "https://group.softbank",
    logo: "/assets/logos/softbank-logo.svg"
  },
  {
    name: "NVIDIA",
    role: "Core Technology Partner",
    description: "Deep collaboration since 2016 in AI computing",
    website: "https://nvidia.com",
    logo: "/assets/logos/nvidia-logo.svg"
  },
  {
    name: "Oracle",
    role: "Infrastructure Partner",
    description: "Key infrastructure and computing systems provider",
    website: "https://oracle.com",
    logo: "/assets/logos/oracle-logo.svg"
  },
  {
    name: "Microsoft",
    role: "Strategic Technology Partner",
    description: "Azure cloud infrastructure and continued partnership",
    website: "https://microsoft.com",
    logo: "/assets/logos/microsoft-logo.svg"
  },
  {
    name: "ARM",
    role: "Technology Partner",
    description: "Advanced computing architecture provider",
    website: "https://arm.com",
    logo: "/assets/logos/arm-logo.svg"
  }
];

// API Service Class
export class StargateApiService {
  private static instance: StargateApiService;
  
  public static getInstance(): StargateApiService {
    if (!StargateApiService.instance) {
      StargateApiService.instance = new StargateApiService();
    }
    return StargateApiService.instance;
  }

  // Get official Stargate Project data
  public async getStargateProjectData(): Promise<StargateProjectData> {
    // In a real implementation, this would fetch from the official API
    // For now, we return the official data we have
    return Promise.resolve(stargateProjectData);
  }

  // Get official Cristal Intelligence data
  public async getCristalIntelligenceData(): Promise<CristalIntelligenceData> {
    // In a real implementation, this would fetch from the official API
    // For now, we return the official data we have
    return Promise.resolve(cristalIntelligenceData);
  }

  // Get official partners information
  public async getOfficialPartners(): Promise<PartnerInfo[]> {
    // In a real implementation, this would fetch from the official API
    // For now, we return the official data we have
    return Promise.resolve(officialPartners);
  }

  // Verify data freshness
  public async checkDataFreshness(): Promise<{ isFresh: boolean; lastUpdated: string }> {
    const now = new Date();
    const lastUpdate = new Date('2025-02-03'); // Latest official update
    const daysSinceUpdate = Math.floor((now.getTime() - lastUpdate.getTime()) / (1000 * 60 * 60 * 24));
    
    return {
      isFresh: daysSinceUpdate < 30, // Consider fresh if updated within 30 days
      lastUpdated: lastUpdate.toISOString()
    };
  }

  // Get source attribution
  public getSourceAttribution(): { sources: string[]; copyright: string } {
    return {
      sources: [
        "https://stargateprojects.net/",
        "https://group.softbank/en/news/press/20250203_0",
        "https://openai.com/index/announcing-the-stargate-project/"
      ],
      copyright: "All information sourced from official project announcements and press releases. Copyright belongs to respective organizations: OpenAI, SoftBank Group, ARM, NVIDIA, Oracle, Microsoft."
    };
  }
}

// Export singleton instance
export const stargateApi = StargateApiService.getInstance();
