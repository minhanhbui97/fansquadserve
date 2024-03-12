import { createRouter, createWebHistory } from 'vue-router';
import Landing from '@/Pages/Landing.vue';
import TicketQueue from '@/Pages/TicketQueue.vue';
import Login from '@/Pages/Login.vue';
import ServiceRequest from '@/Pages/ServiceRequest.vue';
import { useAuthStore } from './Stores/AuthStore';
import { storeToRefs } from 'pinia';

const routes = [
  {
    path: '/',
    name: 'landing',
    component: Landing,
    meta: {
      requiresAuth: false,
    },
  },
  {
    path: '/ticket-queue',
    name: 'ticket-queue',
    component: TicketQueue,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      requiresAuth: false,
    },
  },
  {
    path: '/service-request',
    name: 'service-request',
    component: ServiceRequest,
    meta: {
      requiresAuth: false,
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from) => {
  const authStore = useAuthStore();
  const { isLoggedIn, user } = storeToRefs(authStore);
  const { getAuthUser, reset } = authStore;
  const needAuthentication = to.matched.some(
    (route) => route.meta.requiresAuth,
  );

  if (to.name !== 'login' && isLoggedIn.value && user.value === null) {
    try {
      await getAuthUser();
    } catch (err) {
      reset();
      return { name: 'login' };
    }
  }

  if (needAuthentication && !isLoggedIn.value) {
    return { name: 'login' };
  }

  if (isLoggedIn.value && !needAuthentication) {
    return { name: 'ticket-queue' };
  }
});

export default router;
