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
      path: '/events',
      name: 'events',
      component: () => import('../views/EventsView.vue'),
    },
    {
      path: '/news',
      name: 'news',
      component: () => import('../views/NewsView.vue'),
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
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      beforeEnter: userGuard,
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
      path: '/subscribe',
      name: 'subscribe',
      component: () => import('../views/UnifiedLoginView.vue'),
    },
    {
      path: '/auth',
      name: 'user-auth',
      component: () => import('../views/UserAuthView.vue'),
    },
    // Removed admin routes as they are unnecessary for educational platform
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('../views/NotFoundView.vue'),
    },
  ],
})

export default router
