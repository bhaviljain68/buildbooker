<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import Receipt_bs from './Receipt_bs.vue';

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
  <div class="">
    <!-- View Button -->
    <button @click="open = true"
      class="flex items-center justify-center gap-2 mx-auto bg-primary hover:bg-primary/90 text-white text-sm font-semibold py-2 px-4 rounded-lg transition duration-200">
      <Icon icon="fluent-mdl2:view" width="18" height="18" />
      View
    </button>

    <!-- Modal Overlay -->
    <div v-if="open" class="fixed inset-0 z-50 flex items-center overflow-y-auto justify-center bg-gray-100">
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
