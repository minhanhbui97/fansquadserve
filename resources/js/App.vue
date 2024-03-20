<script setup>
import { ref } from 'vue';
import { useAuthStore } from './Stores/AuthStore';
import { storeToRefs } from 'pinia';
import Sidenav from '@/Components/Sidenav.vue'

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);
</script>

<template>
  <main class="h-screen w-full flex flex-row">
    <Sidenav v-if="user" />
    <div class="w-full bg-white">
      <router-view></router-view>
    </div>
  </main>
</template>
