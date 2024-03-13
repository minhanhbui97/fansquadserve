<script setup>
import { useTicketStore } from '@/Stores/TicketStore';
import { storeToRefs } from 'pinia';
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
// const router = useRouter();
const ticketStore = useTicketStore();
const { getPriorities } = ticketStore;
const { priorities } = storeToRefs(ticketStore);

const selectOptions = computed(() => {
  return priorities.value.map((priority) => {
    return {
      value: priority.id,
      label: priority.name,
    };
  });
});

onMounted(() => {
  getPriorities();
});
</script>

<template>
  <h1>This is Ticket Details page</h1>
  <h1>{{ priorities }}</h1>

  <Vueform>
    <!-- // https://vueform.com/reference/text-element
    <TextElement name="“email”" label rules /> -->

    <!-- // https://vueform.com/reference/select-element // get id & name from api // -->
    <!-- value = id, label = name -->
    <SelectElement
      :native="false"
      name="“courseId”"
      :items="selectOptions"
    />

    <!-- // https://vueform.com/reference/date-element#option-time
    <DateElement :date="true" :time="true" ... name />

    <ButtonElement name="submit" submits> Submit </ButtonElement> -->
  </Vueform>
</template>
