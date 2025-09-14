import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { adminGuard, guestGuard, userGuard } from './guards'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/services',
      name: 'services',
      component: () => import('../views/ServicesView.vue'),
    },
    {
      path: '/partnership',
      name: 'partnership',
      component: () => import('../views/PartnershipView.vue'),
    },
    {
      path: '/insights',
      name: 'insights',
      component: () => import('../views/InsightsView.vue'),
    },
    {
      path: '/insights/:slug',
      name: 'insight-detail',
      component: () => import('../views/InsightDetailView.vue'),
    },
    {
      path: '/faq',
      name: 'faq',
      component: () => import('../views/FAQView.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('../views/ContactView.vue'),
    },
    {
      path: '/assessment',
      name: 'assessment',
      component: () => import('../views/AssessmentView.vue'),
    },
    {
      path: '/learning',
      name: 'learning',
      component: () => import('../views/LearningPathsView.vue'),
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      beforeEnter: userGuard,
    },
    {
      path: '/templates',
      name: 'templates',
      component: () => import('../views/TemplatesView.vue'),
    },
  {
    path: '/community',
    name: 'community',
    component: () => import('../views/CommunityView.vue'),
  },
  {
    path: '/search',
    name: 'search',
    component: () => import('../views/SearchView.vue'),
  },
    {
      path: '/profile/:userId?',
      name: 'profile',
      component: () => import('../components/UserProfile.vue'),
    },
    {
      path: '/privacy',
      name: 'privacy',
      component: () => import('../views/PrivacyView.vue'),
    },
    {
      path: '/terms',
      name: 'terms',
      component: () => import('../views/TermsView.vue'),
    },
    {
      path: '/disclaimer',
      name: 'disclaimer',
      component: () => import('../views/DisclaimerView.vue'),
    },
    {
      path: '/legal-disclaimer',
      name: 'legal-disclaimer',
      component: () => import('../views/LegalDisclaimerView.vue'),
    },
    {
      path: '/login',
      name: 'unified-login',
      component: () => import('../views/UnifiedLoginView.vue'),
      beforeEnter: guestGuard,
    },
    {
      path: '/auth',
      name: 'user-auth',
      component: () => import('../views/UserAuthView.vue'),
    },
    {
      path: '/admin/login',
      name: 'admin-login',
      component: () => import('../views/AdminLoginView.vue'),
      beforeEnter: guestGuard,
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminView.vue'),
      beforeEnter: adminGuard,
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('../views/NotFoundView.vue'),
    },
  ],
})

export default router
