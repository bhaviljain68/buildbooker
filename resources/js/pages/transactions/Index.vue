<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ButtonLink from '@/components/ButtonLink.vue';
import BackButton from '@/components/BackButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
// import Receipt_bs from '@/components/receipt_bs.vue';
import ReceiptModel from '@/components/ReceiptModel.vue';

const props = defineProps(['transactions','project','organisation']);
console.log('Transactions:', props.transactions);

function formatCurrency(amount) {
  return new Intl.NumberFormat('en-IN', {
    style: 'currency',
    currency: 'INR',
    maximumFractionDigits: 0 // Removes `.00`
  }).format(amount);
}
</script>

<template>
    <AppLayout>
        <div class="py-0 lg:py-10">
            <div class="w-full lg:w-[80%] mx-auto sm:px-6 lg:px-8">
                <div class="mb-4 flex justify-between items-center">
                    <BackButton :prevRoute="route('projects.index')" />
                    <ButtonLink v-if="project?.id" icon="ic:twotone-add"
                        :route="route('transactions.create', project.id)">
                        Add Transaction
                    </ButtonLink>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg px-8">
                  
                    <div class="text-gray-800 py-8">
                        <div class="flex flex-col items-center justify-center py-4 border-gray-200 mb-8">
                            <img :src="project?.logo" :alt="`${project?.name}_logo`" class="max-h-10" />
                            <h6
                                class="text-cyan-700 font-bold text-center lg:text-3xl w-full underline underline-offset-8 mt-4">
                                Project Transactions
                            </h6>
                        </div>

                        <div class="grid grid-cols-8 rounded-t-lg border-b rounded-b-lg">
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center rounded-tl-lg">Unit
                                No.
                            </p>
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center">Date</p>
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center">Instrument</p>
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center">Amount</p>
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center">GST</p>
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center">Receipt No.</p>
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center">Receipt</p>
                            <p class="bg-gray-800 font-black text-zinc-100 border-r py-4 text-center rounded-tr-lg">
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
                                        {{ transaction.receipt_number  }}
                                    </p>
                                    <div class="border-b py-2 border-r border-gray-300 text-center">
                                        <!-- <pre>{{ transaction.customer }}</pre> -->
                                        <ReceiptModel :project="project" :transaction="transaction" size="sm" />
                                    </div>
                                    <div
                                        class="border-b py-2 border-r border-gray-300 flex justify-center items-center gap-x-2 last:rounded-br-lg">
                                        <ButtonLink
                                            :route="route('transactions.edit', [transaction.id, unit.id, project.id])"
                                            icon="mage:edit" status="info">

                                        </ButtonLink>
                                        <a :href="route('unit-unbook', unit.id)"
                                            @click.prevent="() => { if (confirm('Are you sure you want to delete this transaction?')) window.location.href = route('unit-unbook', unit.id) }">
                                            <ButtonLink icon="mingcute:delete-fill" status="error" />
                                        </a>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
