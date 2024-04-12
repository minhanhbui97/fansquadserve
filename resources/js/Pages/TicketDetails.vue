<script setup>
import { useAuthStore } from '@/Stores/AuthStore';
import { useTicketStore } from '@/Stores/TicketStore';
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
dayjs.extend(utc);
dayjs.extend(timezone);
import { storeToRefs } from 'pinia';
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';

// Get local timezone to display Actual Start/End Time
const localTz = dayjs.tz.guess();

const route = useRoute();
const ticket_id = route.params.id;

const ticketStore = useTicketStore();
const {
  getTicketStatuses,
  getPriorities,
  getUsers,
  getCurrentTicket,
  updateCurrentTicket,
} = ticketStore;
const {
  priorities,
  ticket_statuses,
  users,
  currentTicket: ticket,
} = storeToRefs(ticketStore);

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

const formRef = ref();
const toast = useToast();
const isLoading = ref(false);

// Condition to show Update Ticket button only for user who is the assigned tutor
const isAssignedTutor = computed(() => {
  if (!user.value || !ticket.value) return false;

  return (
    user.value.roles.find((role) => role.name === 'Tutor') &&
    ticket.value.assigned_tutor_id === user.value.id
  );
});

// Get data for dropdowns
const selectPriorityOptions = computed(() => {
  return priorities.value.map((priority) => {
    return {
      value: priority.id,
      label: priority.name,
    };
  });
});

const selectTicketStatusOptions = computed(() => {
  const all_ticket_statuses = ticket_statuses.value.map((ticket_status) => {
    return {
      value: ticket_status.id,
      label: ticket_status.name,
    };
  });

  let possibleTicketStatusOptions = [];
  if (ticket.value) {
    // If latest_status is New, show New & Confirmed
    if (ticket.value.latest_status.id === 1) {
      possibleTicketStatusOptions.push(all_ticket_statuses[0]);
      possibleTicketStatusOptions.push(all_ticket_statuses[1]);
    }
    // If latest_status is Confirmed, show Confirmed & In-progress
    else if (ticket.value.latest_status.id === 2) {
      possibleTicketStatusOptions.push(all_ticket_statuses[1]);
      possibleTicketStatusOptions.push(all_ticket_statuses[2]);
    }
    // If latest_status is In-progress, show In-progress, Resolved, On-hold, or Escalated
    else if (ticket.value.latest_status.id === 3) {
      possibleTicketStatusOptions.push(all_ticket_statuses[2]);
      possibleTicketStatusOptions.push(all_ticket_statuses[3]);
      possibleTicketStatusOptions.push(all_ticket_statuses[4]);
      possibleTicketStatusOptions.push(all_ticket_statuses[5]);
    }
    // If latest_status is Resolved, show Resolved
    else if (ticket.value.latest_status.id === 4) {
      possibleTicketStatusOptions.push(all_ticket_statuses[3]);
    }
    // If latest_status is On-hold, show Resolved, On-hold, or Closed
    else if (ticket.value.latest_status.id === 5) {
      possibleTicketStatusOptions.push(all_ticket_statuses[3]);
      possibleTicketStatusOptions.push(all_ticket_statuses[4]);
      possibleTicketStatusOptions.push(all_ticket_statuses[6]);
    }
    // If latest_status is Escalated, show Resolved, Escalated, or Closed
    else if (ticket.value.latest_status.id === 6) {
      possibleTicketStatusOptions.push(all_ticket_statuses[3]);
      possibleTicketStatusOptions.push(all_ticket_statuses[5]);
      possibleTicketStatusOptions.push(all_ticket_statuses[6]);
    }
    // If latest_status is Closed, show Closed
    else if (ticket.value.latest_status.id === 7) {
      possibleTicketStatusOptions.push(all_ticket_statuses[6]);
    }
  }
  return possibleTicketStatusOptions;
});

const selectUserOptions = computed(() => {
  return users.value.map((user) => {
    return {
      value: user.id,
      label: user.full_name,
    };
  });
});

// Helper function to round to 2 decimal place
function roundNum(num) {
  return Math.round((num + Number.EPSILON) * 100) / 100;
}

// Calculate SLA
const allStatuses = computed(() => ticket.value?.ticket_statuses || []);

const firstResponseTime = computed(() => {
  if (!allStatuses.value.length) return 'N/A';

  const status1 = allStatuses.value.find(
    (status) => status.pivot.ticket_status_id === 1,
  );
  const status2 = allStatuses.value.find(
    (status) => status.pivot.ticket_status_id === 2,
  );
  if (!status1 || !status2) return 'N/A';

  const time1 = dayjs(status1.pivot.created_at);
  const time2 = dayjs(status2.pivot.created_at);

  return roundNum(time2.diff(time1, 'h', true));
});

const resolutionTime = computed(() => {
  if (!allStatuses.value.length) return 'N/A';

  const status1 = allStatuses.value.find(
    (status) => status.pivot.ticket_status_id === 3,
  );
  const status2 = allStatuses.value.find(
    (status) => status.pivot.ticket_status_id === 4,
  );

  if (!status1 || !status2) return 'N/A';

  const time1 = dayjs(status1.pivot.created_at);
  const time2 = dayjs(status2.pivot.created_at);

  return roundNum(time2.diff(time1, 'h', true));
});

async function submitTicket(values) {
  await updateCurrentTicket(ticket.value.id, values.data);
  toast.success('Update ticket successfully!');
}

async function initialize() {
  isLoading.value = true;
  await getCurrentTicket(ticket_id);
  await getPriorities();
  await getTicketStatuses();
  isLoading.value = false;
}

onMounted(() => {
  initialize();
});

watch(ticket, () => {
  if (ticket.value) {
    getUsers(ticket.value.course_id);
  }
});

// Populate ticket info in input fields on the form
watch([users, priorities, ticket_statuses], () => {
  if (ticket.value) {
    formRef.value.el$('priority_id').update(ticket.value.priority_id);
    formRef.value.el$('ticket_status_id').update(ticket.value.latest_status.id);
    formRef.value.el$('tutor_id').update(ticket.value.assigned_tutor_id);
    formRef.value
      .el$('scheduled_start_time')
      .update(
        dayjs(ticket.value.scheduled_start_time).format('YYYY-MM-DD HH:mm'),
      );
    formRef.value
      .el$('scheduled_end_time')
      .update(
        dayjs(ticket.value.scheduled_end_time).format('YYYY-MM-DD HH:mm'),
      );
    formRef.value.el$('tutor_note').update(ticket.value.tutor_note);
  }
});
</script>

<template>
  <div
    class="max-w-6xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow"
  >
    <h1 class="text-red-700 text-3xl font-bold flex-shrink-0">
      Ticket Details
      <font-awesome-icon icon="fa-spinner" v-if="isLoading" class="fa-spin" />
    </h1>

    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <Vueform
        ref="formRef"
        @submit="submitTicket"
        :endpoint="false"
        :display-errors="false"
      >
        <StaticElement name="id" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Ticket ID:</span> {{ ticket?.id }}
          </div>
        </StaticElement>
        <StaticElement name="reference_number" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Reference Number:</span>
            {{ ticket?.reference_number }}
          </div>
        </StaticElement>
        <StaticElement name="id" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Request Type:</span>
            {{ ticket?.course_id === 1 ? 'Tech/Lab Support' : 'Peer Tutoring' }}
          </div>
        </StaticElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <DateElement
          :date="true"
          :time="true"
          name="scheduled_start_time"
          class="col-span-4"
          label="Scheduled Start Time *"
          :rules="['required']"
        />
        <DateElement
          :date="true"
          :time="true"
          name="scheduled_end_time"
          class="col-span-4"
          label="Scheduled End Time *"
          :rules="['required', 'after_or_equal:scheduled_start_time']"
        />
        <StaticElement class="col-span-4" name="sla">
          <div class="flex flex-col gap-4">
            <h1 class="font-medium text-sm">SLA</h1>
            <div class="text-sm">
              First Response Time: {{ firstResponseTime }} hour(s)
            </div>
            <div class="text-sm">
              Resolution Time: {{ resolutionTime }} hour(s)
            </div>
          </div>
        </StaticElement>
        <StaticElement name="actual_start_time" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Actual Start Time:</span>
            {{
              ticket?.actual_start_time
                ? dayjs
                    .utc(ticket.actual_start_time)
                    .tz(localTz)
                    .format('YYYY-MM-DD HH:mm')
                : 'N/A'
            }}
          </div>
        </StaticElement>
        <StaticElement name="actual_end_time" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Actual End Time:</span>
            {{
              ticket?.actual_end_time
                ? dayjs
                    .utc(ticket.actual_end_time)
                    .tz(localTz)
                    .format('YYYY-MM-DD HH:mm')
                : 'N/A'
            }}
          </div>
        </StaticElement>
        <StaticElement name="created_at" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Created At:</span>
            {{
              dayjs
                .utc(ticket?.created_at)
                .tz(localTz)
                .format('YYYY-MM-DD HH:mm')
            }}
          </div>
        </StaticElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <SelectElement
          :native="false"
          name="priority_id"
          :items="selectPriorityOptions"
          :default="ticket?.priority_id"
          label="Priority"
          class="col-span-4"
          :can-deselect="false"
          :disabled="isLoading"
        />
        <SelectElement
          :native="false"
          name="ticket_status_id"
          :items="selectTicketStatusOptions"
          :default="ticket?.latest_status.id"
          label="Status *"
          class="col-span-4"
          :disabled="
            ticket?.latest_status.id == 7 || ticket?.latest_status.id == 4 || isLoading
          "
          :rules="['required']"
          :can-clear="false"
          :can-deselect="false"
        />
        <SelectElement
          :native="false"
          name="tutor_id"
          :items="selectUserOptions"
          label="Assignee *"
          class="col-span-4"
          :rules="['required']"
          :can-clear="false"
          :can-deselect="false"
          :disabled="isLoading"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <StaticElement name="description" class="col-span-6 row-span-2">
          <div class="text-sm">
            <div class="font-medium">Description</div>
            <div>{{ ticket?.description }}</div>
          </div>
        </StaticElement>

        <TextareaElement
          name="tutor_note"
          class="col-span-6 row-span-2"
          label="Tutor's Note"
          :rules="['between:0,500']"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <StaticElement name="section_title">
          <h2 class="text-red-700 text-lg font-bold">Student Details</h2>
        </StaticElement>
        <StaticElement name="student_name" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Student Name:</span>
            {{ ticket?.student.first_name }} {{ ticket?.student.last_name }}
          </div>
        </StaticElement>

        <StaticElement name="student_email" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Student Email:</span>
            {{ ticket?.student.email }}
          </div>
        </StaticElement>

        <StaticElement name="student_id" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Student ID:</span>
            {{ ticket?.student_id }}
          </div>
        </StaticElement>
        <StaticElement name="program" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Program:</span>
            {{ ticket?.program.name }}
          </div>
        </StaticElement>
        <StaticElement name="program_level" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Program Level:</span>
            {{ ticket?.program_level ? ticket?.program_level : 'N/A' }}
          </div>
        </StaticElement>
        <StaticElement name="course" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Course:</span>
            {{ ticket?.course.code }} - {{ ticket?.course.name }}
          </div>
        </StaticElement>
        <StaticElement name="student_email" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Machine Type:</span>
            {{ ticket?.type_of_machine ? ticket?.type_of_machine.name : 'N/A' }}
          </div>
        </StaticElement>
        <StaticElement name="student_email" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Operating System:</span>
            {{
              ticket?.operating_system ? ticket?.operating_system.name : 'N/A'
            }}
          </div>
        </StaticElement>
        <ButtonElement
          v-if="isAssignedTutor"
          name="button"
          button-class="font-semibold"
          submits
          class="mx-auto mt-4"
          :override-classes="{
            ButtonElement: {
              button_primary: 'bg-red-700 text-white'
            }
          }"
          >Update Ticket</ButtonElement
        >
      </Vueform>
    </div>
  </div>
</template>
