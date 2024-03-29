<script setup>
import { createUser } from '@/Services/UserService';
import { storeToRefs } from 'pinia';
import { useUserStore } from '@/Stores/UserStore';
import { computed, onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';


const router = useRouter();
const userStore = useUserStore();
const { getCourses, getRoles } = userStore;

const { courses, roles } = storeToRefs(userStore);

const formRef = ref(null);
const visible = ref(false);
const formData = ref(null);
const user = ref(null);
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

function selectRoles(option) {
  s_roles.value.push(option);

  console.log(s_roles.value);
  var roleObj = {};
  roleObj['id'] = option;
  selectedRolesArr.push(roleObj);
  console.log(roleObj);
  console.log(selectedRolesArr);
}

function deselectRoles(option) {
  console.log('hereeeeeee');

  selectedRolesArr = selectedRolesArr.filter(function (roleObj) {
    return roleObj.id !== option;
  });
  console.log(selectedRolesArr);
}

function deselectAllRoles() {
  selectedRolesArr = [];
  console.log(selectedRolesArr);
}

let selectedCoursesArr = [];

function selectCourses(option) {
  s_courses.value.push(option);

  console.log(s_courses.value);
  var courseObj = {};
  courseObj['id'] = option;
  selectedCoursesArr.push(courseObj);
  console.log(courseObj);
  console.log(selectedCoursesArr);
}

function deselectCourses(option) {
  console.log('hereeeeeee');

  selectedCoursesArr = selectedCoursesArr.filter(function (courseObj) {
    return courseObj.id !== option;
  });
  console.log(selectedCoursesArr);
}

function deselectAllCourses() {
  selectedCoursesArr = [];
  console.log(selectedCoursesArr);
}

async function submitUser(values) {
  try{
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

    const data = await createUser(payload);
    user.value = data;
    visible.value = true;
    formRef.value.reset();

    toast.success('Create user successfully!');
    router.push({ name: 'user-management' });
  }
  catch{
    toast.error('Unable to create user. Please try again!');

  }


}
</script>
<template>
  <div
    class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow"
  >
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">Add User</h1>
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <Vueform ref="formRef" @submit="submitUser" :endpoint="false">
        <TextElement
          name="first_name"
          label="First Name *"
          input-type="text"
          :rules="['required', 'max:255']"
          class="col-span-6"
        />
        <TextElement
          name="last_name"
          label="Last Name *"
          input-type="text"
          class="col-span-6"
          :rules="['required', 'max:255']"
        />
        <TextElement
          name="email"
          label="Email"
          input-type="email"
          :rules="['required', 'email', 'max:255']"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <TextElement
          name="password"
          label="Password"
          input-type="password"
          class="col-span-6"
          :rules="['required']"
        />
        <TextElement
          name="repassword"
          label="Retype Password"
          input-type="password"
          :rules="['required']"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <MultiselectElement
          name="roles"
          label="Role"
          :native="false"
          :items="selectRoleOptions"
          class="col-span-6"
          :close-on-select="false"
          :hide-selected="false"
          @select="selectRoles"
          @deselect="deselectRoles"
          :can-clear="false"
        />
        <MultiselectElement
          name="courses"
          label="Course"
          :native="false"
          :items="selectCourseOptions"
          class="col-span-6"
          :close-on-select="false"
          :hide-selected="false"
          @select="selectCourses"
          @deselect="deselectCourses"
          :can-clear="true"
          @clear="deselectAllCourses"
          :search="true"
        />
        <TextElement
          name="schedule_page"
          label="Tutor's Schedule"
          input-type="text"
        />
        <ButtonElement
          name="button"
          button-class="font-semibold"
          danger
          submits
          class="mx-auto mt-4"
        >
          Create User
        </ButtonElement>
      </Vueform>
    </div>
  </div>
</template>
