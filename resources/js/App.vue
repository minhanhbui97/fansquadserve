<script setup>
import { ref } from 'vue';
import { useAuthStore } from './Stores/AuthStore';
import { storeToRefs } from 'pinia';

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);
</script>

<template>
  <main class="h-screen w-full flex flex-row">
    <div v-if="user">User here</div>
    <div class="w-full">
      <router-view></router-view>
    </div>
  </main>
</template>
