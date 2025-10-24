import { createRouter, createWebHistory } from 'vue-router';
import Home from './pages/Home.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import NotFound from './pages/NotFound.vue';
import DefaultLayout from './components/DefaultLayout.vue';
import CategoryItems from './pages/CategoryItems.vue';

const routes = [
    {
        path: '/',
        component: DefaultLayout,
        children: [
            { path: '', name: 'home', component: Home },
            {
                path: 'category/:categoryName/:itemType?',
                name: 'CategoryItems',
                component: CategoryItems,
                props: true,
            },
        ],
    },

    // {
    //     path: '/category/:categoryName/:itemType?',
    //     name: 'CategoryItems',
    //     component: CategoryItems,
    //     props: true,
    // },

    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
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

export default router;