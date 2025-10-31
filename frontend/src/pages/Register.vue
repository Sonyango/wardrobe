<script setup>
import GuestLayout from '../components/GuestLayout.vue';
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = reactive({
    fname: '',
    lname: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: ''
});

const loading = ref(false);
const error = ref(null);
const showCodeModal = ref(false);
const verificationCode = ref('');
const verifying = ref(false);
const verificationError = ref(null);
const resending = ref(false);


async function submitRegistration(e) {
    e.preventDefault();

    if (form.password !== form.password_confirmation) {
        error.value = 'Passwords do not match.';
        return;
    }

    loading.value = true;
    error.value = null;


    try {
        await axios.post('/api/register/send-code', {
            fname: form.fname,
            lname: form.lname,
            email: form.email,
            phone: form.phone,
            password: form.password,
            password_confirmation: form.password_confirmation
        });

        showCodeModal.value = true;
    } catch (err) {
        error.value = err.response?.data?.message || 'Error occured while sending verification code. Failed to register. Please try again.';
    } finally {
        loading.value = false;
    }
}

async function verifyCode(e) {
    e.preventDefault();
    verifying.value = true;
    verificationError.value = null;

    try {
        await axios.post('/api/register/verify', {
            email: form.email,
            code: verificationCode.value
        });

        router.push({ name: 'login' });
    } catch (err) {
        verificationError.value = err.response?.data?.message || 'Invalid or expired verification code.';
    } finally {
        verifying.value = false;
    }
}

async function resendCode() {
    resending.value = true;
    verificationError.value = null;

    try {
        await axios.post('/api/register/send-code', {
            fname: form.fname,
            lname: form.lname,
            email: form.email,
            phone: form.phone,
            password: form.password,
            password_confirmation: form.password_confirmation
        });

        verificationError.value = 'New verification code sent! Please check your email.';
    } catch (err) {
        verificationError.value = err.response?.data?.message || 'Error occured while resending verification code.';
    } finally {
        resending.value = false;
    }
}

</script>

<template>
    <GuestLayout>
        <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign up for an account</h2>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

                <form v-if="!showCodeModal" class="space-y-3" @submit="submitRegistration">
                    <div class="flex justify-between">
                        <div>
                            <label for="fname" class="block text-sm/6 font-medium text-gray-900">First Name</label>
                            <div class="mr-2">
                                <input type="text" 
                                    v-model="form.fname" 
                                    name="fname" 
                                    id="fname" 
                                    autocomplete="fname" 
                                    required 
                                    :disabled="loading" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div>
                            <label for="lname" class="block text-sm/6 font-medium text-gray-900 ml-3">Last Name</label>
                            <div class="ml-2">
                                <input type="text" 
                                    v-model="form.lname" 
                                    name="lname" 
                                    id="lname" 
                                    autocomplete="lname" 
                                    required :disabled="loading" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div>
                            <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                            <div class="mr-2">
                                <input type="email" 
                                    v-model="form.email" 
                                    name="email" 
                                    id="email" 
                                    autocomplete="email" 
                                    required 
                                    :disabled="loading" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm/6 font-medium text-gray-900 ml-3">Phone</label>
                            <div class="ml-2">
                                <input type="tel" 
                                    v-model="form.phone" 
                                    name="phone" 
                                    id="phone" 
                                    autocomplete="phone" 
                                    required 
                                    :disabled="loading" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div>
                            <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                            <div class="mr-2">
                                <input type="password" 
                                    v-model="form.password" 
                                    name="password" 
                                    id="password" 
                                    autocomplete="new-password" 
                                    required 
                                    :disabled="loading" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div>
                            <label for="password-confirmation" class="block text-sm/6 font-medium text-gray-900 ml-3">Confirm Password</label>
                            <div class="ml-2">
                                <input type="password" 
                                v-model="form.password_confirmation" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                autocomplete="new-password" 
                                required 
                                :disabled="loading" 
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>

                    <div v-if="error" class="mt-2 text-sm text-red-600">
                        {{ error }}
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            :disabled="loading" 
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            {{ loading ? 'Sending...' : 'Sign-Up' }}
                        </button>
                    </div>
                </form>

                <div v-if="showCodeModal" class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Enter Verification Code</h3>
                    <p class="text-sm text-gray-600">We've sent a verification code to {{ form.email }}</p>

                    <form @submit="verifyCode" class="space-y-4">
                        <div>
                            <label for="" class="block text-sm/6 font-medium text-gray-900">Verification Code</label>
                            <input type="text"
                                v-model="verificationCode"
                                id="code"
                                required
                                :disabled="verifying"
                                placeholder="Enter 6-digit code"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                class="mt-1 block w-full rounded-md bg-white px-3 py-1 5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 text-center tracking-widest" />
                        </div>

                        <div v-if="verificationError" class="text-sm text-red-600">
                            {{ verificationError }}
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                :disabled="verifying" 
                                class="flex flex-1 justify-center rounded-md bg-indigo-600 px-3 py-1 5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:bg-indigo-300">
                                {{ verifying ? 'Verifying...' : 'Verify Code' }}
                            </button>
                            <button type="button"
                                @click="resendCode"
                                :disabled="resending || verifying"
                                class="flex flex-1 justify-center rounded-md bg-gray-600 px-3 py-1 5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 disabled:bg-gray-300">
                                {{ resending ? 'Sending...' : 'Resend Code' }}
                            </button>
                        </div>
                    </form>
                </div>

                <p class="mt-5 text-center text-sm/6 text-gray-500">
                    Already have an account?
                    {{ ' ' }}
                    <RouterLink :to="{ name: 'login' }" class="font-semibold text-indigo-600 hover:text-indigo-500">Login</RouterLink>
                </p>
            </div>
    </GuestLayout>
</template>

<style scoped></style>