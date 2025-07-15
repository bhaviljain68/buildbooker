<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import ButtonLink from '@/components/ButtonLink.vue';
import BackButton from '@/components/BackButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
const toast = new ToastMagic();
import ReceiptModel from '@/components/ReceiptModel.vue';

const props = defineProps(['transactions', 'project', 'organisation']);

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0
    }).format(amount);
}
function deleteTransaction(transactionId, organisationId, projectId) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        router.visit(route('delete-transaction', transactionId), {
            method: 'get',
            onSuccess: () => {
                toast.success("Transaction deleted successfully!");
                router.visit(route('transactions.index', {
                    organisation: organisationId,
                    project: projectId,
                }));
            },
            onError: () => {
                toast.error("Failed to delete transaction.");
            }
        });
    }
}

</script>

<template>
    <AppLayout>
        <div class="py-5 lg:py-10 mt-3 lg:mt-0">
            <div class="w-full lg:w-[80%] mx-auto px-6 lg:px-8">
                <div class="mb-4 flex justify-between items-center">
                    <BackButton :prevRoute="route('projects.index')" />
                    <ButtonLink v-if="project?.id" icon="ic:twotone-add"
                        :route="route('transactions.create', project.id)">
                        Add Transaction
                    </ButtonLink>
                </div>

                <div
                    class="bg-gray-100 overflow-hidden lg:shadow-md rounded-lg px-4 lg:px-8 border-t-4 border-primary mt-10">

                    <div class="text-gray-800 py-8">
                        <div class="flex flex-col items-center justify-center border-gray-200 mb-8">
                            <img :src="project?.logo" :alt="`${project?.name}_logo`" class="max-h-24" />
                            <h6 class="text-primary font-bold text-center lg:text-3xl w-full mt-4">
                                Project Transactions
                            </h6>
                        </div>

                        <!-- dasktop  -->
                        <div class="hidden lg:grid grid-cols-8 rounded-t-lg border-b rounded-b-lg">
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center rounded-tl-lg">
                                Unit
                                No.
                            </p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Date</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Instrument</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Amount</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">GST</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Receipt No.</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Receipt</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center rounded-tr-lg">
                                Actions
                            </p>

                            <template v-for="unit in project?.units" :key="unit.id">
                                <template v-for="transaction in unit.transactions" :key="transaction.id">
                                    <p class="border-b py-2 border-x border-gray-300 text-center">{{ unit.unit_no }}</p>
                                    <p class="border-b py-2 border-r border-gray-300 text-center">
                                        {{ new Date(transaction.payment_date).toLocaleDateString('en-GB', {
                                            day: '2-digit',
                                            month: 'short', year: 'numeric'
                                        }) }}
                                    </p>
                                    <p class="border-b py-2 border-r border-gray-300 text-center">
                                        {{ transaction.payment_type.replace('_', ' ').toUpperCase() }}
                                    </p>
                                    <div class="border-b py-2 border-r border-gray-300 text-center">
                                        {{ formatCurrency(transaction.transaction_amount) }}
                                    </div>
                                    <p class="border-b py-2 border-r border-gray-300 text-center">
                                        {{ transaction.gst }}
                                    </p>

                                    <!-- {{  }} -->
                                    <p class="border-b py-2 border-r border-gray-300 text-center">
                                        {{ transaction.receipt_number }}
                                    </p>
                                    <div class="border-b py-2 border-r border-gray-300 text-center">
                                        <!-- <pre>{{ transaction.customer }}</pre> -->
                                        <ReceiptModel :project="project" :transaction="transaction" size="sm" />
                                    </div>
                                    <div
                                        class="border-b py-2 px-5 border-r border-gray-300 flex justify-center items-center gap-x-2 last:rounded-br-lg">
                                        <ButtonLink
                                            :route="route('transactions.edit', [transaction.id, unit.id, project.id])"
                                            icon="mage:edit" status="info">
                                        </ButtonLink>

                                        <ButtonLink icon="mingcute:delete-fill" status="error"
                                            @click="deleteTransaction(transaction.id, organisation.id, project.id)" />
                                    </div>
                                </template>
                            </template>
                        </div>

                        <!-- mobile  -->

                        <div class="block lg:hidden space-y-4 mt-6">
                            <div v-for="unit in project?.units" :key="unit.id">
                                <div v-for="transaction in unit.transactions" :key="transaction.id">
                                    <div class="bg-white rounded-lg shadow border overflow-hidden">
                                        <!-- Header -->
                                        <div class="bg-secondary text-white px-4 py-2 font-bold">
                                            Unit: {{ unit.unit_no }}
                                        </div>

                                        <!-- Transaction Details -->
                                        <div class="px-4 py-2 text-sm space-y-2 text-gray-800">
                                            <p>
                                                <strong>Date:</strong>
                                                {{ new Date(transaction.payment_date).toLocaleDateString('en-GB', {
                                                    day: '2-digit',
                                                    month: 'short',
                                                    year: 'numeric'
                                                }) }}
                                            </p>

                                            <p>
                                                <strong>Instrument:</strong>
                                                {{ transaction.payment_type.replace('_', ' ').toUpperCase() }}
                                            </p>

                                            <p>
                                                <strong>Amount:</strong>
                                                {{ formatCurrency(transaction.transaction_amount) }}
                                            </p>

                                            <p>
                                                <strong>GST:</strong>
                                                {{ transaction.gst }}
                                            </p>

                                            <p>
                                                <strong>Receipt No.:</strong>
                                                {{ transaction.receipt_number }}
                                            </p>


                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="px-4 py-3 flex justify-between gap-3 border-t">
                                            <div>
                                                <!-- <strong>Receipt:</strong> -->
                                                <ReceiptModel :project="project" :transaction="transaction" size="sm" />
                                            </div>
                                            <div class="flex gap-4">
                                                <ButtonLink
                                                    :route="route('transactions.edit', [transaction.id, unit.id, project.id])"
                                                    icon="mage:edit" status="info" />
                                                <ButtonLink icon="mingcute:delete-fill" status="error"
                                                    @click="deleteTransaction(transaction.id, organisation.id, project.id)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
