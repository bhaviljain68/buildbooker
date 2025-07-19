<!-- <script setup>
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
    if (!customer.units || !Array.isArray(customer.units)) return 0

    return customer.units.reduce((sum, unit) => {
        const transactions = Array.isArray(unit.transactions) ? unit.transactions : []
        const used = transactions.reduce((tSum, t) => tSum + (t.transaction_amount || 0), 0)
        return sum + ((unit.total_amount || 0) - used)
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
        <div class="py-0 lg:py-10 mt-10 lg:mt-0">
            <div class="w-full lg:!w-[80%] mx-auto px-6 lg:px-8">

                <div class="flex justify-between w-full mb-4">
                    <BackButton :prevRoute="route('projects.index')" />
                    <ButtonLink v-if="project" icon="ic:twotone-add" :href="route('customers.create', project.id)">Add
                        Customer
                    </ButtonLink>

                </div>
                <div
                    class="bg-gray-100 mt-10 overflow-hidden shadow-md rounded-lg px-4 text-gray-800 py-8 border-t-4 border-primary">
                    <div class="flex items-center justify-between py-4 mb-8 border-gray-200">
                        <h6 class="m-0 text-primary font-bold text-center lg:text-3xl w-full">
                            Customers for <b>{{ project.name }}</b>
                        </h6>
                    </div>

                    <div class="hidden lg:grid grid-cols-6 rounded-t-lg">
                        <div
                            class="col-span-2 font-black bg-secondary text-zinc-100 border-x py-4 px-2 flex justify-center items-center rounded-tl-lg">
                            Name</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Unit No.</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Mobile</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Total Due</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center rounded-tr-lg">
                            Actions
                        </div>

                        <template v-if="project.customers && project.customers.length">
                            <template v-for="customer in project.customers" :key="customer.id">
                                <div
                                    class="col-span-2 border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center">
                                    {{ customer.name }}
                                </div>
                                <div class="border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center"
                                    v-if="customer.units && Array.isArray(customer.units)">

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

                    <div class="block lg:hidden space-y-4 mt-6">
                        <template v-if="project.customers && project.customers.length">
                            <div v-for="customer in project.customers" :key="customer.id"
                                class="bg-white rounded-lg shadow border">
                                <div class="bg-secondary text-white rounded-t-lg px-4 py-2 font-bold">
                                    {{ customer.name }}
                                </div>
                                <div class="p-4 text-sm space-y-2 text-gray-800">
                                    <p><strong>Unit No.:</strong>
                                        <span v-if="customer.units?.length === 1">{{ customer.units[0].unit_no }}</span>
                                        <span v-else>{{customer.units?.map(u => u.unit_no).join(', ')}}</span>
                                    </p>
                                    <p><strong>Mobile:</strong> {{ customer.mobile }}</p>
                                    <p><strong>Total Due:</strong> ₹{{ getDueAmount(customer).toLocaleString('en-IN') }}
                                    </p>
                                </div>
                                <div class="px-4 pb-4 flex justify-end gap-3">
                                    <Link :href="route('customers.edit', customer.id)"
                                        class="bg-green-700 hover:bg-green-800 text-white rounded px-3 py-1 text-sm flex items-center gap-1">
                                    <Icon icon="lucide:edit" width="18" height="18" />

                                    </Link>
                                    <button @click="confirmDelete(customer)"
                                        class="bg-red-700 hover:bg-red-800 text-white rounded px-3 py-1 text-sm flex items-center gap-1">
                                        <Icon icon="mingcute:delete-fill" width="18" height="18" />

                                    </button>
                                </div>
                            </div>
                        </template>

                        <div v-else class="p-4 text-center text-yellow-600 text-lg font-medium">
                            No Customer!
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template> -->
<script setup>
import { ref } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import BackButton from '@/components/BackButton.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'
import ButtonLink from '@/components/ButtonLink.vue'

const toast = new ToastMagic()

const props = defineProps(['project', 'organisation', 'customers'])
const { project, organisation } = props

const getDueAmount = (customer) => {
    if (!customer.units || !Array.isArray(customer.units)) return 0

    return customer.units.reduce((sum, unit) => {
        const transactions = Array.isArray(unit.transactions) ? unit.transactions : []
        const used = transactions.reduce((tSum, t) => tSum + (t.transaction_amount || 0), 0)
        return sum + ((unit.total_amount || 0) - used)
    }, 0)
}

function confirmDelete(customer) {
    router.delete(route('customers.destroy', customer.id), {
        onSuccess: () => {
            toast.success('Delete Customer successfully!')
            if (project) {
                router.visit(route('customers.index', project.id))
            } else {
                router.visit(route('org-customers', props.organisation.id))
            }
        },
        onError: () => {
            toast.error('Failed to delete customer. Please try again.')
        },
    })
}
</script>

<template>
    <AppLayout>
        <div class="py-0 lg:py-10 mt-10 lg:mt-0">
            <div class="w-full lg:!w-[80%] mx-auto px-6 lg:px-8">

                <!-- Header -->
                <div class="flex justify-between w-full mb-4">
                    <BackButton :prevRoute="route(project ? 'projects.index' : 'dashboard')" />
                    <ButtonLink v-if="project" icon="ic:twotone-add" :href="route('customers.create', project.id)">
                        Add Customer
                    </ButtonLink>
                </div>

                <!-- Card -->
                <div
                    class="bg-gray-100 mt-10 overflow-hidden shadow-md rounded-lg px-4 text-gray-800 py-8 border-t-4 border-primary">
                    <div class="flex items-center justify-between py-4 mb-8 border-gray-200">
                        <h6 class="m-0 text-primary font-bold text-center lg:text-3xl w-full">
                            Customers for <b>{{ project?.name ?? 'All Projects' }}</b>
                        </h6>
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden lg:grid grid-cols-6 rounded-t-lg">
                        <div
                            class="col-span-2 font-black bg-secondary text-zinc-100 border-x py-4 px-2 flex justify-center items-center rounded-tl-lg">
                            Name</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Unit No.</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Mobile</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center">Total Due</div>
                        <div class="font-black bg-secondary text-zinc-100 border-r py-4 text-center rounded-tr-lg">
                            Actions</div>

                        <template v-if="customers.length">
                            <template v-for="customer in customers" :key="customer.id">
                                <div
                                    class="col-span-2 border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center">
                                    {{ customer.name }}
                                    <!-- <p v-if="!project" class="text-xs text-gray-500 italic">
                                        Project: {{ customer.units[0]?.project?.name ?? '-' }}
                                    </p> -->
                                </div>
                                <div
                                    class="border-x border-b border-gray-300 py-2 flex flex-col justify-center items-center">
                                    <span v-if="customer.units?.length === 1">{{ customer.units[0].unit_no }}</span>
                                    <span v-else>{{customer.units?.map(u => u.unit_no).join(', ')}}</span>
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
                            <h1 class="text-yellow-600 text-2xl">No Customers!</h1>
                        </div>
                    </div>

                    <!-- Mobile Layout -->
                    <div class="block lg:hidden space-y-4 mt-6">
                        <template v-if="customers.length">
                            <div v-for="customer in customers" :key="customer.id"
                                class="bg-white rounded-lg shadow border">
                                <div class="bg-secondary text-white rounded-t-lg px-4 py-2 font-bold">
                                    {{ customer.name }}
                                    <div v-if="!project" class="text-xs text-gray-200 italic">
                                        Project: {{ customer.units[0]?.project?.name ?? '-' }}
                                    </div>
                                </div>
                                <div class="p-4 text-sm space-y-2 text-gray-800">
                                    <p><strong>Unit No.:</strong> {{customer.units?.map(u => u.unit_no).join(', ')}}
                                    </p>
                                    <p><strong>Mobile:</strong> {{ customer.mobile }}</p>
                                    <p><strong>Total Due:</strong> ₹{{ getDueAmount(customer).toLocaleString('en-IN') }}
                                    </p>
                                </div>
                                <div class="px-4 pb-4 flex justify-end gap-3">
                                    <Link :href="route('customers.edit', customer.id)"
                                        class="bg-green-700 hover:bg-green-800 text-white rounded px-3 py-1 text-sm flex items-center gap-1">
                                    <Icon icon="lucide:edit" width="18" height="18" />
                                    </Link>
                                    <button @click="confirmDelete(customer)"
                                        class="bg-red-700 hover:bg-red-800 text-white rounded px-3 py-1 text-sm flex items-center gap-1">
                                        <Icon icon="mingcute:delete-fill" width="18" height="18" />
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div v-else class="p-4 text-center text-yellow-600 text-lg font-medium">
                            No Customers!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
