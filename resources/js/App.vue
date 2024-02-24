<script setup>
import { ref } from 'vue';
const count = ref(1);
</script>

<template>
  <main class="h-screen flex flex-col">
    <router-view></router-view>
  </main>
</template>
