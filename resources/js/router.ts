import { createRouter, createWebHistory } from 'vue-router'

const route = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: '/home',
    },
    {
      path: '/home',
      name: 'home',
      component: () => import('./pages/Home.vue'),
      meta: {requiresAuth: false}
    },
    {
      path: '/quotes',
      name: 'quotes',
      component: () => import('./pages/Quotes.vue'),
      meta: {requiresAuth: false}
    },
    {
      path: '/favorites',
      name: 'favorites',
      component: () => import('./pages/Favorites.vue'),
      meta: {requiresAuth: false}
    },
  ],
});

export default route;
