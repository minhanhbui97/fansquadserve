<script setup>
import { useUserStore } from '@/Stores/UserStore';
import { storeToRefs } from 'pinia';
import { nextTick } from 'vue';
import { isReactive } from 'vue';
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';

const route = useRoute();
const user_id = route.params.id;

const userStore = useUserStore();
const { getRoles, getCourses, getCurrentUser, updateCurrentUser } = userStore;

const { courses, roles, currentUser: user } = storeToRefs(userStore);

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
  console.log(user.value.id);
  console.log(values.requestData);
  await updateCurrentUser(user.value.id, values.requestData);
  toast.success('Update user successfully!');
}

onMounted(() => {
  getCurrentUser(user_id);
  getCourses();
  getRoles();
});

// Handle Role MultiSelect element
let selectedRoleIds = ref([]);
let selectedRolesArr = ref([]);

function selectRoles(option) {
  selectedRoleIds.value.push(option);
  if (option === 1) {
    is_tutor.value = true;
  }

  var roleObj = {};
  roleObj['id'] = option;
  selectedRolesArr.value.push(roleObj);
}

function deselectRoles(option) {
  selectedRoleIds.value = selectedRoleIds.value.filter(
    function (deselected_option) {
      return deselected_option !== option;
    },
  );

  is_tutor.value = false;

  selectedRolesArr.value = selectedRolesArr.value.filter(function (roleObj) {
    return roleObj.id !== option;
  });
}

// function deselectAllRoles() {
//   selectedRolesArr.value = [];
// }

// Handle Course MultiSelect element
let selectedCourseIds = ref([]);
let selectedCoursesArr = ref([]);

function selectCourses(option) {
  selectedCourseIds.value.push(option);

  var courseObj = {};
  courseObj['id'] = option;
  selectedCoursesArr.value.push(courseObj);
}

function deselectCourses(option) {
  selectedCourseIds.value = selectedCourseIds.value.filter(
    function (deselected_option) {
      return deselected_option !== option;
    },
  );

  selectedCoursesArr.value = selectedCoursesArr.value.filter(
    function (courseObj) {
      return courseObj.id !== option;
    },
  );
}

// function deselectAllCourses() {
//   selectedCoursesArr.value = [];
// }

const is_tutor = ref(false);

watch(user, () => {
  if (user.value) {
    if (user.value.roles.find((r) => r.id === 1)) {
      is_tutor.value = true;
    }
  }
});

// const existingCourseIds = ref([]);

watch(is_tutor, () => {
  if (is_tutor) {
    if (is_tutor.value) {
      user.value.courses.forEach((course) => {
        if (!selectedCourseIds.value.includes(course.id)) {
          selectedCourseIds.value.push(course.id);
          formRef.value.el$('courses').select(course.id);
        }
      });
      console.log(selectedCourseIds.value);

      if (user.value.schedule_page) {
        formRef.value
          .el$('schedule_page')
          .update(user.value.schedule_page.schedule_url);
      }
    }
  }
});

watch(user, () => {
  if (user.value) {
    formRef.value.el$('first_name').update(user.value.first_name);
    formRef.value.el$('last_name').update(user.value.last_name);
    formRef.value.el$('email').update(user.value.email);

    user.value.roles.forEach((role) => {
      if (!selectedRoleIds.value.includes(role.id)) {
        selectedRoleIds.value.push(role.id);
        formRef.value.el$('roles').select(role.id);
      }
    });

    if (selectedRoleIds.value.includes(1)) {
      is_tutor.value = true;
      console.log(is_tutor.value);
    }

    if (user.value.is_active === true) {
      formRef.value.el$('is_active').check();
    }
  }
});
</script>
<template>
  <div
    class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow"
  >
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">Update User</h1>
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <Vueform ref="formRef" @submit="submitUser" :endpoint="false">
        <TextElement
          name="first_name"
          label="First Name"
          input-type="text"
          :rules="['max:255']"
          class="col-span-6"
        />

        <TextElement
          name="last_name"
          label="Last Name"
          input-type="text"
          class="col-span-6"
          :rules="['max:255']"
        />
        <TextElement
          name="email"
          label="Email"
          input-type="email"
          :rules="['email', 'max:255']"
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
        />
        <TextElement
          name="repassword"
          label="Retype Password"
          input-type="password"
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
          :can-clear="false"
          :search="true"
          :conditions="[(form$, el$) => form$.el$('roles')?.value.includes(1)]"
        />
        <TextElement
          name="schedule_page"
          label="Tutor's Schedule"
          class="col-span-6"
          input-type="text"
          :conditions="[(form$, el$) => form$.el$('roles')?.value.includes(1)]"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <CheckboxElement
          name="is_active"
          label="Is Active?"
          true-value="1"
          false-value="0"
        />

        <button class="bg-red-700 h-12 w-40 text-white rounded">
          Update User
        </button>
      </Vueform>
    </div>
  </div>
</template>
