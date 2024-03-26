<script setup>
import { useUserStore } from '@/Stores/UserStore';
import dayjs from 'dayjs';
import { storeToRefs } from 'pinia';
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';
//const route = useRoute();

const userStore = useUserStore();
const {
  getRoles,
  getCourses,
 // getCurrentUser,
  //updateCurrentUser,
} = userStore;

const {

courses,
roles,
} = storeToRefs(userStore);

const selectCourseOptions = computed(() => {
  return courses.value.map((course) => {
    return {
      value: course.id,
      label: course.name,
      code: course.code,
    };
  });
});


const selectRoleOptions = computed(() => {
  return roles.value.map((role) => {
    return {
      value: role.id,
      label: role.name,
    };
  });
});



onMounted(() => {
  getCourses();
  getRoles();
});



</script>
<template>

  <div class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow">
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">
      Update User
    </h1>
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <Vueform ref="formRef" @submit="submitUser" :endpoint="false">

        <TextElement name="first_name" label="FirstName" input-type="text" :rules="['required', 'max:255']"
          class="col-span-6" />

        <TextElement name="last_name" label="LastName" input-type="text" class=" col-span-6"
          :rules="['required', 'max:255']" />
        <TextElement name="email" label="Email" input-type="email" :rules="['required', 'email', 'max:255']"
          class="col-span-6" />
        <MultiselectElement name="roles" label="Role" :native="false" :items="selectRoleOptions" class="col-span-6"
          :close-on-select="false" :hide-selected="false" @select="selectedRoles" @deselect="deselectRoles"
          :can-clear="true" @clear="deselectAllRoles" :search="true" />
        <TextElement name="password" label="Password" input-type="password" class="col-span-6" :rules="['required']" />

        <MultiselectElement name="courses" label="Course" :native="false" :items="selectCourseOptions"
        :close-on-select="false" :hide-selected="false"  @select="selectedCourses" @deselect="deselectCourses" 
        :can-clear="true" @clear="deselectAllCourses" :search="true" class="col-span-6" />

        <TextElement name="repassword" label="Retype Password" input-type="password" :rules="['required']"
          class="col-span-6" />

        <TextElement name="schedule_page" label="Tutor's Schedule" class="col-span-6" input-type="text" />
        <button class="bg-red-700 h-12 w-40 text-white rounded">Update User</button>

      </Vueform>
    </div>
  </div>
</template>