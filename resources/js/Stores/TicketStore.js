import {
  getAllTickets,
  getAllPriorities,
  getAllTicketStatuses,
  getUsers as getAllUsers,
  getTicketById,
  updateTicket,
  getAllOperatingSystems,
  getAllPrograms,
  getAllTypeofMachines,
  getAllCourses,
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
  const operating_systems = ref([]);
  const programs = ref([]);
  const type_of_machines = ref([]);
  const courses = ref([]);

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

  async function getOperatingSystems() {
    isLoading.value = true;
    const data = await getAllOperatingSystems();
    operating_systems.value = data;
  }

  async function getPrograms() {
    isLoading.value = true;
    const data = await getAllPrograms();
    programs.value = data;
  }

  async function getTypeofMachines() {
    isLoading.value = true;
    const data = await getAllTypeofMachines();
    type_of_machines.value = data;
  }

  async function getCourses() {
    isLoading.value = true;
    const data = await getAllCourses();
    courses.value = data;
  }

  return {
    tickets,
    priorities,
    operating_systems,
    programs,
    type_of_machines,
    courses,
    isLoading,
    error,
    currentTicket,
    ticket_statuses,
    users,
    getTickets,
    getPriorities,
    getOperatingSystems,
    getPrograms,
    getTypeofMachines,
    getCourses,
    getCurrentTicket,
    getTicketStatuses,
    getUsers,
    updateCurrentTicket,
  };
});
