import { createRouter, createWebHistory } from 'vue-router';
import Home from './pages/Home.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import NotFound from './pages/NotFound.vue';
import DefaultLayout from './components/DefaultLayout.vue';
import CategoryItems from './pages/CategoryItems.vue';
import Dashboard from './components/Dashboard.vue';
import { useAuthStore } from './stores/auth';
import UserProfile from './pages/UserProfile.vue';

const routes = [
    {
        path: '/',
        component: DefaultLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'home', component: Home },
            { 
                path: 'dashboard', 
                name: 'dashboard', 
                component: Dashboard,
                meta: { requiresAuth: true } 
            },
            {
                path: 'category/:categoryName/:itemType?',
                name: 'CategoryItems',
                component: CategoryItems,
                props: true,
            },
            {
                path: 'profile',
                name: 'profile',
                component: UserProfile,
                meta: { requiresAuth: true }
            }
        ],
    },

    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { requiresGuest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { requiresGuest: true }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound
    }
];

const router = createRouter({
  history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login' });
        return;
    }

    if (to.meta.requiresGuest && authStore.isAuthenticated) {
        next({ name: 'dashboard' });
        return;
    }

    next();
});

export default router;