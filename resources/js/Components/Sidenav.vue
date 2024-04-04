<script setup>
import PeopleIcon from '@/Components/SVGIcons/PeopleIcon.vue';
import GraphIcon from '@/Components/SVGIcons/GraphIcon.vue';
import DocIcon from '@/Components/SVGIcons/DocIcon.vue';
import { useAuthStore } from '@/Stores/AuthStore';
import { useUserStore } from '@/Stores/UserStore';
import { storeToRefs } from 'pinia';
import { useRouter } from 'vue-router';
import { ref, watch } from 'vue';

const router = useRouter();
const authStore = useAuthStore();
const { user, isAdmin } = storeToRefs(authStore);
const { logout } = authStore;

async function handleLogout() {
  await logout();
  router.push('login');
}

async function submit() {
  router.push({ name: 'user-profile' });
}
</script>

<template>
  <div
    class="w-[280px] flex flex-col flex-shrink-0 bg-blue-gray border-r border-gray-300"
  >
    <header class="p-8">
      <img src="../../images/logo.png" alt="" />
    </header>
    <div class="flex flex-col justify-between flex-grow">
      <section class="flex flex-col w-full justify-center items-center">
        <router-link
          :to="{ name: 'tickets' }"
          class="flex gap-5 items-center py-4 px-8 font-semibold w-full"
        >
          <font-awesome-icon icon="fa-file-lines" size="2xl" />
          <div>Ticket Queue</div>
        </router-link>
        <router-link
          :to="{ name: 'data-dashboard' }"
          class="flex gap-4 py-4 px-8 items-center font-semibold w-full"
        >
          <font-awesome-icon icon="fa-chart-simple" size="2xl" />
          <div>Data Dashboard</div>
        </router-link>
        <router-link
          v-if="isAdmin"
          :to="{ name: 'users' }"
          class="flex gap-3.5 items-center py-4 px-8 font-semibold w-full"
        >
          <font-awesome-icon icon="fa-users" size="xl" />
          <div>User Management</div>
        </router-link>
      </section>
      <section class="flex flex-col gap-4 justify-center items-center p-8">
        <div class="flex gap-4 items-center">
          <font-awesome-icon icon="fa-solid fa-user" size="2xl" />
          <h1 class="font-bold">{{ user.first_name }} {{ user.last_name }}</h1>
        </div>
        <div class="mb-4">
          <button
            @click="() => submit()"
            class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
          >
            View Your Profile
          </button>
        </div>

        <button
          @click="handleLogout"
          class="py-2 px-4 bg-red-500 rounded text-white hover:bg-red-500/90"
        >
          Log Out
        </button>
      </section>
    </div>
  </div>
</template>

<style scoped lang="postcss">
.router-link-active {
  @apply bg-theme-purple text-white;
}
</style>
