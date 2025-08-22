<script setup>
defineProps(['label', 'id', 'modelValue', 'error', 'required', 'type', 'hint', 'disabled']);
defineEmits(['update:modelValue']);
</script>
<template>
  <div>
    <label :for="id" class="text-sm font-medium text-gray-700">
      <span v-if="required" class="text-red-500 text-xs align-super">*</span>
      {{ label }}
      <span v-if="hint" class="text-gray-400 text-xs">{{ hint }}</span>
    </label>
    <div class="relative">
      <slot name="prefix" />
      <input :id="id" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
        :required="required" :disabled="disabled"
        class="mt-1 block w-full px-3 py-3 bg-white pl-5 border border-gary-400 rounded-md shadow-sm focus:ring-cyan-700 focus:border-cyan-700"
        :class="[error ? 'border-red-500' : 'border-gray-300', disabled ? 'bg-blue-400 cursor-not-allowed' : '']" />
    </div>
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>
