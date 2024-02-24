<script setup></script>

<template>
  <div
    class="flex-grow bg-blue-gray items-center justify-center flex flex-col shadow"
  >
    <div
      class="w-[500px] h-[500px] bg-white border max-w-xl rounded-lg flex flex-col justify-center items-center gap-32"
    >
      <div>Logo</div>
      <div class="flex justify-around w-full">
        <router-link class="btn w-32" to="/student">Student</router-link>
        <router-link class="btn w-32" to="/about">LabSquad</router-link>
      </div>
    </div>
  </div>
</template>
