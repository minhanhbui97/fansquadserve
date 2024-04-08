<script setup>
import TicketByDateBarChart from '@/Components/TicketByDateBarChart.vue';
import TicketByStatusBarChart from '@/Components/TicketByStatusBarChart.vue';
import AverageSLABarChart from '@/Components/AverageSLABarChart.vue';

import { useTicketStore } from '@/Stores/TicketStore';
import { storeToRefs } from 'pinia';
import { onMounted, ref, watch, computed } from 'vue';
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import isBetween from 'dayjs/plugin/isBetween';
dayjs.extend(isBetween);
dayjs.extend(utc);
dayjs.extend(timezone);
import _ from 'lodash';

const ticketStore = useTicketStore();
const { getTickets, getUsers } = ticketStore;
const { tickets, users } = storeToRefs(ticketStore);
const isLoading = ref(false);

// Get data for Assignee dropdown
const selectUserOptions = computed(() => {
  if (!users.value) return [];
  return users.value.map((user) => {
    return {
      value: user.id,
      label: user.full_name,
    };
  });
});

async function initialize() {
  isLoading.value = true;
  await getTickets();
  await getUsers();
  isLoading.value = false;
}

onMounted(() => {
  initialize();
});

// Get date range option for Date filter
const format_string = ref('MM/DD');
const date_diff = ref(7);

function selectDateFilter(option) {
  if (option == 'hour') {
    format_string.value = 'MM/DD: HH:00';
    date_diff.value = 1;
  } else if (option == 'day-7') {
    format_string.value = 'MM/DD';
    date_diff.value = 7;
  } else if (option == 'day-30') {
    format_string.value = 'MM/DD';
    date_diff.value = 30;
  } else if (option == 'month') {
    format_string.value = 'MM';
    date_diff.value = 365;
  }
}

// Get assignees for Assignee filter
const options = ref([]);
const assigneeFilter = ref('');

// If get data for all assignees
function selectAssigneeRadio(newValue) {
  assigneeFilter.value = newValue;
  if (newValue === 'Select all assignees') {
    options.value = users.value.map((user) => user.id);
  } else if (newValue === 'Specify assignees to filter') {
    options.value = [];
  }
}

// If filter data by specifying assignees
function selectAssigneeFilter(option) {
  options.value.push(option);
}

function deselectAssigneeFilter(option) {
  options.value = options.value.filter(function (deselected_option) {
    return deselected_option !== option;
  });
}

function deselectAllAssigneeFilter() {
  options.value = [];
}

// Filter ticket data by active tutors only, and selecteddate and assignee filters
const ticket_data = computed(() => {
  let tickets_filtered_by_active_tutor = _.filter(tickets.value, function (t) {
    return t.tutor.is_active;
  });

  let tickets_filtered_by_active_tutor_and_date = _.filter(
    tickets_filtered_by_active_tutor,
    function (t) {
      return dayjs(t.created_at).isBetween(
        dayjs(),
        dayjs().subtract(date_diff.value, 'day'),
      );
    },
  );

  let tickets_filtered_by_active_tutor_date_and_assignee = _.filter(
    tickets_filtered_by_active_tutor_and_date,
    function (t) {
      return options.value.includes(t.assigned_tutor_id);
    },
  );
  return tickets_filtered_by_active_tutor_date_and_assignee;
});

// Get data for TicketByDateBarChart
const date_chart_result = computed(() => {
  let data_obj = _(ticket_data.value)
    .groupBy((ticket) => dayjs(ticket.created_at).format(format_string.value))
    .mapValues((ticket) => ticket.length)
    .value();
  // Sort chart data by date in ascending order
  let sortedKeys = Object.keys(data_obj).sort();
  const sortedObj = {};
  sortedKeys.forEach((key) => {
    sortedObj[key] = data_obj[key];
  });
  console.log(sortedObj);
  return sortedObj;
});

// Get data for TicketByStatusBarChart
const status_chart_result = computed(() => {
  let data_obj = _(ticket_data.value)
    .groupBy((ticket) => ticket.latest_status.name)
    .mapValues((ticket) => ticket.length)
    .value();
  // Sort chart data by status
  let sortedKeys = [
    'New',
    'Confirmed',
    'In-progress',
    'Resolved',
    'Escalated',
    'On-hold',
    'Closed',
  ];
  const sortedObj = {};
  sortedKeys.forEach((key) => {
    sortedObj[key] = data_obj[key];
  });
  return sortedObj;
});

// Helper function to round to 2 decimal place
function roundNum(num) {
  return Math.round((num + Number.EPSILON) * 100) / 100;
}

// Get data for AverageSLABarChart
const sla_chart_result = computed(() => {
  // Include only tickets with current status as "Resolved"
  const tickets_filtered_by_resolved_status = _.filter(
    ticket_data.value,
    function (t) {
      return t.latest_status.id === 4;
    },
  );

  //Exclude tickets that has ever been moved to "On-hold" or "Escalated" status
  const tickets_filtered_by_resolved_status_and_excl_statuses = _.filter(
    tickets_filtered_by_resolved_status,
    function (t) {
      return (
        t.ticket_statuses.find(
          (status) => status.id === 5 || status.id === 6,
        ) === undefined
      );
    },
  );

  // Calculate TFR & TR for Low priority tickets
  let total_tfr_low_priority = 0;
  let num_of_tickets_tfr_low_priority = 0;
  let avg_tfr_low_priority = 0;
  tickets_filtered_by_resolved_status_and_excl_statuses.forEach(
    calculate_tfr_low_priority,
  );

  function calculate_tfr_low_priority(ticket) {
    // Calculate the difference between when ticket has "New" & "Confirmed" status
    if (
      ticket.ticket_statuses.find((s) => s.id === 1) &&
      ticket.ticket_statuses.find((s) => s.id === 2) &&
      ticket.priority_id === 1
    ) {
      const time0 = dayjs(ticket.ticket_statuses[0].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[1].pivot.created_at);

      const time_to_fr = roundNum(time1.diff(time0, 'h', true));
      total_tfr_low_priority += time_to_fr;
      num_of_tickets_tfr_low_priority += 1;
    }
    avg_tfr_low_priority =
      total_tfr_low_priority / num_of_tickets_tfr_low_priority;
  }

  let total_tr_low_priority = 0;
  let num_of_tickets_tr_low_priority = 0;
  let avg_tr_low_priority = 0;
  tickets_filtered_by_resolved_status_and_excl_statuses.forEach(
    calculate_tr_low_priority,
  );

  function calculate_tr_low_priority(ticket) {
    // Calculate the difference between when ticket has "In-progress" & "Resolved" status
    if (
      ticket.ticket_statuses.find((s) => s.id === 3) &&
      ticket.ticket_statuses.find((s) => s.id === 4) &&
      ticket.priority_id === 1
    ) {
      const time0 = dayjs(ticket.ticket_statuses[2].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[3].pivot.created_at);

      const time_to_r = roundNum(time1.diff(time0, 'h', true));
      total_tr_low_priority += time_to_r;
      num_of_tickets_tr_low_priority += 1;
    }
    avg_tr_low_priority =
      total_tr_low_priority / num_of_tickets_tr_low_priority;
  }

  // Calculate TFR & TR for Medium priority tickets
  let total_tfr_medium_priority = 0;
  let num_of_tickets_tfr_medium_priority = 0;
  let avg_tfr_medium_priority = 0;
  tickets_filtered_by_resolved_status_and_excl_statuses.forEach(
    calculate_tfr_medium_priority,
  );

  function calculate_tfr_medium_priority(ticket) {
    // Calculate the difference between when ticket has "New" & "Confirmed" status
    if (
      ticket.ticket_statuses.find((s) => s.id === 1) &&
      ticket.ticket_statuses.find((s) => s.id === 2) &&
      ticket.priority_id === 2
    ) {
      const time0 = dayjs(ticket.ticket_statuses[0].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[1].pivot.created_at);

      const time_to_fr = roundNum(time1.diff(time0, 'h', true));
      total_tfr_medium_priority += time_to_fr;
      num_of_tickets_tfr_medium_priority += 1;
    }
    avg_tfr_medium_priority =
      total_tfr_medium_priority / num_of_tickets_tfr_medium_priority;
  }

  let total_tr_medium_priority = 0;
  let num_of_tickets_tr_medium_priority = 0;
  let avg_tr_medium_priority = 0;
  tickets_filtered_by_resolved_status_and_excl_statuses.forEach(
    calculate_tr_medium_priority,
  );

  function calculate_tr_medium_priority(ticket) {
    // Calculate the difference between when ticket has "In-progress" & "Resolved" status
    if (
      ticket.ticket_statuses.find((s) => s.id === 3) &&
      ticket.ticket_statuses.find((s) => s.id === 4) &&
      ticket.priority_id === 2
    ) {
      const time0 = dayjs(ticket.ticket_statuses[2].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[3].pivot.created_at);

      const time_to_r = roundNum(time1.diff(time0, 'h', true));
      total_tr_medium_priority += time_to_r;
      num_of_tickets_tr_medium_priority += 1;
    }
    avg_tr_medium_priority =
      total_tr_medium_priority / num_of_tickets_tr_medium_priority;
  }

  // Calculate TFR & TR for High priority tickets
  let total_tfr_high_priority = 0;
  let num_of_tickets_tfr_high_priority = 0;
  let avg_tfr_high_priority = 0;
  tickets_filtered_by_resolved_status_and_excl_statuses.forEach(
    calculate_tfr_high_priority,
  );

  function calculate_tfr_high_priority(ticket) {
    // Calculate the difference between when ticket has "New" & "Confirmed" status
    if (
      ticket.ticket_statuses.find((s) => s.id === 1) &&
      ticket.ticket_statuses.find((s) => s.id === 2) &&
      ticket.priority_id === 3
    ) {
      const time0 = dayjs(ticket.ticket_statuses[0].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[1].pivot.created_at);

      const time_to_fr = roundNum(time1.diff(time0, 'h', true));
      total_tfr_high_priority += time_to_fr;
      num_of_tickets_tfr_high_priority += 1;
    }
    avg_tfr_high_priority =
      total_tfr_high_priority / num_of_tickets_tfr_high_priority;
  }

  let total_tr_high_priority = 0;
  let num_of_tickets_tr_high_priority = 0;
  let avg_tr_high_priority = 0;
  tickets_filtered_by_resolved_status_and_excl_statuses.forEach(
    calculate_tr_high_priority,
  );

  function calculate_tr_high_priority(ticket) {
    // Calculate the difference between when ticket has "In-progress" & "Resolved" status
    if (
      ticket.ticket_statuses.find((s) => s.id === 3) &&
      ticket.ticket_statuses.find((s) => s.id === 4) &&
      ticket.priority_id === 3
    ) {
      const time0 = dayjs(ticket.ticket_statuses[2].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[3].pivot.created_at);

      const time_to_r = roundNum(time1.diff(time0, 'h', true));
      total_tr_high_priority += time_to_r;
      num_of_tickets_tr_high_priority += 1;
    }
    avg_tr_high_priority =
      total_tr_high_priority / num_of_tickets_tr_high_priority;
  }
  console.log(avg_tfr_low_priority);

  return {
    'Low - TFR': roundNum(avg_tfr_low_priority),
    'Low - TR': roundNum(avg_tr_low_priority),
    'Medium - TFR': roundNum(avg_tfr_medium_priority),
    'Medium - TR': roundNum(avg_tr_medium_priority),
    'High - TFR': roundNum(avg_tfr_high_priority),
    'High - TR': roundNum(avg_tr_high_priority),
  };
});

watch(users, () => {
  options.value = users.value.map((user) => user.id);
});
</script>

<template>
  <div class="max-w-full mx-auto p-8 h-full overflow-auto">
    <div class="flex flex-col gap-8 max-w-6xl mx-auto">
      <h1 class="text-red-700 text-3xl font-bold">
        Data Dashboard
        <font-awesome-icon icon="fa-spinner" v-if="isLoading" class="fa-spin" />
      </h1>
      <Vueform ref="formRef" :endpoint="false">
        <SelectElement
          name="date_range"
          label="Filter by Date:"
          :native="false"
          :items="[
            { value: 'hour', label: 'Last 24 hours' },
            { value: 'day-7', label: 'Last 7 days' },
            { value: 'day-30', label: 'Last 30 days' },
            { value: 'month', label: 'Last year' },
          ]"
          @select="selectDateFilter"
          default="day-7"
          :can-clear="false"
          :can-deselect="false"
        />
        <RadiogroupElement
          name="radiogroup"
          :items="['Select all assignees', 'Specify assignees to filter']"
          @change="selectAssigneeRadio"
          default="Select all assignees"
          class="col-span-3"
        />
        <MultiselectElement
          name="assignee"
          label="Filter by Assignee:"
          :native="false"
          :items="selectUserOptions"
          :close-on-select="false"
          :hide-selected="false"
          @select="selectAssigneeFilter"
          @deselect="deselectAssigneeFilter"
          :can-clear="true"
          @clear="deselectAllAssigneeFilter"
          :search="true"
          v-if="assigneeFilter == 'Specify assignees to filter'"
        />
      </Vueform>
      <div class="flex gap-8 w-full">
        <div
          class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50 basis-1/2"
        >
          <h2 class="text-red-700 text-lg font-bold">Tickets by Date</h2>
          <div>
            <TicketByDateBarChart :data="date_chart_result" />
          </div>
        </div>
        <div
          class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50 basis-1/2"
        >
          <h2 class="text-red-700 text-lg font-bold">Tickets by Status</h2>
          <div>
            <TicketByStatusBarChart :data="status_chart_result" />
          </div>
        </div>
      </div>
      <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
        <h2 class="text-red-700 text-lg font-bold">Average SLA By Priority</h2>
        <div>
          <AverageSLABarChart :data="sla_chart_result" />
        </div>
      </div>
    </div>
  </div>
</template>
