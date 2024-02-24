import { createRouter, createWebHistory } from 'vue-router';
import Landing from '@/Pages/Landing.vue';
import Tickets from '@/Pages/Tickets.vue';
import Login from '@/Pages/Login.vue';
import StudentHome from '@/Pages/StudentHome.vue';

const routes = [
  {
    path: '/',
    component: Landing
  },
  {
    path: '/tickets',
    component: Tickets
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/student',
    component: StudentHome
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
