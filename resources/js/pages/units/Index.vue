<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { computed } from 'vue';
import ButtonLink from '@/components/ButtonLink.vue';
import BackButton from '@/components/BackButton.vue';
const props = defineProps(['project', 'units', 'unit']);
const toast = new ToastMagic();

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0 // Removes `.00`
    }).format(amount);
}
function capitalizeFirst(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
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
function cancelBooking(unitId) {
    if (confirm('All transactions will be removed after unbook. Are you sure you want to unbook?')) {
        router.visit(route('unit-unbook', unitId), {
            onSuccess: () => {
                toast.success('Unit unbooked successfully!');
            },
            onError: () => {
                toast.error('Failed to unbook unit.');
            }
        });
    }
}

</script>

<template>
    <AppLayout>
        <div class="py-0 lg:py-10">
            <div class="w-full lg:!w-[80%] mx-auto px-6 py-5 lg:px-0">
                <div class="mb-4 flex justify-between items-center">
                    <BackButton :prevRoute="route('projects.index')" />
                    <ButtonLink icon="ic:twotone-add" class=" bg-primary" :href="route('units.create', project)">
                        Add Units
                    </ButtonLink>
                </div>

                <div
                    class="bg-gray-100 mt-10 border-t-4 border-primary overflow-hidden shadow-md rounded-lg px-4 lg:px-8">
                    <div class="text-gray-800 py-8">
                        <div class="flex flex-col items-center justify-center py-4 border-b border-gray-200 mb-8">
                            <img :src="project.logo" :alt="`${project.name}_logo`" class="max-h-24" />
                            <h6 class="m-0 text-primary font-bold text-center lg:text-3xl w-full mt-4">
                                Project Units
                            </h6>
                        </div>

                        <!-- dashtop view -->
                        <div class="hidden lg:grid grid-cols-10 border-b rounded-lg">
                            <template
                                v-for="(header, index) in ['Unit No.', 'Unit Type', 'Status', 'Book', 'Customer Name', 'Selling Price', 'Amt. Received', 'Amt. Due', 'Ledger', 'Actions']"
                                :key="index">
                                <p class="bg-secondary font-black text-zinc-100 border-r py-4 border-zinc-100 text-center"
                                    :class="{ 'rounded-tl-lg': index === 0, 'rounded-tr-lg': index === 9 }">
                                    {{ header }}
                                </p>
                            </template>

                            <template v-for="(unit, index) in units" :key="unit.id">
                                <p
                                    :class="['border-b py-2 border-x border-gray-300 flex justify-center items-center', index === units.length - 1 ? 'rounded-bl-lg' : '']">
                                    {{ unit.unit_no }}</p>
                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    {{ capitalizeFirst(unit.type) }}</p>
                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">{{
                                    unit.is_sold ? 'Sold' : 'Not Sold' }}
                                    <!-- Show Edit button only if sold -->
                                <div v-if="unit.is_sold" class="flex justify-center mt-2">
                                    <Link
                                        :href="route('units.booking.edit', { project: unit.project_id, unit: unit.id })"
                                        class="bg-green-700 ml-2 -mt-2 text-white px-1 py-1 rounded hover:bg-teal-600">
                                    <Icon icon="bxs:edit" width="15" height="15" />
                                    </Link>

                                </div>
                                </p>
                                <div
                                    class="border-b py-2 border-r border-gray-300 flex justify-center items-center w-full">
                                    <!-- Book Button if not sold -->
                                    <Link v-if="!unit.is_sold"
                                        :href="route('units.booking', { project: unit.project_id, unit: unit.id })"
                                        class="rounded-lg flex items-center gap-x-2 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 text-base bg-green-700 hover:bg-green-700 text-white">
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
                                    <button v-else @click="cancelBooking(unit.id)"
                                        class="rounded-lg flex gap-x-1 items-center transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 text-base bg-red-700 hover:bg-red-700 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-dasharray="24"
                                                stroke-dashoffset="24" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2">
                                                <path d="M5 5l14 14">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.4s"
                                                        values="24;0" />
                                                </path>
                                                <path d="M19 5l-14 14">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset"
                                                        begin="0.4s" dur="0.4s" values="24;0" />
                                                </path>
                                            </g>
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
                                <p class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    {{ unit.gst_amount === 0 ? 'N/A' :
                                        formatCurrency(unit.gst_amount) }}
                                </p>
                                <div class="border-b py-2 border-r border-gray-300 flex justify-center items-center">
                                    <Link v-if="unit.is_sold" :href="route('units.show', unit.id)"
                                        class="bg-primary text-white hover:bg-primary-dark rounded-lg flex items-center gap-x-2 px-3 py-1 text-base transition-colors">
                                    <Icon icon="lets-icons:view-alt-fill" width="20" height="20" />
                                    View
                                    </Link>

                                    <div v-else
                                        class="bg-teal-700 text-zinc-100 opacity-80 cursor-not-allowed rounded-lg flex items-center gap-x-2 px-3 py-1 text-base">
                                        <Icon icon="lets-icons:view-alt-fill" width="20" height="20" />
                                        View
                                    </div>
                                </div>
                                <div class="border-b py-2 border-r border-gray-300 flex justify-center items-center gap-x-2"
                                    :class="{ 'rounded-br-lg': index === units.length - 1 }">
                                    <Link v-if="project?.id" :href="route('transactions.create', project.id)"
                                        class="bg-primary text-white px-1 py-1 rounded"   title="Add Transaction">
                                    <Icon icon="ic:twotone-add" width="20" height="20" />
                                    </Link>
                                    <Link :href="route('units.edit', { project, unit })"
                                        class="bg-green-700 text-white px-1 py-1 rounded" title="Edit">
                                    <Icon icon="bxs:edit" width="20" height="20" />
                                    </Link>
                                    <button @click="handleDelete(unit)" class="bg-red-700 text-white px-1 py-1 rounded" title="Delete">
                                        <Icon icon="mingcute:delete-fill" width="20" height="20" />
                                    </button>
                                </div>
                            </template>
                        </div>

                        <!-- mobile view -->

                        <div class="block lg:hidden space-y-4 mt-6">
                            <div v-for="unit in units" :key="unit.id" class="bg-white rounded-lg shadow border">
                                <div
                                    class="px-4 py-2 border-b flex bg-primary justify-between items-center rounded-t-lg">
                                    <div>
                                        <h3 class="font-bold text-white">Unit No: {{ unit.unit_no }}</h3>
                                    </div>
                                    <div class="flex">
                                        <div>
                                            <span class="text-sm text-white px-2 py-1 rounded-lg"
                                                :class="unit.is_sold ? 'bg-red-600' : 'bg-green-600'">
                                                {{ unit.is_sold ? 'Sold' : 'Not Sold' }}
                                            </span>
                                        </div>
                                        <div v-if="unit.is_sold" class="flex justify-center mt-2">
                                            <Link
                                                :href="route('units.booking.edit', { project: unit.project_id, unit: unit.id })"
                                                class="bg-green-700 ml-2 -mt-2 text-white px-1 py-1 rounded hover:bg-teal-600">
                                            <Icon icon="bxs:edit" width="15" height="15" />
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-2 space-y-1 text-sm text-gray-800">
                                    <p><strong>Unit Type:</strong> {{ unit.type }}</p>
                                    <p><strong>Customer:</strong> {{ unit.is_sold ? (unit.customer?.name ?? 'N/A') :
                                        'N/A' }}</p>
                                    <p><strong>Selling Price:</strong> {{ unit.total_amount === 0 ? 'N/A' :
                                        formatCurrency(unit.total_amount) }}
                                    </p>
                                    <p><strong>Amt. Received:</strong> {{ unit.base_amount === 0 ? 'N/A' :
                                        formatCurrency(unit.base_amount) }}
                                    </p>
                                    <p><strong>GST:</strong> {{ unit.gst_amount === 0 ? 'N/A' :
                                        formatCurrency(unit.gst_amount) }}</p>
                                </div>
                                <div class="px-4 py-3 flex gap-2 justify-between border-t">
                                    <template v-if="!unit.is_sold">
                                        <Link
                                            :href="route('units.booking', { project: unit.project_id, unit: unit.id })"
                                            class="text-white bg-green-700 hover:bg-green-800 px-3 py-1 rounded text-sm">
                                        Book
                                        </Link>
                                    </template>
                                    <template v-else>
                                        <button @click="cancelBooking(unit.id)"
                                            class="text-white bg-red-700 hover:bg-red-800 px-3 py-1 rounded text-sm">
                                            Cancel
                                        </button>
                                    </template>

                                    <Link :href="route('units.show', unit.id)"
                                        :class="['px-3 py-1 rounded text-sm flex items-center gap-1', unit.is_sold ? 'bg-primary text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed']">
                                    <Icon icon="lets-icons:view-alt-fill" width="16" height="16" />
                                    View
                                    </Link>

                                    <Link :href="route('units.edit', { project, unit })"
                                        class="bg-green-700 text-white px-2 py-1 rounded text-sm">
                                    <Icon icon="bxs:edit" width="16" height="16" />

                                    </Link>

                                    <!-- <ButtonLink v-if="project?.id" icon="ic:twotone-add"
                                        :route="route('transactions.create', project.id)">
                                        Add Transaction
                                    </ButtonLink> -->
                                    <button @click="handleDelete(unit)"
                                        class="bg-red-700 text-white px-2 py-1 rounded text-sm">
                                        <Icon icon="mingcute:delete-fill" width="16" height="16" />
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
