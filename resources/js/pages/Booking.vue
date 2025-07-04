<script setup>
import FormInput from '@/components/FormInput.vue'
import FormTextarea from '@/components/FormTextarea.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps(['customers', 'project', 'unit'])

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
}

function submitForm() {
  form.post(route('units.booking.store', {
    project: props.project.id,
    unit: props.unit.id
  }), {
    onSuccess: () => alert('Booking submitted successfully!')
  })
}
</script>

<template>
  <AppLayout>
    <form @submit.prevent="submitForm">
      <div
        class="grid grid-cols-1 max-w-4xl w-full mx-auto gap-y-8 px-5 bg-gray-100 my-10 rounded-lg border-t-4 border-primary shadow-md">
        <h1 class="font-bold text-primary text-center lg:text-2xl pt-10">
          Customer & Booking Details
        </h1>

        <!-- Select Existing Customer -->
        <div>
          <label class="text-sm font-medium text-gray-700">Select Existing Customer</label>
          <select v-model="form.selectedCustomerId" @change="setCustomerDetails"
            class="mt-1 block w-full px-3 py-3 bg-white rounded-md shadow-sm border border-gray-300 focus:ring-cyan-700 focus:border-cyan-700">
            <option  value=""> Select</option>
            <option v-for="customer in props.customers" :key="customer.id" :value="customer.id">
              {{ customer.name }} ({{ customer.email }})
            </option>
          </select>
        </div>

        <!-- Customer Info -->
        <div class="flex flex-col gap-4 mt-4">
          <FormInput label="Customer Name" id="customer_name" v-model="form.customer.name" :error="form.errors.name"
            required="true" :disabled="!!form.selectedCustomerId" />

          <div class="flex gap-2">
            <FormInput label="Mobile No." id="customer_phone" type="number" v-model="form.customer.mobile"
              :error="form.errors.mobile" required="true" class="w-full" :disabled="!!form.selectedCustomerId" />
            <FormInput label="Email" id="customer_email" type="email" v-model="form.customer.email"
              :error="form.errors.email" required="true" class="w-full" :disabled="!!form.selectedCustomerId" />
          </div>

          <FormTextarea label="Address" id="customer_address" v-model="form.customer.address"
            :error="form.errors.address" required="true"  :disabled="!!form.selectedCustomerId" />
        </div>

        <!-- Booking Section -->
        <div class="py-4">
          <h1 class="font-bold text-primary text-center lg:text-xl border-t border-gray-300 py-4 pt-7">
            Booking Details
          </h1>

          <div class="grid grid-cols-3 gap-x-2">
            <!-- Base Amount -->
            <FormInput label="Base Amount" id="base_amount" type="number" v-model="form.customer.base"
              @input="recalculateTotal" :error="form.errors.base" required="true" class="text-left">
              <template #prefix>
                <span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span>
              </template>
            </FormInput>

            
            <FormInput label="GST Amount" id="gst_amount" type="number" v-model="form.customer.gst"
              @input="recalculateTotal" :error="form.errors.gst" required="true" class="pl-7">
              <template #prefix>
                <span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span>
              </template>
            </FormInput>



            <!-- Total Amount -->
            <FormInput label="Total Amount" id="total_amount" type="number" :modelValue="form.customer.total">
              <template #prefix>
                <span class="absolute ml-2 top-1/2 -translate-y-1/2">₹</span>
              </template>
            </FormInput>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-between mb-6 gap-4 mb-10">
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
