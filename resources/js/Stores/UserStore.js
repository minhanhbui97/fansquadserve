import {
    getAllUsers,
    getAllCourses,
    getAllRoles,
    getUserById,
    updateUser,
    



} from '@/Services/UserService';
import { defineStore } from 'pinia';
import { ref } from 'vue';



  export const useUserStore = defineStore('user', () => {
    const isLoading = ref(false);
    const error = ref(null);
  const currentUser = ref(null);

    const users = ref([]);
    const courses = ref([]);
    const roles = ref([]);
  
    async function getUsers() {
      isLoading.value = true;
      const data = await getAllUsers();
      users.value = data;
      isLoading.value = false;
    }
  
    //getcurrentuser
   async function getCurrentUser(id) {
      isLoading.value = true;
      const data = await getUserById(id);
      currentUser.value = data;
      isLoading.value = false;
    }


    async function updateCurrentUser(userId, payload) {
      isLoading.value = true;
      const data = await updateUser(userId, payload);
      currentUser.value = data;
      isLoading.value = false;
    }

     async function getRoles() {
      isLoading.value = true;
      const data = await getAllRoles();
      roles.value = data;
    }
  
    
  
    async function getCourses() {
      isLoading.value = true;
      const data = await getAllCourses();
      courses.value = data;
    }
  
    return {
      users,
      courses,
      roles,
      isLoading,
     currentUser,

      error,
      getUsers,
      getRoles,
      getCourses,
       getCurrentUser,
     updateCurrentUser,


      
    };
  });
  