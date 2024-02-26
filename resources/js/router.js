import { createRouter, createWebHistory } from 'vue-router';
import Landing from '@/Pages/Landing.vue';
import TicketQueue from '@/Pages/TicketQueue.vue';
import Login from '@/Pages/Login.vue';
import ServiceRequest from '@/Pages/ServiceRequest.vue';

const routes = [
  {
    path: '/',
    component: Landing
  },
  {
    path: '/ticket-queue',
    component: TicketQueue
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/service-request',
    component: ServiceRequest
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
