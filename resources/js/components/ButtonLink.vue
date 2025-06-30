<script setup>
import { Icon } from '@iconify/vue';
defineProps({
    route: String,
    icon: String,
    title: {
        type: String,
        default: ''
    },
    status: {
        type: String,
        default: 'primary'
    }
})
</script>

<template>
    <a :href="route"
        class="rounded-lg flex items-center gap-x-2 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 px-4 py-2 text-base bg-gray-800 hover:bg-gray-900 text-zinc-100"
        :class="{
            'bg-blue-600 hover:bg-blue-700 text-white': status === 'primary',
            'bg-green-600 hover:bg-green-700 text-white': status === 'success',
            'bg-yellow-500 hover:bg-yellow-600 text-white': status === 'warning',
            'bg-red-600 hover:bg-red-700 text-white': status === 'error',
            'bg-gray-600 hover:bg-gray-700 text-white': status === 'info'
        }">
        <Icon v-if="icon" :icon="icon" width="24" height="24" />
        <slot>{{ title }}</slot>
    </a>
</template>

<style scoped>
/* Add icon font if not already loaded */
@import url('https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css');
</style>
