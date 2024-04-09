<script setup>
import { useUserStore } from '@/Stores/UserStore';
import { useAuthStore } from '@/Stores/AuthStore';
import { storeToRefs } from 'pinia';
import { onMounted, ref } from 'vue';

const userStore = useUserStore();
const { getCurrentUser } = userStore;
const { currentUser } = storeToRefs(userStore);

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

const isLoading = ref(false);

async function initialize() {
  isLoading.value = true;
  await getCurrentUser(user?.value.id);
  isLoading.value = false;
}

onMounted(() => {
  initialize();
});
</script>
<template>
  <div
    class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow"
  >
    <h1 class="text-red-700 text-3xl font-bold flex-shrink-0">
      User Profile
      <font-awesome-icon icon="fa-spinner" v-if="isLoading" class="fa-spin" />
    </h1>
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <Vueform>
        <StaticElement name="first_name" class="col-span-6">
          <div class="text-sm">
            <span class="font-medium">First Name:</span>
            {{ currentUser?.first_name }}
          </div>
        </StaticElement>

        <StaticElement name="last_name" class="col-span-6">
          <div class="text-sm">
            <span class="font-medium">Last Name:</span>
            {{ currentUser?.last_name }}
          </div>
        </StaticElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <StaticElement name="email" class="col-span-12">
          <div class="text-sm">
            <span class="font-medium">Email:</span> {{ currentUser?.email }}
          </div>
        </StaticElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <StaticElement name="roles" class="col-span-12">
          <div class="text-sm">
            <div class="font-medium">Roles:</div>

            <li class="text-sm" v-for="role in currentUser?.roles">
              {{ role.name }}
            </li>
          </div>
        </StaticElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <StaticElement name="courses" class="col-span-12">
          <div class="text-sm">
            <div class="font-medium">Courses:</div>

            <li class="text-sm" v-for="course in currentUser?.courses">
              {{ course.code }} - {{ course.name }}
            </li>
          </div>
        </StaticElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <StaticElement name="schedule_page" class="col-span-12">
          <div class="text-sm">
            <span class="font-medium">Schedule Page:</span>
            {{ currentUser?.schedule_page.schedule_url }}
          </div>
        </StaticElement>
      </Vueform>
    </div>
  </div>
</template>
