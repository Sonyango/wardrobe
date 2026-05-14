import axios from "axios";
import { useAuthStore } from "../stores/auth";

const baseURL = import.meta.env.VITE_API_URL || 'https://wardrobe-production.up.railway.app/api';

const api = axios.create({
    baseURL: baseURL,
    withCredentials: true,
});

export async function login(email, password) {
    try {
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

api.interceptors.request.use((config) => {
    const authStore = useAuthStore();
    if (authStore.token) {
        config.headers.Authorization = `Bearer ${authStore.token}`;
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