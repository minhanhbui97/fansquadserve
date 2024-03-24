<script setup>
import {
  createStudent,
  getReferenceNumber as getReferenceNumberService,
  getStudent,
  updateStudent,
} from '@/Services/StudentService';
import { createTicket } from '@/Services/TicketService';
import { useTicketStore } from '@/Stores/TicketStore';
import { storeToRefs } from 'pinia';
import { computed, onMounted, ref, watch } from 'vue';

const ticketStore = useTicketStore();
const {
  getPriorities,
  getOperatingSystems,
  getPrograms,
  getTypeofMachines,
  getCourses,
  getUsers,
} = ticketStore;
const {
  priorities,
  operating_systems,
  programs,
  type_of_machines,
  courses,
  users,
} = storeToRefs(ticketStore);

const student = ref(null);
const formRef = ref(null);
const referenceNumber = ref(null);
const formData = ref(null);
const ticket = ref(null);

const selectPriorityOptions = computed(() => {
  return priorities.value.map((priority) => {
    return {
      value: priority.id,
      label: priority.name,
    };
  });
});

const selectOperatingSystemOptions = computed(() => {
  if (!operating_systems.value) return [];
  return operating_systems.value.map((operatingsystem) => {
    return {
      value: operatingsystem.id,
      label: operatingsystem.name,
    };
  });
});

const selectProgramOptions = computed(() => {
  if (!programs.value) return [];
  return programs.value.map((program) => {
    return {
      value: program.id,
      label: program.name,
    };
  });
});

const selectTypeofMachineOptions = computed(() => {
  return type_of_machines.value.map((typeofmachine) => {
    return {
      value: typeofmachine.id,
      label: typeofmachine.name,
    };
  });
});

const selectCourseOptions = computed(() => {
  return courses.value.map((course) => {
    return {
      value: course.id,
      label: course.name,
      code: course.code,
    };
  });
});

const selectUserOptions = computed(() => {
  if (!users.value) return [];
  return users.value.map((user) => {
    return {
      value: user.id,
      label: user.full_name,
      url: user.schedule_page?.schedule_url,
    };
  });
});

function selectCourse(courseId) {
  getUsers(courseId);
}

const is_submitted = ref(false);

async function submitTicket(values) {
  formData.value = values.data;
  // search again for student
  const fanshawe_id = formRef.value.el$('fanshawe_id').value;

  try {
    const studentData = await getStudent(fanshawe_id);
    student.value = studentData;
  } catch (err) {
    console.log(err);
    student.value = null;
  }

  const { first_name, last_name, email } = values.data;

  try {
    if (student.value) {
      await updateStudent(fanshawe_id, {
        fanshawe_id,
        first_name,
        last_name,
        email,
      });
    } else {
      const studentData = await createStudent({
        first_name,
        last_name,
        email,
        fanshawe_id,
      });
      student.value = studentData;
    }
  } catch (err) {
    student.value = null;
    throw err;
  }

  const payload = {
    ...values.data,
    first_name: undefined,
    last_name: undefined,
    email: undefined,
    fanshawe_id: undefined,
    student_id: student.value.id,
    reference_number: `${referenceNumber.value}-${formRef.value.el$('fanshawe_id').value}`,
  };

  const data = await createTicket(payload);
  ticket.value = data;
  is_submitted.value = true;
}

async function search() {
  const fanshawe_id = formRef.value.el$('fanshawe_id').value;
  try {
    const data = await getStudent(fanshawe_id);
    student.value = data;
  } catch (err) {
    console.log(err);
  }
}

async function getReferenceNumber() {
  const data = await getReferenceNumberService();
  referenceNumber.value = data;
}

onMounted(() => {
  getPriorities();
  getOperatingSystems();
  getPrograms();
  getTypeofMachines();
  getCourses();
  getReferenceNumber();
});

watch(student, () => {
  if (!student.value) return;
  formRef.value.el$('first_name').update(student.value.first_name);
  formRef.value.el$('last_name').update(student.value.last_name);
  formRef.value.el$('email').update(student.value.email);
});
</script>
<template>
  <div
    class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-hidden flex-grow"
  >
    <div>
      <img class="w-64" src="../../images/logo.png" alt="image description" />
    </div>
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">
      Service Request Form
    </h1>
    <div class="bg-gray-50 p-8 overflow-auto" v-if="!is_submitted">
      <Vueform ref="formRef" @submit="submitTicket" :endpoint="false">
        <TextElement
          name="fanshawe_id"
          label="Fanshawe ID"
          input-type="text"
          class="col-span-4"
        />
        <ButtonElement
          name="button"
          class="col-span-2 mt-7 w-full"
          danger
          @click="search"
          >Search</ButtonElement
        >
        <TextElement
          name="email"
          label="Student Email"
          input-type="email"
          class="col-span-6"
        />
        <TextElement
          name="first_name"
          label="First Name"
          input-type="text"
          class="col-span-6"
        />
        <TextElement
          name="last_name"
          label="Last Name"
          input-type="text"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <SelectElement
          name="program_id"
          label="Program"
          :native="false"
          :items="selectProgramOptions"
          class="col-span-6"
        />
        <TextElement
          name="program_level"
          label="Program Level*"
          input-type="text"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <SelectElement
          name="type_of_machine_id"
          label="Type Of Machine"
          :native="false"
          :items="selectTypeofMachineOptions"
          class="col-span-6"
        />
        <SelectElement
          name="operating_system_id"
          label="Operating System"
          :native="false"
          :items="selectOperatingSystemOptions"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <SelectElement
          name="priority_id"
          label="Priority Level"
          :native="false"
          :items="selectPriorityOptions"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <TextareaElement
          name="description"
          label="Description"
          input-type="message"
          class="col-span-12"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <SelectElement
          name="course_id"
          label="Course"
          :native="false"
          :items="selectCourseOptions"
          class="col-span-6"
          @select="selectCourse"
        >
          <template v-slot:option="{ option }">
            {{ option.code }} - {{ option.label }}
          </template>
        </SelectElement>
        <SelectElement
          name="assigned_tutor_id"
          label="Tutor"
          :native="false"
          :items="selectUserOptions"
          class="col-span-6"
        >
          <template v-slot:description="{ el$ }">
            <div>
              <a
                v-if="
                  selectUserOptions.find((option) => option.value === el$.value)
                    ?.url
                "
                class="text-blue-400"
                :href="
                  selectUserOptions.find((option) => option.value === el$.value)
                    ?.url
                "
              >
                Please click here to confirm booking from the Tutor's Schedule
                page</a
              >
              <div
                v-if="
                  formRef?.el$('fanshawe_id').value &&
                  selectUserOptions.find((option) => option.value === el$.value)
                    ?.url
                "
              >
                Please use Reference Number
                <span class="font-bold"
                  >{{ referenceNumber }}-{{
                    formRef?.el$('fanshawe_id').value
                  }}</span
                >
                in your booking
              </div>
            </div>
          </template>
          <template v-slot:option="{ option }">
            <div class="flex justify-between items-center w-full">
              <div>{{ option.label }}</div>
              <div class="text-blue-500">{{ option.url || '' }}</div>
            </div>
          </template>
        </SelectElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <DateElement
          name="scheduled_start_time"
          label="Start Time"
          class="col-span-6"
          :date="true"
          :time="true"
        />
        <DateElement
          name="scheduled_end_time"
          label="End Time"
          class="col-span-6"
          :date="true"
          :time="true"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <ButtonElement name="button" type="submit" submits>
          Confirm Appointment
        </ButtonElement>
      </Vueform>
    </div>

    <div v-if="is_submitted" class="border-2 border-amber-800 rounded-md p-8">
      <h2 class="text-black text-lg font-bold mb-4">Confirmation message</h2>

      <div class="flex flex-col gap-2 mb-4">
        <p>Dear {{ formData?.first_name }} {{ formData?.last_name }},</p>
        <p class="mb-4">
          Thank you for making a service request to the LabSquad at Fanshawe
          College London South! Your information has been recorded as follows:
        </p>

        <div class="text-sm flex flex-col gap-2 mb-4">
          <p>Ticket Number: {{ ticket?.id }}</p>
          <p>Reference Number: {{ ticket?.reference_number }}</p>

          <p>
            Type of Service:
            {{ ticket?.course_id === 1 ? 'Tech/Lab Support' : 'Peer Tutoring' }}
          </p>
          <p>
            Student Email:
            {{ ticket?.student.email }}
          </p>
          <p>
            Student ID:
            {{ ticket?.student.fanshawe_id }}
          </p>
          <p>
            Program:
            {{ ticket?.program.code }} - {{ ticket?.program.name }}
          </p>
          <p>
            Program Level:
            {{ ticket?.program_level }}
          </p>
          <p>
            Type of Machine:
            {{ ticket?.type_of_machine.name }}
          </p>
          <p>
            Operating System:
            {{ ticket?.operating_system.name }}
          </p>
          <p>
            Description:
            {{ ticket?.description }}
          </p>
          <p>
            Course:
            {{ ticket?.course.code }} - {{ ticket?.course.name }}
          </p>
          <p>
            Tutor:
            {{ ticket?.tutor.full_name }}
          </p>
          <p>
            Start time:
            {{ ticket?.scheduled_start_time }}
          </p>
          <p>
            End time:
            {{ ticket?.scheduled_end_time }}
          </p>
        </div>
      </div>
      <p class="mb-8">
        If you have any questions or need to modify your appointment details, please
        contact the LabSquad at {email}
      </p>
      <div class="flex gap-8 justify-end">
        <router-link to="/"
          ><button class="bg-amber-800 py-2 px-4 text-white rounded">
            Back to Home Page
          </button></router-link
        >
      </div>
    </div>
    <!-- </Dialog> -->
  </div>
</template>
