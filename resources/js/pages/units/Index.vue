<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { computed } from 'vue';
defineProps(['project', 'units']);
const toast = new ToastMagic();
// const formatCurrency = (value) => {
//     return parseFloat(value).toLocaleString('en-IN', {
//         minimumFractionDigits: 2,
//         maximumFractionDigits: 2,
//     });
// };


function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0 // Removes `.00`
    }).format(amount);
}
function handleDelete(unit) {
    const confirmText = unit.is_sold
        ? 'This unit is already booked. Please unbook it before deleting.'
        : 'Are you sure you want to delete this unit?';

    if (!window.confirm(confirmText)) return;

    router.delete(route('units.delete', { unit: unit.id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Unit deleted successfully!");
        },
        onError: () => {
            toast.error("Failed to unit deleted.");
        },
    });
}
function cancelBooking() {
    if (confirm('All transactions will be removed after unbook. Are you sure you want to unbook?')) {
        window.location.href = route('unit-unbook', { unit: unit.id })
    }
}


</script>

<template>
    <AppLayout>
        <div class="py-0 lg:py-10">
            <div class="w-full lg:!w-[80%] mx-auto sm:px-6 lg:px-8">
                <div class="mb-4 flex justify-between items-center">
                    <Link :href="route('projects.index')"
                        class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold cursor-pointer px-4 py-1 rounded-lg text-base flex justify-between items-center gap-x-2">
                    <Icon icon="pajamas:go-back" width="16" height="16" />
                    Go Back</Link>
                    <Link :href="route('units.create', project)"
                        class="rounded-lg flex items-center gap-x-2 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 px-4 py-2 text-base bg-gray-800 hover:bg-gray-900 text-zinc-100">
                    <Icon icon="ic:twotone-add" width="24" height="24" />
                    Add Units</Link>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg px-8">
                    <div class="text-gray-800 py-8">
                        <div class="flex flex-col items-center justify-center py-4 border-b border-gray-200 mb-8">
                            <img :src="project.logo" :alt="`${project.name}_logo`" class="max-h-10" />
                            <h6
                                class="m-0 text-cyan-700 font-bold text-center lg:text-3xl w-full underline underline-offset-8 mt-4">
                                Project Units
                            </h6>
                        </div>

                        <div class="grid grid-cols-10 border-b rounded-lg">
                            <template
                                v-for="(header, index) in ['Unit No.', 'Unit Type', 'Status', 'Book', 'Customer Name', 'Selling Price', 'Amt. Received', 'Amt. Due', 'Ledger', 'Actions']"
                                :key="index">
                                <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 border-zinc-100 text-center"
                                    :class="{ 'rounded-tl-lg': index === 0, 'rounded-tr-lg': index === 9 }">
                                    {{ header }}
                                </p>
                            </template>

                            <template v-for="(unit, index) in units" :key="unit.id">
                                <p
                                    :class="['border-b py-2 border-x border-gray-300 flex justify-center items-center', index === units.length - 1 ? 'rounded-bl-lg' : '']">
                                    {{ unit.unit_no }}</p>
                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">{{
                                    unit.type }}</p>
                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">{{
                                    unit.is_sold ? 'Sold' : 'Not Sold' }}</p>

                                <div
                                    class="border-b py-2 border-r border-gray-300 flex justify-center items-center w-full">
                                    <!-- Book Button if not sold -->
                                    <Link v-if="!unit.is_sold"
                                        :href="route('units.booking', { project: unit.project_id, unit: unit.id })"
                                        class="rounded-lg flex items-center gap-x-2 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 text-base bg-green-600 hover:bg-green-700 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="1.5">
                                            <path
                                                d="M14 9.846c-1-.923-3.667-1.23-3.667.616S14 11.385 14 13.23s-3 1.846-4 .615m2 .857V16m0-6.887V8M2 8l9.732-4.866a.6.6 0 0 1 .536 0L22 8" />
                                            <path d="M20 11v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8" />
                                        </g>
                                    </svg>
                                    Book
                                    </Link>

                                    <!-- Cancel Button if sold -->
                                    <button v-else @click="cancelBooking"
                                        class="rounded-lg flex items-center transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 text-base bg-red-600 hover:bg-red-700 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20M9.46 8.46a1 1 0 1 1 1.41-1.41L12 8.59l1.12-1.12a1 1 0 1 1 1.41 1.41L13.41 10l1.12 1.12a1 1 0 0 1-1.41 1.41L12 11.41l-1.12 1.12a1 1 0 0 1-1.41-1.41L10.59 10z" />
                                        </svg>
                                        Cancel
                                    </button>
                                </div>


                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    {{ unit.is_sold ? (unit.customer?.name ?? 'N/A') : 'N/A' }}
                                </p>
                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    {{ unit.total_amount === 0 ? 'N/A' : formatCurrency(unit.total_amount) }}
                                </p>

                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    {{ unit.base_amount === 0 ? 'N/A' :
                                        formatCurrency(unit.base_amount) }}
                                </p>
                                <!-- <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    {{ unit.gst_amount === 0 ? 'N/A' : formatCurrency(unit.total_amount -
                                        unit.gst_amount) }}
                                </p> -->
                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    {{ unit.gst_amount === 0 ? 'N/A' :
                                        formatCurrency(unit.gst_amount) }}
                                </p>
                                <div class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    <Link :href="route('units.show', unit.id)"
                                        :class="['px-2 py-1 rounded text-white', unit.is_sold ? 'bg-blue-500' : 'rounded-lg flex items-center gap-x-2 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 text-base bg-gray-800 hover:bg-gray-900 text-zinc-100 opacity-80 cursor-not-allowed']">
                                    <Icon icon="lets-icons:view-alt-fill" width="20" height="20" /> View</Link>
                                </div>

                                <div class="border-b py-2 border-r border-gray-300 flex justify-center items-center gap-x-2"
                                    :class="{ 'rounded-br-lg': index === units.length - 1 }">
                                    <Link :href="route('units.edit', { project, unit })"
                                        class="bg-cyan-600 text-white px-2 py-1 rounded">
                                    <Icon icon="bxs:edit" width="20" height="20" />
                                    </Link>
                                    <button @click="handleDelete(unit)" class="bg-red-600 text-white px-2 py-1 rounded">
                                        <Icon icon="mingcute:delete-fill" width="20" height="20" />
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
