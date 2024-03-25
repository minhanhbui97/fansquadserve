<script setup>
import { useUserStore } from '@/Stores/UserStore';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import dayjs from 'dayjs';
import { useRouter } from 'vue-router';

const router = useRouter();
const userStore = useUserStore();
const { getUsers } = userStore;
const { users } = storeToRefs(userStore);

onMounted(() => {
  getUsers();
});

async function submit(id) {
  console.log(id);
  router.push({ name: 'ticket-details', params: { id } });
}
</script>

<template>
  <div class="max-w-6xl mx-auto p-8 flex flex-col gap-8 justify-center">
    <h1 class="text-amber-800 text-3xl font-bold">User Details</h1>

    <router-link to="/add-user">
      <button class="bg-red-700 py-2 px-4 text-white rounded w-40">
        Add User
      </button>
    </router-link>

    <div class="flex-grow">
      <DataTable
        class="text-[14px]"
        :value="users"
        size="small"
        scrollable
        scrollHeight="500px"
        paginator
        :rows="20"
        stripedRows
        showGridlines
      >
        <Column
          field="id"
          header="User ID"
          sortable
          style="width: 100px"
        ></Column>

        <Column
          field="first_name"
          header="First Name"
          sortable
          style="width: 100px"
        ></Column>
        <Column
          field="last_name"
          header="Last Name"
          sortable
          style="width: 130px"
        ></Column>

        <Column field="roles" sortable header="Role">
          <template #body="slotProps">
            <!--{{ slotProps.data.roles[0].name }}-->
          </template>
        </Column>

        <Column
          field="email"
          header="Email"
          sortable
          style="width: 100px"
        ></Column>

        <!--<Column field="description" header="Description" style="width: 200px">
          <template #body="slotProps">
            <div class="singleLine">
              {{ slotProps.data.description }}
            </div>
          </template>
        </Column>-->

        <Column field="last_used_at" sortable header="Last LogIn">
          <template #body="slotProps">{{
            dayjs(slotProps.data.last_used_at).format('MMM DD, YYYY HH:mm:ss')
          }}</template>
        </Column>
        <Column header="Action">
          <template #body="slotProps">
            <router-link to="/user-details">
              <button
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
              >
                View Details
              </button></router-link
            >
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
