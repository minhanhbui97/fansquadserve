import { getAllTickets } from '@/Services/TicketService';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useTicketStore = defineStore('ticket', () => {
  const tickets = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  async function getTickets() {
    isLoading.value = true;
    const data = await getAllTickets();
    tickets.value = data;
  }

  return { tickets, isLoading, error, getTickets };
});
