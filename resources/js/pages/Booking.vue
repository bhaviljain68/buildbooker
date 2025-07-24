<script setup>
import BackButton from '@/components/BackButton.vue'
import FormInput from '@/components/FormInput.vue'
import FormTextarea from '@/components/FormTextarea.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const toast = new ToastMagic()
const props = defineProps(['customers', 'project', 'unit'])

const isNewCustomer = ref(false) // Default to Existing Customer


const form = useForm({
  selectedCustomerId: '',
  customer: {
    name: '',
    email: '',
    mobile: '',
    address: '',
    base: '',
    gst: '',
    total: ''
  }
})

function setCustomerDetails() {
  const selected = props.customers.find(c => c.id === form.selectedCustomerId)
  if (selected) {
    form.customer.name = selected.name
    form.customer.email = selected.email
    form.customer.mobile = selected.mobile
    form.customer.address = selected.address
    isNewCustomer.value = false
  }
}

function recalculateTotal() {
  const base = parseFloat(form.customer.base) || 0
  const gst = parseFloat(form.customer.gst) || 0
  form.customer.total = (base + gst).toFixed()
}

function resetForm() {
  form.reset()
  form.customer.total = ''
  isNewCustomer.value = null
}

function submitForm() {
  form.post(route('units.booking.store', {
    project: props.project.id,
    unit: props.unit.id
  }), {
    onSuccess: () => toast.success('Booking submitted successfully!'),
    onError: () => toast.error('Failed to submit booking')
  })
}
</script>

<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto w-full px-6 lg:px-0 mt-5">
      <div class="flex items-center justify-start my-4">
        <BackButton :prevRoute="route('units.index', {
          organisation: project.organisation_id,
          project: project.id
        })" />
      </div>
    </div>

    <form @submit.prevent="submitForm" class="px-4 lg:px-0">
      <div
        class="grid grid-cols-1 max-w-4xl w-full mx-auto gap-y-8 px-4 lg:px-6 bg-gray-100 mb-10 mt-5 rounded-lg border-t-4 border-primary shadow-md">
        <h1 class="font-bold text-primary text-center lg:text-2xl pt-10">
          Customer & Booking Details
        </h1>

        <!-- Customer Type Buttons -->
        <div class="flex justify-center rounded-full mt-2">
          <button type="button" @click="() => { isNewCustomer = false; form.selectedCustomerId = '' }" :class="[
            'px-6 py-2 rounded-l-full font-semibold',
            isNewCustomer === false ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
          ]" selected>
            Existing Customer
          </button>

          <button type="button" @click="() => { isNewCustomer = true; form.selectedCustomerId = '' }" :class="[
            'px-6 py-2 rounded-r-full font-semibold',
            isNewCustomer === true ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
          ]">
            New Customer
          </button>
        </div>

        <!-- Existing Customer Select -->
        <div v-if="isNewCustomer === false">
          <label class="text-sm font-medium text-gray-700 mt-4 block">Select Existing Customer</label>
          <select v-model="form.selectedCustomerId" @change="setCustomerDetails"
            class="mt-1 block w-full px-3 py-3 bg-white rounded-md shadow-sm border border-gray-300 focus:ring-cyan-700 focus:border-cyan-700">
            <option value="">Select</option>
            <option v-for="customer in props.customers" :key="customer.id" :value="customer.id">
              {{ customer.name }} ({{ customer.email }})
            </option>
          </select>
        </div>

        <!-- Customer Info Form (conditionally shown) -->
        <div v-if="isNewCustomer !== null && (isNewCustomer || form.selectedCustomerId)"
          class="flex flex-col gap-4 mt-4">
          <FormInput label="Customer Name" id="customer_name" v-model="form.customer.name" :error="form.errors.name"
            required :disabled="!isNewCustomer" />

          <div class="flex gap-2">
            <FormInput label="Mobile No." id="customer_phone" type="number" v-model="form.customer.mobile"
              :error="form.errors.mobile" required class="w-full" :disabled="!isNewCustomer" />
            <FormInput label="Email" id="customer_email" type="email" v-model="form.customer.email"
              :error="form.errors.email" required class="w-full" :disabled="!isNewCustomer" />
          </div>

          <FormTextarea label="Address" id="customer_address" v-model="form.customer.address"
            :error="form.errors.address" required :disabled="!isNewCustomer" />
        </div>

        <!-- Booking Details Section -->
        <div v-if="isNewCustomer !== null" class="py-4">
          <h1 class="font-bold text-primary text-center lg:text-xl border-t border-gray-300 py-4 pt-7">
            Booking Details
          </h1>

          <div class="grid lg:grid-cols-3 grid-cols-1 gap-4 lg:gap-x-2">
            <FormInput label="Base Amount" id="base_amount" type="number" v-model="form.customer.base"
              @input="recalculateTotal" :error="form.errors.base" required>
              <template #prefix>
                <span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span>
              </template>
            </FormInput>

            <FormInput label="GST Amount" id="gst_amount" type="number" v-model="form.customer.gst"
              @input="recalculateTotal" :error="form.errors.gst" required>
              <template #prefix>
                <span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span>
              </template>
            </FormInput>

            <FormInput label="Total Amount" id="total_amount" type="number" :modelValue="form.customer.total" readonly>
              <template #prefix>
                <span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span>
              </template>
            </FormInput>
          </div>
        </div>

        <!-- Buttons -->
        <div v-if="isNewCustomer !== null" class="flex justify-between mb-6 gap-4 mb-10">
          <button type="submit" class="bg-primary w-full text-white px-10 py-2.5 rounded-lg hover:bg-teal-800">
            Submit
          </button>
          <button type="reset" @click="resetForm"
            class="bg-teal-500 text-white px-10 py-2.5 rounded-lg w-full hover:bg-teal-600">
            Reset
          </button>
        </div>
      </div>
    </form>
  </AppLayout>
</template>
