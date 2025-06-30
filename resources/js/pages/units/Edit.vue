<script setup>
import { ref } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import FormInput from '@/components/FormInput.vue';
const toast = new ToastMagic();
const props = defineProps(['project', 'unit']);
const unitTypes = ['apartment', 'shop', 'showroom', 'plot']

const units = ref([
    {
        unit_no: props.unit?.unit_no ?? '',
        unit_type: props.unit?.unit_type ?? '',
    },
])

function submitForm() {
    router.post(
        route('units.update', {
            project: props.project.id,
            unit: props.unit.id,
        }),
        {
            units: units.value,
        },
        {
            onSuccess: () => {
                toast.success("Unit Updated successfully!");
            },
            onError: () => {
                toast.error("Failed to unit Update.");
            },
        }
    )
}
</script>
<template>
    <AppLayout>
        <div class="py-0 lg:py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center justify-start my-4">
                    <Link :href="route('units.index', { organisation: project.organisation, project: project.id })"
                        class="bg-yellow-500 hover:bg-yellow-600 text-black cursor-pointer px-4 py-1 rounded-lg text-base flex justify-between items-center gap-x-2">
                    ← Go Back
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="text-gray-900">
                        <div class="flex items-center justify-center pt-8 pr-3">
                            <img :src="project.logo" :alt="`${project.name}_logo`" class="max-h-24" />
                        </div>

                        <div class="flex items-center justify-between pt-4 py-4 rounded-none border-gray-200 px-4 pr-3">
                            <h6
                                class="m-0 font-bold text-cyan-700 text-center lg:text-3xl w-full underline underline-offset-8">
                                Add Units to {{ project.name }}
                            </h6>
                        </div>

                        <form @submit.prevent="submitForm" class="bg-white p-6 shadow-md rounded">
                            <div class="grid grid-cols-[1fr_1fr_12px] gap-x-4 mb-4 underline underline-offset-8">
                                <p class="text-center text-xl">Unit Number</p>
                                <p class="text-center text-xl">Unit Type</p>
                            </div>

                            <div v-for="(unit, index) in units" :key="index"
                                class="grid grid-cols-[1fr_1fr_12px] gap-4 mb-4">
                                <!-- <input v-model="unit.unit_no" type="text" class="border p-2 rounded w-full"
                                    placeholder="Unit Number" required /> -->
                                <FormInput placeholder="Unit Number" id="unit_no" v-model="unit.unit_no"
                                    :error="unit.errors?.unit_no" />
                                <select v-model="unit.unit_type" class="border p-2 rounded w-full" required>
                                    <option disabled value="">Select Unit Type</option>
                                    <option v-for="type in unitTypes" :key="type" :value="type">
                                        {{ type }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex justify-between mt-6">

                                <button type="submit"
                                    class="bg-green-600 text-white w-full px-4 py-2 rounded hover:bg-green-700">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
