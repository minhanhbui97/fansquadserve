<script setup>
import { useUserStore } from '@/Stores/UserStore';
import { storeToRefs } from 'pinia';
import { onMounted, ref, computed } from 'vue';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import { useRouter } from 'vue-router';
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import InputText from 'primevue/inputtext';

const router = useRouter();
const userStore = useUserStore();
const { getUsers } = userStore;
const { users } = storeToRefs(userStore);

const isLoading = ref(false);

async function initialize() {
  isLoading.value = true;
  await getUsers();
  await initFilters();
  isLoading.value = false;
}

onMounted(() => {
  initialize();
});

async function submit(id) {
  router.push({ name: 'user-details', params: { id } });
}

// Add filter for id, first_name, and last_name columns
const filters = ref();

const initFilters = () => {
  filters.value = {
    id: {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
    },
    first_name: {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
    },

    last_name: {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
    },
  };
};
</script>

<template>
  <div class="max-w-6xl mx-auto p-8 flex flex-col gap-8 justify-center">
    <h1 class="text-red-700 text-3xl font-bold">
      List of Users
      <font-awesome-icon icon="fa-spinner" v-if="isLoading" class="fa-spin" />
    </h1>

    <router-link
      class="btn w-48 bg-red-700 hover:bg-red-700 text-white text-md font-bold"
      to="/add-user"
    >
      <button class="">Add User</button>
    </router-link>

    <div class="flex-grow">
      <DataTable
        class="text-[14px]"
        :value="users"
        size="small"
        scrollable
        scrollHeight="500px"
        paginator
        :rows="10"
        stripedRows
        showGridlines
        v-model:filters="filters"
        filterDisplay="menu"
        sortField="id"
        :sortOrder="-1"
      >
        <template #empty> No users found.</template>

        <Column field="id" header="User ID" sortable style="width: 100px">
          <template #filter="{ filterModel }">
            <InputText
              v-model="filterModel.value"
              type="text"
              class="p-column-filter"
              placeholder="Search by ID"
            />
          </template>
        </Column>
        <Column
          field="first_name"
          header="First Name"
          sortable
          style="width: 100px"
        >
          <template #filter="{ filterModel }">
            <InputText
              v-model="filterModel.value"
              type="text"
              class="p-column-filter"
              placeholder="Search by First Name"
            />
          </template>
        </Column>
        <Column
          field="last_name"
          header="Last Name"
          sortable
          style="width: 130px"
        >
          <template #filter="{ filterModel }">
            <InputText
              v-model="filterModel.value"
              type="text"
              class="p-column-filter"
              placeholder="Search by Last Name"
            />
          </template>
        </Column>
        <Column field="roles" header="Role">
          <template #body="slotProps">
            <template v-for="role in slotProps?.data.roles">
              <ul>
                {{
                  role.name
                }}
              </ul></template
            >
          </template>
        </Column>
        <Column
          field="email"
          header="Email"
          sortable
          style="width: 100px"
        ></Column>
        <Column field="is_active" header="Status" style="width: 100px" sortable>
          <template #body="slotProps">
            <div class="singleLine">
              {{ slotProps.data.is_active === true ? 'Active' : 'Inactive' }}
            </div>
          </template>
        </Column>
        <Column header="Action">
          <template #body="slotProps">
            <button
              @click="() => submit(slotProps.data.id)"
              class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
            >
              View Details
            </button>
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
