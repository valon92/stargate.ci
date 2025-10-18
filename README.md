# Stargate.ci - Cristal Intelligence Platform

An independent educational platform dedicated to informing the public about the Stargate project and Cristal Intelligence, created by SoftBank, OpenAI, and Arm. Where Stargate meets Cristal Intelligence to guide companies toward ethical AI implementation while preserving intellectual property rights.

## 🚀 Project Overview

This project provides transparent, accessible information about cutting-edge technologies in AI, cloud computing, and advanced data processing. It serves as an educational resource for understanding the transformative power of technology and its ethical implications.

### 🎯 Mission
- **Stargate**: The groundbreaking AI project by OpenAI, SoftBank, and Arm
- **Cristal Intelligence**: A new paradigm of cristalline computing that transcends traditional boundaries
- **Ethical Guidance**: Guiding companies toward Stargate while preserving intellectual property rights
- **Innovation**: Pioneering the future where technology meets transparency, ethics, and cristalline clarity

## 🏗️ Architecture

### Frontend (Vue.js)
- **Framework**: Vue 3 with TypeScript
- **Styling**: TailwindCSS with custom design system
- **Build Tool**: Vite
- **State Management**: Pinia
- **Meta Management**: @unhead/vue for dynamic SEO

### Backend (Laravel)
- **Framework**: Laravel 12
- **Database**: SQLite (development) / PostgreSQL (production)
- **API**: RESTful API with JSON responses
- **Authentication**: Laravel Sanctum (for admin features)

## 📁 Project Structure

```
stargate.ci/
├── frontend/                 # Vue.js frontend application
│   ├── src/
│   │   ├── components/       # Reusable Vue components
│   │   │   ├── InteractiveContent.vue
│   │   │   ├── EngagementStats.vue
│   │   │   └── layout/
│   │   │       ├── Header.vue
│   │   │       └── Footer.vue
│   │   ├── views/           # Page components
│   │   │   ├── HomeView.vue
│   │   │   ├── AboutView.vue
│   │   │   ├── EventsView.vue
│   │   │   ├── NewsView.vue
│   │   │   ├── FAQView.vue
│   │   │   ├── ContactView.vue
│   │   │   ├── SubscribeView.vue
│   │   │   ├── DisclaimerView.vue
│   │   │   └── NotFoundView.vue
│   │   ├── services/        # API services
│   │   │   ├── videoApiService.ts
│   │   │   ├── newsApiService.ts
│   │   │   ├── eventsApiService.ts
│   │   │   └── apiClient.ts
│   │   └── router/          # Vue Router configuration
│   └── package.json
├── backend/                  # Laravel backend API
│   ├── app/
│   │   ├── Http/Controllers/Api/  # API controllers
│   │   │   ├── VideoController.php
│   │   │   ├── VideoInteractionController.php
│   │   │   ├── VideoCommentController.php
│   │   │   ├── SubscriberController.php
│   │   │   ├── ArticleController.php
│   │   │   ├── FAQController.php
│   │   │   └── ContactController.php
│   │   └── Models/          # Eloquent models
│   │       ├── Video.php
│   │       ├── VideoInteraction.php
│   │       ├── VideoComment.php
│   │       ├── Subscriber.php
│   │       ├── Article.php
│   │       └── FAQ.php
│   ├── database/
│   │   ├── migrations/      # Database migrations
│   │   └── seeders/         # Database seeders
│   └── routes/api.php       # API routes
└── README.md
```

## 🛠️ Development Setup

### Prerequisites
- Node.js 20+ 
- PHP 8.2+
- Composer
- SQLite

### Frontend Setup
```bash
cd frontend
npm install
npm run dev
```

### Backend Setup
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## 🌐 Features

### Public Features
- **Home Page**: Introduction to Stargate and key technologies with interactive videos
- **About**: Mission, vision, and project information
- **Events**: Real events and meetings related to Stargate Project and Cristal Intelligence
- **News**: Latest news from OpenAI, SoftBank, Arm, and other relevant sources
- **FAQ**: Frequently asked questions
- **Contact**: Contact form with backend integration
- **Subscribe**: User subscription system for notifications
- **Interactive Content**: Like, comment, and share functionality for videos and articles

### Core Functionality
- **Video System**: YouTube video integration with engagement tracking
- **News System**: Real-time news from official sources
- **Events System**: Real events and meetings information
- **Subscription System**: User subscription with email notifications
- **Interactive Features**: Likes, comments, shares for all content
- **Responsive Design**: Mobile-first design with modern UI/UX

## 🔧 API Endpoints

### Public Endpoints
- `GET /api/v1/content/articles` - List published articles
- `GET /api/v1/content/articles/{id}` - Get article by ID
- `GET /api/v1/faqs` - List active FAQs
- `GET /api/v1/faqs/{id}` - Get FAQ by ID
- `POST /api/v1/contact` - Submit contact form
- `GET /api/v1/videos` - List all videos
- `GET /api/v1/videos/{contentId}` - Get video by content ID
- `GET /api/v1/videos/stats/overview` - Get video statistics
- `POST /api/v1/videos/interactions/like` - Toggle video like
- `POST /api/v1/videos/interactions/share` - Add video share
- `POST /api/v1/videos/comments` - Add video comment
- `GET /api/v1/subscribers` - List subscribers
- `POST /api/v1/subscribers` - Create new subscriber

## 🎨 Design System

### Colors
- **Primary**: Blue gradient (#0ea5e9 to #a855f7)
- **Secondary**: Purple gradient
- **Dark Theme**: Dark slate colors
- **Accent**: Gradient text effects

### Typography
- **Primary Font**: Inter
- **Monospace**: JetBrains Mono

### Components
- Responsive grid layouts
- Card-based design
- Gradient buttons and text
- Smooth animations and transitions

## 🚀 Deployment

### Manual Deployment
1. Build the frontend: `npm run build`
2. Upload the `dist` folder to your web server
3. Configure the backend API endpoints
4. Set up the database and run migrations

### Environment Variables
- `VITE_API_URL`: Backend API URL
- `APP_ENV`: Laravel environment
- `DB_CONNECTION`: Database connection type

## 📝 Content Guidelines

### Educational Focus
- All content is based on publicly available information
- No proprietary or copyrighted materials
- Clear disclaimers about independence from official projects
- Focus on education and transparency

### Legal Compliance
- Independent educational platform
- No official affiliation claims
- Respect for intellectual property
- International digital law compliance

## 🌐 Live Deployment

### Current Status
- **Frontend**: ✅ Live at https://stargate.ci
- **Backend**: ✅ API running
- **Last Update**: January 2025

### Features Live
- ✅ Cristal Intelligence concept implementation
- ✅ Domain explanation section
- ✅ Responsive design
- ✅ SEO optimization
- ✅ Modern UI/UX with TailwindCSS
- ✅ Interactive video system
- ✅ Real-time news integration
- ✅ Events system
- ✅ Subscription system
- ✅ Like, comment, share functionality

## 🤝 Contributing

This is an educational project. Contributions should focus on:
- Improving educational content
- Enhancing user experience
- Adding new educational features
- Maintaining transparency and accuracy

## 📄 License

This project is for educational purposes. All content is based on publicly available information and respects intellectual property rights.

## ⚠️ Disclaimer

This site is an independent educational initiative, not an official representative of the Stargate project. All content is based on publicly available information and is intended for educational purposes only.