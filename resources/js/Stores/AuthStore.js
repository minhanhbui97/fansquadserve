import { fetchAuthUser } from '@/Services/AuthService';
import { useStorage } from '@vueuse/core';
import { defineStore } from 'pinia';
import {
  login as authServiceLogin,
  logout as authServiceLogout,
} from '@/Services/AuthService';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const isLoggedIn = useStorage('isLoggedIn', false);
  const isLoading = ref(false);
  const error = ref(null);

  async function getAuthUser() {
    isLoading.value = true;

    try {
      const authUser = await fetchAuthUser();
      user.value = authUser;
      isLoggedIn.value = true;
    } catch (err) {
      error.value = err.response.data.message;
    } finally {
      isLoading.value = false;
    }
  }

  async function login({ email, password }) {
    isLoading.value = true;
    await authServiceLogin({ email, password });
    await getAuthUser();
    isLoading.value = false;
  }

  async function logout() {
    isLoading.value = true;
    await authServiceLogout();
    reset();
    isLoading.value = false;
  }

  function reset() {
    user.value = null;
    isLoggedIn.value = false;
  }

  return { user, error, isLoggedIn, isLoading, getAuthUser, login, reset, logout };
});
