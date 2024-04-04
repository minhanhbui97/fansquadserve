<script setup>
import {
  createStudent,
  getReferenceNumber as getReferenceNumberService,
  getStudent,
  updateStudent,
} from '@/Services/StudentService';
import { createTicket } from '@/Services/TicketService';
import { useTicketStore } from '@/Stores/TicketStore';
import { useClipboard } from '@vueuse/core';
import { storeToRefs } from 'pinia';
import { computed, onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

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

const finalReferenceNumber = computed(() => {
  return `${referenceNumber.value} - ${formRef.value.el$('fanshawe_id').value}`;
});

const { text, copy, copied, isSupported } = useClipboard({
  source: finalReferenceNumber,
});

function selectCourse(courseId) {
  getUsers(courseId);
}

const is_submitted = ref(false);

async function submitTicket(values) {
  formData.value = values.data;
  // search again for student
  const fanshawe_id = formRef.value.el$('fanshawe_id').value;

  const { first_name, last_name, email } = values.data;

  if (
    !email.includes('@fanshaweonline.ca') &&
    !email.includes('@fanshawec.ca')
  ) {
    toast.error(
      'Unable to create ticket. Please use your @fanshaweonline.ca or @fanshawec.ca email address!',
    );
    return;
  }

  try {
    const studentData = await getStudent(fanshawe_id);
    student.value = studentData;
  } catch (err) {
    console.log(err);
    student.value = null;
  }

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
  <div class="max-w-4xl mx-auto p-8 flex flex-col gap-8 flex-grow">
    <div>
      <router-link :to="{ name: 'landing' }">
        <img class="w-64" src="../../images/logo.png" alt="image description" />
      </router-link>
    </div>
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">
      Service Request Form
    </h1>
    <div class="bg-gray-50 p-8 overflow-auto" v-if="!is_submitted">
      <Vueform
        ref="formRef"
        @submit="submitTicket"
        :endpoint="false"
        :display-errors="false"
      >
        <TextElement
          name="fanshawe_id"
          label="Fanshawe ID *"
          input-type="text"
          class="col-span-4"
          :rules="['required', 'digits_between:7,10']"
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
          label="Student Email *"
          input-type="email"
          class="col-span-6"
          :rules="['required', 'email']"
        >
          <template v-slot:description="{ el$ }">
            <div>Please use your Fanshawe student email.</div>
          </template>
        </TextElement>
        <TextElement
          name="first_name"
          label="First Name *"
          input-type="text"
          class="col-span-6"
          :rules="['required', 'alpha', 'between:3,50']"
        />
        <TextElement
          name="last_name"
          label="Last Name *"
          input-type="text"
          class="col-span-6"
          :rules="['required', 'alpha', 'between:3,50']"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <SelectElement
          name="program_id"
          label="Program *"
          :native="false"
          :items="selectProgramOptions"
          class="col-span-6"
          :can-clear="false"
          :can-deselect="false"
          :rules="['required']"
        />
        <TextElement
          name="program_level"
          label="Program Level"
          input-type="text"
          class="col-span-6"
          :rules="['nullable', 'digits_between:0,1']"
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
          :can-deselect="false"
        />
        <SelectElement
          name="operating_system_id"
          label="Operating System"
          :native="false"
          :items="selectOperatingSystemOptions"
          class="col-span-6"
          :can-deselect="false"
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
          :can-deselect="false"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <TextareaElement
          name="description"
          label="Description *"
          input-type="message"
          class="col-span-12"
          :rules="['required', 'between:10,500']"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <SelectElement
          name="course_id"
          label="Course *"
          :native="false"
          :items="selectCourseOptions"
          class="col-span-6"
          @select="selectCourse"
          :can-clear="false"
          :can-deselect="false"
          :rules="['required']"
          :search="true"
        >
          <template v-slot:option="{ option }">
            {{ option.code }} - {{ option.label }}
          </template>
        </SelectElement>
        <SelectElement
          name="assigned_tutor_id"
          label="Tutor *"
          :native="false"
          :items="selectUserOptions"
          class="col-span-6"
          :can-clear="false"
          :can-deselect="false"
          :rules="['required']"
        >
          <template v-slot:description="{ el$ }">
            <div
              v-if="
                formRef?.el$('fanshawe_id').value &&
                selectUserOptions.find((option) => option.value === el$.value)
                  ?.url
              "
              class="mb-2"
            >
              Please use Reference Number
              <span class="font-bold">{{ finalReferenceNumber }}</span>
              <button
                @click="copy(finalReferenceNumber)"
                class="bg-gray-100 p-1 text-black rounded ml-1 hover:bg-gray-200"
                type="button"
              >
                <font-awesome-icon icon="fa-regular fa-copy" v-if="!copied" />
                <font-awesome-icon icon="fa-solid fa-copy" v-else />
              </button>
              in your booking.
            </div>
            <div>
              <a
                v-if="
                  selectUserOptions.find((option) => option.value === el$.value)
                    ?.url
                "
                class="text-blue-400 hover:underline"
                :href="
                  selectUserOptions.find((option) => option.value === el$.value)
                    ?.url
                " target=”_blank”
              >
                Click here to confirm booking from the Tutor's Schedule page.</a
              >
            </div>
          </template>
        </SelectElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <DateElement
          name="scheduled_start_time"
          label="Start Time *"
          class="col-span-6"
          :date="true"
          :time="true"
          :rules="['required', 'after_or_equal:today']"
        />
        <DateElement
          name="scheduled_end_time"
          label="End Time *"
          class="col-span-6"
          :date="true"
          :time="true"
          :rules="['required', 'after_or_equal:scheduled_start_time']"
        />
        <ButtonElement
          name="button"
          button-class="font-semibold "
          danger
          type="submit"
          submits
          class="mx-auto mt-4"
          >Confirm Appointment</ButtonElement
        >
      </Vueform>
    </div>

    <div v-if="is_submitted" class="border-2 border-amber-800 rounded-md p-8">
      <h2 class="text-black text-lg font-bold mb-4">Confirmation message</h2>

      <div class="flex flex-col gap-2 mb-4">
        <p>Dear {{ formData?.first_name }} {{ formData?.last_name }},</p>
        <p class="mb-4">
          Thank you for making a service request to the LabSquad at Fanshawe
          College London South Campus! Your information has been recorded as
          follows:
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
            {{ ticket?.program_level ? ticket?.program_level : 'N/A' }}
          </p>
          <p>
            Type of Machine:
            {{ ticket?.type_of_machine ? ticket?.type_of_machine.name : 'N/A' }}
          </p>
          <p>
            Operating System:
            {{
              ticket?.operating_system ? ticket?.operating_system.name : 'N/A'
            }}
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
        If you have any questions or need to modify your appointment details,
        please contact the LabSquad at {email}
      </p>
      <div class="flex gap-8 justify-end">
        <router-link to="/"
          ><button class="bg-red-700 py-2 px-4 text-white rounded">
            Back to Home Page
          </button></router-link
        >
      </div>
    </div>
    <!-- </Dialog> -->
  </div>
</template>
