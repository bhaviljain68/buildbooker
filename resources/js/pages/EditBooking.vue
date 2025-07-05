<script setup>
import FormInput from '@/components/FormInput.vue'
import FormTextarea from '@/components/FormTextarea.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps(['customer', 'project', 'unit'])
console.log('customers data', props.customer);

const form = useForm({
    customer: {
        name: props.customer?.name || '',
        email: props.customer?.email || '',
        phone: props.customer?.mobile || '',
        address: props.customer?.address || '',
        base: props.unit?.base_amount || '',
        gst: props.unit?.gst_amount || '',
        total: props.unit?.total_amount || ''
    }
})
console.log('edit customer', form);

console.log('Booking Details:', form.customer)
function recalculateTotal() {
    const base = parseFloat(form.customer.base) || 0
    const gst = parseFloat(form.customer.gst) || 0
    form.customer.total = (base + gst).toFixed()
}
function submitForm() {
    console.log('Submitting:', form.data());

    form.post(route('units.booking.update', {
        project: props.project.id,
        unit: props.unit.id,
    }), {
        onSuccess: () => {

            console.log('Redirecting to Booking.vue...');
        },
        onError: () => {
            alert('Failed to update booking.');
        }
    });
}

</script>

<template>
    <AppLayout>
        <form @submit.prevent="submitForm">
            <div
                class="grid grid-cols-1 max-w-4xl w-full mx-auto gap-y-8 px-5 bg-gray-100 my-10 rounded-lg border-t-4 border-primary shadow-md">
                <h1 class="font-bold text-primary text-center lg:text-2xl pt-10">
                    Edit Booking Details
                </h1>

                <!-- Customer Info -->
                <div class="flex flex-col gap-4 mt-4">
                    <FormInput label="Customer Name" id="customer_name" v-model="form.customer.name" required
                        readonly />

                    <div class="flex gap-2">
                        <FormInput label="Mobile No." id="customer_mobile" type="number" v-model="form.customer.phone"
                            required readonly class="w-full" />
                        <FormInput label="Email" id="customer_email" type="email" v-model="form.customer.email" required
                            readonly class="w-full" />
                    </div>

                    <FormTextarea label="Address" id="customer_address" v-model="form.customer.address" required
                        readonly />
                </div>

                <!-- Booking Section -->
                <div class="py-4">
                    <h1 class="font-bold text-primary text-center lg:text-xl border-t border-gray-300 py-4 pt-7">
                        Booking Details
                    </h1>

                    <div class="grid grid-cols-3 gap-x-2">
                        <!-- Base Amount -->
                        <FormInput label="Base Amount" id="base_amount" type="number" v-model="form.customer.base"
                            @input="recalculateTotal" required class="text-left">
                            <template #prefix><span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span></template>
                        </FormInput>

                        <!-- GST Amount -->
                        <FormInput label="GST Amount" id="gst_amount" type="number" v-model="form.customer.gst"
                            @input="recalculateTotal" required class="pl-7">
                            <template #prefix><span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span></template>
                        </FormInput>

                        <!-- Total Amount -->
                        <FormInput label="Total Amount" id="total_amount" type="number"
                            :modelValue="form.customer.total" readonly>
                            <template #prefix><span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span></template>
                        </FormInput>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between mb-6 gap-4 mb-10">
                    <button type="submit"
                        class="bg-primary w-full text-white px-10 py-2.5 rounded-lg hover:bg-teal-800">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
