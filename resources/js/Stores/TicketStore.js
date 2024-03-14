import { getAllTickets, getAllPriorities, getAllOperatingSystem, getAllProgram, getAllTypeofMachines,getAllCourses } from '@/Services/TicketService';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useTicketStore = defineStore('ticket', () => {
  const tickets = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const priorities = ref([]);
  const operating_system = ref([]);
  const programs = ref([]);
  const type_of_machines = ref([]);
  const courses = ref([]);


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

  async function getOperatingSystem() {
    isLoading.value = true;
    const data = await getAllOperatingSystem();
    operating_system.value = data;
  }

  async function getAllProgram() {
    isLoading.value = true;
    const data = await getAllProgram();
    programs.value = data;
  }

  async function getAllTypeofMachines() {
    isLoading.value = true;
    const data = await getAllTypeofMachines();
    type_of_machines.value = data;
  }
  async function getAllCourses() {
    isLoading.value = true;
    const data = await getAllCourses();
    courses.value = data;
  }

  return { tickets, priorities,operating_system,programs,type_of_machines,courses, isLoading, error, getTickets, getPriorities, getOperatingSystem, getAllProgram,getAllTypeofMachines,getAllCourses };
});
