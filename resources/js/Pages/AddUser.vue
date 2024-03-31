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

const is_tutor = ref(false);

async function submitUser(values) {
  const allowed_domains = ['fanshaweonline.ca', 'fanshawec.ca'];
  let submitted_email = values.requestData.email;
  let submitted_email_domain = submitted_email.split('@')[1];
  if (allowed_domains.includes(submitted_email_domain)) {
    try {
      const data = await createUser(values.requestData);
      user.value = data;
      visible.value = true;
      formRef.value.reset();

      toast.success('Create user successfully!');
      router.push({ name: 'users' });
    } catch {
      toast.error('Unable to create user. Please try again!');
    }
  } else {
    toast.error(
      'Unable to create user. Please use your @fanshaweonline.ca or @fanshawec.ca email address!',
    );
  }
}
</script>
<template>
  <div
    class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow"
  >
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">Add User</h1>
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <Vueform
        ref="formRef"
        @submit="submitUser"
        :endpoint="false"
        :display-errors="false"
      >
        <TextElement
          name="first_name"
          label="First Name *"
          input-type="text"
          :rules="['required', 'alpha', 'between:3,50']"
          class="col-span-6"
        />
        <TextElement
          name="last_name"
          label="Last Name *"
          input-type="text"
          class="col-span-6"
          :rules="['required', 'alpha', 'between:3,50']"
        />
        <TextElement
          name="email"
          label="Email *"
          input-type="email"
          :rules="['required', 'email']"
          class="col-span-6"
        >
          <template v-slot:description="{ el$ }">
            <div>Please use your Fanshawe domain email.</div>
          </template>
        </TextElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <TextElement
          name="password"
          label="Password *"
          input-type="password"
          class="col-span-6"
          :rules="['required', 'between:8,255', 'confirmed']"
        />
        <TextElement
          name="password_confirmation"
          label="Password Confirmation *"
          input-type="password"
          :rules="['required', 'between:8,255']"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <MultiselectElement
          name="roles"
          label="Role *"
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
