<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

// onMounted(() => {
//     getTicket(route.params.id);
// })
</script>

<template>
  <h1>This is Ticket Details page</h1>
</template>
