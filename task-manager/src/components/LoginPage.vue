<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold text-center mb-4">Login</h2>

            <form @submit.prevent="login">
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        v-model="email"
                        type="email"
                        placeholder="Enter your email"
                        class="w-full p-2 border rounded"
                        required
                    />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        v-model="password"
                        type="password"
                        placeholder="Enter your password"
                        class="w-full p-2 border rounded"
                        required
                    />
                </div>

                <!-- Error Message -->
                <p v-if="error" class="text-red-500 text-sm mb-4">{{ error }}</p>

                <!-- Login Button -->
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                    Login
                </button>
            </form>
        </div>
    </div> 
</template>
<script>
import api from '../axios.js'
    export default {
        data() {
            return {
                email: "amy10@gmail.com",
                password: "Angelo12345",
                error: "",
            };
        },
        methods: {
            async login() {
                const {data } = await api.post("http://localhost:8000/api/login", {
                    email: this.email,
                    password: this.password
                });

                const token = data.authorisation.token;

                if (token) {
                    localStorage.setItem("token", token);
                    localStorage.setItem("user", JSON.stringify(data.user));
                    this.$router.push("/"); 
                }
            }
        }
    };
  </script>
  