<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { login } from '../services/auth';
import GuestLayout from '../components/GuestLayout.vue';
import ForgotPassword from '../components/ForgotPassword.vue';
import { useToast } from 'vue-toastification';
import Logo from '../components/Logo.vue';

const router = useRouter();
const authStore = useAuthStore();
const toast = useToast();

const loading = ref(false);

const email = ref('');
const password = ref('');
const error = ref('');
const showForgotPassword = ref(false);
const currentImageIndex = ref(0);

const heroImages = [
    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60',
    'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60',
    'https://images.unsplash.com/photo-1595777707802-e176fc7f92b7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60',
];

async function handleSubmit(e) {
    e.preventDefault();
    loading.value = true;
    try {
        const response = await login(email.value, password.value);
        //authStore.setUser(response.data.user);
        router.push({ name: 'dashboard' });
    } catch (err) {
        error.value = err.response?.data?.message || 'Login failed.';
    } finally {
        loading.value = false;
    }
}

// Show success toast after password reset
function passwordResetSuccess(email) {
    toast.success(`Password reset successful for ${email}. Please log in with your new password.`);
}

function nextImage() {
    currentImageIndex.value = (currentImageIndex.value + 1) % heroImages.length;
}

function prevImage() {
    currentImageIndex.value = (currentImageIndex.value - 1 + heroImages.length) % heroImages.length;
}
</script>

<template>
    <GuestLayout>

            <!-- Forgot Password Modal -->
             <!-- <ForgotPassword v-model="showForgotPassword" @passwordResetSuccess="passwordResetSuccess" /> -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 h-screen md:h-screen">

                <!-- Left side section -->
                 <div class="hidden md:flex flex-col justify-center items-center bg-linear-to-br from-indigo-400 to-indigo-600 p-12">

                    <!-- Brand info-->
                     <div class="text-center mb-8">
                        <h1 class="text-4xl font-bold text-white mb-4">Wardrobe</h1>
                        <p class="text-indigo-100 text-lg mb-6">
                            Your personal fashion assistant. Organize your closet, plan outfits, and get style recommendations all in one place.
                        </p>
                     </div>

                    <!-- Features list -->
                      <div class="space-y-4 mb-8 w-full max-w-sm">

                            <div class="flex items-center space-x-3 text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                                <span> Organize your clothes by category </span>
                            </div>
                            <div class="flex items-center space-x-3 text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Track outfit combinations</span>
                            </div>
                            <div class="flex items-center space-x-3 text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Discover personal style insights</span>
                            </div>
                      </div>
                    <!-- Image carousel -->
                     <div class="relative w-full max-w-sm">
                        <div class="relative h-64 bg-indigo-700 rounded-lg overflow-hidden">
                            <img 
                                :src="heroImages[currentImageIndex]" 
                                :alt="`Wardrobe showcase ${currentImageIndex + 1}`" 
                                class="w-full h-full object-cover" />
                        </div>

                        <!-- Carousel controls -->
                         <button
                            @click="prevImage"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full transition"
                         >
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button 
                            @click="nextImage"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full transition"
                        >
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Carousel indicators -->
                         <div class="flex justify-center gap-2 mt-4">
                            <button
                                v-for="(_, index) in heroImages"
                                :key="index"
                                @click="currentImageIndex = index"
                                :class="[
                                    'h-2 rounded-full transition',
                                    currentImageIndex === index ? 'bg-white w-6' : 'bg-white/50 w-2'
                                ]"
                            />
                         </div>
                     </div>
                 </div>

                <!-- Right side section -->
                 <div class="flex flex-col justify-center items-center p8 md:p-12 bg-white md:bg-gray-50 h-full md:h-screen">
                    <div class="w-full max-w-sm">

                        <!--Header-->
                        <div class="text-center mb-8">
                            <Logo :logoPath="'/logo.png'" size="lg" altText="Wardrobe Logo" />
                            <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
                            <p class="mt-2 text-gray-600">Sign in to your account to access your personalized style insights.</p>
                        </div>

                        <!--Login form-->
                        <form class="space-y-6" @submit="handleSubmit">

                            <!-- Error message -->
                             <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                                {{ error }}
                            </div>

                            <!-- Email input -->
                             <div>
                            <label for="email" class="block text-sm font-medium text-gray-900 mb-2">
                                Email Address
                            </label>
                            <input 
                                v-model="email" 
                                type="email" 
                                name="email" 
                                id="email" 
                                autocomplete="email" 
                                required 
                                placeholder="you@example.com"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            />
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-900">
                                    Password
                                </label>
                                <button 
                                    type="button"
                                    @click.prevent="showForgotPassword = true"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition"
                                >
                                    Forgot password?
                                </button>
                            </div>
                            <input 
                                v-model="password" 
                                type="password" 
                                name="password" 
                                id="password" 
                                autocomplete="current-password" 
                                required 
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            />
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            :disabled="!email || !password || loading" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <div v-if="loading" class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Signing in...</span>
                            </div>
                            <span v-else>Sign In</span>
                        </button>
                        </form>

                        <!-- Sign up link -->
                         <p class="mt-8 text-center text-sm text-gray-600">
                            Don't have an account?
                            <RouterLink 
                                :to="{ name: 'register' }" 
                                class="font-semibold text-indigo-600 hover:text-indigo-500 transition"
                            >
                                Create one
                            </RouterLink>
                        </p>
                    </div>
                 </div>
            </div>

            <!-- Forgot Password Modal -->
             <ForgotPassword v-model="showForgotPassword" @passwordResetSuccess="passwordResetSuccess" />
        
    </GuestLayout>
</template>

<style scoped></style>