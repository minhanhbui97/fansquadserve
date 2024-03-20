<script setup>
import { createUser } from '@/Services/UserService';
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

async function submitUser(values) {
  formData.value = values.data;
  try {
    const userData = await createUser(values.data);
     console.log('User created successfully:', userData);
  
    } catch (error) {
    
    console.error('Error creating user:', error);
  }
}

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
    name="password"
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
    <SelectElement 
     name="role"
     label="Role"
     :items="selectRoleOptions"
     class="w-80 absolute right-80 mt-28"

    />
    <br>
    <SelectElement 
    name="course"
    label="Course"
    :items="selectCourseOptions"
    class="w-80 absolute right-80 mt-44"

    />
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