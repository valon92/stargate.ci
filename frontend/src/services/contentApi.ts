// Enhanced Content API Service for Stargate.ci
// This service provides comprehensive content management for the platform

import { backendApi, type ApiResponse, type FAQ as BackendFAQ } from './backendApi'

export interface Article {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  category: string;
  icon: string;
  author: string;
  read_time: number;
  published: boolean;
  created_at: string;
  updated_at: string;
  published_at: string;
}

export interface FAQ {
  id: number;
  question: string;
  answer: string;
  order: number;
  active: boolean;
  created_at: string;
  updated_at: string;
}

export interface ContactMessage {
  id: number;
  name: string;
  email: string;
  subject: string;
  message: string;
  read: boolean;
  created_at: string;
  updated_at: string;
}

export interface NewsItem {
  id: number;
  title: string;
  summary: string;
  date: string;
  source: string;
  url?: string;
  category: string;
}

class ContentApiService {

  // Mock data that mirrors backend structure
  private mockArticles: Article[] = [
    {
      id: 1,
      title: "Understanding Artificial Intelligence: A Beginner's Guide",
      slug: "understanding-artificial-intelligence-beginners-guide",
      excerpt: "Explore the fundamentals of AI, from machine learning basics to advanced neural networks, and understand how these technologies are shaping our world.",
      content: `<h2>Introduction to Artificial Intelligence</h2>
<p>Artificial Intelligence (AI) represents one of the most transformative technologies of our time. From simple rule-based systems to complex neural networks, AI has evolved dramatically over the past few decades.</p>

<h3>What is Artificial Intelligence?</h3>
<p>AI refers to the simulation of human intelligence in machines that are programmed to think and learn like humans. These systems can perform tasks that typically require human intelligence, such as visual perception, speech recognition, decision-making, and language translation.</p>

<h3>Types of AI</h3>
<p>There are several types of AI systems:</p>
<ul>
<li><strong>Narrow AI:</strong> Designed for specific tasks, like image recognition or language translation</li>
<li><strong>General AI:</strong> Hypothetical AI that can understand, learn, and apply knowledge across different domains</li>
<li><strong>Superintelligence:</strong> AI that surpasses human intelligence in all areas</li>
</ul>

<h3>Machine Learning Fundamentals</h3>
<p>Machine learning is a subset of AI that enables computers to learn and improve from experience without being explicitly programmed. It uses algorithms to identify patterns in data and make predictions or decisions.</p>

<h3>Real-World Applications</h3>
<p>AI is already transforming various industries:</p>
<ul>
<li>Healthcare: Medical diagnosis and drug discovery</li>
<li>Finance: Fraud detection and algorithmic trading</li>
<li>Transportation: Autonomous vehicles and traffic optimization</li>
<li>Entertainment: Content recommendation and game AI</li>
</ul>

<h3>Ethical Considerations</h3>
<p>As AI becomes more powerful, it's crucial to consider the ethical implications. Issues such as bias, privacy, job displacement, and decision-making transparency need to be addressed to ensure AI benefits all of humanity.</p>

<h3>Conclusion</h3>
<p>Understanding AI is essential in our increasingly digital world. As these technologies continue to evolve, staying informed about their capabilities, limitations, and implications will help us navigate the future effectively.</p>`,
      category: "AI",
      icon: "🤖",
      author: "Stargate.ci Team",
      read_time: 8,
      published: true,
      created_at: "2025-09-08T00:00:00Z",
      updated_at: "2025-09-08T00:00:00Z",
      published_at: "2025-09-08T00:00:00Z"
    },
    {
      id: 2,
      title: "The Future of Cloud Computing in AI Infrastructure",
      slug: "future-cloud-computing-ai-infrastructure",
      excerpt: "Discover how cloud computing is revolutionizing AI development and deployment, enabling scalable solutions for complex machine learning workloads.",
      content: `<h2>Cloud Computing and AI: A Perfect Match</h2>
<p>Cloud computing has become the backbone of modern AI infrastructure, providing the scalability, flexibility, and computational power needed for advanced machine learning applications.</p>

<h3>Scalability Benefits</h3>
<p>Cloud platforms allow AI systems to scale resources dynamically based on demand, ensuring optimal performance while controlling costs. This elasticity is crucial for training large language models and processing massive datasets.</p>

<h3>Global Accessibility</h3>
<p>Cloud-based AI services make advanced capabilities accessible to organizations worldwide, democratizing access to cutting-edge technology and leveling the playing field for innovation.</p>

<h3>Cost Efficiency</h3>
<p>Pay-as-you-use models allow organizations to access expensive computational resources without significant upfront investments, making AI development more accessible to startups and smaller companies.</p>

<h3>Integration and Collaboration</h3>
<p>Cloud platforms facilitate seamless integration of AI tools and enable global teams to collaborate on complex projects in real-time.</p>`,
      category: "Cloud",
      icon: "☁️",
      author: "Stargate.ci Team",
      read_time: 6,
      published: true,
      created_at: "2025-09-10T00:00:00Z",
      updated_at: "2025-09-10T00:00:00Z",
      published_at: "2025-09-10T00:00:00Z"
    },
    {
      id: 3,
      title: "Ethical AI: Building Responsible Technology",
      slug: "ethical-ai-building-responsible-technology",
      excerpt: "Learn about the importance of ethical considerations in AI development and how organizations are working to ensure AI benefits all of humanity.",
      content: `<h2>The Importance of Ethical AI</h2>
<p>As AI systems become more powerful and widespread, ensuring they are developed and deployed ethically is crucial for building trust and maximizing benefits for society.</p>

<h3>Key Principles</h3>
<p>Ethical AI development should prioritize:</p>
<ul>
<li><strong>Fairness:</strong> Ensuring AI systems don't discriminate against individuals or groups</li>
<li><strong>Transparency:</strong> Making AI decision-making processes understandable and explainable</li>
<li><strong>Accountability:</strong> Establishing clear responsibility for AI system outcomes</li>
<li><strong>Privacy:</strong> Protecting individual data and maintaining user privacy</li>
<li><strong>Human-centric:</strong> Designing AI to augment rather than replace human capabilities</li>
</ul>

<h3>Industry Initiatives</h3>
<p>Leading organizations are establishing frameworks and guidelines to promote responsible AI development, including ethics boards, impact assessments, and algorithmic auditing processes.</p>

<h3>Challenges and Solutions</h3>
<p>Addressing bias in training data, ensuring algorithmic transparency, and balancing innovation with safety are ongoing challenges that require continuous attention and improvement.</p>`,
      category: "Ethics",
      icon: "⚖️",
      author: "Stargate.ci Team",
      read_time: 10,
      published: true,
      created_at: "2025-09-12T00:00:00Z",
      updated_at: "2025-09-12T00:00:00Z",
      published_at: "2025-09-12T00:00:00Z"
    },
    {
      id: 4,
      title: "Data Security in the Age of AI",
      slug: "data-security-age-ai",
      excerpt: "Understand the critical importance of data security and privacy protection in AI systems, and learn about best practices for secure AI deployment.",
      content: `<h2>Securing AI Systems</h2>
<p>As AI systems process vast amounts of sensitive data, implementing robust security measures is essential to protect privacy and prevent misuse.</p>

<h3>Privacy Protection Techniques</h3>
<ul>
<li><strong>Differential Privacy:</strong> Adding controlled noise to datasets to protect individual privacy</li>
<li><strong>Federated Learning:</strong> Training models across decentralized data without centralizing sensitive information</li>
<li><strong>Homomorphic Encryption:</strong> Performing computations on encrypted data</li>
<li><strong>Secure Multi-party Computation:</strong> Enabling collaborative learning without data sharing</li>
</ul>

<h3>Best Practices</h3>
<p>Organizations should implement comprehensive security frameworks covering:</p>
<ul>
<li>Data encryption at rest and in transit</li>
<li>Access controls and authentication</li>
<li>Regular security audits and monitoring</li>
<li>Incident response plans</li>
<li>Employee training on security protocols</li>
</ul>

<h3>Regulatory Compliance</h3>
<p>Understanding and complying with regulations like GDPR, CCPA, and emerging AI-specific legislation is crucial for responsible AI deployment.</p>`,
      category: "Security",
      icon: "🔒",
      author: "Stargate.ci Team",
      read_time: 7,
      published: true,
      created_at: "2025-09-11T00:00:00Z",
      updated_at: "2025-09-11T00:00:00Z",
      published_at: "2025-09-11T00:00:00Z"
    },
    {
      id: 5,
      title: "Machine Learning Algorithms Explained",
      slug: "machine-learning-algorithms-explained",
      excerpt: "Dive deep into different types of machine learning algorithms, from supervised learning to reinforcement learning, and their real-world applications.",
      content: `<h2>Types of Machine Learning</h2>
<p>Machine learning can be categorized into three main types: supervised, unsupervised, and reinforcement learning, each with distinct approaches and applications.</p>

<h3>Supervised Learning</h3>
<p>Uses labeled training data to learn patterns and make predictions on new, unseen data. Common algorithms include:</p>
<ul>
<li><strong>Linear Regression:</strong> Predicting continuous values</li>
<li><strong>Decision Trees:</strong> Making decisions through tree-like models</li>
<li><strong>Random Forest:</strong> Combining multiple decision trees</li>
<li><strong>Support Vector Machines:</strong> Finding optimal decision boundaries</li>
<li><strong>Neural Networks:</strong> Modeling complex patterns through interconnected nodes</li>
</ul>

<h3>Unsupervised Learning</h3>
<p>Finds hidden patterns in data without labeled examples, useful for:</p>
<ul>
<li><strong>Clustering:</strong> Grouping similar data points (K-means, hierarchical clustering)</li>
<li><strong>Dimensionality Reduction:</strong> Simplifying data while preserving information (PCA, t-SNE)</li>
<li><strong>Association Rules:</strong> Finding relationships between variables</li>
</ul>

<h3>Reinforcement Learning</h3>
<p>Learns through interaction with an environment, receiving rewards or penalties for actions. Applications include:</p>
<ul>
<li>Game playing (AlphaGo, chess engines)</li>
<li>Robotics and autonomous systems</li>
<li>Resource allocation and optimization</li>
<li>Financial trading strategies</li>
</ul>

<h3>Deep Learning</h3>
<p>A subset of machine learning using neural networks with multiple layers to model complex patterns in large datasets. Key architectures include:</p>
<ul>
<li><strong>Convolutional Neural Networks (CNNs):</strong> Excellent for image processing</li>
<li><strong>Recurrent Neural Networks (RNNs):</strong> Designed for sequential data</li>
<li><strong>Transformers:</strong> State-of-the-art for natural language processing</li>
</ul>`,
      category: "AI",
      icon: "🧠",
      author: "Stargate.ci Team",
      read_time: 12,
      published: true,
      created_at: "2025-09-09T00:00:00Z",
      updated_at: "2025-09-09T00:00:00Z",
      published_at: "2025-09-09T00:00:00Z"
    },
    {
      id: 6,
      title: "Edge Computing: Bringing AI Closer to Users",
      slug: "edge-computing-bringing-ai-closer-users",
      excerpt: "Explore how edge computing is enabling AI applications to run closer to users, reducing latency and improving performance in real-world scenarios.",
      content: `<h2>What is Edge Computing?</h2>
<p>Edge computing brings computation and data storage closer to the sources of data, reducing latency and improving response times for critical applications.</p>

<h3>AI at the Edge</h3>
<p>Running AI models on edge devices enables:</p>
<ul>
<li><strong>Real-time Processing:</strong> Immediate responses without cloud dependencies</li>
<li><strong>Reduced Bandwidth:</strong> Less data transmission to central servers</li>
<li><strong>Enhanced Privacy:</strong> Sensitive data can be processed locally</li>
<li><strong>Improved Reliability:</strong> Continued operation during network outages</li>
</ul>

<h3>Use Cases</h3>
<ul>
<li><strong>Autonomous Vehicles:</strong> Real-time decision making for safety</li>
<li><strong>Smart Manufacturing:</strong> Predictive maintenance and quality control</li>
<li><strong>Healthcare:</strong> Continuous patient monitoring and emergency detection</li>
<li><strong>Retail:</strong> Personalized experiences and inventory management</li>
</ul>

<h3>Challenges and Solutions</h3>
<p>Edge AI deployment faces challenges including limited computational resources, model optimization, and device management, which are being addressed through specialized hardware and software solutions.</p>`,
      category: "Computing",
      icon: "⚡",
      author: "Stargate.ci Team",
      read_time: 9,
      published: true,
      created_at: "2025-09-07T00:00:00Z",
      updated_at: "2025-09-07T00:00:00Z",
      published_at: "2025-09-07T00:00:00Z"
    }
  ];

  private mockFAQs: FAQ[] = [
    {
      id: 1,
      question: "What is the Stargate project?",
      answer: "The Stargate project is a large-scale AI infrastructure initiative involving SoftBank, OpenAI, and Arm. It represents a significant investment in advanced computing capabilities and AI research, aimed at pushing the boundaries of artificial intelligence and machine learning technologies.",
      order: 1,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 2,
      question: "What is Cristal Intelligence?",
      answer: "Cristal Intelligence represents a new paradigm of cristalline computing that transcends traditional boundaries, offering transparent and ethical AI solutions with crystal-like precision and clarity.",
      order: 2,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 3,
      question: "Is this the official Stargate project website?",
      answer: "No, this is an independent educational platform. We are not officially affiliated with the Stargate project, SoftBank, OpenAI, or Arm. Our mission is to provide educational content and information based on publicly available sources about these technologies and organizations.",
      order: 3,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 4,
      question: "What technologies are involved in Stargate?",
      answer: "The Stargate project involves several key technologies including artificial intelligence, machine learning, cloud computing, advanced data processing, and high-performance computing infrastructure. These technologies work together to create a comprehensive AI ecosystem.",
      order: 4,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 5,
      question: "How can I learn more about AI and these technologies?",
      answer: "You can explore our Insights section for educational articles, take our Stargate Readiness Assessment, follow our Interactive Learning Paths, or browse our Project Templates. We also recommend following official sources and academic publications for the most up-to-date information.",
      order: 5,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 6,
      question: "What is the investment scale of the Stargate project?",
      answer: "While specific details are not publicly disclosed, the Stargate project represents one of the largest investments in AI infrastructure, with estimates suggesting investments in the range of $100 billion or more. This reflects the scale and ambition of the project.",
      order: 6,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 7,
      question: "How does this project impact global technology?",
      answer: "The Stargate project has the potential to significantly advance AI capabilities globally. By combining the expertise of leading technology companies, it aims to accelerate AI research and development, potentially leading to breakthroughs in various fields including healthcare, finance, and scientific research.",
      order: 7,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 8,
      question: "Are there any ethical considerations discussed?",
      answer: "Yes, ethical considerations are an important part of AI development. Organizations like OpenAI have publicly committed to developing AI safely and ensuring it benefits all of humanity. We discuss these topics in our educational content to promote awareness of responsible AI development.",
      order: 8,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 9,
      question: "How can I get started with Stargate technologies?",
      answer: "You can begin by taking our Stargate Readiness Assessment to understand your organization's current capabilities, explore our Interactive Learning Paths for structured education, or browse our Project Templates to find implementation guides for specific use cases.",
      order: 9,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    },
    {
      id: 10,
      question: "What makes Cristal Intelligence different?",
      answer: "Cristal Intelligence focuses on transparency, ethical AI development, and crystal-clear decision-making processes. It emphasizes explainable AI, responsible data usage, and ensuring that AI systems benefit all stakeholders while maintaining the highest standards of ethical conduct.",
      order: 10,
      active: true,
      created_at: "2025-09-13T00:00:00Z",
      updated_at: "2025-09-13T00:00:00Z"
    }
  ];

  private mockNews: NewsItem[] = [
    {
      id: 1,
      title: "OpenAI Announces Major Partnership with SoftBank and Arm",
      summary: "OpenAI, SoftBank, and Arm have announced a groundbreaking partnership to advance AI infrastructure and computing capabilities.",
      date: "2025-09-01",
      source: "OpenAI Official Blog",
      url: "https://openai.com/index/announcing-the-stargate-project/",
      category: "Partnership"
    },
    {
      id: 2,
      title: "Stargate Project Aims to Transform AI Computing",
      summary: "The new Stargate initiative represents a significant step forward in AI infrastructure development and computational capabilities.",
      date: "2025-09-05",
      source: "Tech Industry News",
      category: "Technology"
    },
    {
      id: 3,
      title: "AI Safety and Ethics Take Center Stage in New Initiative",
      summary: "Leading AI organizations emphasize the importance of responsible AI development and ethical considerations in the Stargate project.",
      date: "2025-09-10",
      source: "AI Ethics Council",
      category: "Ethics"
    },
    {
      id: 4,
      title: "Cristal Intelligence: A New Paradigm for Transparent AI",
      summary: "Introducing Cristal Intelligence's approach to transparent, explainable, and ethical artificial intelligence systems.",
      date: "2025-09-12",
      source: "Cristal Intelligence",
      category: "Innovation"
    }
  ];

  // Articles API
  async getArticles(category?: string): Promise<{ data: Article[]; total: number }> {
    try {
      const response = await backendApi.getArticles(category);
      if (response.status === 'success' && response.data) {
        return {
          data: response.data as Article[],
          total: response.data.length
        };
      }
    } catch (error) {
      console.warn('Failed to fetch articles from API, using fallback:', error);
    }

    // Fallback to mock data
    let articles = this.mockArticles.filter(article => article.published);
    
    if (category && category !== 'all') {
      articles = articles.filter(article => 
        article.category.toLowerCase() === category.toLowerCase()
      );
    }
    
    // Sort by published date (newest first)
    articles.sort((a, b) => new Date(b.published_at).getTime() - new Date(a.published_at).getTime());
    
    return {
      data: articles,
      total: articles.length
    };
  }

  async getArticleBySlug(slug: string): Promise<Article | null> {
    await new Promise(resolve => setTimeout(resolve, 100));
    
    const article = this.mockArticles.find(article => 
      article.slug === slug && article.published
    );
    
    return article || null;
  }

  async getFeaturedArticles(limit: number = 3): Promise<Article[]> {
    const { data } = await this.getArticles();
    return data.slice(0, limit);
  }

  // FAQs API
  async getFAQs(): Promise<FAQ[]> {
    try {
      const response = await backendApi.getFAQs();
      if (response.status === 'success' && response.data) {
        // Convert backend FAQ format to local FAQ format
        return response.data.map((backendFAQ: BackendFAQ): FAQ => ({
          id: backendFAQ.id,
          question: backendFAQ.question,
          answer: backendFAQ.answer,
          order: backendFAQ.order,
          active: true, // Assume all FAQs from backend are active
          created_at: backendFAQ.created_at,
          updated_at: backendFAQ.updated_at
        }));
      }
    } catch (error) {
      console.warn('Failed to fetch FAQs from API, using fallback:', error);
    }

    // Fallback to mock data
    return this.mockFAQs
      .filter(faq => faq.active)
      .sort((a, b) => a.order - b.order);
  }

  // News API
  async getNews(limit?: number): Promise<NewsItem[]> {
    await new Promise(resolve => setTimeout(resolve, 100));
    
    const news = [...this.mockNews].sort((a, b) => 
      new Date(b.date).getTime() - new Date(a.date).getTime()
    );
    
    return limit ? news.slice(0, limit) : news;
  }

  // Contact API
  async submitContactMessage(message: Omit<ContactMessage, 'id' | 'read' | 'created_at' | 'updated_at'>): Promise<ContactMessage> {
    await new Promise(resolve => setTimeout(resolve, 500));
    
    const newMessage: ContactMessage = {
      id: Date.now(),
      ...message,
      read: false,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString()
    };
    
    return newMessage;
  }

  // Categories API
  async getCategories(): Promise<string[]> {
    try {
      const response = await backendApi.getContentCategories();
      if (response.status === 'success' && response.data) {
        return response.data.map((cat: any) => cat.name);
      }
    } catch (error) {
      console.warn('Failed to fetch categories from API, using fallback:', error);
    }

    // Fallback: get categories from articles
    const articles = await this.getArticles();
    const categories = [...new Set(articles.data.map(article => article.category))];
    return categories.sort();
  }

  // Search API
  async searchContent(query: string): Promise<{
    articles: Article[];
    faqs: FAQ[];
  }> {
    await new Promise(resolve => setTimeout(resolve, 200));
    
    const searchTerm = query.toLowerCase();
    
    const articles = this.mockArticles.filter(article =>
      article.published && (
        article.title.toLowerCase().includes(searchTerm) ||
        article.excerpt.toLowerCase().includes(searchTerm) ||
        article.content.toLowerCase().includes(searchTerm) ||
        article.category.toLowerCase().includes(searchTerm)
      )
    );
    
    const faqs = this.mockFAQs.filter(faq =>
      faq.active && (
        faq.question.toLowerCase().includes(searchTerm) ||
        faq.answer.toLowerCase().includes(searchTerm)
      )
    );
    
    return { articles, faqs };
  }

  // Admin API (for content management)
  async createArticle(article: Omit<Article, 'id' | 'created_at' | 'updated_at'>): Promise<Article> {
    await new Promise(resolve => setTimeout(resolve, 300));
    
    const newArticle: Article = {
      ...article,
      id: Date.now(),
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString()
    };
    
    this.mockArticles.push(newArticle);
    return newArticle;
  }

  async updateArticle(id: number, updates: Partial<Article>): Promise<Article | null> {
    await new Promise(resolve => setTimeout(resolve, 300));
    
    const index = this.mockArticles.findIndex(article => article.id === id);
    if (index === -1) return null;
    
    this.mockArticles[index] = {
      ...this.mockArticles[index],
      ...updates,
      updated_at: new Date().toISOString()
    };
    
    return this.mockArticles[index];
  }

  async deleteArticle(id: number): Promise<boolean> {
    await new Promise(resolve => setTimeout(resolve, 200));
    
    const index = this.mockArticles.findIndex(article => article.id === id);
    if (index === -1) return false;
    
    this.mockArticles.splice(index, 1);
    return true;
  }

  async createFAQ(faq: Omit<FAQ, 'id' | 'created_at' | 'updated_at'>): Promise<FAQ> {
    await new Promise(resolve => setTimeout(resolve, 300));
    
    const newFAQ: FAQ = {
      ...faq,
      id: Date.now(),
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString()
    };
    
    this.mockFAQs.push(newFAQ);
    return newFAQ;
  }

  async updateFAQ(id: number, updates: Partial<FAQ>): Promise<FAQ | null> {
    await new Promise(resolve => setTimeout(resolve, 300));
    
    const index = this.mockFAQs.findIndex(faq => faq.id === id);
    if (index === -1) return null;
    
    this.mockFAQs[index] = {
      ...this.mockFAQs[index],
      ...updates,
      updated_at: new Date().toISOString()
    };
    
    return this.mockFAQs[index];
  }

  async deleteFAQ(id: number): Promise<boolean> {
    await new Promise(resolve => setTimeout(resolve, 200));
    
    const index = this.mockFAQs.findIndex(faq => faq.id === id);
    if (index === -1) return false;
    
    this.mockFAQs.splice(index, 1);
    return true;
  }
}

export const contentApi = new ContentApiService();
