<script setup>
import { reactive, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BackButton from '@/components/BackButton.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps(['units', 'project'])

console.log('Props:', props.units);
// Reactive state
const state = reactive({
    selectedUnit: null,
    unitDefined: false
})

// Filter only sold units
const soldUnits = computed(() => props.units.filter(u => u.is_sold))

// Transaction form
const form = useForm({
    payment_date: '',
    receipt_date: '',
    transaction_amount: '',
    gst: '',
    payment_type: '',
    payment_reference: '',
    bank_name: '',
    bank_branch: ''
})

// Submit handler
function submitTransaction() {
    if (!state.selectedUnit?.id) return

    form.post(route('transactions.store', { unit: state.selectedUnit.id }), {
        onSuccess: () => {
            console.log('Transaction submitted successfully')
            // state.selectedUnit = null
            // state.unitDefined = false
            // form.reset()
        },
        onError: (errors) => {
            console.error('Validation errors:', errors)
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="py-0 lg:py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 min-h-[80vh]">
                <div class="mb-4 w-40">
                    <BackButton></BackButton>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-center text-cyan-700 underline mb-6">
                        Add Transaction for Sold Unit
                    </h2>

                    <!-- Sold Unit Dropdown (Grouped) -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Select Sold Unit:</label>
                        <select v-model="state.selectedUnit" @change="state.unitDefined = true"
                            class="w-full p-2 border rounded">
                            <option disabled value="">Select a sold unit</option>

                            <!-- Optional grouping -->
                            <optgroup label="Available (Sold)">
                                <option v-for="unit in props.units.filter(u => u.is_sold)" :key="'sold-' + unit.id"
                                    :value="unit">
                                    Unit No: {{ unit.unit_no }}
                                </option>
                            </optgroup>

                            <optgroup label="Unavailable (Not Sold)">
                                <option v-for="unit in props.units.filter(u => !u.is_sold)" :key="'unsold-' + unit.id"
                                    :value="unit" disabled>
                                    Unit No: {{ unit.unit_no }}
                                </option>
                            </optgroup>
                        </select>
                    </div>


                    <!-- Message if no sold units -->
                    <div v-if="soldUnits.length === 0" class="text-center text-red-500 mt-10">
                        No sold units found to add a transaction.
                    </div>

                    <!-- Transaction Form -->
                    <div v-if="state.unitDefined && state.selectedUnit" class="mt-8">
                        <div class="p-6 bg-white rounded-md shadow">
                            <h3 class="text-xl font-bold text-cyan-700 text-center mb-6 underline">
                                Transaction Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Payment Date -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        Date Of Payment
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <input v-model="form.payment_date" type="date"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                                </div>

                                <!-- Receipt Date -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        Date Of Receipt
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <input v-model="form.receipt_date" type="date"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                                </div>

                                <!-- Transaction Amount -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        Transaction Amount
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <input v-model="form.transaction_amount" type="text"
                                        placeholder="Transaction Amount"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                                </div>

                                <!-- GST -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        GST
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <select v-model="form.gst"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 bg-white focus:ring focus:ring-cyan-100 focus:outline-none">
                                        <option disabled :value="null">Select</option>
                                        <option :value="true">GST</option>
                                        <option :value="false">Non-GST</option>
                                    </select>
                                </div>


                                <!-- Payment Type -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        Payment Type
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <select v-model="form.payment_type"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 bg-white focus:ring focus:ring-cyan-100 focus:outline-none">
                                        <option disabled value="">Select Payment Type</option>
                                        <option value="bank_draft">Bank Draft</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="rtgs">RTGS</option>
                                        <option value="neft">NEFT</option>
                                        <option value="cash">Cash</option>
                                        <option value="imps">IMPS</option>
                                    </select>
                                </div>

                                <!-- Payment Reference -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        Select Payment Type
                                        <!-- <span class="text-gray-400 text-[12px]">(Not required for cash)</span> -->
                                    </label>
                                    <input v-model="form.payment_reference" :disabled="form.payment_type === 'cash'"
                                        placeholder="Reference No"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none disabled:bg-gray-100" />
                                </div>

                                <!-- Bank Name -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        Bank Name
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <input v-model="form.bank_name" placeholder="Bank Name"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                                </div>

                                <!-- Bank Branch -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        Bank Branch
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <input v-model="form.bank_branch" placeholder="Bank Branch"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                                </div>
                            </div>


                            <div class="mt-6 text-right">
                                <button @click="submitTransaction"
                                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                                    Submit Transaction
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
