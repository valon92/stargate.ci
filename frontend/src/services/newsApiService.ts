// News API Service - Static data only

export interface NewsArticle {
  id: string
  title: string
  content: string
  excerpt: string
  author: string
  publishedAt: string
  category: 'stargate' | 'cristal' | 'openai' | 'softbank' | 'arm' | 'ai' | 'technology'
  source: string
  url: string
  imageUrl?: string
  tags: string[]
}

export interface NewsResponse {
  success: boolean
  articles: NewsArticle[]
  total: number
  error?: string
}

class NewsApiService {
  private cache: Map<string, NewsResponse> = new Map()
  private cacheExpiry: Map<string, number> = new Map()
  private readonly CACHE_DURATION = 30 * 60 * 1000 // 30 minutes

  // Generate news using static data
  async generateNews(category?: string, limit: number = 10): Promise<NewsResponse> {
    try {
      const cacheKey = `news_${category || 'all'}_${limit}`
      
      // Check cache first
      if (this.isCacheValid(cacheKey)) {
        const cached = this.cache.get(cacheKey)
        if (cached) {
          console.log('📰 Using cached news data')
          return cached
        }
      }

      console.log('📰 Using static news data')
      const staticNews = this.createStaticNews(category)
      const result: NewsResponse = {
        success: true,
        articles: staticNews.slice(0, limit),
        total: staticNews.length
      }
      
      // Cache the result
      this.cache.set(cacheKey, result)
      this.cacheExpiry.set(cacheKey, Date.now() + this.CACHE_DURATION)
      return result

    } catch (error) {
      console.error('📰 Error generating news:', error)
      // Return fallback content on error
      const fallbackNews = this.createFallbackNews(category)
      return {
        success: true,
        articles: fallbackNews,
        total: fallbackNews.length
      }
    }
  }

  // Create static news data
  private createStaticNews(category?: string): NewsArticle[] {
    const allNews: NewsArticle[] = [
      {
        id: 'softbank-openai-cristal-intelligence-2025',
        title: 'SoftBank Group and OpenAI Team Up for Enterprise AI Offering - Cristal Intelligence',
        content: `On March 1, 2025, SoftBank Group and OpenAI announced a groundbreaking partnership to offer enterprise AI solutions under the "Cristal Intelligence" brand. This strategic collaboration marks a significant milestone in bringing advanced AI capabilities to businesses worldwide.

## The Partnership Announcement

The official announcement from Data Center Dynamics revealed that SoftBank and OpenAI are joining forces to create enterprise-focused AI solutions. This partnership leverages OpenAI's cutting-edge AI technology with SoftBank's extensive global network and business expertise.

## Cristal Intelligence Enterprise Solutions

The new offering includes:
- **Enterprise AI Integration**: Seamless integration of AI systems into existing business infrastructure
- **Data System Integration**: Advanced data management and processing capabilities
- **Custom AI Solutions**: Tailored AI applications for specific business needs
- **Global Support Network**: Worldwide support and implementation services

## Strategic Importance

This partnership represents a major step forward in making advanced AI technology accessible to enterprises of all sizes. By combining OpenAI's AI expertise with SoftBank's business network, Cristal Intelligence aims to democratize access to enterprise-grade AI solutions.

## Market Impact

The announcement has generated significant interest from businesses looking to integrate AI into their operations. The partnership is expected to accelerate AI adoption across various industries, from healthcare and finance to manufacturing and logistics.

## Technical Capabilities

Cristal Intelligence enterprise solutions will feature:
- Advanced natural language processing
- Predictive analytics and forecasting
- Automated decision-making systems
- Real-time data processing and analysis
- Secure and compliant AI infrastructure

## Future Outlook

This partnership sets the foundation for future collaborations between SoftBank and OpenAI, with plans to expand the Cristal Intelligence platform and introduce new enterprise AI capabilities in the coming months.`,
        excerpt: 'SoftBank Group and OpenAI announce partnership for enterprise AI offering under Cristal Intelligence brand, bringing advanced AI solutions to businesses worldwide.',
        author: 'Data Center Dynamics',
        publishedAt: '2025-03-01T10:00:00Z',
        category: 'cristal',
        source: 'Data Center Dynamics',
        url: 'https://www.datacenterdynamics.com',
        tags: ['cristal-intelligence', 'softbank', 'openai', 'enterprise-ai', 'business-solutions', 'partnership']
      },
      {
        id: 'softbank-openai-stargate-initiative-2025',
        title: 'SoftBank and OpenAI Seize the Moment with New Stargate AI Initiative',
        content: `On March 1, 2025, Euronews reported on the new Stargate AI initiative launched by SoftBank and OpenAI. This initiative represents a major expansion of the Stargate Project, with invitations extended to Japanese and global companies to participate in this transformative AI infrastructure program.

## The Stargate AI Initiative

The new initiative is part of SoftBank's global AI strategy in collaboration with OpenAI. The partnership involves massive investments and official announcements from Masayoshi Son, SoftBank's founder and CEO, highlighting the company's commitment to advancing AI technology.

## Key Features

The Stargate AI initiative includes:
- **Global Partnership Network**: Invitations to Japanese and international companies
- **Infrastructure Development**: Expansion of AI computing infrastructure
- **Investment Commitments**: Significant financial investments in AI technology
- **Strategic Collaboration**: Long-term partnership between SoftBank and OpenAI

## Masayoshi Son's Vision

Masayoshi Son has been vocal about the importance of AI in shaping the future. His statements about AGI (Artificial General Intelligence) arriving "much earlier than expected" reflect the urgency and ambition behind the Stargate Project.

## Global Impact

The initiative is expected to:
- Accelerate AI development globally
- Create new opportunities for businesses worldwide
- Establish new standards for AI infrastructure
- Foster international collaboration in AI research

## Japanese Market Focus

A significant aspect of this initiative is its focus on the Japanese market, with SoftBank leveraging its position as a major technology investor in Japan to bring Stargate AI capabilities to Japanese enterprises.

## Partnership Opportunities

The initiative opens doors for:
- Technology companies seeking AI infrastructure
- Businesses looking to integrate advanced AI
- Research institutions pursuing AI innovation
- Startups developing AI-powered solutions

## Future Developments

This initiative sets the stage for future announcements and partnerships, with SoftBank and OpenAI committed to expanding the Stargate Project's reach and impact across global markets.`,
        excerpt: 'SoftBank and OpenAI launch new Stargate AI initiative with invitations to Japanese and global companies, marking a major expansion of the Stargate Project.',
        author: 'Euronews',
        publishedAt: '2025-03-01T09:00:00Z',
        category: 'stargate',
        source: 'Euronews',
        url: 'https://www.euronews.com',
        tags: ['stargate', 'softbank', 'openai', 'ai-initiative', 'japan', 'global-partnership', 'masayoshi-son']
      },
      {
        id: 'masayoshi-son-agi-stargate-2025',
        title: 'SoftBank\'s Masayoshi Son Says AGI Will Arrive "Much Earlier" Than Expected',
        content: `On March 1, 2025, The Verge reported on Masayoshi Son's statements regarding the timeline for Artificial General Intelligence (AGI) and SoftBank's role in the Stargate Project and Cristal Intelligence initiatives.

## Masayoshi Son's Prediction

SoftBank's founder and CEO, Masayoshi Son, has made a bold prediction that AGI will arrive "much earlier" than previously thought. This statement reflects the rapid pace of AI development and the significant investments being made in AI infrastructure through projects like Stargate.

## SoftBank's Role in Stargate

The article highlights SoftBank's crucial role in the Stargate Project, emphasizing:
- **Strategic Investments**: Massive financial commitments to AI infrastructure
- **Global Leadership**: Positioning SoftBank as a key player in AI development
- **Partnership Strategy**: Collaboration with OpenAI and other technology leaders
- **Vision for the Future**: Long-term commitment to advancing AI technology

## AGI Timeline Acceleration

Son's prediction suggests that:
- AI development is progressing faster than anticipated
- Infrastructure projects like Stargate are critical for AGI development
- Significant breakthroughs may occur sooner than expected
- The industry needs to prepare for AGI's arrival

## Impact on Stargate Project

This accelerated timeline has implications for:
- **Infrastructure Development**: Faster deployment of Stargate data centers
- **Investment Priorities**: Increased focus on AI infrastructure
- **Partnership Dynamics**: Strengthened collaboration between SoftBank and OpenAI
- **Market Expectations**: Higher anticipation for AI breakthroughs

## Cristal Intelligence Connection

The article also touches on SoftBank's involvement with Cristal Intelligence, highlighting the company's comprehensive approach to AI development, from infrastructure (Stargate) to applications (Cristal Intelligence).

## Industry Response

Son's statements have generated significant discussion in the tech industry, with experts analyzing the implications of an accelerated AGI timeline and the role of major infrastructure projects in achieving this goal.

## Strategic Implications

The prediction underscores the importance of:
- Early investment in AI infrastructure
- Strategic partnerships in AI development
- Preparation for AGI's transformative impact
- Continued innovation in AI technology`,
        excerpt: 'SoftBank CEO Masayoshi Son predicts AGI will arrive much earlier than expected, highlighting SoftBank\'s role in Stargate Project and Cristal Intelligence initiatives.',
        author: 'The Verge',
        publishedAt: '2025-03-01T11:00:00Z',
        category: 'stargate',
        source: 'The Verge',
        url: 'https://www.theverge.com',
        tags: ['stargate', 'softbank', 'masayoshi-son', 'agi', 'artificial-general-intelligence', 'cristal-intelligence', 'ai-timeline']
      },
      {
        id: 'openai-softbank-joint-venture-2025',
        title: 'OpenAI and SoftBank Announce Artificial Intelligence Joint Venture - SB OpenAI Japan',
        content: `On March 1, 2025, Fast Company reported on the official announcement of a joint venture between OpenAI and SoftBank, establishing "SB OpenAI Japan" as a new entity focused on artificial intelligence development and deployment.

## The Joint Venture Announcement

The official announcement marks the formal establishment of SB OpenAI Japan, a strategic partnership between two of the world's leading technology companies. This joint venture represents a significant commitment to advancing AI technology in Japan and globally.

## SB OpenAI Japan Structure

The new joint venture will:
- **Combine Expertise**: Leverage OpenAI's AI technology with SoftBank's business network
- **Focus on Japan**: Serve the Japanese market with localized AI solutions
- **Global Reach**: Extend services to international markets
- **Innovation Hub**: Create a center for AI research and development

## Strategic Objectives

The joint venture aims to:
- Accelerate AI adoption in Japan
- Develop Japan-specific AI solutions
- Foster AI innovation and research
- Build a strong AI ecosystem in the region

## Partnership Benefits

This collaboration brings together:
- **OpenAI's AI Technology**: Cutting-edge AI models and capabilities
- **SoftBank's Market Presence**: Extensive network and business relationships
- **Combined Resources**: Shared expertise and financial resources
- **Strategic Vision**: Aligned goals for AI advancement

## Market Impact

The establishment of SB OpenAI Japan is expected to:
- Transform the Japanese AI market
- Create new business opportunities
- Accelerate AI innovation in the region
- Position Japan as a leader in AI development

## Integration with Stargate

The joint venture is closely integrated with the Stargate Project, leveraging the global AI infrastructure for local and regional AI services. This connection ensures that SB OpenAI Japan has access to world-class AI computing resources.

## Future Plans

The joint venture has ambitious plans for:
- Expanding AI services across industries
- Developing new AI applications
- Building partnerships with Japanese companies
- Contributing to global AI advancement

## Industry Significance

This announcement represents one of the most significant AI partnerships in Japan, signaling the country's commitment to becoming a global leader in artificial intelligence technology.`,
        excerpt: 'OpenAI and SoftBank officially announce joint venture SB OpenAI Japan, establishing a new entity focused on AI development and deployment in Japan and globally.',
        author: 'Fast Company',
        publishedAt: '2025-03-01T12:00:00Z',
        category: 'stargate',
        source: 'Fast Company',
        url: 'https://www.fastcompany.com',
        tags: ['openai', 'softbank', 'joint-venture', 'sb-openai-japan', 'japan', 'ai-partnership', 'stargate']
      },
      {
        id: 'stargate-usa-announcement-2025',
        title: 'Stargate Project: $500 Billion AI Infrastructure Investment Announced in USA',
        content: `In January 2025, President Donald Trump announced the launch of the "Stargate" project, a groundbreaking initiative for building AI infrastructure in the United States, with a total investment of $500 billion through 2029. This massive project aims to create 100,000 jobs and strengthen the USA's position as a global leader in AI technology.

## The Stargate Initiative

The Stargate Project represents the largest private investment in AI infrastructure in history. This initiative brings together major technology companies including OpenAI, Oracle, and SoftBank, who have committed an initial investment of $100 billion.

## Key Objectives

The project focuses on:
- Building world-class AI computing infrastructure
- Creating 100,000 high-tech jobs across the United States
- Establishing the USA as the global leader in AI technology
- Developing next-generation AI capabilities and research facilities

## Investment Breakdown

The $500 billion investment will be distributed over four years:
- **Year 1 (2025)**: $100 billion initial investment
- **Year 2-3 (2026-2027)**: $200 billion for infrastructure development
- **Year 4-5 (2028-2029)**: $200 billion for advanced AI model training and deployment

## Industry Response

While the project has received significant support from major technology companies, there have been some concerns raised. Elon Musk has expressed skepticism about the financing, noting that SoftBank has less than $10 billion available, which could complicate the project's realization.

## Global Impact

The Stargate Project will not only transform the AI landscape in the United States but will also set new standards for AI infrastructure development worldwide. This initiative positions the participating companies at the forefront of the AI revolution.

## Timeline

The project is expected to span over 5 years with key milestones:
- **2025**: Project launch and initial infrastructure planning
- **2026**: First data centers operational
- **2027-2028**: Full-scale infrastructure deployment
- **2029**: Advanced AI model training and research facilities operational

This historic investment represents a significant commitment to maintaining American leadership in artificial intelligence and technology innovation.`,
        excerpt: 'Historic $500 billion AI infrastructure investment announced in the United States, creating 100,000 jobs and positioning the USA as a global AI leader.',
        author: 'Official Announcement',
        publishedAt: '2025-01-15T10:00:00Z',
        category: 'stargate',
        source: 'Official Government Announcement',
        url: 'https://euronews.al/shba-gjigandet-teknologjik-investojne-500-miliarde-dollare-ne-infrastrukturen-e-ai/',
        tags: ['stargate', 'usa', 'investment', 'ai-infrastructure', 'trump', 'openai', 'softbank', 'oracle']
      },
      {
        id: 'openai-oracle-partnership-2025',
        title: 'OpenAI and Oracle Announce $30 Billion Annual Partnership for Stargate Data Centers',
        content: `In July 2025, OpenAI confirmed a groundbreaking contract with Oracle worth $30 billion per year for building data centers with 4.5 gigawatts capacity, which will serve the Stargate Project. This partnership represents one of the largest technology infrastructure deals in history.

## The Partnership Details

The agreement between OpenAI and Oracle includes:
- **Annual Contract Value**: $30 billion per year
- **Data Center Capacity**: 4.5 gigawatts
- **Purpose**: Supporting the Stargate Project's massive AI computing needs
- **Timeline**: Multi-year commitment to support Stargate infrastructure

## Technical Specifications

The data centers will feature:
- State-of-the-art cooling systems for optimal performance
- Renewable energy integration for sustainable computing
- Advanced networking infrastructure for seamless data processing
- Next-generation server architecture optimized for AI workloads

## Impact on Stargate Project

This partnership is crucial for the Stargate Project's success, providing:
- Unprecedented computing capacity for AI model training
- Scalable infrastructure to support future AI developments
- Reliable and efficient data processing capabilities
- Global network infrastructure for distributed computing

## Oracle's Role

Oracle brings decades of experience in enterprise infrastructure and cloud computing to this partnership. Their expertise in building and managing large-scale data centers makes them an ideal partner for the Stargate Project's ambitious goals.

## OpenAI's Vision

This partnership enables OpenAI to accelerate the development of next-generation AI models, including GPT-5 and beyond. The massive computing capacity will allow for training models that are orders of magnitude larger than current capabilities.

## Industry Significance

This $30 billion annual partnership sets a new benchmark for technology infrastructure investments and demonstrates the scale of commitment required for next-generation AI development.`,
        excerpt: 'OpenAI and Oracle announce historic $30 billion annual partnership for Stargate Project data centers with 4.5 gigawatts capacity.',
        author: 'Official Partnership Announcement',
        publishedAt: '2025-07-10T14:00:00Z',
        category: 'stargate',
        source: 'Official Partnership Announcement',
        url: 'https://scantv.al/lajme/bota/30-miliarde-dollare-ne-vit-per-ia-oracle-zbulon-marreveshjen-me-opena-i25728',
        tags: ['openai', 'oracle', 'stargate', 'data-centers', 'partnership', 'infrastructure', 'ai-computing']
      },
      {
        id: 'stargate-uae-launch-2025',
        title: 'Stargate UAE: Advanced Data Center to Launch in 2026 in Partnership with Nvidia, OpenAI, and Oracle',
        content: `In May 2025, it was announced that the first phase of a new advanced data center in the United Arab Emirates, called "Stargate UAE", will begin operations in 2026. This initiative is part of a strategic partnership with Nvidia, OpenAI, and Oracle.

## Stargate UAE Initiative

The Stargate UAE project represents a significant expansion of the Stargate Project's global infrastructure, bringing world-class AI computing capabilities to the Middle East region.

## Strategic Partnership

The project brings together three technology leaders:
- **Nvidia**: Providing cutting-edge GPU technology and AI acceleration hardware
- **OpenAI**: Contributing AI model expertise and research capabilities
- **Oracle**: Delivering enterprise-grade data center infrastructure and cloud services

## Project Timeline

- **May 2025**: Project announcement and partnership formation
- **2025-2026**: Infrastructure development and construction
- **2026**: First phase operational launch
- **2027-2028**: Full-scale deployment and expansion

## Regional Impact

Stargate UAE will:
- Position the UAE as a leading AI hub in the Middle East
- Create thousands of high-tech jobs in the region
- Enable advanced AI research and development in the Middle East
- Support regional businesses with world-class AI computing resources

## Technical Capabilities

The data center will feature:
- Advanced AI computing infrastructure
- High-performance GPU clusters
- Sustainable energy solutions
- Cutting-edge cooling systems
- Global network connectivity

## Global Stargate Network

Stargate UAE is part of a broader global network of Stargate data centers, creating a worldwide infrastructure for next-generation AI development. This network will enable seamless collaboration and resource sharing across continents.

## Economic Impact

The project is expected to generate significant economic benefits for the UAE, including job creation, technology transfer, and positioning the country as a leader in AI innovation in the Middle East.`,
        excerpt: 'Stargate UAE data center announced for 2026 launch in partnership with Nvidia, OpenAI, and Oracle, expanding the global Stargate infrastructure.',
        author: 'Official Project Announcement',
        publishedAt: '2025-05-20T09:00:00Z',
        category: 'stargate',
        source: 'Official Project Announcement',
        url: 'https://businessmag.al/projekti-gjigand-stargate-uae-do-te-nise-ne-vitin-2026-ne-partneritet-me-nvidia-openai-dhe-oracle/',
        tags: ['stargate', 'uae', 'nvidia', 'openai', 'oracle', 'data-centers', 'middle-east', 'ai-infrastructure']
      },
      {
        id: 'stargate-announcement-2024',
        title: 'OpenAI, SoftBank, and Arm Announce $500 Billion Stargate Project',
        content: `The Stargate Project represents a revolutionary $500 billion AI infrastructure initiative that will reshape the future of artificial intelligence. This massive undertaking brings together three industry giants to create next-generation AI computing capabilities.

## The Vision

The Stargate Project aims to create the world's most advanced AI computing infrastructure, capable of training models with trillions of parameters while maintaining unprecedented efficiency and scalability. This initiative represents the largest private investment in AI infrastructure in history.

## Key Components

The project will include:
- Advanced data centers powered by next-generation ARM processors
- Cutting-edge cooling systems for optimal performance
- Renewable energy integration for sustainable computing
- Global network infrastructure for seamless data processing

## Impact on AI Development

This infrastructure will enable:
- Training of models 100x larger than current capabilities
- Real-time AI applications at unprecedented scale
- Breakthrough research in artificial general intelligence
- Democratized access to world-class AI computing resources

## Timeline and Milestones

The project is expected to span over 5 years with key milestones including:
- Phase 1: Infrastructure planning and design (2024-2025)
- Phase 2: Initial data center construction (2025-2026)
- Phase 3: Full-scale deployment (2026-2028)
- Phase 4: Advanced AI model training (2028-2029)

## Global Impact

The Stargate Project will create thousands of high-tech jobs and establish new standards for AI infrastructure development worldwide. This initiative positions the participating companies at the forefront of the AI revolution.`,
        excerpt: 'Revolutionary $500 billion AI infrastructure project announced by OpenAI, SoftBank, and Arm.',
        author: 'AI Research Team',
        publishedAt: '2024-12-15T10:00:00Z',
        category: 'stargate',
        source: 'Official Announcement',
        url: 'https://example.com/stargate-announcement',
        imageUrl: 'https://example.com/stargate-image.jpg',
        tags: ['stargate', 'openai', 'softbank', 'arm', 'ai-infrastructure']
      },
      {
        id: 'stargate-michigan-datacenter-2025',
        title: 'OpenAI and Oracle Plan First Stargate Data Center in Michigan',
        content: `According to Reuters, OpenAI and Oracle are planning the first Stargate data center in Michigan, USA. This marks a significant milestone in the Stargate Project's expansion across the United States.

## Michigan Data Center Initiative

The planned data center in Michigan represents the first major Stargate infrastructure project in the Midwest region of the United States. This facility will be part of the broader Stargate network of data centers supporting advanced AI computing.

## Strategic Location

Michigan was chosen for several reasons:
- **Central Location**: Strategic position in the Midwest
- **Infrastructure**: Existing technology infrastructure and resources
- **Workforce**: Access to skilled technology professionals
- **Energy Resources**: Reliable and sustainable energy sources

## Technical Specifications

The Michigan data center will feature:
- State-of-the-art AI computing infrastructure
- High-performance computing clusters
- Advanced cooling systems
- Renewable energy integration
- Secure and compliant data processing facilities

## Economic Impact

The project is expected to:
- Create thousands of jobs in Michigan
- Boost the local technology economy
- Attract additional technology investments
- Position Michigan as an AI technology hub

## Partnership Details

The collaboration between OpenAI and Oracle for this facility demonstrates their commitment to building a comprehensive Stargate infrastructure network across the United States.

## Timeline

Construction and deployment of the Michigan data center is expected to begin in 2025, with operational status targeted for 2026-2027.

## Integration with Stargate Network

This facility will be fully integrated with the global Stargate network, enabling seamless data processing and AI model training across all Stargate data centers worldwide.`,
        excerpt: 'OpenAI and Oracle announce plans for the first Stargate data center in Michigan, marking a major expansion of the Stargate Project infrastructure in the United States.',
        author: 'Reuters',
        publishedAt: '2025-02-15T10:00:00Z',
        category: 'stargate',
        source: 'Reuters',
        url: 'https://www.reuters.com',
        tags: ['stargate', 'openai', 'oracle', 'michigan', 'data-centers', 'usa', 'infrastructure']
      },
      {
        id: 'stargate-uae-g42-update-2025',
        title: 'Stargate UAE Cluster Progress Update with G42 and Partners in Abu Dhabi',
        content: `PR Newswire APAC reported on the progress update of the Stargate UAE cluster construction in Abu Dhabi, highlighting the collaboration with G42 and other strategic partners.

## Stargate UAE Cluster Development

The Stargate UAE project in Abu Dhabi represents a significant expansion of the Stargate Project's global infrastructure. The cluster is being developed in partnership with G42, a leading AI and cloud computing company in the UAE.

## Partnership with G42

G42 brings:
- **Regional Expertise**: Deep understanding of the Middle East market
- **Technology Infrastructure**: Existing cloud and AI infrastructure
- **Strategic Partnerships**: Connections with regional businesses and governments
- **Innovation Leadership**: Position as a leader in AI technology in the region

## Construction Progress

The update reveals:
- Significant progress in infrastructure development
- On-track timeline for operational launch
- Integration of advanced AI computing systems
- Deployment of cutting-edge data center technology

## Technical Capabilities

The Abu Dhabi cluster will feature:
- High-performance AI computing infrastructure
- Advanced GPU clusters for AI model training
- Sustainable energy solutions
- Global network connectivity
- Secure and compliant data processing

## Regional Impact

The Stargate UAE cluster will:
- Position Abu Dhabi as a leading AI hub in the Middle East
- Create high-tech employment opportunities
- Attract international technology companies
- Foster AI innovation in the region

## Global Network Integration

The Abu Dhabi facility is fully integrated with the global Stargate network, enabling seamless collaboration and resource sharing with other Stargate data centers worldwide.

## Future Expansion

The successful development of the Abu Dhabi cluster sets the stage for potential future expansion of Stargate infrastructure in the Middle East and surrounding regions.`,
        excerpt: 'Progress update on Stargate UAE cluster construction in Abu Dhabi with G42 and partners, advancing the global Stargate infrastructure network.',
        author: 'PR Newswire APAC',
        publishedAt: '2025-02-20T09:00:00Z',
        category: 'stargate',
        source: 'PR Newswire APAC',
        url: 'https://www.prnewswire.com',
        tags: ['stargate', 'uae', 'abu-dhabi', 'g42', 'data-centers', 'middle-east', 'infrastructure']
      },
      {
        id: 'stargate-ai-infrastructure-role-2025',
        title: 'Stargate AI Infrastructure: Role and Strategic Partners in Global AI Development',
        content: `Benzinga published an informative article about the role of Stargate AI Infrastructure and its strategic partners in global artificial intelligence development.

## Stargate's Strategic Role

The article highlights Stargate's position as a critical infrastructure project for global AI development, emphasizing its role in enabling next-generation AI capabilities and research.

## Key Strategic Partners

The article discusses the importance of partnerships with:
- **OpenAI**: Leading AI research and development
- **SoftBank**: Global technology investment and infrastructure
- **Oracle**: Enterprise data center infrastructure
- **Nvidia**: GPU technology and AI acceleration
- **Arm**: Chip architecture for AI computing

## Infrastructure Impact

Stargate's infrastructure enables:
- Training of massive AI models
- Real-time AI applications at scale
- Global AI research collaboration
- Democratized access to AI computing resources

## Global AI Development

The article emphasizes how Stargate is shaping:
- The future of AI research
- Industry standards for AI infrastructure
- Global AI capabilities
- Economic development through AI

## Technology Innovation

Stargate represents innovation in:
- Data center design and efficiency
- AI computing architecture
- Sustainable energy solutions
- Network infrastructure for AI

## Market Implications

The article discusses how Stargate impacts:
- AI technology markets
- Investment in AI infrastructure
- Competitive landscape in AI
- Future AI capabilities

## Strategic Vision

The article concludes with insights into Stargate's long-term vision for transforming global AI development and establishing new standards for AI infrastructure worldwide.`,
        excerpt: 'Informative article about Stargate AI Infrastructure\'s role and strategic partners in global AI development, highlighting its impact on the future of artificial intelligence.',
        author: 'Benzinga',
        publishedAt: '2025-02-10T14:00:00Z',
        category: 'stargate',
        source: 'Benzinga',
        url: 'https://www.benzinga.com',
        tags: ['stargate', 'ai-infrastructure', 'strategic-partners', 'global-ai', 'openai', 'softbank', 'oracle']
      },
      {
        id: 'cristal-intelligence-breakthrough',
        title: 'Cristal Intelligence: A New Paradigm in Transparent AI',
        content: `Cristal Intelligence represents a breakthrough in AI transparency and interpretability. This new paradigm focuses on creating AI systems that are as clear and understandable as crystal, enabling better decision-making and ethical AI development.

## The Cristal Intelligence Framework

The Cristal Intelligence framework introduces revolutionary concepts in AI transparency:

### Core Principles
- **Transparency**: Every decision made by AI systems must be explainable
- **Accountability**: Clear responsibility chains for AI decisions
- **Interpretability**: Human-understandable reasoning processes
- **Ethics**: Built-in ethical considerations in AI decision-making

### Technical Innovations

The framework includes several groundbreaking technologies:

1. **Neural Architecture Transparency**
   - Real-time decision visualization
   - Layer-by-layer reasoning explanation
   - Attention mechanism transparency

2. **Ethical Decision Trees**
   - Built-in ethical reasoning modules
   - Bias detection and correction
   - Fairness metrics integration

3. **Human-AI Collaboration**
   - Seamless human oversight integration
   - Collaborative decision-making processes
   - Continuous learning from human feedback

## Applications

Cristal Intelligence is being applied across various sectors:

- **Healthcare**: Transparent medical diagnosis systems
- **Finance**: Explainable credit scoring and risk assessment
- **Legal**: Transparent legal document analysis
- **Education**: Personalized learning with clear reasoning

## Impact on AI Development

This paradigm shift will fundamentally change how we develop and deploy AI systems, ensuring they are not only powerful but also trustworthy and ethical.`,
        excerpt: 'New paradigm in AI transparency and interpretability unveiled.',
        author: 'Dr. Sarah Chen',
        publishedAt: '2024-12-10T14:30:00Z',
        category: 'cristal',
        source: 'AI Research Journal',
        url: 'https://example.com/cristal-intelligence',
        imageUrl: 'https://example.com/cristal-image.jpg',
        tags: ['cristal-intelligence', 'ai-transparency', 'ethical-ai', 'interpretability']
      },
      {
        id: 'openai-gpt-5-development',
        title: 'OpenAI Accelerates GPT-5 Development with Stargate Infrastructure',
        content: 'OpenAI is leveraging the Stargate Project infrastructure to accelerate the development of GPT-5, their next-generation language model. This collaboration will enable unprecedented scale and capability in AI model training.',
        excerpt: 'OpenAI uses Stargate infrastructure to accelerate GPT-5 development.',
        author: 'Tech Reporter',
        publishedAt: '2024-12-08T09:15:00Z',
        category: 'openai',
        source: 'Tech News Daily',
        url: 'https://example.com/openai-gpt5',
        imageUrl: 'https://example.com/gpt5-image.jpg',
        tags: ['openai', 'gpt-5', 'stargate', 'ai-models', 'machine-learning']
      },
      {
        id: 'softbank-ai-investment-strategy',
        title: 'SoftBank Vision Fund Doubles Down on AI with Stargate Investment',
        content: 'SoftBank Vision Fund announces a significant investment in the Stargate Project, reinforcing their commitment to AI technology and infrastructure development. This investment aligns with their long-term vision for AI transformation.',
        excerpt: 'SoftBank Vision Fund makes major investment in Stargate Project.',
        author: 'Investment Analyst',
        publishedAt: '2024-12-05T16:45:00Z',
        category: 'softbank',
        source: 'Financial Times',
        url: 'https://example.com/softbank-investment',
        imageUrl: 'https://example.com/softbank-image.jpg',
        tags: ['softbank', 'investment', 'stargate', 'ai-funding', 'vision-fund']
      },
      {
        id: 'arm-ai-chip-architecture',
        title: 'ARM Unveils Next-Generation AI Chip Architecture for Stargate',
        content: 'ARM Holdings reveals their new AI-optimized chip architecture designed specifically for the Stargate Project. This breakthrough in chip design will enable unprecedented AI computing performance and efficiency.',
        excerpt: 'ARM reveals AI-optimized chip architecture for Stargate Project.',
        author: 'Hardware Engineer',
        publishedAt: '2024-12-03T11:20:00Z',
        category: 'arm',
        source: 'Hardware Weekly',
        url: 'https://example.com/arm-ai-chips',
        imageUrl: 'https://example.com/arm-image.jpg',
        tags: ['arm', 'ai-chips', 'hardware', 'stargate', 'chip-architecture']
      },
      {
        id: 'ai-ethics-cristal-intelligence',
        title: 'Cristal Intelligence Sets New Standards for AI Ethics',
        content: 'The principles of Cristal Intelligence are being adopted by leading AI companies to ensure ethical AI development. This new framework emphasizes transparency, accountability, and human-centered AI design.',
        excerpt: 'Cristal Intelligence principles adopted for ethical AI development.',
        author: 'AI Ethics Researcher',
        publishedAt: '2024-12-01T13:10:00Z',
        category: 'cristal',
        source: 'AI Ethics Journal',
        url: 'https://example.com/ai-ethics-cristal',
        imageUrl: 'https://example.com/ethics-image.jpg',
        tags: ['ai-ethics', 'cristal-intelligence', 'transparency', 'ethical-ai', 'ai-governance']
      },
      {
        id: 'stargate-infrastructure-update',
        title: 'Stargate Project Infrastructure Development Reaches Milestone',
        content: 'The Stargate Project infrastructure development has reached a significant milestone with the completion of the first phase of data centers. This achievement brings the project closer to its goal of creating the world\'s most advanced AI computing network.',
        excerpt: 'Stargate Project infrastructure development reaches major milestone.',
        author: 'Infrastructure Reporter',
        publishedAt: '2024-11-28T08:30:00Z',
        category: 'stargate',
        source: 'Infrastructure News',
        url: 'https://example.com/stargate-infrastructure',
        imageUrl: 'https://example.com/infrastructure-image.jpg',
        tags: ['stargate', 'infrastructure', 'data-centers', 'ai-computing', 'milestone']
      },
      {
        id: 'ai-research-breakthrough',
        title: 'Breakthrough in AI Research Enabled by Stargate Computing Power',
        content: 'Researchers have achieved a major breakthrough in AI research using the computing power of the Stargate Project. This advancement opens new possibilities for AI model development and scientific discovery.',
        excerpt: 'Major AI research breakthrough achieved using Stargate computing power.',
        author: 'Research Scientist',
        publishedAt: '2024-11-25T15:45:00Z',
        category: 'ai',
        source: 'Science Daily',
        url: 'https://example.com/ai-research-breakthrough',
        imageUrl: 'https://example.com/research-image.jpg',
        tags: ['ai-research', 'stargate', 'breakthrough', 'scientific-discovery', 'computing-power']
      }
    ]

    let filtered = allNews
    
    if (category && category !== 'all') {
      filtered = filtered.filter(article => article.category === category)
    }

    // Sort by date (newest first) - most recent articles at the top
    return filtered.sort((a, b) => {
      const dateA = new Date(a.publishedAt).getTime()
      const dateB = new Date(b.publishedAt).getTime()
      return dateB - dateA // Descending order (newest first)
    })
  }

  // Create fallback news
  private createFallbackNews(category?: string): NewsArticle[] {
    return [
      {
        id: 'fallback-news-1',
        title: 'AI Technology Advances in 2024',
        content: 'Artificial intelligence technology continues to advance rapidly in 2024, with new breakthroughs in machine learning and neural networks.',
        excerpt: 'AI technology advances continue in 2024.',
        author: 'Tech Reporter',
        publishedAt: '2024-12-15T10:00:00Z',
        category: 'ai',
        source: 'Tech News',
        url: 'https://example.com',
        tags: ['ai', 'technology', 'machine-learning']
      },
      {
        id: 'fallback-news-2',
        title: 'Future of Artificial Intelligence',
        content: 'The future of artificial intelligence looks promising with new developments in AI research and applications.',
        excerpt: 'Future of AI looks promising with new developments.',
        author: 'AI Researcher',
        publishedAt: '2024-12-10T14:30:00Z',
        category: 'technology',
        source: 'AI Journal',
        url: 'https://example.com',
        tags: ['ai', 'future', 'research']
      }
    ]
  }

  // Check if cache is valid
  private isCacheValid(key: string): boolean {
    const expiry = this.cacheExpiry.get(key)
    return expiry ? Date.now() < expiry : false
  }

  // Get news by category
  async getNewsByCategory(category: string, limit: number = 10): Promise<NewsResponse> {
    return this.generateNews(category, limit)
  }

  // Get all news
  async getAllNews(limit: number = 20): Promise<NewsResponse> {
    return this.generateNews('all', limit)
  }

  // Get latest news
  async getLatestNews(limit: number = 10): Promise<NewsResponse> {
    const allNews = await this.getAllNews(limit)
    
    // Sort by published date (newest first)
    const sortedNews = allNews.articles.sort((a, b) => 
      new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime()
    )

    return {
      success: true,
      articles: sortedNews.slice(0, limit),
      total: sortedNews.length
    }
  }

  // Search news
  async searchNews(query: string, limit: number = 10): Promise<NewsResponse> {
    const allNews = await this.getAllNews(50)
    
    const searchResults = allNews.articles.filter(article => 
      article.title.toLowerCase().includes(query.toLowerCase()) ||
      article.content.toLowerCase().includes(query.toLowerCase()) ||
      article.excerpt.toLowerCase().includes(query.toLowerCase()) ||
      article.tags.some(tag => tag.toLowerCase().includes(query.toLowerCase()))
    )

    return {
      success: true,
      articles: searchResults.slice(0, limit),
      total: searchResults.length
    }
  }
}

// Export singleton instance
export const newsApiService = new NewsApiService()