<script setup>
defineProps(['label', 'id', 'modelValue', 'error', 'required', 'hint']);
defineEmits(['update:modelValue']);
</script>

<template>
  <div>
    <label :for="id" class="text-sm font-medium text-gray-700">
      <span v-if="required" class="text-red-500 text-xs align-super">*</span>
      {{ label }}
      <span v-if="hint" class="text-gray-400 text-xs ml-1">{{ hint }}</span>
    </label>

    <textarea
      :id="id"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :required="required"
      rows="4"
      class="mt-1 block w-full px-3 py-3 border border-gary-200 rounded-md shadow-sm focus:ring-cyan-700 focus:border-cyan-700"
      :class="error ? 'border-red-500' : 'border-gray-300'"
    ></textarea>

    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>
