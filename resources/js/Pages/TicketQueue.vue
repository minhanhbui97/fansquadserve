<script setup>
import { useAuthStore } from '@/Stores/AuthStore';
import { useRouter } from 'vue-router';

const router = useRouter();
const authStore = useAuthStore();
const { logout } = authStore;

async function handleLogout() {
  await logout();
  router.push('login');
}
</script>

<template>
  <div>
    <h1>This is Ticket Queue page</h1>
    <button @click="handleLogout">Logout</button>
  </div>
</template>
