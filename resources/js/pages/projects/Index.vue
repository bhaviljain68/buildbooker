<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import ButtonLink from '@/components/ButtonLink.vue';
const props = defineProps(['projects']);
</script>

<template>
    <AppLayout>
        <div class="py-0 lg:py-10">
            <div class="w-full lg:!w-[80%] mx-auto px-6 py-5 lg:py-0 lg:px-8 mt-3 lg:mt-0">
                <!-- Add Button -->
                <div class="mb-4 flex justify-end items-center">

                    <ButtonLink icon="ic:twotone-add" class=" bg-primary" :route="route('projects.create')">
                        Add New Project
                    </ButtonLink>

                </div>

                <!-- Flash Messages -->
                <div class="bg-gray-100 mt-10 overflow-hidden shadow-md rounded-lg px-4 border-t-4 border-primary">
                    <div class="text-gray-900">

                        <!-- Header -->
                        <div class="text-gray-800 py-8">
                            <div class="flex items-center justify-between py-4 border-gray-200 mb-8">
                                <h6
                                    class="m-0 text-primary font-bold text-center lg:text-3xl w-full underline underline-offset-8">
                                    Your Projects
                                </h6>
                            </div>

                            <!-- Table Header -->
                            <!-- dashtop view -->
                            <div class="hidden lg:grid grid-cols-8 font-black rounded-t-lg bg-secondary text-zinc-100">
                                <p
                                    class="border-x py-4 border-l-gray-800 border-zinc-100 col-span-2 flex justify-center gap-x-4 px-2 items-center rounded-tl-lg">
                                    Project Name
                                </p>
                                <p class="border-r py-4 border-zinc-100 text-center">Units (Sold / Unsold)</p>
                                <p class="border-r py-4 border-zinc-100 text-center">Units</p>
                                <p class="border-r py-4 border-zinc-100 text-center">Customers</p>
                                <p class="border-r py-4 border-zinc-100 text-center">Sell</p>
                                <p class="border-r py-4 border-zinc-100 text-center">Transactions</p>
                                <p class="border-r py-4 border-gray-800 text-center rounded-tr-lg">Project</p>
                            </div>

                            <!-- Table Data -->
                            <div class="hidden lg:grid grid-cols-8 rounded-b-lg border-b border-gray-300">
                                <template v-for="project in projects" :key="project.id">
                                    <!-- Project Name & Logo -->
                                    <div
                                        class="border-x border-gray-300 col-span-2 flex flex-col justify-center gap-y-2 py-2 items-center rounded-bl-lg">
                                        <img :src="project.logo" :alt="`${project.name}_logo`" class="max-h-10" />
                                        <span class="font-bold text-lg">{{ project.name }}</span>
                                    </div>

                                    <!-- Sold / Unsold -->
                                    <p class="border-r py-1 border-gray-300 flex justify-center items-center">
                                        <span class="font-bold">{{ project.total_units }}</span>&nbsp;
                                        (<span class="text-green-700 mx-1">{{ project.sold_units }}</span>/
                                        <span class="text-red-700 mx-1">{{ project.total_units - project.sold_units
                                        }}</span>)
                                    </p>

                                    <!-- Units -->
                                    <div class="border-r py-1 border-gray-300 flex justify-center items-center">
                                        <ButtonLink icon="lets-icons:view-alt-fill"
                                            :route="route('units.index', [project.organisation_id, project.id])">
                                            View
                                        </ButtonLink>
                                    </div>

                                    <!-- Customers -->
                                    <div class="border-r py-1 border-gray-300 flex justify-center items-center">
                                        <ButtonLink icon="material-symbols:manage-accounts"
                                            :route="route('customers.index', [project.id])">
                                            Manage
                                        </ButtonLink>
                                    </div>

                                    <!-- Sell -->
                                    <div class="border-r py-1 border-gray-300 flex justify-center items-center">
                                        <ButtonLink icon="material-symbols:add-home-rounded"
                                            :route="route('transactions.create', project.id)">
                                            Add
                                        </ButtonLink>
                                    </div>

                                    <!-- Transactions -->
                                    <div class="border-r py-1 border-gray-300 flex justify-center items-center">
                                        <ButtonLink icon="lets-icons:view-alt-fill"
                                            :route="route('transactions.index', [project.organisation_id, project.id])">
                                            View
                                        </ButtonLink>
                                    </div>

                                    <!-- Manage -->
                                    <div
                                        class="border-r py-1 border-gray-300 flex justify-center items-center rounded-br-lg">
                                        <ButtonLink icon="material-symbols:settings"
                                            :route="route('projects.edit', project.id)">
                                            Manage
                                        </ButtonLink>
                                    </div>
                                </template>
                            </div>

                            <!-- mobile view show the card  -->
                            <div class="lg:hidden space-y-6 mt-4" v-for="project in projects" :key="project.id">
                                <div class="bg-white shadow-md rounded-lg p-4 border">
                                    <div class="flex flex-col items-center justify-center text-center gap-2 mb-4">
                                        <!-- Logo -->
                                        <img :src="project.logo" :alt="`${project.name}_logo`"
                                            class="h-12 w-12 object-contain rounded-md" />

                                        <!-- Project Name -->
                                        <h2 class="font-semibold text-base sm:text-lg text-gray-800 truncate">
                                            {{ project.name }}
                                        </h2>
                                    </div>



                                    <p class="text-sm font-semibold mb-1">
                                        Units (Sold / Unsold):
                                        <span class="text-green-700">{{ project.sold_units }}</span> /
                                        <span class="text-red-700">{{ project.total_units - project.sold_units }}</span>
                                        (Total: {{ project.total_units }})
                                    </p>

                                    <div class="mt-4 grid grid-cols-2 gap-2">
                                        <ButtonLink icon="lets-icons:view-alt-fill"
                                            :route="route('units.index', [project.organisation_id, project.id])">
                                            Units
                                        </ButtonLink>
                                        <ButtonLink icon="material-symbols:manage-accounts"
                                            :route="route('customers.index', [project.id])">
                                            Customers
                                        </ButtonLink>
                                        <ButtonLink icon="material-symbols:add-home-rounded"
                                            :route="route('transactions.create', project.id)">
                                            Sell
                                        </ButtonLink>
                                        <ButtonLink icon="lets-icons:view-alt-fill"
                                            :route="route('transactions.index', [project.organisation_id, project.id])">
                                            Transactions
                                        </ButtonLink>
                                        <ButtonLink icon="material-symbols:settings"
                                            :route="route('projects.edit', project.id)">
                                            Manage
                                        </ButtonLink>
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
