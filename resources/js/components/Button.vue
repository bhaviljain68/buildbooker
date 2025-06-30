<script setup>
const props = defineProps(['type', 'color', 'padding', 'rounded', 'fullWidth', 'textSize', 'disabled'])
const emit = defineEmits(['click']);

// Classes for color variants
const colorClass = {
    primary: 'bg-primary text-white hover:bg-[#145e75]',
    secondary: 'bg-gray-200 text-gray-800 hover:bg-gray-300',
    danger: 'bg-danger  text-white hover:bg-red-600',
    gray: '.bg-gray text-gray-800 hover:bg-gray-300',
    boder: 'border border-gray-300 text-gray-800 hover:bg-gray-300',
}[props.color] ?? 'bg-primary text-white'

// Padding sizes
const paddingClass = {
    sm: 'py-1 px-3 text-sm',
    md: 'py-2 px-4',
    lg: 'py-3 px-6 text-lg',
}[props.padding] ?? 'py-2 px-4'

// Rounded classes
const roundedClass = {
    none: '',
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    full: 'rounded-full',
}[props.rounded] ?? 'rounded-md'

// text size classes
const textSizeClass = {
    xs: 'text-xs',
    sm: 'text-sm',
    base: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl'
}[props.textSize] ?? ''

</script>

<template>
    <button type="button" @click="$emit('click')" :class="[
        ' transition duration-200 font-medium focus:outline-none',
        colorClass,
        paddingClass,
        roundedClass,
        textSizeClass,
        fullWidth ? 'w-full' : '',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
    ]" :disabled="disabled">
        <slot>Button</slot>
    </button>
</template>
