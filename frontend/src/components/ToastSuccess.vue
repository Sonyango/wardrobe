<template>
    <div class="flex items-center space-x-3 p-4 bg-green-100 border border-green-300 rounded-lg shadow-md">
        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <div>
            <h4 class="text-green-800 font-semibold">Success!</h4>
            <p class="text-green-700 text-sm">{{ message }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    message: {
        type: String,
        default: 'Operation completed successfully.',
    },
});
</script>