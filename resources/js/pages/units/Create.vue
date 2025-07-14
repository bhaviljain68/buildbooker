<script setup>
import { ref, reactive } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import BackButton from '@/components/BackButton.vue';
import FormInput from '@/components/FormInput.vue';
const props = defineProps(['project']);
const project = props.project;

const unitTypes = ['apartment', 'shop', 'showroom', 'plot'];
const units = ref([{ unit_no: '', unit_type: '' }]);
const isSubmitting = ref(false);
const toast = new ToastMagic();
function addRow() {
    units.value.push({ unit_no: '', unit_type: '' });
}

function removeRow(index) {
    if (units.value.length > 1) {
        units.value.splice(index, 1);
    }
}

function submitForm() {
    isSubmitting.value = true;
    router.post(route('units.store', [project.id]), { units: units.value }, {
        onSuccess: () => {
            toast.success("Unit created successfully!");
            units.value = [{ unit_no: '', unit_type: '' }];
        },
        onError: (errors) => {
            toast.error("Failed to unit deleted.");
            successMessage.value = '';
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
}
</script>
<template>
    <AppLayout>
        <!-- <pre>{{ project }}</pre> -->
        <div class="py-0 lg:py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center justify-start my-4">
                    <!-- <BackButton :prevRoute="route('units.index', { organisation: project.organisation, project: project.id })" /> -->
                    <BackButton :prevRoute="route('units.index', {
                        organisation: project.organisation_id,
                        project: project.id
                    })" />
                </div>

                <div class="bg-gray-100 overflow-hidden shadow-md sm:rounded-lg mt-10 border-t-4 border-primary">
                    <div class="text-gray-900">
                        <div class="flex items-center justify-center pt-8 pr-3">
                            <img :src="project.logo" :alt="`${project.name}_logo`" class="max-h-24" />
                        </div>

                        <div class="flex items-center justify-between pt-4 py-4 rounded-none border-gray-200 px-4 pr-3">
                            <h6 class="m-0 font-bold text-primary text-center lg:text-3xl w-full">
                                Add Units to {{ project.name }}
                            </h6>
                        </div>

                        <form @submit.prevent="submitForm" class="p-7 rounded">
                            <div
                                class="grid grid-cols-[1fr_1fr_12px] gap-x-4 mb-4 text-secondary font-bold underline underline-offset-8">
                                <p class="text-center text-xl">Unit Number</p>
                                <p class="text-center text-xl">Unit Type</p>
                            </div>

                            <div v-for="(unit, index) in units" :key="index" class="unit-row mb-4 rounded-lg">
                                <div class="grid grid-cols-[1fr_1fr_12px] gap-4">
                                    <div class="pr-2">
                                        <FormInput placeholder="Unit Number" id="unit_no" v-model="unit.unit_no"
                                            :error="unit.errors?.unit_no" />
                                    </div>
                                    <div class="pl-2">
                                        <select v-model="unit.unit_type"
                                            class="mt-1 block w-full px-3 py-3 bg-white rounded-md shadow-sm border border-gray-300"
                                            required>
                                            <option value="" disabled>Select Unit Type</option>
                                            <option v-for="type in unitTypes" :key="type" :value="type">{{ type }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="flex items-center justify-end">
                                        <button type="button" class="remove-row text-red-600 hover:text-red-800"
                                            @click="removeRow(index)" v-if="units.length > 1">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-[1fr_1fr_12px] gap-x-4 mt-10">
                                <div class="flex justify-end pr-2">
                                    <button type="button" @click="addRow"
                                        class="px-4 py-2 bg-primary text-white rounded hover:bg-texl-950 w-full">
                                        Add Unit
                                    </button>
                                </div>
                                <div class="flex justify-start pl-2">
                                    <button type="submit"
                                        class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 w-full"
                                        :disabled="isSubmitting">
                                        {{ isSubmitting ? 'Saving...' : 'Save All Units' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
