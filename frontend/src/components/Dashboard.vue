<template>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">

            <div class="bg-white shadow rounded-lg p-6 h-fit">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Clothes Collection</h2>
                <div v-if="stats.clothes">
                    <p class="text-sm text-gray-600">Total Clothes: <span class="font-semibold">{{ stats.clothes.total
                            }}</span></p>
                    <p class="text-sm text-gray-600">Men: <span class="font-semibold">{{ stats.clothes.men }}</span></p>
                    <p class="text-sm text-gray-600">Women: <span class="font-semibold">{{ stats.clothes.women }}</span>
                    </p>
                </div>
                <div v-else class="text-sm text-gray-500">Loading...</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 h-fit">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Shoes Collection</h2>
                <div v-if="stats.shoes">
                    <p class="text-sm text-gray-600">Total Shoes: <span class="font-semibold">{{ stats.shoes.total
                            }}</span></p>
                    <p class="text-sm text-gray-600">Men: <span class="font-semibold">{{ stats.shoes.men }}</span></p>
                    <p class="text-sm text-gray-600">Women: <span class="font-semibold">{{ stats.shoes.women }}</span>
                    </p>
                </div>
                <div v-else class="text-sm text-gray-500">Loading...</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 h-fit">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between flex-wrap gap-2 mb-4 w-full">
                    <h2 class="text-lg font-medium text-gray-900 sm:mb-0">Statistics</h2>

                    <!-- Headless UI Dropdown-->
                     <div class="w-full sm:w-auto">
                    <Listbox v-model="selected">
                        <div class="relative">

                            <!-- Button-->
                            <ListboxButton
                                class="relative w-full sm:w-40 cursor-pointer rounded-md border border-gray-300 bg-white py-1.5 pl-3 pr-10 text-left text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <span class="block truncate">{{ selected.name }}</span>

                                <!-- Dropdown Icon-->
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronDownIcon class="h-4 w-4 text-gray-400" />
                                </span>
                            </ListboxButton>

                            <!--Dropdown options-->
                            <ListboxOptions
                                class="absolute right-0 mt-1 max-h-60 w-40 overflow-auto rounded-md bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none z-10">
                                <ListboxOption v-for="option in options" :key="option.id" :value="option"
                                    v-slot="{ active, selected: isSelected, disabled }">
                                    <li :class="[
                                        'cursor-pointer select-none px-3 py-2',
                                        active ? 'bg-indigo-100 text-indigo-900' : 'text-gray-900'
                                    ]">
                                        <span :class="[isSelected ? 'font-semibold' : 'font-normal']">
                                            {{ option.name }}
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </div>
                    </Listbox>
                    </div>

                    
                </div>

                <div class="text-sm text-gray-600">
                    <p v-if="selected.value === ''">Select category to see statstics</p>
                    <p v-else-if="selected.value === 'men'">Men's Clothes</p>
                    <p v-else-if="selected.value === 'women'">Women's Clothes</p>
                    <p v-else-if="selected.value === 'menShoes'">Men's Shoes</p>
                    <p v-else-if="selected.value === 'womenShoes'">Women's Shoes</p>
                </div>

                <!--Loading-->
                    <div v-if="loading" class="text-sm text-gray-500">Loading data...</div>

                <!--Data table-->
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border-collapse border border-gray-200">
                        <thead class="bg-white-100">
                            <tr>
                                <th class="px-4 py-2 border-collapse border border-gray-200">Item</th>
                                <th class="px-4 py-2 border-collapse border border-gray-200">Quantity</th>
                            </tr>

                            <tr v-if="statsData.length === 0">
                                <td colspan="2" class="text-center py-4 text-gray-500">No data available</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in statsData" :key="item.name">
                                <td class="px-4 py-2 border-collapse border border-gray-200 capitalize">{{ item.name }}</td>
                                <td class="px-4 py-2 border-collapse border border-gray-200">{{ item.total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { api } from '../services/auth.js';
import { ChevronDownIcon } from '@heroicons/vue/24/solid';
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue';

export default {
    name: 'Dashboard',
    components: {
        Listbox,
        ListboxButton,
        ListboxOptions,
        ListboxOption,
        ChevronDownIcon,
    },
    data() {
        return {
            stats: {
                clothes: null,
                shoes: null,
            },
            options: [
                { id: 0, name: 'Select category', value: '', disabled: true },
                { id: 1, name: 'Men clothes', value: 'men' },
                { id: 2, name: 'Women clothes', value: 'women'},
                { id: 3, name: 'Men shoes', value: 'menShoes'},
                { id: 4, name: 'Women shoes', value: 'womenShoes'}
            ],
            selected: { id: 0, name: 'Select category', value: '', disabled: true },

            statsData: [],
            loading: false,
        };
    },
    
    methods: {
        async fetchCategoryStats(gender, type) {
            this.loading = true;

            try {
                const response = await api.get('/dashboard/category-stats', {
                    params: { gender, type }
                });

                this.statsData = response.data;

            } catch (error) {
                console.error('Error fetching category stats:', error);
                this.statsData = [];
            } finally {
                this.loading = false;
            }
        }
    },

    watch: {
        selected(newValue) {
            if (!newValue.value) return;

            if (newValue.value === 'men') {
                this.fetchCategoryStats('men', 'clothes');
            }

            if (newValue.value === 'women') {
                this.fetchCategoryStats('women', 'clothes');
            }

            if (newValue.value === 'menShoes') {
                this.fetchCategoryStats('men', 'shoes');
            }

            if (newValue.value === 'womenShoes') {
                this.fetchCategoryStats('women', 'shoes');
            }
        }
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