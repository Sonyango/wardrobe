<template>
    <Menu as="div" class="relative ml-3">
        <div>
            <MenuButton class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                <span class="sr-only">Open user menu</span>
                <img 
                    class="h-8 w-8 rounded-full" 
                    :src="UserCircleIcon" 
                    :alt="user.name"
                />
                <span class="ml-2 text-white text-sm hidden md:block">{{ user.name }}</span>
                <ChevronDownIcon class="ml-1 h-4 w-4 text-gray-300 hidden md:block" />
            </MenuButton>
        </div>

        <transition 
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <MenuItem v-slot="{ active }">
                    <router-link 
                        :to="{ name: 'profile'}"
                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                        Your Profile
                    </router-link>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                    <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                        Settings
                    </a>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                    <button @click="$emit('logout')" :class="[active ? 'bg-gray-100' : '', 'block w-full text-left px-4 py-2 text-sm text-gray-700']">
                        Sign out
                    </button>
                </MenuItem>
            </MenuItems>
        </transition>

    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { ChevronDownIcon, UserCircleIcon } from '@heroicons/vue/24/outline';

defineProps({
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
</script>