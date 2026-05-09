<template>
    <div v-if="modelValue" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Reset Password</h2>
                <button @click="handleClose" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Error Message -->
                <div v-if="error" class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-md">
                    {{ error }}
                </div>

                <!-- Step 1: Email Input -->
                <form v-if="step === 'email'" @submit="handleSendCode" class="space-y-4">
                    <p class="text-sm text-gray-600">
                        Enter your registered email address and we'll send you a verification code.
                    </p>
                    <div>
                        <label for="reset-email" class="block text-sm font-medium text-gray-900"></label>
                        <input id="reset-email" v-model="email" type="email" required :disabled="loading"
                            placeholder="your@email.com"
                            class="mt-2 block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    <button type="submit" :disabled="!isEmailReady || loading"
                        class="w-full flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:bg-indigo-300 disabled:cursor-not-allowed">
                        {{ loading ? 'Sending...' : 'Send Verification Code' }}
                    </button>
                </form>

                <!-- Step 2: Code Input -->
                <form v-if="step === 'code'" @submit="handleVerifyCode" class="space-y-4">
                    <p class="text-sm text-gray-600">
                        We've sent a verification code to {{ email }}. Please enter it below to reset your password.
                    </p>
                    <div>
                        <label for="reset-code" class="block text-sm font-medium text-gray-900">Verification
                            Code</label>
                        <input id="reset-code" v-model="code" type="text" required :disabled="loading"
                            placeholder="000000" pattern="[0-9]{6}" maxlength="6"
                            class="mt-2 block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 text-center tracking-widest font-semibold" />
                    </div>
                    <button type="submit" :disabled="!isCodeReady"
                        class="w-full flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:bg-indigo-300 disabled:cursor-not-allowed">
                        {{ loading ? 'Verifying...' : 'Verify Code' }}
                    </button>
                </form>

                <!-- Step 3: New Password -->
                <form v-if="step === 'password'" @submit="handleResetPassword" class="space-y-4">
                    <p class="text-sm text-gray-600">
                        Enter your new password. Must be at least 8 characters long.
                    </p>
                    <div>
                        <label for="new-password" class="block text-sm font-medium text-gray-900">New Password</label>
                        <input id="new-password" v-model="password" type="password" required :disabled="loading"
                            minlength="8" placeholder="••••••••"
                            class="mt-2 block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    <div>
                        <label for="confirm-password" class="block text-sm font-medium text-gray-900">Confirm New
                            Password</label>
                        <input id="confirm-password" v-model="passwordConfirmation" type="password" required
                            :disabled="loading" minlength="8" placeholder="••••••••"
                            class="mt-2 block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    <div v-if="password && passwordConfirmation && password !== passwordConfirmation"
                        class="text-sm text-red-600">
                        Passwords do not match.
                    </div>
                    <div v-if="password && password.length < 8" class="text-sm text-red-600">
                        Password must be at least 8 characters.
                    </div>
                    <button type="submit" :disabled="!isPasswordReady"
                        class="w-full flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:bg-indigo-300 disabled:cursor-not-allowed">
                        {{ loading ? 'Resetting...' : 'Reset Password' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { sendPasswordResetCode, verifyPasswordResetCode, resetPassword } from '../services/auth';

const props = defineProps({
    modelValue: Boolean
});

const emit = defineEmits(['update:modelValue']);

const step = ref('email');
const email = ref('');
const code = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const error = ref('');
const loading = ref(false);

// Computed properties
const isEmailReady = computed(() => email.value.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value));
const isCodeReady = computed(() => /^\d{6}$/.test(code.value) && !loading.value);
const isPasswordReady = computed(() => {
    return password.value && passwordConfirmation.value &&
        password.value === passwordConfirmation.value &&
        password.value.length >= 8 && !loading.value;
});

// Step 1: Send reset code
async function handleSendCode(e) {
    e.preventDefault();
    loading.value = true;
    error.value = '';

    try {
        await sendPasswordResetCode(email.value);
        step.value = 'code';
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to send reset code. Please try again.';
    } finally {
        loading.value = false;
    }
}

// Step 2: Verify reset code
async function handleVerifyCode(e) {
    e.preventDefault();
    loading.value = true;
    error.value = '';

    try {
        await verifyPasswordResetCode(email.value, code.value);
        step.value = 'password';
    } catch (err) {
        error.value = err.response?.data?.message || 'Invalid or expired verification code. Please try again.';
    } finally {
        loading.value = false;
    }
}

// Step 3: Reset password
async function handleResetPassword(e) {
    e.preventDefault();
    loading.value = true;
    error.value = '';

    try {
        await resetPassword(email.value, code.value, password.value, passwordConfirmation.value);
        emit('update:modelValue', false);
        resetForm();
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to reset password. Please try again.';
    } finally {
        loading.value = false;
    }
}

// Helper function to reset form state
function resetForm() {
    step.value = 'email';
    email.value = '';
    code.value = '';
    password.value = '';
    passwordConfirmation.value = '';
    error.value = '';
}

// Close modal
function handleClose() {
    emit('update:modelValue', false);
    resetForm();
}
</script>