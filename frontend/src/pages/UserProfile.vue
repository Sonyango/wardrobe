<template>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-xl font-semibold mb-1">User Profile</h1>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <!--Profile header-->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-3">
                <div class="flex items-center">
                    <img 
                        :src="userProfileImage" 
                        :alt="user.name" 
                        class="h-16 w-16 rounded-full mr-4" />
                
                    <div>
                        <h2 class="text-sm font-bold">{{ user.name }}</h2>
                        <p class="text-gray-500">{{ user.email }}</p>
                    </div>
                </div>
            </div>

            

            <!--Profile details-->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                        <p class="text-gray-500">{{ user.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <p class="text-gray-500">{{ user.email }}</p>
                    </div>
                    <div v-if="user.phone">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Phone</label>
                        <p class="text-gray-500">{{ user.phone }}</p>
                    </div>
                </div>
            </div>

            <!--Action buttons-->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="flex gap-4">
                    <button 
                        @click="gotToSettings" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Edit Profile
                    </button>
                    <button 
                        @click="goBack" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Back
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';
import { UserCircleIcon } from '@heroicons/vue/24/outline';

const router = useRouter();
const authStore = useAuthStore();

const user = authStore.user;

const userProfileImage = user?.avatarUrl || UserCircleIcon;

function gotToSettings() {
    router.push({ name: 'settings' });
}

function goBack() {
    router.back();
}
</script>