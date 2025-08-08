<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import ButtonLink from '@/components/ButtonLink.vue';
import BackButton from '@/components/BackButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
const toast = new ToastMagic();
import ReceiptModel from '@/components/ReceiptModel.vue';

const props = defineProps(['transactions', 'project', 'organisation', 'isProject']);

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

                if (projectId) {
                    router.visit(route('transactions.index', {
                        organisation: organisationId,
                        project: projectId,
                    }));
                } else {
                    router.visit(route('transactions.index', {
                        organisation: organisationId,
                    }));
                }
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
                    <BackButton v-if="project?.id" :prevRoute="route('projects.index')" />

                    <ButtonLink v-if="project?.id"
                        :href="route('transactions.create', project.id) + `?back=TransactionsIndex&organisation=${project.organisation_id}&project=${project.id}`"
                        icon="ic:twotone-add" status="primary">
                        Add Transaction
                    </ButtonLink>
                </div>
                <div
                    class="bg-gray-100 overflow-hidden lg:shadow-md rounded-lg px-4 lg:px-8 border-t-4 border-primary mt-10">
                    <div class="text-gray-800 py-8">

                        <div class="flex flex-col items-center justify-center border-gray-200 mb-8">
                            <template v-if="isProject">
                                <img :src="project?.logo" :alt="`${project?.name}_logo`" class="max-h-24" />
                                <h6 class="text-primary font-bold text-center lg:text-3xl w-full mt-4">
                                    Project Transactions
                                </h6>
                            </template>
                            <template v-else>

                                <h6 class="text-primary font-bold text-center lg:text-3xl w-full mt-4">
                                    All Organisation Transactions
                                </h6>
                            </template>
                        </div>

                        <div
                            :class="['hidden lg:grid', isProject ? 'grid-cols-8' : 'grid-cols-9', 'rounded-tl-lg border-b rounded-b-lg']">
                            <p v-if="!isProject"
                                class="bg-secondary font-black text-zinc-100 border-r py-4 text-center rounded-tl-lg">
                                Logo</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Unit No.</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Date</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Instrument</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Amount</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">GST</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Receipt No.</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center">Receipt</p>
                            <p class="bg-secondary font-black text-zinc-100 border-r py-4 text-center rounded-tr-lg">
                                Actions</p>


                            <template v-if="isProject" v-for="unit in project?.units" :key="unit.id">
                                <template v-for="transaction in unit.transactions" :key="transaction.id">
                                    <Link :href="route('units.show', unit.id)" :class="[
                                        'border-b py-2 border-x border-gray-300 text-center block',
                                        transaction.deleted_at ? ' line-through' : ''
                                    ]">
                                    {{ unit.unit_no }}
                                    </Link>

                                    <p :class="['border-b py-2 border-r border-gray-300 text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                        {{ new Date(transaction.payment_date).toLocaleDateString('en-GB', {
                                            day: '2-digit',
                                            month: 'short',
                                            year: 'numeric'
                                        }) }}
                                    </p>

                                    <p :class="['border-b py-2 border-r border-gray-300 text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                        {{ transaction.payment_type.charAt(0).toUpperCase() +
                                            transaction.payment_type.slice(1) }}
                                    </p>

                                    <div :class="['border-b py-2 border-r border-gray-300 text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                        {{ formatCurrency(transaction.transaction_amount) }}
                                    </div>

                                    <p :class="['border-b py-2 border-r border-gray-300 text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                        {{ transaction.gst ? 'Yes' : 'No' }}
                                    </p>

                                    <p :class="['border-b py-2 border-r border-gray-300 text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                        {{ transaction.receipt_number }}
                                    </p>

                                    <div :class="[
                                        'border-b py-2 border-r border-gray-300 text-center',
                                        transaction.deleted_at ? ' pointer-events-none' : ''
                                    ]">
                                        <ReceiptModel :project="project" :transaction="transaction" size="sm"
                                            :disabled="transaction.deleted_at" />
                                    </div>

                                    <div :class="['border-b py-2 px-5 border-r border-gray-300 flex justify-center items-center gap-x-2 last:rounded-br-lg',
                                        transaction.deleted_at ? 'line-through' : '']">
                                        <!-- <ButtonLink
                                            :route="route('transactions.edit', [transaction.id, unit.id, project.id])"
                                            icon="mage:edit" status="info" /> -->
                                        <Link :href="route('transactions.edit', [transaction.id, unit.id, project.id])">
                                        <button :class="[
                                            'flex items-center justify-center gap-2 text-sm font-semibold py-2 px-4 rounded-lg transition duration-200',
                                            transaction.deleted_at
                                                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                : 'bg-primary text-white hover:bg-primary/90'
                                        ]" :disabled="transaction.deleted_at">
                                            <Icon icon="mage:edit" width="18" height="18" />
                                            
                                        </button>
                                        </Link>
                                        <!-- <ButtonLink icon="mingcute:delete-fill" status="error"
                                            @click="deleteTransaction(transaction.id, organisation.id, project.id)" /> -->
                                            <button @click="deleteTransaction(transaction.id, organisation.id, project.id)"
                                            :disabled="transaction.deleted_at"
                                            :class="[
                                                'flex items-center justify-center gap-2 text-sm font-semibold py-2 px-4 rounded-lg transition duration-200',
                                                transaction.deleted_at
                                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                    : 'bg-red-600 text-white hover:bg-red-700'
                                            ]">
                                            <Icon icon="mingcute:delete-fill" width="18" height="18" />
                                        </button>
                                    </div>
                                </template>
                            </template>


                            <template v-else v-for="transaction in props.transactions" :key="transaction.id">
                                <div class="border-b border border-gray-300 flex items-center justify-center py-2">
                                    <img :src="transaction.project?.logo" :alt="`${transaction.project?.name} logo`"
                                        class="max-h-16 max-w-[80px] object-contain" />
                                </div>

                                <Link :href="route('units.show', transaction.unit_id)"
                                    :class="['border-b py-2 border-x border-gray-300 flex items-center justify-center text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                {{ transaction.unit?.unit_no ?? 'N/A' }}
                                </Link>

                                <p
                                    :class="['border-b py-2 border-x border-gray-300 flex items-center justify-center text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                    {{ new Date(transaction.payment_date).toLocaleDateString('en-GB', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    }) }}
                                </p>

                                <p
                                    :class="['border-b py-2 border-x border-gray-300 flex items-center justify-center text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                    {{ transaction.payment_type.charAt(0).toUpperCase() +
                                        transaction.payment_type.slice(1) }}
                                </p>
                                <div
                                    :class="['border-b py-2 border-x border-gray-300 flex items-center justify-center text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                    {{ formatCurrency(transaction.transaction_amount) }}
                                </div>

                                <p
                                    :class="['border-b py-2 border-x border-gray-300 flex items-center justify-center text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                    {{ transaction.gst ? 'Yes' : 'No' }}
                                </p>

                                <p
                                    :class="['border-b py-2 border-x border-gray-300 flex items-center justify-center text-center',
                                        transaction.deleted_at ? 'line-through' : '']">
                                    {{ transaction.receipt_number }}
                                </p>

                                <div
                                    class="border-b py-2 border-x border-gray-300 flex items-center justify-center text-center">
                                    <ReceiptModel :project="project" :transaction="transaction" size="sm" />
                                </div>

                                <!-- Actions -->
                                <div
                                    class="border-b py-2 px-3 border-r border-gray-300 flex justify-center items-center gap-x-2 last:rounded-br-lg">

                                    <!-- <ButtonLink :route="route('transactions.edit', [
                                        transaction.id,
                                        transaction.unit_id,
                                        transaction.project_id || project?.id
                                    ])" icon="mage:edit" status="info" /> -->

                                    <Link :href="route('transactions.edit', [
                                        transaction.id,
                                        transaction.unit_id,
                                        transaction.project_id || project?.id
                                    ])">
                                        <button :class="[
                                            'flex items-center justify-center gap-2 text-sm font-semibold py-2 px-4 rounded-lg transition duration-200',
                                            transaction.deleted_at
                                                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                : 'bg-primary text-white hover:bg-primary/90'
                                        ]" :disabled="transaction.deleted_at">
                                            <Icon icon="mage:edit" width="18" height="18" />
                                        </button>
                                    </Link>
                                    <!-- <ButtonLink icon="mingcute:delete-fill" status="error"
                                        @click="deleteTransaction(transaction.id, organisation.id)" /> -->
                                        <button @click="deleteTransaction(transaction.id, organisation.id)"
                                            :disabled="transaction.deleted_at"
                                            :class="[
                                                'flex items-center justify-center gap-2 text-sm font-semibold py-2 px-4 rounded-lg transition duration-200',
                                                transaction.deleted_at
                                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                    : 'bg-red-600 text-white hover:bg-red-700'
                                            ]">
                                            <Icon icon="mingcute:delete-fill" width="18" height="18" />
                                        </button>
                                </div>

                            </template>
                        </div>


                        <!-- mobile view -->
                        <div class="lg:hidden space-y-4 mt-6">

                            <template v-if="isProject">
                                <template v-for="unit in project?.units" :key="unit.id">
                                    <template v-for="transaction in unit.transactions" :key="transaction.id">
                                        <div class="bg-white shadow-md rounded-lg border">
                                            <p class="font-semibold text-lg text-white bg-primary p-2 rounded-t-lg">Unit
                                                No.
                                                <Link :href="route('units.show', unit.id)"
                                                    class="text-lg font-bold text-white">
                                                {{ unit.unit_no }}
                                                </Link>
                                            </p>
                                            <div class="p-4">
                                                <div class="font-semibold text-lg text-gray-600 mt-2 flex">Date :
                                                    <p class="ml-3">{{ new
                                                        Date(transaction.payment_date).toLocaleDateString('en-GB',
                                                            {
                                                                day: '2-digit', month: 'short', year: 'numeric'
                                                            }) }}</p>
                                                </div>

                                                <div class="font-semibold text-lg text-gray-600 mt-2 flex">Instrument :
                                                    <p class="ml-3">{{ transaction.payment_type.charAt(0).toUpperCase()
                                                        +
                                                        transaction.payment_type.slice(1) }}</p>
                                                </div>

                                                <div class="font-semibold text-lg text-gray-600 mt-2 flex">Amount :
                                                    <p class="ml-3">{{ formatCurrency(transaction.transaction_amount) }}
                                                    </p>
                                                </div>

                                                <div class="font-semibold text-lg text-gray-600 mt-2 flex">GST :
                                                    <p class="ml-3">{{ transaction.gst ? 'Yes' : 'No' }}</p>
                                                </div>

                                                <div class="font-semibold text-lg text-gray-600 mt-2 flex">Receipt No :
                                                    <p class="ml-3">{{ transaction.receipt_number }}</p>
                                                </div>

                                                <div class="font-semibold text-lg text-gray-600 mt-2 flex">Receipt :
                                                    <ReceiptModel :project="project" :transaction="transaction"
                                                        size="sm" class="ml-3" />
                                                </div>

                                                <div class="mt-4 flex justify-end space-x-3">
                                                    <ButtonLink
                                                        :route="route('transactions.edit', [transaction.id, unit.id, project.id])"
                                                        icon="mage:edit" status="info" />
                                                    <ButtonLink icon="mingcute:delete-fill" status="error"
                                                        @click="deleteTransaction(transaction.id, organisation.id, project.id)" />
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </template>


                            <template v-else>
                                <template v-for="transaction in props.transactions" :key="transaction.id">
                                    <div class="bg-white shadow-md rounded-lg border p-4">
                                        <div class="flex justify-center mb-4">
                                            <img :src="transaction.project?.logo"
                                                :alt="`${transaction.project?.name} logo`"
                                                class="max-h-16 max-w-[100px] object-contain" />
                                        </div>

                                        <div class="font-semibold text-lg text-gray-600">Unit No :
                                            <Link :href="route('units.show', transaction.unit_id)"
                                                class="text-lg font-bold text-primary ml-3">
                                            {{ transaction.unit?.unit_no ?? 'N/A' }}
                                            </Link>
                                        </div>

                                        <div class="font-semibold text-lg text-gray-600 mt-2 flex">Date :
                                            <p class="ml-3">{{ new
                                                Date(transaction.payment_date).toLocaleDateString('en-GB', {
                                                    day: '2-digit', month: 'short', year: 'numeric'
                                                }) }}</p>
                                        </div>

                                        <div class="font-semibold text-lg text-gray-600 mt-2 flex">Instrument :
                                            <p class="ml-3">{{ transaction.payment_type.charAt(0).toUpperCase() +
                                                transaction.payment_type.slice(1) }}</p>
                                        </div>

                                        <div class="font-semibold text-lg text-gray-600 mt-2 flex">Amount :
                                            <p class="ml-3">{{ formatCurrency(transaction.transaction_amount) }}</p>
                                        </div>

                                        <div class="font-semibold text-lg text-gray-600 mt-2 flex">GST :
                                            <p class="ml-3">{{ transaction.gst ? 'Yes' : 'No' }}</p>
                                        </div>

                                        <div class="font-semibold text-lg text-gray-600 mt-2 flex">Receipt No :
                                            <p class="ml-3">{{ transaction.receipt_number }}</p>
                                        </div>

                                        <div class="font-semibold text-lg text-gray-600 mt-2 flex">Receipt :
                                            <ReceiptModel :project="project" :transaction="transaction" size="sm"
                                                class="ml-3" />
                                        </div>

                                        <div class="mt-4 flex justify-end space-x-3">
                                            <ButtonLink :route="route('transactions.edit', [
                                                transaction.id,
                                                transaction.unit_id,
                                                transaction.project_id || project?.id
                                            ])" icon="mage:edit" status="info" />
                                            <ButtonLink icon="mingcute:delete-fill" status="error"
                                                @click="deleteTransaction(transaction.id, organisation.id)" />
                                        </div>
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
