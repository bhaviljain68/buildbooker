<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import BackButton from '@/components/BackButton.vue'

const props = defineProps({
    unit: Object
})

// Capitalize first letter of each word
const ucwords = (str) => str?.replace(/\b\w/g, char => char.toUpperCase()) || ''

// Go back to units index
// const goBack = () => {
//     const orgId = usePage().props.auth.user.organisation_id
//     router.visit(route('units.index', { organisation: orgId, project: props.unit.project.id }))
// }

// Tabs
const activeTab = ref('all')

const filteredTransactions = computed(() => {
    if (activeTab.value === 'base') {
        return props.unit.transactions.filter(t => !t.gst)
    }
    if (activeTab.value === 'gst') {
        return props.unit.transactions.filter(t => t.gst)
    }
    return props.unit.transactions
})

const tabClass = (tab) => {
    return [
        'px-4 py-2 rounded border font-semibold',
        tab === activeTab.value
            ? 'bg-cyan-600 text-white'
            : 'bg-cyan-100 hover:bg-cyan-200 text-cyan-800'
    ]
}
</script>

<template>
    <div class="py-0 lg:py-10">
        <div class="w-full lg:!w-[80%] mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center">
                <!-- <button class="px-4 py-2 bg-cyan-700 text-white rounded hover:bg-cyan-800" @click="goBack">
                    ← Back
                </button> -->
                <BackButton :href="route('units.index', { organisation: orgId, project: props.unit.project.id })"></BackButton>
            </div>

            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg px-8">
                <div class="text-gray-800 py-8">
                    <div class="flex flex-col items-center justify-center py-4 border-gray-200 mb-8">
                        <img :src="unit.project.logo" :alt="`${unit.project.name}_logo`" class="max-h-10" />
                        <h6
                            class="m-0 text-cyan-700 font-bold text-center lg:text-3xl w-full underline underline-offset-8 mt-4">
                            Unit : {{ unit.unit_no }}
                        </h6>
                    </div>

                    <!-- Unit Details -->
                    <div
                        class="grid grid-cols-1 lg:grid-cols-3 lg:mx-24 mt-8 border border-cyan-500 bg-cyan-200 bg-opacity-10 rounded-lg">
                        <div class="flex flex-col gap-4 p-8 border-r border-b border-cyan-300">
                            <p class="text-xl">Unit Type</p>
                            <p class="text-2xl">{{ ucwords(unit.type) }}</p>
                        </div>

                        <div class="flex flex-col gap-4 p-8 border-r border-b border-cyan-300">
                            <p class="text-xl">Status</p>
                            <p class="text-2xl">
                                {{ unit.is_sold ? 'Sold' : 'Not Sold' }}
                                <span v-if="unit.is_sold">&#128513;</span>
                            </p>
                        </div>

                        <div class="flex flex-col gap-4 p-8 border-b border-cyan-300">
                            <p class="text-xl">Selling Price</p>
                            <p class="text-2xl">₹ {{ unit.total_amount }}</p>
                        </div>

                        <div class="flex flex-col gap-4 p-8 border-r border-b border-cyan-300">
                            <p class="text-xl">Customer Name</p>
                            <p class="text-2xl">{{ ucwords(unit.customer.name) }}</p>
                        </div>

                        <div class="flex flex-col gap-4 p-8 border-r border-b border-cyan-300">
                            <p class="text-xl">Customer Contact Number</p>
                            <p class="text-2xl">{{ unit.customer.mobile }}</p>
                        </div>

                        <div class="flex flex-col gap-4 p-8 border-b border-cyan-300">
                            <p class="text-xl">Customer Email</p>
                            <p class="text-2xl break-words whitespace-normal">{{ unit.customer.email || 'N/A' }}</p>
                        </div>

                        <!-- Base Amount -->
                        <div class="flex flex-col gap-4 p-8 border-r border-cyan-300">
                            <div>
                                <p class="text-xl">Base Amount</p>
                                <p><span class="text-green-700">Received</span> / <span class="text-red-700">Due</span>
                                </p>
                            </div>
                            <p class="text-2xl">
                                <span class="text-green-700">₹ {{ unit.formatted_base_received_amount }}</span> /
                                <span class="text-red-700">₹ {{ unit.base_amount }}</span>
                            </p>
                        </div>

                        <!-- GST Amount -->
                        <div class="flex flex-col gap-4 p-8 border-r border-cyan-300">
                            <div>
                                <p class="text-xl">GST Amount</p>
                                <p><span class="text-green-700">Received</span> / <span class="text-red-700">Due</span>
                                </p>
                            </div>
                            <p class="text-2xl">
                                <span class="text-green-700">₹ {{ unit.base_amount }}</span> /
                                <span class="text-red-700">₹ {{ unit.formatted_gst_due_amount }}</span>
                            </p>
                        </div>

                        <!-- Total Amount -->
                        <div class="flex flex-col gap-4 p-8 border-cyan-300">
                            <div>
                                <p class="text-xl">Total Amount</p>
                                <p><span class="text-green-700">Received</span> / <span class="text-red-700">Due</span>
                                </p>
                            </div>
                            <p class="text-2xl">
                                <span class="text-green-700">₹ {{ unit.base_amount }}</span> /
                                <span class="text-red-700">₹ {{ unit.gst_amount }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="mt-8 lg:mx-24">
                        <details class="mt-4 rounded-lg bg-cyan-200 bg-opacity-10 border border-cyan-500">
                            <summary class="cursor-pointer px-4 py-3 font-semibold text-cyan-800">
                                View Transactions
                            </summary>
                            <div class="px-4 py-6">
                                <!-- Tabs -->
                                <div class="mb-4 flex gap-4">
                                    <button @click="activeTab = 'all'" :class="tabClass('all')">All
                                        Transactions</button>
                                    <button @click="activeTab = 'base'" :class="tabClass('base')">Base
                                        Transactions</button>
                                    <button @click="activeTab = 'gst'" :class="tabClass('gst')">GST
                                        Transactions</button>
                                </div>

                                <!-- Table -->
                                <table class="w-full text-left border border-gray-200">
                                    <thead class="bg-cyan-100">
                                        <tr>
                                            <th class="px-4 py-2 border">#</th>
                                            <th class="px-4 py-2 border">Payment Date</th>
                                            <th class="px-4 py-2 border">Amount</th>
                                            <th class="px-4 py-2 border">Instrument</th>
                                            <th class="px-4 py-2 border">Bank</th>
                                            <th class="px-4 py-2 border">Branch</th>
                                            <th class="px-4 py-2 border">Receipt Date</th>
                                            <th class="px-4 py-2 border">Receipt</th>
                                            <th class="px-4 py-2 border">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(t, index) in filteredTransactions" :key="index" class="border-t">
                                            <td class="px-4 py-2 border">{{ index + 1 }}</td>
                                            <td class="px-4 py-2 border">{{ t.payment_date }}</td>
                                            <td class="px-4 py-2 border">₹ {{ t.amount }}</td>
                                            <td class="px-4 py-2 border">{{ t.instrument ?? '-' }}</td>
                                            <td class="px-4 py-2 border">{{ t.bank ?? '-' }}</td>
                                            <td class="px-4 py-2 border">{{ t.branch ?? '-' }}</td>
                                            <td class="px-4 py-2 border">{{ t.receipt_date ?? '-' }}</td>
                                            <td class="px-4 py-2 border">
                                                <a v-if="t.receipt" :href="t.receipt" class="text-blue-600 underline"
                                                    target="_blank">
                                                    View
                                                </a>
                                                <span v-else>N/A</span>
                                            </td>
                                            <td class="px-4 py-2 border">{{ t.gst ? 'GST' : 'Base' }}</td>
                                        </tr>
                                        <tr v-if="filteredTransactions.length === 0">
                                            <td colspan="9" class="text-center py-4 text-gray-500">
                                                No transactions found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </details>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
