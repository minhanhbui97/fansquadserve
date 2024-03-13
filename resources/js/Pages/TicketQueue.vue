<script setup>
import { useTicketStore } from '@/Stores/TicketStore';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import dayjs from 'dayjs';
import { useRouter } from 'vue-router';

const router = useRouter();
const ticketStore = useTicketStore();
const { getTickets } = ticketStore;
const { tickets } = storeToRefs(ticketStore);

onMounted(() => {
  getTickets();
});


async function submit(id) {
  console.log(id)
  router.push({ name: 'ticket-details', params: { id }})
}
</script>

<template>
  <div class="max-w-6xl mx-auto p-8 flex flex-col gap-8 justify-center">
    <h1 class="text-amber-800 text-xl font-bold">Ticket Queue</h1>
    <div class="flex-grow">
      <DataTable
        class="text-[14px]"
        :value="tickets"
        size="small"
        scrollable
        scrollHeight="flex"
        paginator
        :rows="20"
        stripedRows
        showGridlines
      >
        <Column
          field="id"
          header="Ticket ID"
          sortable
          style="width: 100px"
        ></Column>
        <Column header="Request Type" sortable style="width: 100px">
          <template #body="slotProps">
            <div class="singleLine">
              {{
                slotProps.data.course.id === 1
                  ? 'Tech/Lab Support'
                  : 'Peer Tutoring'
              }}
            </div>
          </template>
        </Column>
        <Column
          field="priority.name"
          header="Priority"
          sortable
          style="width: 100px"
        ></Column>
        <Column
          field="tutor.full_name"
          header="Assignee"
          sortable
          style="width: 130px"
        ></Column>

        <Column field="description" header="Description" style="width: 200px">
          <template #body="slotProps">
            <div class="singleLine">
              {{ slotProps.data.description }}
            </div>
          </template>
        </Column>
        <Column
          field="latest_status.name"
          header="Status"
          sortable
          style="width: 130px"
        ></Column>
        <Column field="created_at" sortable header="Created At">
          <template #body="slotProps">{{
            dayjs(slotProps.data.created_at).format('MMM DD, YYYY HH:mm:ss')
          }}</template>
        </Column>
        <Column header="Action">
          <template #body="slotProps">
            <button @click="() => submit(slotProps.data.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View Details</button>
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>

<style scoped lang="postcss">
.singleLine {
  width: 200px;
  text-wrap: none;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
