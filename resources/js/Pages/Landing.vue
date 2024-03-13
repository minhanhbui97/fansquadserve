<script setup></script>

<template>
  <div
    class="flex-grow bg-blue-gray items-center justify-center flex flex-col shadow h-full"
  >
    <div
      class="bg-white border rounded-lg flex flex-col justify-center items-center gap-32 p-8"
    >
      <div>Logo</div>
      <div class="flex justify-around w-full gap-32">
        <div class="flex flex-col gap-4">
          <h2 class="font-bold text-lg">For students:</h2>
          <div class="flex flex-col gap-4">
            <router-link class="btn w-48" to="/service-request">Service Request</router-link>
          </div>
        </div>
        <div class="flex flex-col gap-4">
          <h2 class="font-bold text-lg">For LabSquad:</h2>
          <router-link class="btn w-48" to="/login">Login</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

