<script setup>
import { categories } from '../data/categories';
import { computed } from 'vue';
import { useRoute } from 'vue-router';


const route = useRoute();
const categoryName = computed(() => route.params.categoryName);
const itemType = computed(() => route.params.itemType);

// Find the category based on the route parameter.
const selectedCategory = computed(() => 
    categories.find(cat => cat.name.replace(/\s+/g, '-').toLowerCase() === categoryName.value)
)

// If itemType is provided, filter items within the category.
const filteredItems = computed(() => {
    if (!selectedCategory.value) return [];
    if (!itemType.value) return selectedCategory.value.items;

    return selectedCategory.value.items.filter(
        item => item.name.replace(/\s+/g, '-').toLowerCase() === itemType.value
    )
})

// Dynamic header based on category and item type.
const header = computed(() => 
    itemType.value ? (filteredItems.value[0]?.name || 'Items') :
    selectedCategory.value?.name || 'Categories'
);
</script>

<template>
    <div class="relative h-[calc(100vh-15rem)] overflow-y-auto py-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">{{ header }}</h2>
        <div v-if="filteredItems.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <div v-for="item in filteredItems"
                :key="item.name" 
                class="group bg-white rounded-lg shadow flex flex-col items-center justify-center aspect-square w-full"
            >
                <img :src="item.imageSrc" :alt="item.imageAlt" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg group-hover:opacity-80" />
                <h4 class="mt-2 text-xs sm:text-sm font-medium text-gray-700 text-center">{{ item.name }}</h4>
                <p class="text-[10px] sm:text-xs text-gray-500 mt-1 text-center">{{ item.description }}</p>
                <button class="mt-2 px-2 py-1 bg-gray-800 text-white rounded hover:bg-gray-700 text-[10px] sm:text-xs">
                    View
                </button>
        </div>
        </div>
        <div v-else class="text-gray-500 text-center py-12">No items found</div>
    </div>
</template>