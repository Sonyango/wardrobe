<script setup>
import { defineEmits } from 'vue';
import { RouterLink } from 'vue-router';

const props = defineProps({
    navigation: { type: Array, default: () => [] },
});
const emit = defineEmits(['close']);

const slug = (s = '') => String(s).trim().replace(/\s+/g, '-').toLowerCase();

function routeFor(item, parent = null) {
    if (parent) {
        return { name: 'CategoryItems', params: { categoryName: slug(parent), itemType: slug(item.name) } };
    }
    return { name: 'CategoryItems', params: { categoryName: slug(item.name) } };
}
</script>

<template>
    <transition name="fade">
        <div class="fixed inset-0 z-50 md:hidden" role="dialog" aria-model="true">
            <div class="absolute inset-0 bg-black/50" @click="emit('close')"></div>

            <aside class="fixed left-0 top-0 bottom-0 w-72 bg-gray-800 text-white shadow-lg overflow-auto">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-700">
                    <div class="font-semibold">Wardrobe</div>
                    <button @click="emit('close')" class="p-1 text-gray-300 hover:text-white" aria-label="Close sidebar">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <nav class="px-2 py-4">
                    <ul class="space-y-1">
                        <li v-for="(group, gIdx) in navigation" :key="gIdx">
                            <div class="px-2 py-1 text-xs text-gray-400 uppercase">{{ group.name }}</div>

                            <template v-if="group.children">
                                <template v-for="(sub, sIdx) in group.children" :key="sIdx">
                                    <div class="pl-4 pr-2 py-1 text-sm font-medium text-gray-200">{{ sub.name }}</div>
                                    <ul class="pl-6 space-y-1">
                                        <li v-for="(leaf, lIdx) in sub.children" :key="lIdx">
                                            <RouterLink
                                                :to="routeFor(leaf, sub.name)"
                                                class="block px-2 py-1 rounded text-sm text-gray-200 hover:bg-gray-700"
                                                @click.native="emit('close')">
                                                {{ leaf.name }}
                                            </RouterLink>
                                        </li>
                                    </ul>
                                </template>
                            </template>

                            <template v-else>
                                <RouterLink
                                    :to="routeFor(group)"
                                    class="block px-2 py-1 rounded text-sm text-gray-200 hover:bg-gray-700"
                                    @click.native="emit('close')"
                                >
                                    {{ group.name }}
                                </RouterLink>
                            </template>
                        </li>
                    </ul>
                </nav>
            </aside>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .15s }
.fade-enter-from, .fade-laeve-to { opacity: 0; }
</style>