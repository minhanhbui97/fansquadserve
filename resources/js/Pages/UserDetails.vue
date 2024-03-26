<script setup>
import { useUserStore } from '@/Stores/UserStore';
import { storeToRefs } from 'pinia';
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';



const route = useRoute();
const user_id = route.params.id;

const userStore = useUserStore();
const {
  getRoles,
  getCourses,
  getCurrentUser,
  updateCurrentUser,
} = userStore;

const {

courses,
roles,
currentUser: user,

} = storeToRefs(userStore);

const formRef = ref();
const toast = useToast();


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

async function submitUser(values) {
  await updateCurrentUser(user.value.id, values.data);
  toast.success('Update User successfully!');
}



let s_roles = ref([]);
let s_courses = ref([]);

// const role = ["tutor","staff","admin"];
// role.forEach(function(s_roles.value)
// {
//     const selectedOption ={};
//    selectedOption ['id'] = '';
//    selectedOption ['value'] = s_roles.value;
// });

let selectedRolesArr = [];

function selectedRoles(option) {
  s_roles.value.push(option);

  console.log(s_roles.value);
  var roleObj = {};
  roleObj['id'] = option;
  selectedRolesArr.push(roleObj);
  console.log(roleObj);
  console.log(selectedRolesArr);

}

function deselectRoles(option) {
  console.log("hereeeeeee");


  selectedRolesArr = selectedRolesArr.filter(function(roleObj){

    return roleObj.id !== option;
  });
  console.log(selectedRolesArr);

}

function deselectAllRoles() {
  selectedRolesArr = [];
  console.log(selectedRolesArr);

}



let selectedCoursesArr = [];

function selectedCourses(option) {
  s_courses.value.push(option);

  console.log(s_courses.value);
  var courseObj = {};
  courseObj['id'] = option;
  selectedCoursesArr.push(courseObj);
  console.log(courseObj);
  console.log(selectedCoursesArr);

}

function deselectCourses(option) {
  console.log("hereeeeeee");


  selectedCoursesArr = selectedCoursesArr.filter(function(courseObj){

    return courseObj.id !== option;
  });
  console.log(selectedCoursesArr);

}

function deselectAllCourses() {
  selectedCoursesArr = [];
  console.log(selectedCoursesArr);

}




onMounted(() => {
  getCurrentUser(user_id);

  getCourses();
  getRoles();
});

const isActive = ref(true); // Default value is true, so the checkbox is checked by default



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

        <CheckboxElement  name="is_active" label="Is Active"  true-value= 1 false-value= 0 />

       
     


        <button class="bg-red-700 h-12 w-40 text-white rounded">Update User</button>

      </Vueform>
    </div>
  </div>
</template>