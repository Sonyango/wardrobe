<script setup>
import { ref, computed, watch } from 'vue';
import CustomSelect from './CustomSelect.vue';

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
        men: ['shirt', 'pants', 'jacket'],
        women: ['dress', 'skirt', 'blouse']
    },
    shoes: {
        men: ['sneakers', 'loafers', 'boots'],
        women: ['heels', 'flats', 'sandals']
    }
};

const formValid = computed(() =>
    Boolean(
        category.value &&
        gender.value &&
        item.value &&
        imageFile.value &&
        description.value?.toString().trim().length > 0
    )
);

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

    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-start sm:items-center justify-center bg-black/60 p-4">
        <div role="dialog" aria-modal="true" aria-labelledby="add-collection-title"
            class="w-full max-w-full sm:max-w-xl md:max-w-2xl lg:max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-y-auto overflow-x-hidden flex flex-col max-h-[90vh]">

            <header class="flex-shrink-0 px-6 py-4">
                <h3 id="add-collection-title" class="text-lg font-semibold text-gray-800">Add New Collection</h3>
            </header>

            <section class="px-6 py-5 space-y-4 overflow-visible">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center mb-3">
                    <label for="category" class="text-sm font-semibold text-gray-700">Category</label>
                    <div class="sm:col-span-2">
                        <CustomSelect
                            v-model="category"
                            :options="['clothes', 'shoes']"
                            placeholder="Select category"
                        />
                    </div>
                </div>

                <div v-if="category" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center mb-3">
                    <label for="gender" class="text-sm font-semibold text-gray-700">Gender</label>
                    <div class="sm:col-span-2">
                        <CustomSelect
                            v-model="gender"
                            :options="['men', 'women']"
                            placeholder="Select gender"
                        />
                    </div>
                </div>

                <div v-if="gender" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                        <label for="item" class="text-sm font-semibold text-gray-700">Item</label>
                        <div class="sm:col-span-2">
                            <CustomSelect
                                v-model="item"
                                :options="itemOptions"
                                placeholder="Select item"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-start">
                        <label for="image" class="text-sm font-semibold text-gray-700 mt-2">Image</label>
                        <div class="sm:col-span-2">
                            <input type="file" accept="image/*" @change="onImageChange"
                                class="w-full rounded-md border-gray-300 px-2 py-2" />
                            <div v-if="imagePreview" class="mt-2">
                                <img :src="imagePreview" alt="preview"
                                    class="w-full sm:w-[160px] max-h-[160px] object-cover rounded" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-start">
                        <label for="description" class="text-sm font-semibold text-gray-700">Description</label>
                        <div class="sm:col-span-2">
                            <textarea v-model="description" rows="3" class="w-full rounded-md border-gray-300 px-3 py-2"
                                placeholder="Enter Item description"></textarea>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="px-6 py-4 bg-gray-50 flex-shrink-0">
                <div class="flex flex-col sm:flex-row justify-center items-center gap-3">
                    <button @click="submitForm" :disabled="!formValid" :class="formValid
                        ? 'w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500'
                        : 'w-full sm:w-auto px-4 py-2 bg-indigo-300 text-white rounded-md cursor-not-allowed'"
                        :aria-disabled="!formValid">
                        Save
                    </button>
                    <button @click="cancel"
                        class="w-full sm:w-auto px-4 py-2 bg-white border rounded-md hover:bg-gray-100">Cancel</button>
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped></style>