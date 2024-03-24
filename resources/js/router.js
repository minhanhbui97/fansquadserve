import { createRouter, createWebHistory } from 'vue-router';
import Landing from '@/Pages/Landing.vue';
import TicketQueue from '@/Pages/TicketQueue.vue';
import TicketDetails from '@/Pages/TicketDetails.vue';
import Login from '@/Pages/Login.vue';
import DataDashboard from '@/Pages/DataDashboard.vue';
import UserManagement from '@/Pages/UserManagement.vue';
import AddUser from '@/Pages/AddUser.vue';
import UserDetails from '@/Pages/UserDetails.vue';


import ServiceRequest from '@/Pages/ServiceRequest.vue';
import { useAuthStore } from './Stores/AuthStore';
import { storeToRefs } from 'pinia';
import { h } from 'vue'
import { RouterView } from 'vue-router'

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
    path: '/tickets',
    name: 'tickets',
    // component: { render: () => h(RouterView) },
    meta: {
      requiresAuth: true,
    },
    redirect: '/tickets',
    children: [
      { 
        path: '',
        name: 'tickets',
        component: TicketQueue
      },
      { 
        path: ':id',
        name: 'ticket-details',
        component: TicketDetails
      }
    ]
  },
  // {
  //   path: '/tickets/:id',
  //   name: 'ticket-details',
  //   component: TicketDetails,
  //   meta: {
  //     requiresAuth: true,
  //   },
  // },
  {
    path: '/data-dashboard',
    name: 'data-dashboard',
    component: DataDashboard,
    meta: {
      requiresAuth: true,
    },
  },

 
  {
    path: '/user-management',
    name: 'user-management',
    component: UserManagement,
    meta: {
      requiresAuth: true,
    },

    
  },

  {
    path: '/add-user',
    name: 'add-user',
    component: AddUser,
    meta: {
      requiresAuth: true,
    },

    
  },

  {
    path: '/user-details',
    name: 'user-details',
    component: UserDetails,
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
      console.log('in here')

      return { name: 'login' };
    }
  }

  if (needAuthentication && !isLoggedIn.value) {
    return { name: 'login' };
  }

  if (isLoggedIn.value && !needAuthentication) {
    return { name: 'tickets' };
  }
});

export default router;
