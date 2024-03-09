<script setup>
import PeopleIcon from '@/Components/SVGIcons/PeopleIcon.vue';
import GraphIcon from '@/Components/SVGIcons/GraphIcon.vue';
import DocIcon from '@/Components/SVGIcons/DocIcon.vue';
</script>

<template>
  <div class="w-[500px] flex flex-col bg-gray-200">
    <header class="p-8">
      <img src="../../images/logo.png" alt="" />
    </header>
    <div class="flex flex-col justify-between">
      <section
        class="flex flex-col flex-grow w-full justify-center items-center"
      >
        <router-link
          :to="{ name: 'ticket-queue' }"
          class="flex gap-4 items-center py-4 px-8 font-semibold w-full"
        >
          <div class="w-8 h-8">
            <DocIcon class="h-full w-full" />
          </div>
          <div>Ticket Queue</div>
        </router-link>
        <router-link
          :to="{ name: 'data-dashboard' }"
          class="flex gap-4 py-4 px-8 items-center font-semibold w-full"
        >
          <div class="w-8 h-8">
            <GraphIcon class="h-full w-full" />
          </div>
          <div>Data Dashboard</div>
        </router-link>
        <router-link
          :to="{ name: 'user-management' }"
          class="flex gap-4 items-center py-4 px-8 font-semibold w-full"
        >
          <div class="w-8 h-8">
            <PeopleIcon class="h-full w-full" />
          </div>
          <div>User Management</div>
        </router-link>
      </section>
    </div>
  </div>
</template>

<style scoped lang="postcss">
.router-link-active {
  @apply bg-theme-purple text-white;
}
</style>
