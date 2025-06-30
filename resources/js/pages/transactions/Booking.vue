<script setup>
import { reactive, ref } from 'vue'
import TransactionDetails from './TransactionDetails.vue'

const props = defineProps({
  showTransactionOnly: Boolean,
  unit: Object,
  unitDefined: Boolean
})

const showTransactionDetails = ref(false)

const form = reactive({
  selectedCustomerId: '',
  allCustomers: [],

  customer: { id: '', name: '', phone: '', email: '', address: '', new_customer: true },
  booking: { base: '', gst: '', total: '' }
})

function setCustomerDetails() {
  const selected = form.allCustomers.find(c => c.id === form.selectedCustomerId)
  if (selected) {
    form.customer = {
      id: selected.id,
      name: selected.name,
      phone: selected.phone,
      email: selected.email,
      address: selected.address,
      new_customer: false
    }
  }
}

function recalculateTotal() {
  const base = parseFloat(form.booking.base) || 0
  const gst = parseFloat(form.booking.gst) || 0
  form.booking.total = (base + gst).toFixed(2)
}

function submitForm() {
  // Automatically set new_customer true/false based on customer selection
  form.customer.new_customer = !form.selectedCustomerId
  form.customer.id = form.selectedCustomerId || null

  showTransactionDetails.value = true
}

function resetForm() {
  location.reload()
}
</script>

<template>
  <div v-if="props.unitDefined">
    <TransactionDetails
      v-if="showTransactionDetails"
      :customer="form.customer"
      :booking="form.booking"
      :unit="props.unit"
    />

    <div v-else class="grid grid-cols-1 gap-y-8 px-5">
      <div v-if="!showTransactionOnly">
        <h1 class="font-bold text-cyan-700 text-center lg:text-xl underline underline-offset-8 pt-4">
          Customer & Booking Details
        </h1>

        <!-- Select Existing Customer -->
        <div>
          <label class="text-sm font-medium text-gray-700">Select Existing Customer</label>
          <select v-model="form.selectedCustomerId" @change="setCustomerDetails"
            class="mt-1 block w-full px-3 py-3 rounded-md shadow-sm focus:ring-cyan-700 focus:border-cyan-700">
            <option disabled value="">Select</option>
            <option v-for="customer in form.allCustomers" :key="customer.id" :value="customer.id">
              {{ customer.name }} ({{ customer.email }})
            </option>
          </select>
        </div>

        <!-- Customer Info -->
        <div class="flex flex-col gap-4 mt-4">
          <div>
            <label class="text-sm font-medium text-gray-700">Customer Name</label>
            <input type="text" v-model="form.customer.name"
              class="mt-1 block w-full px-3 py-3 rounded-md border border-gray-300" />
          </div>

          <div class="flex gap-2">
            <input type="number" v-model="form.customer.phone" placeholder="Mobile No."
              class="w-full px-3 py-3 rounded-md border border-gray-300" />
            <input type="email" v-model="form.customer.email" placeholder="Email"
              class="w-full px-3 py-3 rounded-md border border-gray-300" />
          </div>

          <div>
            <label class="text-sm font-medium text-gray-700">Address</label>
            <textarea v-model="form.customer.address"
              class="w-full px-3 py-3 rounded-md border border-gray-300"></textarea>
          </div>
        </div>

        <!-- Booking Section -->
        <div class="py-4">
          <h1 class="font-bold text-cyan-700 text-center lg:text-lg border-t border-gray-300 py-4">
            Booking Details
          </h1>

          <div class="grid grid-cols-3 gap-x-2">
            <div>
              <label>Base Amount</label>
              <input type="text" v-model="form.booking.base" @input="recalculateTotal"
                class="w-full px-3 py-3 rounded-md border border-gray-300 text-right" />
            </div>
            <div>
              <label>GST Amount</label>
              <input type="text" v-model="form.booking.gst" @input="recalculateTotal"
                class="w-full px-3 py-3 rounded-md border border-gray-300 text-right" />
            </div>
            <div>
              <label>Total Amount</label>
              <input type="text" :value="form.booking.total" disabled
                class="w-full px-3 py-3 rounded-md border border-gray-300 text-right bg-gray-100" />
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-between mb-6 gap-4 mt-6">
        <button @click="submitForm"
          class="bg-green-700 w-full text-white px-10 py-2.5 rounded-lg hover:bg-green-800">
          Submit
        </button>
        <button @click="resetForm"
          class="bg-yellow-400 text-white px-10 py-2.5 rounded-lg w-full hover:bg-yellow-500">
          Reset
        </button>
      </div>
    </div>
  </div>
</template>
