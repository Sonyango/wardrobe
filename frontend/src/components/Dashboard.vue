<template>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Clothes Collection</h2>
                <div v-if="stats.clothes">
                    <p class="text-sm text-gray-600">Total Clothes: <span class="font-semibold">{{ stats.clothes.total }}</span></p>
                    <p class="text-sm text-gray-600">Men: <span class="font-semibold">{{ stats.clothes.men }}</span></p>
                    <p class="text-sm text-gray-600">Women: <span class="font-semibold">{{ stats.clothes.women }}</span></p>
                </div>
                <div v-else class="text-sm text-gray-500">Loading...</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Shoes Collection</h2>
                <div v-if="stats.shoes">
                    <p class="text-sm text-gray-600">Total Shoes: <span class="font-semibold">{{ stats.shoes.total }}</span></p>
                    <p class="text-sm text-gray-600">Men: <span class="font-semibold">{{ stats.shoes.men }}</span></p>
                    <p class="text-sm text-gray-600">Women: <span class="font-semibold">{{ stats.shoes.women }}</span></p>
                </div>
                <div v-else class="text-sm text-gray-500">Loading...</div>
            </div>
        </div>
    </div>
</template>

<script>
import { api } from '../services/auth.js';

export default {
    name: 'Dashboard',
    data() {
        return {
            stats: {
                clothes: null,
                shoes: null,
            }
        };
    },
    async mounted() {
        try {
            const response = await api.get('/dashboard/stats');
            this.stats = response.data;
        } catch (error) {
            console.error('Error fetching dashboard stats:', error);
        }
    }
};
</script>