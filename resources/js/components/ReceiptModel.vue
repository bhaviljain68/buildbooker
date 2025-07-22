<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import Receipt_bs from './Receipt_bs.vue';

const props = defineProps({
  project: Object,
  transaction: Object,
})

const open = ref(false)

function closeModal() {
  open.value = false
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
      <div class="rounded-lg relative">
        <!-- Receipt Content -->
        <div class="max-h-[90vh] ">
          <Receipt_bs :project="project" :transaction="transaction" :closeModal="closeModal" />
        </div>
      </div>
    </div>
  </div>
</template>
