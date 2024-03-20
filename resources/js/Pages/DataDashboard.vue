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

// const router = useRouter();
const ticketStore = useTicketStore();
const { getTickets, getUsers } = ticketStore;
const { tickets, users } = storeToRefs(ticketStore);

const selectUserOptions = computed(() => {
  if (!users.value) return [];
  // console.log(users.value);
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

//ref
const format_string = ref('MMM DD');
const date_diff = ref(7);

function selectDateFilter(option) {
  // console.log(option);
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
const options = ref([]);

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

//computed
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

  // console.log(tickets_filtered_by_date_and_assignee);

  return _(tickets_filtered_by_date_and_assignee)
    .groupBy((ticket) => dayjs(ticket.created_at).format(format_string.value))
    .mapValues((ticket) => ticket.length)
    .value();
});

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
  console.log(tickets_filtered_by_date_and_assignee);

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
  console.log(tickets_filtered_by_date_and_assignee_and_excl_statuses);
  // tickets_filtered_by_date_and_assignee.forEach(calculate_tfr_low_priority);
  // const total_tfr_low_priority = 0;
  // function calculate_tfr_low_priority(ticket) {
  //   if(ticket.priority_id === 1){
  //     if(ticket.ticketStatuses.includes(5) || ticket.ticketStatuses.includes(6)){
  //       const no_of_ticket =
  //       time_to_fr = ticket.ticketStatuses[1].created_at - ticket.ticketStatuses[0].created_at
  //       total_tfr_low_priority += time_to_fr;
  //     }
  //   }
  // const avg_tfr_low_priority = total_tfr_low_priority / 1;
  //
  // }

  // return _(tickets_filtered_by_date_and_assignee)
  //   .groupBy((ticket) => ticket.latest_status.name)
  //   .mapValues((ticket) => ticket.length)
  //   .value();
  return;
});
</script>

<template>
  <div class="max-w-6xl mx-auto p-8 flex flex-col gap-8 justify-center">
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
        :search="true"
        :default="users?.value"
        :can-clear="true"
        @clear="deselectAllAssigneeFilter"
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
