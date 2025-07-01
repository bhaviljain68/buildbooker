<script setup>
import { reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import BackButton from '@/components/BackButton.vue'
import FormInput from '@/components/FormInput.vue'

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

            <div class="flex justify-between max-w-4xl w-full mx-auto mb-4">
                <BackButton :prevRoute="route('projects.index')" />
            </div>
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-0 mt-10">
                <div class="bg-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6 border-t-4 border-primary">
                    <h2 class="text-2xl font-bold text-center text-primary mb-6">
                        Edit Transaction for Unit {{ unit.unit_no }}
                    </h2>

                    <div class="p-6 bg-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Payment Date -->
                            <div>
                                <!-- <label class="block font-medium text-gray-700 mb-1">
                                    Date Of Payment
                                </label> -->
                                <FormInput label="Date of Payment" hint="(This will be displayed on your
                                            receipts)" v-model="form.payment_date" type="date"
                                    :error="form.errors.payment_date" />
                                <!-- <input v-model="form.payment_date" type="date"
                                class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" /> -->
                            </div>

                            <!-- Receipt Date -->
                            <div>
                                <!-- <label class="block font-medium text-gray-700 mb-1">
                                    Date Of Receipt
                                </label>
                                <input v-model="form.receipt_date" type="date"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" /> -->
                                <FormInput label="Date of Receipt" hint="(This will be displayed on your
                                            receipts)" v-model="form.receipt_date" type="date"  />
                            </div>

                            <!-- Transaction Amount -->
                            <div>
                                <!-- <label class="block font-medium text-gray-700 mb-1">
                                    Transaction Amount
                                </label>
                                <input v-model="form.transaction_amount" type="text" placeholder="Transaction Amount"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" /> -->
                                    <FormInput label="Transaction Amount" hint="(This will be displayed on your
                                            receipts)" v-model="form.transaction_amount" type="text"/>
                            </div>

                            <!-- GST -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">GST</label>
                                <select v-model="form.gst"
                                    class="w-full border border-gray-300 rounded-md px-4 py-3 bg-white focus:ring focus:ring-cyan-100 focus:outline-none">
                                    <option disabled :value="null">Select</option>
                                    <option :value="true">GST</option>
                                    <option :value="false">Non-GST</option>
                                </select>
                            </div>

                            <!-- Payment Type -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Payment Type</label>
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
                                <!-- <label class="block font-medium text-gray-700 mb-1">
                                    Payment Reference
                                </label>
                                <input v-model="form.payment_reference" :disabled="form.payment_type === 'cash'"
                                    placeholder="Reference No"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none disabled:bg-gray-100" /> -->
                                    <FormInput label="Payment Reference No" hint="(Not required for cash)"
                                        v-model="form.payment_reference" type="text" :disabled="form.payment_type === 'cash'" />
                            </div>

                            <!-- Bank Name -->
                            <div>
                                <!-- <label class="block font-medium text-gray-700 mb-1">Bank Name</label>
                                <input v-model="form.bank_name" placeholder="Bank Name"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" /> -->
                                    <FormInput label="Bank Name" hint="(This will be displayed on your
                                            receipts)" v-model="form.bank_name" type="text"/>
                            </div>

                            <!-- Bank Branch -->
                            <div>
                                <!-- <label class="block font-medium text-gray-700 mb-1">Bank Branch</label>
                                <input v-model="form.bank_branch" placeholder="Bank Branch"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-cyan-100 focus:outline-none" /> -->
                                    <FormInput label="Bank Branch" hint="(This will be displayed on your
                                            receipts)" v-model="form.bank_branch" type="text"/>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button @click="submitUpdate"
                                class="bg-primary w-full text-white px-6 py-2 rounded-lg hover:bg-teal-700">
                                Update Transaction
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
