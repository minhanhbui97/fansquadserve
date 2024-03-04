<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/Stores/AuthStore';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');

const router = useRouter();
const authStore = useAuthStore();
const { login, error } = authStore;

async function submit() {
  await login({ email: email.value, password: password.value });
  if (!error) {
    router.push({ name: 'ticket-queue' });
  }
}
</script>

<template>
  <div>
    <form @submit.prevent="submit">
      <input type="text" v-model="email" />
      <input type="text" v-model="password" />
      <button type="submit">Login</button>
    </form>
  </div>
</template>
