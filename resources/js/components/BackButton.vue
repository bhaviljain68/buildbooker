<script setup>
import { Icon } from '@iconify/vue';
defineProps({
    prevRoute: {
        type: String,
        required: true
    }
})
</script>

<template>
    <a :href="prevRoute"
        class="bg-teal-700 hover:bg-teal-600 text-white cursor-pointer px-4 py-1 rounded-lg text-base flex justify-between items-center gap-x-2">
        <Icon icon="pajamas:go-back" width="16" height="16" />
        Go Back
    </a>
</template>

