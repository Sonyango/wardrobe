<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { login } from '../services/auth';
import GuestLayout from '../components/GuestLayout.vue';

const router = useRouter();
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const error = ref('');

async function handleSubmit(e) {
    e.preventDefault();
    try {
        const response = await login(email.value, password.value);
        authStore.setUser(response.data.user);
        router.push({ name: 'dashboard' });
    } catch (err) {
        error.value = err.response?.data?.message || 'Login failed.';
    }
}
</script>

<template>
    <GuestLayout>
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign in to your account</h2>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-3" @submit="handleSubmit">

                    <div class="bg-red-50 text-red-500 p-2 rounded text-sm mb-4">
                        {{ error }}
                    </div>

                    <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input 
                            v-model="email" 
                            type="email" 
                            name="email" 
                            id="email" 
                            autocomplete="email" 
                            required 
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    </div>

                    <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                        <div class="text-sm">
                        <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input 
                            v-model="password" 
                            type="password" 
                            name="password" 
                            id="password" 
                            autocomplete="current-password" 
                            required 
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    </div>

                    <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Sign in
                    </button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm/6 text-gray-500">
                    Don't have an account?
                    {{ ' ' }}
                    <RouterLink :to="{ name: 'register' }" class="font-semibold text-indigo-600 hover:text-indigo-500">Signup here</RouterLink>
                </p>
            </div>
        
    </GuestLayout>
</template>

<style scoped></style>