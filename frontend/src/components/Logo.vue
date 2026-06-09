<template>
    <div class="mb-6">
        <img :src="logoPath" :alt="altText" :class="logoClasses" />
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    size: {
        type: String,
        default: 'lg',
        validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
    },
    logoPath: {
        type: String,
        required: true
    },
    altText: {
        type: String,
        default: 'Logo'
    }
});

const logoClasses = computed(() => {
    const sizeMap = {
        'sm': 'h-12 w-12',
        'md': 'h-16 w-16',
        'lg': 'h-20 w-20',
        'xl': 'h-24 w-24'
    };
    return `${sizeMap[props.size]} object-contain mx-auto`;
});
</script>