<script setup>
import { reactive, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BackButton from '@/components/BackButton.vue'
import { useForm } from '@inertiajs/vue3'
import FormInput from '@/components/FormInput.vue'
const toast = new ToastMagic()
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
            toast.success("Transaction submitted successfully!");
        },
        onError: () => {
            toast.error("Failed to Transaction deleted.");
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="py-0 lg:py-10">

            <div class="flex justify-between max-w-4xl w-full mx-auto mb-4">
                <BackButton :prevRoute="route('projects.index')" />
            </div>
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-0">
                <div class="bg-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6 border-t-4 border-primary mt-10">
                    <h2 class="text-2xl font-bold text-center text-primary mt-5 mb-6">
                        Add Transaction for Sold Unit
                    </h2>

                    <!-- Sold Unit Dropdown (Grouped) -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Select Sold Unit:</label>
                        <select v-model="state.selectedUnit" @change="state.unitDefined = true"
                            class="w-full p-2 border rounded bg-white rounded-md">
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
                        <div class="px-6 py-10 bg-gray-100 border border-primary rounded-md shadow">
                            <h3 class="text-2xl font-bold text-primary text-center mb-6">
                                Transaction Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <!-- Payment Date -->
                                <div>
                                    <FormInput label="Date of Payment" hint="(This will be displayed on your
                                            receipts)" v-model="form.payment_date" type="date"
                                        :error="form.errors.payment_date" :required="true" />
                                </div>

                                <!-- Receipt Date -->
                                <div>
                                    <FormInput label="Date of Receipt" hint="(This will be displayed on your
                                            receipts)" v-model="form.receipt_date" type="date"
                                        :error="form.errors.receipt_date" :required="true" />
                                </div>

                                <!-- Transaction Amount -->
                                <div>
                                    <FormInput label="Transaction Amount" hint="(This will be displayed on your
                                            receipts)" v-model="form.transaction_amount" type="text"
                                        :error="form.errors.transaction_amount" :required="true" />
                                </div>

                                <!-- GST -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">
                                        GST
                                        <span class="text-gray-400 text-[12px]">(This will be displayed on your
                                            receipts)</span>
                                    </label>
                                    <select v-model="form.gst"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 bg-white focus:ring focus:ring-cyan-100 focus:outline-none">
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
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 bg-white focus:ring focus:ring-cyan-100 focus:outline-none">
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
                                    <FormInput label="Payment Reference No" hint="(Not required for cash)"
                                        v-model="form.payment_reference" type="text"
                                        :error="form.errors.payment_reference"
                                        :required="form.payment_type !== 'cash'" />
                                </div>

                                <!-- Bank Name -->
                                <div>
                                    <FormInput label="Bank Name" hint="(This will be displayed on your
                                            receipts)" v-model="form.bank_name" type="text"
                                        :error="form.errors.bank_name" :required="true" />
                                </div>

                                <!-- Bank Branch -->
                                <div>
                                    <FormInput label="Bank Branch" hint="(This will be displayed on your
                                            receipts)" v-model="form.bank_branch" type="text"
                                        :error="form.errors.bank_branch" :required="true" />
                                </div>
                            </div>


                            <div class="mt-6 text-right">
                                <button @click="submitTransaction"
                                    class="bg-primary w-full text-white px-6 py-2 rounded-lg hover:bg-teal-700">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
