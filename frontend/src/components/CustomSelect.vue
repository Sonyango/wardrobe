<template>
    <div class="relative">
        <button
            ref="buttonRef"
            @click="toggleOpen"
            @blur="closeDropdown"
            type="button"
            :class="buttonClasses"
            :disabled="disabled"
        >
            <span>{{ modelValue || placeholder }}</span>
            <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </button>

        <Teleport to="body">
            <div
                v-if="isOpen"
                class="fixed bg-white border border-gray-300 rounded-md shadow-lg z-[9999]"
                :style="dropdownStyle">
                <button
                    v-for="option in options"
                    :key="option"
                    @click="selectOption(option)"
                    @mousedown.prevent
                    type="button" 
                    class="block w-full px-3 py-2 text-left text-sm hover:bg-indigo-100 cursor-pointer">
                    {{ option }}
                </button>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    options: { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Select option' },
    disabled: { type: Boolean, default: false },
});

const emits = defineEmits(['update:modelValue']);

const buttonRef = ref(null);
const isOpen = ref(false);
const dropdownTop = ref(0);
const dropdownLeft = ref(0);
const dropdownWidth = ref(0);

const buttonClasses = computed(() => {
    const base = 'w-full flex items-center justify-between rounded-md border border-gray-300 px-3 py-2 text-sm font-medium bg-white';
    return props.disabled
        ? `${base} opacity-50 cursor-not-allowed`
        : `${base} cursor-pointer hover:bg-gray-50`;
});

const dropdownStyle = computed(() => ({
    top: dropdownTop.value + 'px',
    left: dropdownLeft.value + 'px',
    width: dropdownWidth.value + 'px',
    maxHeight: '300px',
    overflowY: 'auto',
}));

function toggleOpen() {
    if (props.disabled) return;
    isOpen.value ? closeDropdown() : openDropdown();
}

function openDropdown() {
    if (!buttonRef.value) return;
    const rect = buttonRef.value.getBoundingClientRect();
    dropdownTop.value = rect.bottom + window.scrollY;
    dropdownLeft.value = rect.left + window.scrollX;
    dropdownWidth.value = rect.width;
    isOpen.value = true;
}

function closeDropdown() {
    isOpen.value = false;
}

function selectOption(option) {
    emits('update:modelValue', option);
    closeDropdown();
}
</script>