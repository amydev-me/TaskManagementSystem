<template>
    <nav class="bg-blue-500 p-4 flex justify-between items-center">
        <h1 class="text-white text-lg font-bold">Task Manager</h1>
        <div class="flex items-center">
            <span class="text-white mr-4">{{ userEmail }}</span>
            <button @click="logout" class="bg-red-500 text-white px-4 py-2 rounded">
                Logout
            </button>
        </div>
    </nav>
</template>
  
<script>
import api from "../axios.js";
export default { 
    computed: {
        userEmail() {
            const { name, email } = JSON.parse(localStorage.getItem("user"));
            return `${name} | ${email}`
        },
    },
    methods: {
        async logout() {
            await api.post('/logout');
            localStorage.removeItem("user");
            localStorage.removeItem("token");
            localStorage.removeItem("email");
            this.$router.push("/login"); // Redirect to login page
        },
    }
  };
  </script>
  