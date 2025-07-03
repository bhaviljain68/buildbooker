<script setup>
import { ref } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import BackButton from '@/components/BackButton.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Icon } from '@iconify/vue';
import ButtonLink from '@/components/ButtonLink.vue'
const toast = new ToastMagic();

const { props } = usePage()
const project = props.project

const getDueAmount = (customer) => {
    return customer.units.reduce((sum, unit) => {
        const used = unit.transactions.reduce((tSum, t) => tSum + t.transaction_amount, 0)
        return sum + (unit.total_amount - used)
    }, 0)
}

function confirmDelete(customer) {
    {
        router.delete(route('customers.destroy', customer.id), {
            onSuccess: () => {
                toast.success("Delete Customer successfully!");
                router.visit(route('customers.index', project.id));
            },
            onError: () => {
                 toast.error("Failed to delete customer. Please try again.");
            },
        })
    }
}

</script>
<template>
    <AppLayout>
        <div class="py-0 lg:py-10">
            <div class="w-full lg:!w-[80%] mx-auto sm:px-6 lg:px-8">

                <!-- Back Button -->
                <div class="flex justify-between w-full mb-4">
                    <BackButton :prevRoute="route('projects.index')" />
                    <!-- <p>{{ route('customers.create', project.id) }}</p> -->
                    <ButtonLink icon="ic:twotone-add" :href="route('customers.create', project.id)">Add Customer
                    </ButtonLink>

                </div>
                <div
                    class="bg-gray-100 mt-10 overflow-hidden shadow-md sm:rounded-lg px-4 text-gray-800 py-8 border-t-4 border-primary">
                    <!-- Title -->
                    <div class="flex items-center justify-between py-4 mb-8 border-gray-200">
                        <h6 class="m-0 text-primary font-bold text-center lg:text-3xl w-full">
                            Customers for <b>{{ project.name }}</b>
                        </h6>
                    </div>

                    <!-- Table Headers -->
                    <div class="grid grid-cols-6 rounded-t-lg">
                        <div
                            class="col-span-2 font-black bg-secondary text-zinc-100 border-x py-4 px-2 flex justify-center items-center rounded-tl-lg">
                            Name</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Unit No.</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Mobile</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Total Due</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center rounded-tr-lg">
                            Actions
                        </div>

                        <!-- Customer Rows -->
                        <template v-if="project.customers && project.customers.length">
                            <template v-for="customer in project.customers" :key="customer.id">
                                <div
                                    class="col-span-2 border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center">
                                    {{ customer.name }}
                                </div>
                                <div
                                    class="border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center">
                                    <span v-if="customer.units.length === 1">{{ customer.units[0].unit_no }}</span>
                                    <span v-else>{{customer.units.map(u => u.unit_no).join(', ')}}</span>
                                </div>
                                <div
                                    class="border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center">
                                    {{ customer.mobile }}
                                </div>
                                <div
                                    class="border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center">
                                    ₹{{ getDueAmount(customer).toLocaleString('en-IN') }}
                                </div>
                                <div class="border-r border-b border-gray-300 py-1 flex justify-center items-center">
                                    <div class="flex flex-row gap-x-2">
                                        <Link :href="route('customers.edit', customer.id)"
                                            class="btn-sm bg-green-800 text-white rounded px-2 py-1">
                                        <Icon icon="lucide:edit" width="20" height="20" />
                                        </Link>
                                        <button @click="confirmDelete(customer)"
                                            class="btn-sm bg-red-700 text-white rounded px-2 py-1">
                                            <Icon icon="mingcute:delete-fill" width="20" height="20" />
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <div v-else class="col-span-6 p-4 flex justify-center">
                            <h1 class="text-yellow-600 text-2xl">No Customer!</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
