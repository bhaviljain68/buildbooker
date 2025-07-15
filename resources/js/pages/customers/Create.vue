<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import FormInput from '@/components/FormInput.vue'
import FormTextarea from '@/components/FormTextarea.vue'
import BackButton from '@/components/BackButton.vue'
const toast = new ToastMagic();
const props = defineProps(['project'])

const form = useForm({
  name: '',
  email: '',
  mobile: '',
  address: '',
  project_id: props.project.id,
})

function submit() {
  form.post(route('customers.index', props.project.id)),
  {
    onSuccess: () => {
      toast.success('Customer Add submitted successfully!')
    },
    onError: () => {
      toast.error('Failed to Customer')
    }
  }
}
</script>

<template>
  <AppLayout>
    <div class="py-0 lg:py-10 mt-10 lg:mt-0">
      <div class="w-full lg:!w-[80%] mx-auto px-6 lg:px-8">

        <div class="flex justify-between max-w-4xl w-full mx-auto mb-4">
          <BackButton :prevRoute="route('projects.index')" />
        </div>
        <form @submit.prevent="submit"
          class="max-w-4xl bg-gray-100 w-full mx-auto mt-10 p-6 border-t-4 border-primary rounded-lg shadow">
          <h2 class="text-2xl font-bold text-primary text-center mb-6">Add Customer</h2>

          <!-- Name -->
          <div class="mb-4">
            <FormInput label="Name" id="name" v-model="form.name" :error="form.errors.name" :required="true" />
          </div>

          <!-- Email -->
          <div class="mb-4">
            <FormInput label="Email" id="email" type="email" v-model="form.email" :error="form.errors.email"
              :required="true" />
          </div>

          <!-- Mobile -->
          <div class="mb-4">
            <FormInput label="Mobile" id="mobile" type="text" v-model="form.mobile" :error="form.errors.mobile"
              :required="true" />
          </div>

          <!-- Address -->
          <div class="mb-4">
            <FormTextarea label="Address" id="address" v-model="form.address" :error="form.errors.address"
              :required="true" />
          </div>

          <button type="submit" class="bg-primary mt-8 w-full text-white px-4 py-2 rounded-lg hover:bg-teal-700">
            Save Customer
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
