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
      meta: {requiresAuth: true}
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./pages/Login.vue'),
      meta: {requiresAuth: false}
    },
    {
      path: '/forbidden',
      name: 'forbidden',
      component: () => import('./pages/Forbidden.vue'),
      meta: {requiresAuth: false}
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('./pages/Register.vue'),
      meta: {requiresAuth: false}
    },
    {
      path: '/quotes',
      name: 'quotes',
      component: () => import('./pages/Quotes.vue'),
      meta: {requiresAuth: true}
    },
    {
      path: '/favorites',
      name: 'favorites',
      component: () => import('./pages/Favorites.vue'),
      meta: {requiresAuth: true}
    },
    {
      path: '/users',
      name: 'users',
      component: () => import('./pages/Users.vue'),
      meta: {requiresAuth: true, requiresAdmin: true}
    },
  ],
});

export default route;
