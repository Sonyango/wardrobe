<script setup>
import { ref } from 'vue';
import { categories } from '../data/categories';
import AddCollectionModal from '../components/AddCollectionModal.vue';

const showModal = ref(false);

const slug = (s = '') => String(s).trim().trim().replace(/\s+/g, '-').toLowerCase();

function openModal() {
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}

function handleCollectionSubmit(collectionName) {
    console.log('Collection submitted:', collectionName);
}

</script>

<template>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Collections</h2>

                <button
                    @click="openModal"
                    type="button"
                    aria-label="Collection"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-indigo-600 px-3 py-1 5 text-sm font-semibold text-white hover:bg-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg> <span>Collection</span>
                </button>
            </div>

            <div class="space-y-12">
                <div v-for="category in categories" :key="category.name">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">{{ category.name }}</h3>

                        <!-- View all for category-->
                         <router-link
                            :to="{ name: 'CategoryItems', params: {categoryName: slug(category.name) } }"
                            class="text-sm text-indigo-600 hover:underline" >
                            View all
                        </router-link>
                    </div>
                    <!-- responsive grid of small square item cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            
                        
                            <div 
                                v-for="item in category.items"
                                :key="item.name" 
                                class="bg-white rounded-lg shadow flex flex-col items-center justify-center p-3 aspect-square">
                                  <img 
                                    :src="item.imageSrc" 
                                    :alt="item.imageAlt" 
                                    class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg mb-2" 
                                  />

                                  <router-link
                                        :to="{
                                            name: 'CategoryItems',
                                            params: { categoryName: slug(category.name), itemType: slug(item.name) }
                                        }"
                                        class="text-sm font-medium text-gray-700 hover:underline text-center"
                                        >
                                        {{  item.name }}
                                 </router-link>
                                 <p class="text-xs text-gray-500 mt-1 text-center">{{ item.description }}</p>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        
<AddCollectionModal
    :isOpen="showModal"
    @close="closeModal"
    @submit="handleCollectionSubmit"
/>
        
</template>

<style scoped>
</style>