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
const imagePreview = ref(null);

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
    
    <div v-if="isOpen" class="modal-overlay">
        <div class="add-collection-modal">
            <label>Category</label>
            <select v-model="category">
                <option value="">Select category</option>
                <option value="clothes">Clothes</option>
                <option value="shoes">Shoes</option>
            </select>

            <div v-if="category" class="mt-2">
                <label>Gender</label>
                <select v-model="gender">
                    <option value="">Select gender</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                </select>
            </div>

            <div class="mt-3">
                <label>Item</label>
                <select v-model="item">
                    <option value="">Select item</option>
                    <option v-for="opt in itemOptions" :key="opt" :value="opt">{{ opt }}</option>
                </select>

                <label class="mt-2">Image</label>
                <input type="file" accept="image/*" @change="onImageChange" />

                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" alt="preview" style="max-width:160px; max-height:160px; object-fit: cover;" />
                </div>

                <label class="mt-2">Description</label>
                <textarea v-model="description" rows="3"></textarea>

                <div class="mt-3">
                    <button @click="submitForm">Save</button>
                    <button type="button" @click="cancel" style="margin-left:8px;">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}

.add-collection-modal {
    background: white;
    padding: 18px;
    width: 420px;
    border-radius: 8px;
}

.add-collection-modal { display:block; margin-top: 8px; font-weight: 600;}
.add-collection-modal select,
.add-collection-modal textarea,
.add-collection-modal input[type="file"] { display:block; width: 100%; margin-top: 6px;}
.mt-2 { margin-top: 8px; }
.mt-3 { margin-top: 12px; }
</style>