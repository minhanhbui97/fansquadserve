<script setup>
import { createUser , assignRoleToUser} from '@/Services/UserService';

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

const s_roles = ref([]);
const s_courses = ref([]);

function selectedRoles(option) {
  s_roles.value.push(option);

  console.log(option);
}





function selectedCourses(option) {
  s_courses.value.push(option);

  console.log(option);
}


async function submitUser(values) {
  formData.value = values.data;
   
  const payload = {
    ...values.data,
    first_name: formData.value.first_name,
    last_name: formData.value.last_name,
    email: formData.value.email,
    password:formData.value.password,
    roles: s_roles.value,
    courses: s_courses.value,
    tutor: formData.value.tutor,
  };



  try {
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
}

 /*const data = await assignRoleToUser(payload);
  user.value = data;
  visible.value = true;
  formRef.value.reset();

  for(const role of s_roles.value){
    await createUser({
      
      user_id: user.id,
      role_id: role.id,

    });
  }

}*/







</script>
<template>
  <h1 class="text-red-700 font-bold text-xl ml-20 mt-20">Add New User</h1>
  <p class="text-gray-500 font-bold text-lg mt-10 ml-20"> Please provide information below to create a new user.</p>
  
  <Vueform  ref="formRef" @submit="submitUser" :endpoint="false">
    <div class="">
    <TextElement 
     name="first_name"
     label="FirstName"
     input-type="text"
     class="ml-20  mt-10 w-80"
     :rules="['required', 'max:255']"

    />
    <br>
   
    <TextElement 
     name="email"
     label="Email"
     input-type="email"
     class="ml-20   w-80"
     :rules="['required', 'email', 'max:255']"

    />
    <br>
    <TextElement 
    name="password"
    label="Password"
    input-type="password"
    class="ml-20 w-80"
    :rules="['required']"

    />
    <TextElement 
    name="repassword"
    label="Retype Password"
    input-type="password"
    class="ml-20 w-80 mt-6"
    :rules="['required']"

    />
    </div>
    <div class="container gap-y-20">
    <TextElement 
     name="last_name"
     label="LastName"
     input-type="text"
     class="absolute right-80 mt-10 w-80"
     :rules="['required','max:255']"

    />
    <br>

    <MultiselectElement
      name="roles"
      label="Role"
      :native="false"
      :items="selectRoleOptions"
      class="w-80 absolute right-80 mt-28"
      @select="selectedRoles"
    />

   
    <br>
    
    <MultiselectElement 
    name="courses"
    label="Course"
    :native="false"
    :items="selectCourseOptions"
    class="w-80 absolute right-80 mt-44"
    @select="selectedCourses"

    /><br>
    <TextElement 
    name="tutor"
    label="Tutor's Schedule"
    class="w-80 absolute right-80 bottom-44 ml-10"

    />
    
    </div>
    <div class="absolute bottom-20 right-80">
    <!--<ButtonElement @click="visible = false" name="button" danger submits class=" absolute bottom-0 ">Cancel</ButtonElement>-->
    <ButtonElement name="button" type="submit" submits class=" ml-24" >
          Add User
        </ButtonElement>
    </div>
  </Vueform>
</template>