<script setup>
import { usePage, Link } from '@inertiajs/vue3'

const props = defineProps({
    customer: Object
})
</script>

<template>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Customer Details</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-100 px-4 py-3">
                <h3 class="text-lg font-semibold text-gray-800">{{ customer.name }}</h3>
            </div>

            <div class="px-4 py-4 space-y-2">
                <p><strong>Email:</strong> {{ customer.email }}</p>
                <p><strong>Phone:</strong> {{ customer.phone }}</p>
                <p><strong>Address:</strong> {{ customer.address }}</p>
            </div>
        </div>

        <div class="mt-4">
            <Link :href="route('customers.index')"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Back to Customers
            </Link>
        </div>
    </div>
</template>
