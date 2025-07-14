<script setup>
import ButtonLink from '@/components/ButtonLink.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const { props } = usePage()

const totalProjects = computed(() => props.totalProjects || 0)
const totalOutStandingAmmount = computed(() => props.totalOutStandingAmmount || 0)
const totalUnsoldUnits = computed(() => props.totalUnsoldUnits || 0)
const projects = computed(() => props.projects || [])

const formatCurrency = (amount) => {
    if (!amount) return '₹0'
    return Number(amount).toLocaleString('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2
    }).replace('₹', '₹ ')
}
</script>

<template>
    <AppLayout>
        <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="flex flex-col lg:flex-row gap-4 p-4 justify-between">
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition w-full lg:max-w-[33%]">
                    <h3 class="text-lg font-semibold text-gray-700">Total Projects</h3>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">{{ totalProjects }}</span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition w-full lg:max-w-[33%]">
                    <h3 class="text-lg font-semibold text-gray-700">Total Outstanding Amount (All Projects)</h3>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">{{ formatCurrency(totalOutStandingAmmount)
                        }}</span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition w-full lg:max-w-[33%]">
                    <h3 class="text-lg font-semibold text-gray-700">Total Unsold Units (All Projects)</h3>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">{{ totalUnsoldUnits }}</span>
                    </div>
                </div>
            </div>

            <!-- Project List -->
            <div v-if="projects.length" v-for="project in projects" :key="project.id"
                class="p-4 m-4 bg-teal-100 rounded-lg">
                <!-- Logo -->
                <div class="flex flex-col lg:flex-row gap-4 items-center mb-6 justify-center pt-6">
                    <img :src="project.project_logo" alt="Project Logo" class="h-18 w-18" />
                </div>

                <!-- Project Cards -->
                <div class="flex flex-col lg:flex-row gap-4 lg:p-4 justify-between">
                    <div class="bg-white p-4 border rounded-lg shadow-md hover:shadow-lg transition w-full lg:max-w-[33%]">
                        <h3 class="text-lg font-semibold text-gray-700">Total Units Amounts</h3>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">
                                {{ formatCurrency(project.totalUnitAmount) }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-white p-4 border rounded-lg shadow-md hover:shadow-lg transition w-full lg:max-w-[33%]">
                        <h3 class="text-lg font-semibold text-gray-700">Total Units</h3>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">{{ project.total_units }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-4 border rounded-lg shadow-md hover:shadow-lg transition w-full lg:max-w-[33%]">
                        <h3 class="text-lg font-semibold text-gray-700">Total Pending Amount Of Project</h3>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">
                                {{ formatCurrency(project.totalPendingAmountOfProject) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex w-full justify-end mt-4 my-6">
                    <div class="flex gap-4">
                        <ButtonLink icon="ic:twotone-add" :href="route('units.create', project.id)">Add Units
                        </ButtonLink>
                        <ButtonLink icon="material-symbols:add-home-rounded"
                            :href="route('units.index', { organisation: project.organisation_id, project: project.id })"
                            class="bg-green-600 hover:bg-green-700 text-white">Book</ButtonLink>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="p-4 m-4 flex flex-col gap-2 items-center bg-white rounded-lg">
                <h1 class="text-2xl font-bold">No Projects Added!</h1>
                <div class="mb-4">
                    <ButtonLink icon="ic:twotone-add" :href="route('projects.create')">Add New Project</ButtonLink>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
