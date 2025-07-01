<script setup>
defineProps(['label', 'id', 'modelValue', 'error', 'required', 'type', 'hint']);
defineEmits(['update:modelValue']);
</script>
<template>
  <div>
    <label :for="id" class="text-sm font-medium text-gray-700">
      <span v-if="required" class="text-red-500 text-xs align-super">*</span>
      {{ label }}
      <span v-if="hint" class="text-gray-400 text-xs">{{ hint }}</span>
      <!-- <span v-if="required" class="text-red-500 text-lg">*</span>
      <span v-else class="text-gray-400 text-sm">(optional)</span> -->
    </label>
    <input :id="id" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
      :required="required"
      class="mt-1 block w-full px-3 py-3 bg-white border border-gary-400 rounded-md shadow-sm focus:ring-cyan-700 focus:border-cyan-700"
      :class="error ? 'border-red-500' : 'border-gray-300'" />
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>
