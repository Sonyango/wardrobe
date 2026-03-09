<template>
    <div class="flex items-center space-x-3 p-4 bg-red-100 border border-red-300 rounded-lg shadow-md">
        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <div>
            <h4 class="text-red-800 font-semibold">Error!</h4>
            <p class="text-red-700 text-sm">{{ message }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    message: {
        type: String,
        default: 'An error occured. Please try again later.',
    },
});
</script>