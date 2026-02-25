<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    }
});


const emits = defineEmits(['save', 'submit', 'close']);

const category = ref('');
const gender = ref('');
const item = ref('');
const description = ref('');
const imageFile = ref(null);
const imagePreview = ref('');

const itemMap = {
    clothes: {
        men: ['Shirt', 'Pants', 'Jacket'],
        women: ['Dress', 'Skirt', 'Blouse']
    },
    shoes: {
        men: ['Sneakers', 'Loafers', 'Boots'],
        women: ['Heels', 'Flats', 'Sandals']
    }
};

const formValid = computed(() => {
    return Boolean(
        category.value &&
        gender.value &&
        item.value &&
        imageFile.value &&
        description.value &&
        description.value.toString().trim().length > 0
    );
})

const itemOptions = computed(() => {
    if (!category.value || !gender.value) return [];
    return itemMap[category.value]?.[gender.value] ?? [];
});

watch(category, () => {
    gender.value = '';
    item.value = '';
    imageFile.value = null;
    imagePreview.value = '';
    description.value = '';
});

watch(gender, () => {
    item.value = '';
    imageFile.value = null;
    imagePreview.value = '';
    description.value = '';
});

function onImageChange(event) {
    const file = event.target.files?.[0] ?? null;
    imageFile.value = file;
    if (!file) {
        imagePreview.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = () => {
        imagePreview.value = reader.result;
    };
    reader.readAsDataURL(file);
}

function submitForm() {
    if (!category.value || !gender.value || !item.value) {
        alert('Please fill in all required fields.');
        return;
    }

    const payload = {
        category: category.value,
        gender: gender.value,
        item: item.value,
        description: description.value,
        image: imageFile.value
    };

    emits('save', payload);
    emits('submit', payload);
    resetAll();
    emits('close');
}

function cancel() {
    emits('close');
    resetAll();
}

function resetAll() {
    category.value = '';
    gender.value = '';
    item.value = '';
    description.value = '';
    imageFile.value = null;
    imagePreview.value = '';
}
</script>

<template>
    
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">

            <header class="px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800">Add New Collection</h3>
            </header>

            <section class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                    <label for="category" class="text-sm font-semibold text-gray-700">Category</label>
                    <div class="sm:col-span-2">
                        <select v-model="category" class="w-full rounded-md border-gray-300 px-3 py-2">
                            <option value="" disabled>Select category</option>
                            <option value="clothes">Clothes</option>
                            <option value="shoes">Shoes</option>
                        </select>
                    </div>
                </div>

                <div v-if="category" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                    <label for="gender" class="text-sm font-semibold text-gray-700">Gender</label>
                    <div class="sm:col-span-2">
                        <select v-model="gender" class="w-full rounded-md border-gray-300 px-3 py-2">
                            <option value="" disabled>Select gender</option>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                        </select>
                    </div>
                </div>

                <div v-if="gender" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                        <label for="item" class="text-sm font-semibold text-gray-700">Item</label>
                        <div class="sm:col-span-2">
                            <select v-model="item" class="w-full rounded-md border-gray-300 px-3 py-2">
                                <option value="" disabled>Select item</option>
                                <option v-for="opt in itemOptions" :key="opt" :value="opt">{{ opt }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-start">
                        <label for="image" class="text-sm font-semibold text-gray-700 mt-2">Image</label>
                        <div class="sm:col-span-2">
                            <input type="file" accept="image/*" @change="onImageChange" class="w-full" />
                            <div v-if="imagePreview" class="mt-2">
                                <img :src="imagePreview" alt="preview" class="max-w-[160px] max-h-[120px] object-cover rounded" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-start">
                        <label for="description" class="text-sm font-semibold text-gray-700">Description</label>
                        <div class="sm:col-span-2">
                            <textarea v-model="description" rows="3" class="w-full rounded-md border-gray-300 px-3 py-2" placeholder="Enter Item description"></textarea>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="px-6 py-4 bg-gray-50">
                <div class="flex justify-center gap-4">
                    <button 
                        @click="submitForm" 
                        :disabled="!formValid" 
                        :class=" formValid 
                            ? 'px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500' 
                            : 'px-4 py-2 bg-indigo-300 text-white rounded-md cursor-not-allowed'" 
                        :aria-disabled="!formValid"
                        >
                        Save
                    </button>
                    <button @click="cancel" class="px-4 py-2 bg-white border rounded-md hover:bg-gray-100">Cancel</button>
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped>
</style>