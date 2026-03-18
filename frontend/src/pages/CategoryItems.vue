<script setup>
import { ref, computed, onMounted } from 'vue';
import { categories as staticCategories } from '../data/categories';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';


const route = useRoute();
const toast = useToast();

const dynamicCategories = ref([]);
const loading = ref(true);
const error = ref(null);

const categoryName = computed(() => route.params.categoryName);
const itemType = computed(() => route.params.itemType);

const mergedCategories = computed(() => {
    return staticCategories.map(staticCat => {
        const dynamicCat = dynamicCategories.value.find(dynCat => {
            const staticGender = staticCat.name.toLowerCase().split(' ')[0];
            const staticCatName = staticCat.name.toLowerCase().split(' ')[1];
            return dynCat.name.toLowerCase() === staticCatName && dynCat.gender?.name.toLowerCase() === staticGender;
        });
        return {
            ...staticCat,
            items: dynamicCat?.items || staticCat.items,
        };
    });
});

// Find the category based on the route parameter.
const selectedCategory = computed(() =>
    mergedCategories.value.find(cat => cat.name.replace(/\s+/g, '-').toLowerCase() === categoryName.value)
);

// If itemType is provided, filter items within the category.
const filteredItems = computed(() => {
    if (!selectedCategory.value) return [];
    if (!itemType.value) return selectedCategory.value.items;

    return selectedCategory.value.items.filter(
        item => item.name.replace(/\s+/g, '-').toLowerCase() === itemType.value.toLowerCase()
    );
});

// Dynamic header based on category and item type.
const header = computed(() =>
    itemType.value ? (filteredItems.value[0]?.name || 'Items') :
        selectedCategory.value?.name || 'Categories'
);

// Fetch dynamic categories from the backend.
const fetchCategories = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/categories', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        dynamicCategories.value = response.data.map(cat => ({
            ...cat,
            gender: cat.gender || {},
        }));
    } catch (error) {
        error.value = 'Failed to load categories';
        toast.error('Failed to load categories');
        console.error('Error fetching categories:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCategories();
});
</script>

<template>


    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <router-link :to="{ name: 'home' }"
                        class="inline-flex items-center px-3 py-1 rounded bg-gray-700 text-amber-50 hover:bg-gray-200 hover:text-gray-900 transition-colors">
                        Back to Collections
                    </router-link>
                    <h1 class="text-2xl font-semibold text-gray-900">{{ header }}</h1>
                </div>

                <div class="text-sm text-gray-500">
                    <span v-if="selectedCategory">{{ selectedCategory.name }}</span>
                </div>
            </div>

            <div v-if="loading" class="py-12 text-center text-gray-500">
                Loading categories...
            </div>

            <div v-else-if="error" class="py-12 text-center text-red-500">
                {{ error }}
                <div class="mt-4">
                    <button @click="fetchCategories" class="text-indigo-600 hover:underline">Retry</button>
                </div>
            </div>

            <div v-else-if="filteredItems.length"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div v-for="item in filteredItems" :key="item.name"
                    class="bg-white rounded-lg shadow p-3 flex flex-col items-center justify-center aspect-square">
                    <img :src="item.image_url || item.imageSrc" :alt="item.name"
                        class="w-20 h-20 object-cover rounded mb-2" />
                    <div class="text-sm font-medium text-gray-700 text-center">{{ item.name }}</div>
                    <p class="text-xs text-gray-500 mt-1 text-center">{{ item.description }}</p>
                </div>
            </div>

            <div v-else class="py-12 text-center text-gray-500">
                No items found
                <div class="mt-4">
                    <router-link :to="{ name: 'home' }" class="text-indigo-600 hover:underline">Return to
                        collections</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

