<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" class="fixed inset-0 z-50">
            <TransitionChild 
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-white/25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                        >
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <div class="flex items-center justify-between mb-4">
                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                    Edit Profile
                                </DialogTitle>
                                <button 
                                    @click="$emit('close')" 
                                    class="text-gray-400 hover:text-gray-600">
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                            </div>

                            <div class="mb-6 flex flex-col items-center">
                                <!-- Profile image preview -->
                                 <div class="relative">
                                    <div v-if="imagePreview" class="h-24 w-24 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mb-4">
                                        <img 
                                            :src="imagePreview"
                                            alt="Profile preview"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div v-else class="h-24 w-24 rounded-full overflow-hidden bg-gray-300 flex items-center justify-center mb-4">
                                        <UserCircleIcon class="h-12 w-12 text-gray-400" />
                                    </div>

                                    <!-- Image upload button -->
                                    <div class="flex gap-2 justify-center">
                                        <button 
                                            type="button"
                                            @click="triggerFileInput" 
                                            class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                            >
                                            {{ imagePreview ? 'Change' : 'Upload' }} Image
                                        </button>
                                        <button
                                            v-if="imagePreview" 
                                            type="button"
                                            @click="removeProfileImage" 
                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded-md hover:bg-red-700 transition"
                                            >
                                            Remove Image
                                        </button>
                                    </div>
                                 </div>

                                <!-- Hidden file input -->
                                 <input
                                    ref="fileInput"
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                    @change="handleFileChange"
                                />
                            </div>

                            <form @submit.prevent="saveProfile" class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">
                                        Name
                                    </label>
                                    <input 
                                        id="name" 
                                        v-model="editedUser.name" 
                                        type="text" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        Email
                                    </label>
                                    <input 
                                        id="email" 
                                        v-model="editedUser.email" 
                                        type="email" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">
                                        Phone
                                    </label>
                                    <input 
                                        id="phone" 
                                        v-model="editedUser.phone" 
                                        type="tel" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <div class="flex justify-end space-x-3 pt-4">
                                    <button 
                                        @click="$emit('close')"
                                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { UserCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { reactive, watch, ref } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true
    },
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close', 'save']);

const imagePreview = ref(null);
const fileInput = ref(null);

const editedUser = reactive({
    name: '',
    email: '',
    phone: '',
    profileImage: null
});

// Watch for changes in the user prop to update the editedUser data
watch(() => props.user, (newUser) => {
    if (newUser) {
        editedUser.name = newUser.name || '';
        editedUser.email = newUser.email || '';
        editedUser.phone = newUser.phone || '';
        editedUser.profileImage = newUser.profileImage || null;
    }
}, { immediate: true });

function triggerFileInput() {
    fileInput.value.click();
}

function handleFileChange(event) {
    const file = event.target.files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (file) {
        if (file.size > maxSize) {
            alert('Image size must be less than 5MB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
            editedUser.profileImage = file;
        };
        reader.readAsDataURL(file);
    }
}

function removeProfileImage() {
    imagePreview.value = null;
    editedUser.profileImage = null;
    if (fileInput.value) {
        fileInput.value.value = null; // Clear the file input
    }
}

function saveProfile() {
    emit('save', { ...editedUser });
}
</script>