<template>
    <!-- Backdrop with blur -->
    <div 
        v-if="modelValue" 
        class="fixed inset-0 bg-white/50 backdrop-blur-sm flex items-center justify-center z-1000"
    >
        <div class="relative z-1001 animate-in fade-in zoom-in duration-200">
            <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-xl">
                <!-- Close button (only way to close) -->
                <button 
                    @click="handleClose" 
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Step 1: Email -->
                <div v-if="step === 'email'">
                    <h3 class="text-lg font-semibold mb-4">Reset Password</h3>
                    <form @submit="handleSendCode" class="space-y-4">
                        <div>
                            <label for="reset-email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <input
                                id="reset-email"
                                v-model="email"
                                type="email"
                                required
                                :class="[
                                    'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500',
                                    emailValidationFailed ? 'border-red-500 bg-red-50' : 'border-gray-300'
                                ]"
                                :disabled="loading || emailValidationFailed"
                                placeholder="Enter registered email"
                            />
                        </div>
                        
                        <div v-if="error" class="text-red-600 text-sm bg-red-50 p-2 rounded">
                            {{ error }}
                        </div>
                        
                        <button
                            type="submit"
                            :disabled="!isEmailReady || loading"
                            :class="[
                                'w-full py-2 rounded-md transition-colors',
                                (!isEmailReady || loading) 
                                    ? 'bg-gray-400 cursor-not-allowed' 
                                    : 'bg-indigo-600 text-white hover:bg-indigo-700'
                            ]"
                        >
                            {{ loading ? 'Sending...' : 'Request Reset Code' }}
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-2">
                            Enter the email address associated with your account.
                        </p>
                    </form>
                </div>

                <!-- Step 2: Code -->
                <div v-if="step === 'code'">
                    <h3 class="text-lg font-semibold mb-2">Enter Verification Code</h3>
                    <p class="text-sm text-gray-600 mb-4">Please enter the 6-digit code sent to {{ email }}</p>
                    
                    <form @submit="handleVerifyCode" class="space-y-4">
                        <div>
                            <label for="reset-code" class="block text-sm font-medium text-gray-700 mb-1">
                                Verification Code
                            </label>
                            <input
                                id="reset-code"
                                v-model="code"
                                type="text"
                                maxlength="6"
                                placeholder="000000"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center text-2xl tracking-widest"
                                :disabled="loading"
                            />
                        </div>
                        
                        <div v-if="error" class="text-red-600 text-sm bg-red-50 p-2 rounded">
                            {{ error }}
                        </div>
                        
                        <button
                            type="submit"
                            :disabled="!isCodeReady || loading"
                            class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                        >
                            {{ loading ? 'Verifying...' : 'Verify Code' }}
                        </button>
                        
                        <button
                            type="button"
                            @click="step = 'email'"
                            class="w-full text-indigo-600 text-sm hover:text-indigo-800"
                        >
                            ← Back to email
                        </button>
                    </form>
                </div>

                <!-- Step 3: Password -->
                <div v-if="step === 'password'">
                    <h3 class="text-lg font-semibold mb-4">Create New Password</h3>
                    
                    <form @submit="handleResetPassword" class="space-y-4">
                        <div>
                            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password
                            </label>
                            <input
                                id="new-password"
                                v-model="password"
                                type="password"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                :disabled="loading"
                            />
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                        </div>
                        
                        <div>
                            <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input
                                id="confirm-password"
                                v-model="passwordConfirmation"
                                type="password"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                :disabled="loading"
                            />
                        </div>
                        
                        <div v-if="error" class="text-red-600 text-sm bg-red-50 p-2 rounded">
                            {{ error }}
                        </div>
                        
                        <button
                            type="submit"
                            :disabled="!isPasswordReady || loading"
                            class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                        >
                            {{ loading ? 'Resetting...' : 'Reset Password' }}
                        </button>
                        
                        <button
                            type="button"
                            @click="step = 'code'"
                            class="w-full text-indigo-600 text-sm hover:text-indigo-800"
                        >
                            ← Back to code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { sendPasswordResetCode, verifyPasswordResetCode, resetPassword } from '../services/auth';

const props = defineProps({
    modelValue: Boolean
});

const emit = defineEmits(['update:modelValue', 'passwordResetSuccess']);

const step = ref('email');
const email = ref('');
const code = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const error = ref('');
const loading = ref(false);

const emailValidationFailed = ref(false);
const failedEmail = ref('');

// Computed properties
//const isEmailReady = computed(() => email.value.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value));
const isEmailReady = computed(() => {
    return email.value.trim() &&
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value) &&
        !emailValidationFailed.value;
});
const isCodeReady = computed(() => /^\d{6}$/.test(code.value) && !loading.value);
const isPasswordReady = computed(() => {
    return password.value && passwordConfirmation.value &&
        password.value === passwordConfirmation.value &&
        password.value.length >= 8 && !loading.value;
});

watch(email, (newEmail, oldEmail) => {
    if (emailValidationFailed.value && newEmail !== failedEmail.value) {
        emailValidationFailed.value = false;
        failedEmail.value = '';
        error.value = '';
    }
});

// Step 1: Send reset code
async function handleSendCode(e) {
    e.preventDefault();
    loading.value = true;
    error.value = '';

    try {
        await sendPasswordResetCode(email.value);
        step.value = 'code';
        emailValidationFailed.value = false;
        failedEmail.value = '';
    } catch (err) {
        //error.value = err.response?.data?.message || 'Failed to send reset code. Please try again.';
        const errorMessage = err.response?.data?.message || 'Failed to send reset code. Please try again.';
        error.value = errorMessage;

        if (errorMessage.toLowerCase().includes('does not exist') ||
            errorMessage.toLowerCase().includes('not found') ||
            errorMessage.toLowerCase().includes('no account')) {
                emailValidationFailed.value = true;
                failedEmail.value = email.value;
            }
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
        emit('passwordResetSuccess', email.value);
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