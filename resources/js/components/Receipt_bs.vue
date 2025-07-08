<script setup>
import { Download } from 'lucide-vue-next'
import { computed } from 'vue'

const props = defineProps({
  project: Object,
  transaction: Object,
  onClose: Function,
  onDownload: Function
})
// console.log('Download url',props.onDownload);
const formattedReceiptDate = computed(() => {
  return new Date(props.transaction.receipt_date).toLocaleDateString('en-GB')
})

const formattedPaymentDate = computed(() => {
  return new Date(props.transaction.payment_date).toLocaleDateString('en-GB')
})
</script>

<template>
  <div class="bg-white px-10 text-gray-900 rounded-lg my-10 pt-10">
    <div v-for="i in 2" :key="i">
      <div class="flex justify-between pt-5">
        <div>
          <img :src="project?.logo" alt="Logo" height="100" width="100" />
        </div>
        <div class="w-[350px]">
          <table class="w-full table-auto border-collapse">
            <tbody>
              <tr>
                <td class="text-xs text-right">
                  <span class="font-bold pr-4 align-top">Office :</span>
                  {{ project?.office_display_address }}
                </td>
              </tr>
              <tr>
                <td class="text-xs text-right">
                  <span class="font-bold pr-4 align-top">Site :</span>
                  {{ project.site_display_address }}
                </td>
              </tr>
              <tr v-if="project.email">
                <td class="text-sm font-bold pr-4 align-top">Email:</td>
                <td class="text-sm">{{ project.email }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="mt-4">
        <div class="flex items-center justify-between">
          <div>
            <label class="text-sm mr-4">Receipt Number:</label>
            <input class="my-input w-24 text-center" :value="transaction.receipt_number" disabled />
          </div>
          <div>
            <label class="text-sm">Date:</label>
            <input :value="formattedReceiptDate" class="my-input w-24 text-center" disabled />
          </div>
        </div>

        <div class="flex items-center mt-2">
          <label class="text-sm mr-4">Name:</label>
          <input type="text" :value="transaction.customer.name" class="my-input w-full" disabled />
        </div>

        
        <div class="text-sm mt-2 text-left">
          deposits below amount for {{ transaction.unit?.type }} Number:
          <input :value="transaction.unit_no" class="my-input w-16 text-center" disabled />
          at <b>"{{ project.name }}"</b>, accepting all the conditions by the developers for becoming a member.
        </div>

        <div class="flex items-center mt-2">
          <label class="text-sm">Bank:</label>
          <input type="text" :value="transaction.bank_name" class="my-input ml-2" disabled />
          <label class="text-sm ml-3">Branch:</label>
          <input :value="transaction.bank_branch" class="my-input ml-2" disabled />
        </div>

        <div class="flex items-center mt-2">
          <label class="text-sm w-[55%] text-left">
            <span :class="transaction.payment_type === 'cheque' ? '' : 'my-label'">Cheque No.</span>
            <span :class="transaction.payment_type === 'bank_draft' ? '' : 'my-label'">/Draft No.</span>
            <span :class="transaction.payment_type === 'rtgs' ? '' : 'my-label'">/RTGS No.</span>
            <span :class="transaction.payment_type === 'neft' ? '' : 'my-label'">/NEFT No.</span>
            <span :class="transaction.payment_type === 'imps' ? '' : 'my-label'">/IMPS</span>
            <span :class="transaction.payment_type === 'cash' ? '' : 'my-label'">/Cash</span>:
          </label>
          <input :value="transaction.payment_reference" class="my-input w-[20%] text-center" disabled />
          <label class="text-sm ml-4 w-[10%] text-center">Date:</label>
          <input :value="formattedPaymentDate" class="my-input w-[15%] text-center" disabled />
        </div>

        <div class="flex mt-2">
          <label class="text-sm">Rupees:</label>
          <input type="text" class="my-input w-full ml-2" :value="transaction.token_amount_in_words" disabled />
        </div>

        <div class="flex items-center mt-2">
          <label class="text-base font-bold mt-4">Amount:</label>
          <input :value="`₹${transaction.transaction_amount}`"
            class="border border-black mt-3 rounded-sm border-dashed ml-4 w-48 text-center h-8 flex justify-center items-center"
            disabled />
        </div>

        <div class="flex justify-between">
          <div class="text-sm">
            <p class="font-bold">
              {{ transaction.payment_type === 'Cheque' ? 'Note: The receipt will be valid only after realization of cheque.' : '' }}
            </p>
            <p class="mt-10">Subject to {{ project.jurisdiction }} Jurisdiction</p>
          </div>
          <div>
            <p class="text-sm font-bold">For, {{ project.name }}</p>
            <div class="box-border border-dashed h-16 w-16 p-4 border-2 ml-12 border-black mt-4 mb-10"></div>
          </div>
        </div>
      </div>

      <hr v-if="i === 1" class="" />
    </div>
    

  <!-- Action Buttons at Bottom -->
    <div class="flex justify-end gap-3 pb-5">
      <button @click="props.onClose"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Close</button>
      <button @click="props.onDownload"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">Download Receipt</button>
    </div>
      </div>
</template>

<!-- <style scoped>
.my-input {
  @apply border border-gray-300 rounded-sm px-2 py-1 text-sm;
}
.my-label {
  @apply text-gray-400;
}
</style> -->