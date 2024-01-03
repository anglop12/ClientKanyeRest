import { createRouter, createWebHistory } from 'vue-router'

export default createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: '/home',
    },
    {
      path: '/home',
      component: () => import('./pages/Home.vue'),
    },
    {
      path: '/quotes',
      component: () => import('./pages/Quotes.vue'),
    },
    {
      path: '/favorites',
      component: () => import('./pages/Favorites.vue'),
    },
  ],
})
