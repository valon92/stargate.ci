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
- **Internationalization**: Vue i18n (English & French)
- **State Management**: Pinia
- **Meta Management**: @unhead/vue for dynamic SEO
- **Deployment**: Surge.sh with custom domain support

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
│   │   ├── views/           # Page components
│   │   ├── locales/         # Translation files
│   │   └── router/          # Vue Router configuration
│   └── package.json
├── backend/                  # Laravel backend API
│   ├── app/
│   │   ├── Http/Controllers/Api/  # API controllers
│   │   └── Models/          # Eloquent models
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
- **Home Page**: Introduction to Stargate and key technologies
- **About**: Mission, vision, and project information
- **Services**: Detailed technology explanations
- **Partners**: Information about SoftBank, OpenAI, Arm, and Crystal Intelligence
- **Insights**: Educational articles and blog posts
- **FAQ**: Frequently asked questions
- **Contact**: Contact form with backend integration
- **Multilingual**: English and French support

### Admin Features (API)
- Article management (CRUD)
- FAQ management (CRUD)
- Contact message management
- User authentication

## 🔧 API Endpoints

### Public Endpoints
- `GET /api/v1/articles` - List published articles
- `GET /api/v1/articles/{slug}` - Get article by slug
- `GET /api/v1/faqs` - List active FAQs
- `POST /api/v1/contact` - Submit contact form

### Admin Endpoints (Protected)
- `GET /api/v1/admin/articles` - List all articles
- `POST /api/v1/admin/articles` - Create article
- `PUT /api/v1/admin/articles/{id}` - Update article
- `DELETE /api/v1/admin/articles/{id}` - Delete article
- Similar endpoints for FAQs and contact messages

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

### Vercel (Recommended)
Both frontend and backend are configured for Vercel deployment:

1. **Frontend**: Deploy to Vercel with automatic builds
2. **Backend**: Deploy Laravel API to Vercel using PHP runtime

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
- **Frontend**: ✅ Live at https://stargate-ci-preview.surge.sh
- **Domain**: ⏳ stargate.ci (DNS configuration pending)
- **Backend**: 🔄 Development phase
- **Last Update**: January 2025

### Features Live
- ✅ Cristal Intelligence concept implementation
- ✅ Domain explanation section
- ✅ Multi-language support (EN/FR)
- ✅ Responsive design
- ✅ SEO optimization
- ✅ Modern UI/UX with TailwindCSS

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

---

**Last Updated**: December 2024
