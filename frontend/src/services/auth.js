import axios from "axios";
import { useAuthStore } from "../stores/auth";

const baseURL = import.meta.env.VITE_API_URL || 'https://wardrobe-production.up.railway.app/api';

const api = axios.create({
    baseURL: baseURL,
    withCredentials: true,
});

// Module-level CSRF token cache. Populated by fetchCsrfToken() and
// automatically injected into every state-mutating request by the
// request interceptor below.
let csrfToken = null;

/**
 * Fetch the CSRF token from the backend and cache it for subsequent
 * requests. Laravel exposes the token via GET /csrf-token.
 * Safe to call multiple times — skips the network round-trip when a
 * token is already cached.
 */
export async function fetchCsrfToken() {
    if (csrfToken) {
        return csrfToken;
    }

    try {
        const response = await api.get('/csrf-token');
        csrfToken = response.data.csrf_token ?? response.data.token ?? response.data;

        // Also honour a token returned in the response header (some
        // Laravel setups send it as X-CSRF-TOKEN).
        if (!csrfToken && response.headers['x-csrf-token']) {
            csrfToken = response.headers['x-csrf-token'];
        }
    } catch (error) {
        // Fall back to the meta tag injected by Laravel's Blade layout,
        // if the app is served from the same origin.
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            csrfToken = metaTag.getAttribute('content');
        } else {
            console.warn('CSRF token could not be retrieved:', error);
        }
    }

    return csrfToken;
}

export async function login(email, password) {
    try {
        // Ensure the CSRF token is available before the first POST.
        await fetchCsrfToken();

        const response = await api.post('/login', {email, password});

        const {token, user} = response.data;

        const authStore = useAuthStore();
        authStore.setToken(token);
        authStore.setUser(user);

        return response.data;
    } catch (error) {
        const authStore = useAuthStore();
        authStore.clearUser();
        throw error;
    }
}

export async function updateProfile(userData) {
    try {

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', userData.name);
        formData.append('email', userData.email);
        formData.append('phone', userData.phone || '');

        if (userData.profileImage instanceof File) {
            formData.append('profileImage', userData.profileImage);
        }

        const response = await api.post('/user/profile', formData);

        const authStore = useAuthStore();
        authStore.updateUser(response.data.user);

        return response.data;
    } catch (error) {
        if (error.response?.status === 422) {
            console.error('Validation errors:', error.response.data.errors);
        }
        throw error;
    }
}

// Password reset functions

export async function sendPasswordResetCode(email) {
    try {
        const response = await api.post('/password-reset/send-code', { email });
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function verifyPasswordResetCode(email, code) {
    try {
        const response = await api.post('/password-reset/verify-code', { email, code });
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function resetPassword(email, code, password, password_confirmation) {
    try {
        const response = await api.post('/password-reset/reset', {
            email,
            code,
            password,
            password_confirmation
        });
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function logout() {
    try {
        const response = await api.post('/logout');

        const authStore = useAuthStore();
        authStore.clearUser();

        return response.data;
    } catch (error) {
        const authStore = useAuthStore();
        authStore.clearUser();
        throw error;
    }
}

const CSRF_METHODS = new Set(['post', 'put', 'patch', 'delete']);

api.interceptors.request.use(async (config) => {
    const authStore = useAuthStore();
    if (authStore.token) {
        config.headers.Authorization = `Bearer ${authStore.token}`;
    }

    // Automatically attach the CSRF token to every state-mutating request
    // so callers don't have to remember to fetch it themselves.
    if (CSRF_METHODS.has(config.method?.toLowerCase())) {
        const token = await fetchCsrfToken();
        if (token) {
            config.headers['X-CSRF-TOKEN'] = token;
        }
    }

    return config;
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            const authStore = useAuthStore();
            authStore.clearUser();
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export { api };