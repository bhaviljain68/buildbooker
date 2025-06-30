<script setup>
import { reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import BackButton from '@/components/BackButton.vue'

const props = defineProps(['transaction', 'unit', 'project'])

const form = useForm({
    payment_date: props.transaction.payment_date || '',
    receipt_date: props.transaction.receipt_date || '',
    transaction_amount: props.transaction.transaction_amount || '',
    gst: props.transaction.gst ?? '',
    payment_type: props.transaction.payment_type || '',
    payment_reference: props.transaction.payment_reference || '',
    bank_name: props.transaction.bank_name || '',
    bank_branch: props.transaction.bank_branch || ''
})

// Submit handler
const submitUpdate = () => {
    form.post(route('transactions.update', props.transaction.id), {
        onSuccess: () => {
            console.log('Transaction updated successfully')
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
                    <BackButton />
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-center text-cyan-700 underline mb-6">
                        Edit Transaction for Unit {{ unit.unit_no }}
                    </h2>

                    <div class="p-6 bg-white rounded-md shadow">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Payment Date -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">
                                    Date Of Payment
                                </label>
                                <input v-model="form.payment_date" type="date"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                            </div>

                            <!-- Receipt Date -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">
                                    Date Of Receipt
                                </label>
                                <input v-model="form.receipt_date" type="date"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                            </div>

                            <!-- Transaction Amount -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">
                                    Transaction Amount
                                </label>
                                <input v-model="form.transaction_amount" type="text"
                                    placeholder="Transaction Amount"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                            </div>

                            <!-- GST -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">GST</label>
                                <select v-model="form.gst"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 bg-white focus:ring focus:ring-cyan-100 focus:outline-none">
                                    <option disabled :value="null">Select</option>
                                    <option :value="true">GST</option>
                                    <option :value="false">Non-GST</option>
                                </select>
                            </div>

                            <!-- Payment Type -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Payment Type</label>
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
                                    Payment Reference
                                </label>
                                <input v-model="form.payment_reference" :disabled="form.payment_type === 'cash'"
                                    placeholder="Reference No"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none disabled:bg-gray-100" />
                            </div>

                            <!-- Bank Name -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Bank Name</label>
                                <input v-model="form.bank_name" placeholder="Bank Name"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                            </div>

                            <!-- Bank Branch -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Bank Branch</label>
                                <input v-model="form.bank_branch" placeholder="Bank Branch"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" />
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button @click="submitUpdate"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Update Transaction
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
