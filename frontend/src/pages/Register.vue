<script setup>
import GuestLayout from '../components/GuestLayout.vue';
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const isFormComplete = computed(() => {
    return (
        form.fname.trim() &&
        form.lname.trim() &&
        form.email.trim() &&
        form.phone.trim() &&
        form.password &&
        form.password_confirmation &&
        form.password === form.password_confirmation
    );
});

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

// Resend cooldown state
// This will hold the timestamp when the user can resend the code
const resendAvailableAt = ref(null);

// human readable countdown like "15:00"
const resendCountdown = ref('');
let resendIntervalId = null;

// computed: verify button enabled - only if code is 6 digits and not currently verifying
const isVerifyReady = computed(() => {
    return /^\d{6}$/.test(verificationCode.value) && !verifying.value;
});


// computed: resend button enabled - current time >= resendAvailableAt, not resending & modal is shown
const isResendReady = computed(() => {
    const now = Date.now();
    if (!showCodeModal.value) return false;
    if (resending.value) return false;
    if (!resendAvailableAt.value) return true; // no cooldown set, allow resend
    return now >= resendAvailableAt.value;
});


// helper: update countdown string & clear interval when ready
function updateResendCountdown() {
    if (!resendAvailableAt.value) {
        resendCountdown.value = '';
        return;
    }
    const msLeft = Math.max(0, resendAvailableAt.value - Date.now());
    if (msLeft <= 0) {
        resendCountdown.value = '';
        clearResendInterval();
        return;
    }
    const totalSeconds = Math.ceil(msLeft / 1000);
    const minutes = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
    const seconds = (totalSeconds % 60).toString().padStart(2, '0');
    resendCountdown.value = `${minutes}:${seconds}`;
}

// start cooldown for given milliseconds (default 15 minutes)
function startResendCooldown(ms = 15 * 60 * 1000) {
    const availableAt = Date.now() + ms;
    resendAvailableAt.value = availableAt;

    //persist so it survives page reloads
    const key = `resendAvailableAt:${form.email || '_anon'}`;
    localStorage.setItem(key, String(availableAt));
    // ensure countdown starts immediately
    clearResendInterval();
    updateResendCountdown();
    resendIntervalId = setInterval(updateResendCountdown, 1000);
}

function clearResendInterval() {
    if (resendIntervalId) {
        clearInterval(resendIntervalId);
        resendIntervalId = null;
    }
    // if cooldown expired, remove stored timestamp
    const key = `resendAvailableAt:${form.email || '_anon'}`;
    const stored = localStorage.getItem(key);
    if (stored && Number(stored) <= Date.now()) {
        localStorage.removeItem(key);
        resendAvailableAt.value = null;
        resendCountdown.value = '';
    }
}

// restore cooldown on load (if exists)
onMounted(() => {
    const key = `resendAvailableAt:${form.email || '_anon'}`;
    const anonKey = `resendAvailableAt:_anon`;
    const stored = localStorage.getItem(key) ?? localStorage.getItem(anonKey);
    if (stored) {
        const ts = Number(stored);
        if (!isNaN(ts) && ts > Date.now()) {
            resendAvailableAt.value = ts;
            updateResendCountdown();
            if (!resendIntervalId) resendIntervalId = setInterval(updateResendCountdown, 1000);
        } else {
            // expired
            localStorage.removeItem(key);
            localStorage.removeItem(anonKey);
            resendAvailableAt.value = null;
            resendCountdown.value = '';
        }
    }
});

// clear interval when component unmounts
onBeforeUnmount(() => {
    clearResendInterval();
});


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

        // start cooldown (only if not already active)
        if (!resendAvailableAt.value || Date.now() >= resendAvailableAt.value) {
            startResendCooldown();
        }
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

        // clear persisted cooldown after successful verification
        const key = `resendAvailableAt:${form.email || '_anon'}`;
        localStorage.removeItem(key);
        resendAvailableAt.value = null;
        resendCountdown.value = '';
        clearResendInterval();

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
        startResendCooldown();
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
                            :disabled="!isFormComplete || loading" 
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:bg-indigo-300 disabled:cursor-not-allowed">
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
                                :disabled="!isVerifyReady" 
                                class="flex flex-1 justify-center rounded-md bg-indigo-600 px-3 py-1 5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:bg-indigo-300 disabled:cursor-not-allowed">
                                {{ verifying ? 'Verifying...' : 'Verify Code' }}
                            </button>

                            <button type="button"
                                @click="resendCode"
                                :disabled="!isResendReady"
                                class="flex flex-1 justify-center rounded-md bg-gray-600 px-3 py-1 5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 disabled:bg-gray-300 disabled:cursor-not-allowed">
                                <span v-if="resending">{{ 'Sending...' }}</span>
                                <span v-else-if="!isResendReady">Resend ({{ resendCountdown || '15:00' }})</span>
                                <span v-else>Resend Code</span>
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