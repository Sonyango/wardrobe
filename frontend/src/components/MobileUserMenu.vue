<template>
    <div class="border-t border-gray-700 pb-3 pt-4">
        <div class="flex items-center px-5">
            <div class="shrink-0">
                <img v-if="userProfileImage" class="h-10 w-10 rounded-full object-cover" :src="userProfileImage"
                    :alt="user.name" />
                <UserCircleIcon v-else class="h-10 w-10 text-gray-400" />
            </div>
            <div class="ml-3">
                <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
                <div class="text-sm font-medium leading-none text-gray-400">{{ user.email }}</div>
            </div>
        </div>

        <div class="mt-3 space-y-1 px-2">
            <DisclosureButton as="a" href="#"
                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                Your Profile
            </DisclosureButton>
            <DisclosureButton as="a" href="#"
                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                Settings
            </DisclosureButton>
            <DisclosureButton as="button" @click="$emit('logout')"
                class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                Sign out
            </DisclosureButton>
        </div>
    </div>
</template>

<script setup>
import { DisclosureButton } from '@headlessui/vue';
import { UserCircleIcon } from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
        default: () => ({
            name: 'User',
            email: '',
        })
    }
})

defineEmits(['logout'])

const userProfileImage = computed(() => {
    if (props.user?.profile_image) {
        return `http://localhost:8000/storage/${props.user.profile_image}`;
    }
    return null;
});
</script>