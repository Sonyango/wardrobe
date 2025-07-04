<script setup>
//import { defineProps } from 'vue';

const props = defineProps({
    collapsed: Boolean,
    navigation: Array,
});
</script>

<template>
    <aside :class="['bg-gray-800 text-white transition-all duration-300 flex flex-col', 'h-screen', collapsed ? 'w-16' : 'w-64']">
        <div class="flex items-center justify-between p-4">
            <slot name="logo"></slot>
            
            
        </div>

        <nav v-if="!collapsed" class="mt-4">
            <a v-for="item in navigation" :key="item.name" :href="item.href"
                class="block px-4 py-2 rounded hover:bg-gray-700"
                :class="item.current ? 'bg-gray-900' : ''">
                {{ item.name }}
            </a>
        </nav>
    </aside>
</template>

<style scoped></style>