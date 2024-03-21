<script setup>
import TicketByDateBarChart from '@/Components/TicketByDateBarChart.vue';
import TicketByStatusBarChart from '@/Components/TicketByStatusBarChart.vue';
import AverageSLABarChart from '@/Components/AverageSLABarChart.vue';

import { useTicketStore } from '@/Stores/TicketStore';
import { storeToRefs } from 'pinia';
import { onMounted, ref, watch, computed } from 'vue';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
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

const selectUserOptions = computed(() => {
  if (!users.value) return [];
  return users.value.map((user) => {
    return {
      value: user.id,
      label: user.full_name,
    };
  });
});

onMounted(() => {
  getTickets();
  getUsers();
});

// Get date range option
const format_string = ref('MMM DD');
const date_diff = ref(7);

function selectDateFilter(option) {
  if (option == 'hour') {
    format_string.value = 'MMM DD: HH:00';
    date_diff.value = 1;
  } else if (option == 'day-7') {
    format_string.value = 'MMM DD';
    date_diff.value = 7;
  } else if (option == 'day-30') {
    format_string.value = 'MMM DD';
    date_diff.value = 30;
  } else if (option == 'month') {
    format_string.value = 'MMM';
    date_diff.value = 365;
  }
}

// Get assignee option
const options = ref([]);
const assigneeFilter = ref('');

// If get data for all assignees
function selectAssigneeRadio(newValue) {
  assigneeFilter.value = newValue;
  if (newValue === 'Select all assignees') {
    options.value = [];
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

// Get data for TicketByDateBarChart
const date_chart_result = computed(() => {
  const tickets_filtered_by_date = _.filter(tickets.value, function (t) {
    return dayjs(t.created_at).isBetween(
      dayjs(),
      dayjs().subtract(date_diff.value, 'day'),
    );
  });

  const tickets_filtered_by_date_and_assignee = _.filter(
    tickets_filtered_by_date,
    function (t) {
      return options.value.includes(t.assigned_tutor_id);
    },
  );

  return _(tickets_filtered_by_date_and_assignee)
    .groupBy((ticket) => dayjs(ticket.created_at).format(format_string.value))
    .mapValues((ticket) => ticket.length)
    .value();
});

// Get data for TicketByStatusBarChart
const status_chart_result = computed(() => {
  const tickets_filtered_by_date = _.filter(tickets.value, function (t) {
    return dayjs(t.created_at).isBetween(
      dayjs(),
      dayjs().subtract(date_diff.value, 'day'),
    );
  });

  const tickets_filtered_by_date_and_assignee = _.filter(
    tickets_filtered_by_date,
    function (t) {
      return options.value.includes(t.assigned_tutor_id);
    },
  );

  return _(tickets_filtered_by_date_and_assignee)
    .groupBy((ticket) => ticket.latest_status.name)
    .mapValues((ticket) => ticket.length)
    .value();
});

// Get data for AverageSLABarChart
const sla_chart_result = computed(() => {
  const tickets_filtered_by_date = _.filter(tickets.value, function (t) {
    return dayjs(t.created_at).isBetween(
      dayjs(),
      dayjs().subtract(date_diff.value, 'day'),
    );
  });

  const tickets_filtered_by_date_and_assignee = _.filter(
    tickets_filtered_by_date,
    function (t) {
      return options.value.includes(t.assigned_tutor_id);
    },
  );

  const tickets_filtered_by_date_and_assignee_and_excl_statuses = _.filter(
    tickets_filtered_by_date_and_assignee,
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
  let avg_tfr_low_priority = 0;
  tickets_filtered_by_date_and_assignee_and_excl_statuses.forEach(
    calculate_tfr_low_priority,
  );

  function calculate_tfr_low_priority(ticket) {
    if (
      ticket.ticket_statuses.find((s) => s.id === 1) &&
      ticket.ticket_statuses.find((s) => s.id === 2) &&
      ticket.priority_id === 1
    ) {
      const time0 = dayjs(ticket.ticket_statuses[0].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[1].pivot.created_at);

      const time_to_fr = time1.diff(time0, 'h');
      total_tfr_low_priority += time_to_fr;
    }
    avg_tfr_low_priority =
      total_tfr_low_priority /
      tickets_filtered_by_date_and_assignee_and_excl_statuses.length;
  }

  let total_tr_low_priority = 0;
  let avg_tr_low_priority = 0;
  tickets_filtered_by_date_and_assignee_and_excl_statuses.forEach(
    calculate_tr_low_priority,
  );

  function calculate_tr_low_priority(ticket) {
    if (
      ticket.ticket_statuses.find((s) => s.id === 3) &&
      ticket.ticket_statuses.find((s) => s.id === 4) &&
      ticket.priority_id === 1
    ) {
      const time0 = dayjs(ticket.ticket_statuses[2].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[3].pivot.created_at);

      const time_to_r = time1.diff(time0, 'h');
      total_tr_low_priority += time_to_r;
    }
    avg_tr_low_priority =
      total_tr_low_priority /
      tickets_filtered_by_date_and_assignee_and_excl_statuses.length;
  }

  // Calculate TFR & TR for Medium priority tickets
  let total_tfr_medium_priority = 0;
  let avg_tfr_medium_priority = 0;
  tickets_filtered_by_date_and_assignee_and_excl_statuses.forEach(
    calculate_tfr_medium_priority,
  );

  function calculate_tfr_medium_priority(ticket) {
    if (
      ticket.ticket_statuses.find((s) => s.id === 1) &&
      ticket.ticket_statuses.find((s) => s.id === 2) &&
      ticket.priority_id === 2
    ) {
      const time0 = dayjs(ticket.ticket_statuses[0].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[1].pivot.created_at);

      const time_to_fr = time1.diff(time0, 'h');
      total_tfr_medium_priority += time_to_fr;
    }
    avg_tfr_medium_priority =
      total_tfr_medium_priority /
      tickets_filtered_by_date_and_assignee_and_excl_statuses.length;
  }

  let total_tr_medium_priority = 0;
  let avg_tr_medium_priority = 0;
  tickets_filtered_by_date_and_assignee_and_excl_statuses.forEach(
    calculate_tr_medium_priority,
  );

  function calculate_tr_medium_priority(ticket) {
    if (
      ticket.ticket_statuses.find((s) => s.id === 3) &&
      ticket.ticket_statuses.find((s) => s.id === 4) &&
      ticket.priority_id === 2
    ) {
      const time0 = dayjs(ticket.ticket_statuses[2].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[3].pivot.created_at);

      const time_to_r = time1.diff(time0, 'h');
      total_tr_medium_priority += time_to_r;
    }
    avg_tr_medium_priority =
      total_tr_medium_priority /
      tickets_filtered_by_date_and_assignee_and_excl_statuses.length;
  }

  // Calculate TFR & TR for High priority tickets
  let total_tfr_high_priority = 0;
  let avg_tfr_high_priority = 0;
  tickets_filtered_by_date_and_assignee_and_excl_statuses.forEach(
    calculate_tfr_high_priority,
  );

  function calculate_tfr_high_priority(ticket) {
    if (
      ticket.ticket_statuses.find((s) => s.id === 1) &&
      ticket.ticket_statuses.find((s) => s.id === 2) &&
      ticket.priority_id === 3
    ) {
      const time0 = dayjs(ticket.ticket_statuses[0].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[1].pivot.created_at);

      const time_to_fr = time1.diff(time0, 'h');
      total_tfr_high_priority += time_to_fr;
    }
    avg_tfr_high_priority =
      total_tfr_high_priority /
      tickets_filtered_by_date_and_assignee_and_excl_statuses.length;
  }

  let total_tr_high_priority = 0;
  let avg_tr_high_priority = 0;
  tickets_filtered_by_date_and_assignee_and_excl_statuses.forEach(
    calculate_tr_high_priority,
  );

  function calculate_tr_high_priority(ticket) {
    if (
      ticket.ticket_statuses.find((s) => s.id === 3) &&
      ticket.ticket_statuses.find((s) => s.id === 4) &&
      ticket.priority_id === 3
    ) {
      const time0 = dayjs(ticket.ticket_statuses[2].pivot.created_at);
      const time1 = dayjs(ticket.ticket_statuses[3].pivot.created_at);

      const time_to_r = time1.diff(time0, 'h');
      total_tr_high_priority += time_to_r;
    }
    avg_tr_high_priority =
      total_tr_high_priority /
      tickets_filtered_by_date_and_assignee_and_excl_statuses.length;
  }

  return {
    'Low - TFR': avg_tfr_low_priority,
    'Low - TR': avg_tr_low_priority,
    'Medium - TFR': avg_tfr_medium_priority,
    'Medium - TR': avg_tr_medium_priority,
    'High - TFR': avg_tfr_high_priority,
    'High - TR': avg_tr_high_priority,
  };
});
</script>

<template>
  <div class="max-w-6xl mx-auto p-8 flex flex-col gap-8 h-full overflow-auto">
    <h1 class="text-amber-800 text-3xl font-bold">Data Dashboard</h1>
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
        class="col-span-6"
        @select="selectDateFilter"
        default="day-7"
        :can-clear="false"
        :can-deselect="false"
      />
      <RadiogroupElement
        name="radiogroup"
        :items="['Select all assignees', 'Specify assignees to filter']"
        @change="selectAssigneeRadio"
      />

      <MultiselectElement
        name="assignee"
        label="Filter by Assignee:"
        :native="false"
        :items="selectUserOptions"
        class="col-span-6"
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
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <h2 class="text-amber-800 text-lg font-bold">Tickets by Date</h2>
      <div>
        <TicketByDateBarChart :data="date_chart_result" />
      </div>
    </div>

    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <h2 class="text-amber-800 text-lg font-bold">Tickets by Status</h2>
      <div>
        <TicketByStatusBarChart :data="status_chart_result" />
      </div>
    </div>

    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <h2 class="text-amber-800 text-lg font-bold">Average SLA By Priority</h2>
      <div>
        <AverageSLABarChart :data="sla_chart_result" />
      </div>
    </div>
  </div>
</template>

// define components // props // ref, computed, watch // binding // hooks:
onMounted
