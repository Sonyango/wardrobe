import axios from "axios";

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true,
});

export function login(email, password) {
    return api.post('/login', { email, password });
}

export function logout() {
    return api.post('/logout');
}