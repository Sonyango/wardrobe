import axios from "axios";
import { useAuthStore } from "../stores/auth";

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
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