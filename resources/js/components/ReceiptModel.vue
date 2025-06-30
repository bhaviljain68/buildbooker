<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Receipt_bs from './receipt_bs.vue'

const props = defineProps({
  project: Object,
  transaction: Object
})

const open = ref(false)

function closeModal() {
  open.value = false
}

function downloadReceipt() {
  const routeUrl = route('new-payment-receipts-download-pdf', {
    project: props.project.id,
    transaction: props.transaction.id
  })
  window.open(routeUrl, '_blank')
}
</script>

<template>
  <div class="relative">
    <!-- View Button -->
    <button @click="open = true"
      class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1 px-3 rounded">
      View
    </button>

    <!-- Modal Overlay -->
    <div v-if="open" class="fixed inset-0 z-50 flex items-center overflow-y-auto justify-center bg-gray-500 bg-gray-75">
      <!-- Modal Box -->
      <div class="rounded-lg shadow-lg relative">
        <!-- Close Button Top Right -->
        <!-- <button @click="closeModal"
          class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button> -->

        <!-- Receipt Content -->
        <div class="max-h-[90vh] ">
          <!-- <Receipt_bs :project="project" :transaction="transaction" /> -->
          <Receipt_bs :project="project" :transaction="transaction" :onClose="closeModal"
            :onDownload="downloadReceipt" />
        </div>

        <!-- Action Buttons -->
        <!-- <div class="flex justify-end gap-3 px-6 pb-6">
          <button @click="closeModal"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Close</button>
          <button @click="downloadReceipt"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">Download Receipt</button>
        </div> -->
      </div>
    </div>
  </div>
</template>
