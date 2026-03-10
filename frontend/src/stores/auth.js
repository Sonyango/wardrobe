import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const token = ref(localStorage.getItem('token'));

    const isAuthenticated = computed(() => !!token.value && !!user.value);

    function setUser(userData) {
        user.value = userData;
        
        if (userData) {
            localStorage.setItem('user', JSON.stringify(userData))
        } else {
            localStorage.removeItem('user')
        }
    }

    function setToken(tokenData) {
        token.value = tokenData;
        if(tokenData) {
            localStorage.setItem('token', tokenData)
        } else (
            localStorage.removeItem('token')
        )
    }

    function clearUser() {
        user.value = null;
        token.value = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
    }

    function initializer() {
        const savedUser = localStorage.getItem('user')
        if (savedUser) {
            user.value = JSON.parse(savedUser)
        }
    }

    return { 
        user, 
        token, 
        isAuthenticated, 
        setUser, 
        setToken, 
        clearUser,
        initializer
     };
})