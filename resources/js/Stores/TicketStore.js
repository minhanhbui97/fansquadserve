import { getAllTickets, getAllPriorities } from '@/Services/TicketService';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useTicketStore = defineStore('ticket', () => {
  const tickets = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const priorities = ref([]);


  async function getTickets() {
    isLoading.value = true;
    const data = await getAllTickets();
    tickets.value = data;
  }


  async function getPriorities() {
    isLoading.value = true;
    const data = await getAllPriorities();
    priorities.value = data;
  }

  return { tickets, priorities, isLoading, error, getTickets, getPriorities };
});
