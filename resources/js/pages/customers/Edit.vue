<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import BackButton from '@/components/BackButton.vue'
import FormInput from '@/components/FormInput.vue'
import FormTextarea from '@/components/FormTextarea.vue'

const props = defineProps({
    customer: Object,
    projectId: Number
})

const form = useForm({
    name: props.customer.name || '',
    email: props.customer.email || '',
    mobile: props.customer.mobile || '',
    address: props.customer.address || ''
})

function submitForm() {
    form.put(route('customers.update', props.customer.id))
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Customer</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="flex justify-start w-full mb-4">
                    <BackButton :prevRoute="route('customers.index', projectId)" />
                </div>

                <!-- Form Card -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submitForm">
                            <!-- Name -->
                            <div class="mb-4">
                                <FormInput label="Name" id="name" v-model="form.name" :error="form.errors.name"
                                    :required="true" />
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <FormInput label="Email" id="email" type="email" v-model="form.email"
                                    :error="form.errors.email" />
                            </div>

                            <!-- Mobile -->
                            <div class="mb-4">
                                <FormInput label="Phone" id="mobile" type="number" v-model="form.mobile"
                                    :error="form.errors.mobile" />
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <FormTextarea label="Address" id="address" v-model="form.address"
                                    :error="form.errors.address" />
                            </div>

                            <!-- Buttons -->
                            <div class="mb-4">
                                <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    Update
                                </button>
                                <Link :href="route('customers.index', projectId)"
                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 ml-2">
                                Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
