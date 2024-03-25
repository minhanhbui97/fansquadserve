<script setup>
import { createUser, assignRoleToUser } from '@/Services/UserService';

import { storeToRefs } from 'pinia';
import { useUserStore } from '@/Stores/UserStore';
import { computed, onMounted, ref, watch } from 'vue';
import Dialog from 'primevue/dialog';
import PrimevueButton from 'primevue/button';

const userStore = useUserStore();
const {
  getCourses,
  getRoles,

} = userStore;

const {

  courses,
  roles,
} = storeToRefs(userStore);


const formRef = ref(null);
const visible = ref(false);
const formData = ref(null);
const user = ref(null);




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


async function submitUser(values) {
  formData.value = values.data;

  const payload = {
    ...values.data,
    first_name: formData.value.first_name,
    last_name: formData.value.last_name,
    email: formData.value.email,
    password: formData.value.password,
    roles: selectedRolesArr,
    courses: selectedCoursesArr,
    tutor: formData.value.tutor,
  };
  console.log(payload);



  /* try {
     const user = await createUser(payload); // Create the user
     user.value = user; // Update user value
     visible.value = true; // Show the dialog
     formRef.value.reset(); // Reset form
 
     // Store selected roles for the user
     for (const role of s_roles.value) {
       await assignRoleToUser({  // Assuming this is the correct function
         role_id: role.id,
         user_id : user.id
       });
     }
 
     // Clear selected roles for next user creation
     s_roles.value = [];
   } catch (error) {
     // Handle any errors
     console.error('Error creating user:', error);
     // Optionally, show an error message to the user
   }
 }*/

  const data = await assignRoleToUser(payload);
  user.value = data;
  visible.value = true;
  formRef.value.reset();

}







</script>
<template>

  <div class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow">
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">
      Add User
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
        <button class="bg-red-700 h-12 w-40 text-white rounded">Create User</button>

      </Vueform>
    </div>
  </div>
</template>