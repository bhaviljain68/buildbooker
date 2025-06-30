<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import FormInput from '@/components/FormInput.vue'
import FormTextarea from '@/components/FormTextarea.vue'

const props = defineProps(['project'])

const form = useForm({
  name: '',
  email: '',
  mobile: '',
  address: '',
  project_id: props.project.id,
})

function submit() {
  form.post(route('customers.store', props.project.id))
}
</script>

<template>
  <AppLayout>
    <form @submit.prevent="submit" class="max-w-4xl w-full mx-auto mt-10 p-6 bg-white rounded shadow">
      <h2 class="text-xl font-bold text-gray-800 text-center mb-6">Add Customer</h2>

      <!-- Name -->
      <div class="mb-4">
        <FormInput
          label="Name"
          id="name"
          v-model="form.name"
          :error="form.errors.name"
          :required="true"
        />
      </div>

      <!-- Email -->
      <div class="mb-4">
        <FormInput
          label="Email"
          id="email"
          type="email"
          v-model="form.email"
          :error="form.errors.email"
        />
      </div>

      <!-- Mobile -->
      <div class="mb-4">
        <FormInput
          label="Mobile"
          id="mobile"
          type="text"
          v-model="form.mobile"
          :error="form.errors.mobile"
        />
      </div>

      <!-- Address -->
      <div class="mb-4">
        <FormTextarea
          label="Address"
          id="address"
          v-model="form.address"
          :error="form.errors.address"
        />
      </div>

      <button type="submit" class="bg-blue-600 w-full text-white px-4 py-2 rounded hover:bg-blue-700">
        Save Customer
      </button>
    </form>
  </AppLayout>
</template>
