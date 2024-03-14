
<script setup> 
import { computed } from 'vue'
import { useTicketStore } from '@/Stores/TicketStore' 

const { getPriorities } = useTicketStore;
const { priorities } = storeToRefs(useTicketStore);
const { getOperatingSystem } = useTicketStore;
const { operating_systems } = storeToRefs(useTicketStore);
const { getProgram } = useTicketStore;
const { programs } = storeToRefs(useTicketStore);
const { getTypeofMachines } = useTicketStore;
const { type_of_machines } = storeToRefs(useTicketStore);
const { getCourse } = useTicketStore;
const { courses } = storeToRefs(useTicketStore);


const selectPriorityOptions = computed(() => {
  return priorities.value.map((priority) => {
    return {
      value: priority.id,
      label: priority.name,
    };
  });
});

const selectOperatingSystemOptions = computed(() => {
    return operating_systems.value.map((operatingsystem) =>{
       return {
        value: operatingsystem.id,
        label: operatingsystem.name,
    };
    });
});

const selectProgramOptions = computed(() => {
    return programs.value.map((program) =>{
       return {
        value: program.id,
        label: program.name,
    };
    });
});

const selectTypeofMachineOptions = computed(() => {
    return type_of_machines.value.map((typeofmachine) =>{
       return {
        value: typeofmachine.id,
        label: typeofmachine.name,
    };
    });
});

const selectCourseOptions = computed(() => {
    return courses.value.map((course) =>{
       return {
        value: course.id,
        label: course.name,
    };
    });
});

onMounted(() => {
  getPriorities();
  getOperatingSystem();
  getProgram();
  getTypeofMachines();
  getCourse();

  
});



</script>
<template>
  <Vueform v-model="formData">
    <TextElement
      name="fanshawe_id"
      label="Student-Id"
      input-type="text"
    />
    <TextElement
      name="email"
      label="Student-Email"
      input-type="email"
    />
    <TextElement
      name="first_name"
      label="FirstName"
      input-type="text"
    />
    <TextElement
      name="last_name"
      label="LastName"
      input-type="text"
    />
    <TextElement
      name="program_level"
      label="Program Level*"
      input-type="text"
    />
    <SelectElement
      name="program"
      label="Program"
      :native="false"
      :items="selectProgramOptions"
    />
    <SelectElement
      name="type_of_machines"
      label="Type Of Machine"
      :native="false"
      :items="selectTypeofMachineOptions"
    />
    <SelectElement
      name="operating_system"
      label="Operating System"
      :native="false"
      :items="selectOperatingSystemOptions"
    />
    <SelectElement
      name="priority"
      label="Priority"
      :native="false"
      :items="selectPriorityOptions"
    />
    <TextElement
      name="description"
      label="Description"
      input-type="message"
    />
    <SelectElement
      name="courses"
      label="Course"
      :native="false"
      :items="selectCourseOptions"
    />
    <SelectElement
      name="assigned_tutor"
      label="Tutor"
      :native="false"
      :items="[
        'Marc',
          ]"/>
     <DateElement name="schedule_start_date" label="Start Time" /> 
     <DateElement name="schedule_end_date" label="End Time" />
    <ButtonElement name="button"> Confirm Appointment </ButtonElement>
          </Vueform>
          </template>