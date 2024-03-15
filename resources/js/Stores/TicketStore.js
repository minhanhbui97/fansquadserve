import {
  getAllTickets,
  getAllPriorities,
  getAllTicketStatuses,
  getUsers as getAllUsers,
  getTicketById,
  updateTicket,
} from '@/Services/TicketService';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useTicketStore = defineStore('ticket', () => {
  const isLoading = ref(false);
  const error = ref(null);

  const tickets = ref([]);
  const currentTicket = ref(null);
  const priorities = ref([]);
  const users = ref([]);
  const ticket_statuses = ref([]);

  async function getTickets() {
    isLoading.value = true;
    const data = await getAllTickets();
    tickets.value = data;
    isLoading.value = false;
  }

  async function getCurrentTicket(id) {
    isLoading.value = true;
    const data = await getTicketById(id);
    currentTicket.value = data;
    isLoading.value = false;
  }

  async function getTicketStatuses() {
    isLoading.value = true;
    const data = await getAllTicketStatuses();
    ticket_statuses.value = data;
  }

  async function getPriorities() {
    isLoading.value = true;
    const data = await getAllPriorities();
    priorities.value = data;
  }

  // Need to get the users available for the course
  // Need to get the user assigned for the ticket
  async function getUsers(courseId) {
    isLoading.value = true;
    const data = await getAllUsers(courseId);
    users.value = data;
    isLoading.value = false;
  }

  async function updateCurrentTicket(ticketId, payload) {
    isLoading.value = true;
    const data = await updateTicket(ticketId, payload);
    currentTicket.value = data;
    isLoading.value = false;
  }

  return {
    currentTicket,
    tickets,
    priorities,
    ticket_statuses,
    users,
    isLoading,
    error,
    getTickets,
    getCurrentTicket,
    getPriorities,
    getTicketStatuses,
    getUsers,
    updateCurrentTicket,
  };
});
