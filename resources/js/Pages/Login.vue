<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/Stores/AuthStore';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const email = ref('');
const password = ref('');
const toast = useToast();


const router = useRouter();
const authStore = useAuthStore();
const { login, error, clearError } = authStore;

async function submit(values) {
  clearError();
  try {
    await login({ email: values.data.email, password: values.data.password });
    router.push({ name: 'tickets' });
  } catch {
    toast.error('Unable to login. Please use your valid email address and password!');
  }
}
</script>

<template>
  <div
    class="h-screen flex flex-col gap-8 justify-center items-center max-w-xl mx-auto"
  >
    <div class="w-64 self-center">
      <img src="../../images/logo.png" alt="" />
    </div>
    <div class="flex flex-col gap-2">
      <h1 class="text-xl text-red-700 font-bold">LabSquad Log In</h1>
      <div class="p-6 bg-gray-100 border-gray-800 border w-96">
        <Vueform
          :columns="{ container: 12, wrapper: 12 }"
          :display-errors="false"
          :endpoint="false"
          @submit="submit"
        >
          <TextElement
            name="email"
            label="Email"
            :rules="['required', 'email', 'max:255']"
            field-name="email"
          />
          <TextElement
            name="password"
            label="Password"
            input-type="password"
            :rules="['required']"
          />
          <ButtonElement name="button" danger submits>Log In</ButtonElement>
        </Vueform>
      </div>
    </div>
  </div>
</template>
