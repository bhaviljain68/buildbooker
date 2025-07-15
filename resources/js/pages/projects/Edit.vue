<script setup>
import { ref } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import FormInput from '@/components/FormInput.vue'
import FormTextarea from '@/components/FormTextarea.vue'
import BackButton from '@/components/BackButton.vue'
import AppLayout from '@/layouts/AppLayout.vue'
const toast = new ToastMagic();
const { props } = usePage()
const project = props.project || {}

const fileInput = ref(null)
const fileName = ref('')
const previewUrl = ref(project.logo_file || '') 

const form = useForm({
  name: project.name || '',
  jurisdiction: project.jurisdiction || '',
  office_display_address: project.office_display_address || '',
  site_display_address: project.site_display_address || '',
  total_units: project.total_units || '',
  rera_no: project.rera_no || '',
  logo_file: null,
})

const errors = ref(props.errors || {})

const triggerFileInput = () => fileInput.value.click()

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    console.log('Selected File:', file)
    fileName.value = file.name
    form.logo_file = file
    const reader = new FileReader()
    reader.onload = (e) => {
      previewUrl.value = e.target.result
      console.log('Base64 Preview:', previewUrl.value)
    }
    reader.readAsDataURL(file)
  } else {
    fileName.value = ''
    previewUrl.value = ''
    form.logo_file = null
  }
}

const resetForm = () => {
  form.reset()
  fileName.value = ''
  previewUrl.value = ''
}

function submitForm() {
  form.transform((data) => ({
    ...data,
    _method: 'put',
  })).post(route('projects.update', project.id), {
    onSuccess: () => {
      toast.success("Project updated successfully!");
      router.visit(route('organisation.edit'));
    },
    onError: (errors) => {
      console.error('Update failed:', errors);
    }
  });
}
</script>

<template>
  <AppLayout>
    <div class="py-0 lg:py-10">
      <div class="max-w-4xl mx-auto px-6 py-5 lg:px-8">

        <!-- Back Button -->
        <div class="flex items-start justify-start mb-4">
          <BackButton :prevRoute="route('projects.index')" />
        </div>


        <div class="bg-gray-100 overflow-hidden shadow-md rounded-lg border-t-4 border-primary mt-10">
          <div class="text-gray-900">
            <!-- Title -->
            <div class="flex items-center justify-between pt-8 py-4 border-gray-200 px-4">
              <h6 class="m-0 font-bold text-primary text-center lg:text-3xl w-full">
                {{ form.name }}
              </h6>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm"  class="p-4 lg:p-7 bg-gary-100 rounded">
              <div class="grid grid-cols-1 gap-y-4">
                <!-- Project Name -->
                <FormInput v-model="form.name" label="Project Name" required :error="errors.name"
                  help="(This will be displayed on your receipts)" />

                <!-- Project Logo -->
                <div>
                  <label class="text-sm font-medium text-gray-700">
                    <span class="text-red-500">*</span> Project Logo
                    <span class="ml-1 text-gray-400 text-xs">(Displayed on your receipts)</span>
                  </label>
                  <input type="text" id="logo_selector"
                    class="mt-1 block w-full px-3 py-3 rounded-md bg-white shadow-sm cursor-pointer border"
                    :class="errors.logo_file ? 'border-red-500' : 'border-gray-300'"
                    :value="fileName || project.logo_file" readonly @click="triggerFileInput" required />
                  <input ref="fileInput" type="file" accept="image/jpeg, image/jpg, image/png, image/webp"
                    class="hidden" @change="handleFileSelect" />
                  <div class="mt-2 text-sm text-red-600" v-if="errors.logo_file">{{ errors.logo_file }}</div>
                  <div
                    class="mt-4 w-full h-[150px] bg-white flex items-center justify-center rounded-md border border-gray-300">
                    <img v-if="previewUrl" :src="previewUrl" class="h-full object-cover" />
                    <span v-else class="text-gray-500">Image preview will appear here...</span>
                  </div>
                </div>

                <!-- Jurisdiction -->
                <FormInput v-model="form.jurisdiction" label="Governing Jurisdiction" required
                  :error="errors.jurisdiction" help="(Displayed on your receipts)" />

                <!-- Office Address -->
                <FormTextarea v-model="form.office_display_address" label="Office Address" required
                  :error="errors.office_display_address" help="(Displayed on your receipts)" />

                <!-- Site Address -->
                <FormTextarea v-model="form.site_display_address" label="Site Address" required
                  :error="errors.site_display_address" help="(Displayed on your receipts)" />

                <!-- Total Units -->
                <FormInput v-model.number="form.total_units" label="Total Number Of Units" type="number" required
                  :error="errors.total_units" />

                <!-- RERA No -->
                <FormInput v-model="form.rera_no" label="Project RERA No." :error="errors.rera_no" help="(Optional)" />
              </div>

              <!-- Submit / Reset -->
              <div class="flex flex-row justify-between mt-6 gap-4">
                <button type="submit"
                  class="text-white bg-primary hover:bg-teal-800 w-full font-medium rounded-lg px-10 py-2.5">
                  Submit
                </button>
                <button type="button" @click="resetForm"
                  class="text-white bg-teal-600 hover:bg-teal-500 w-full font-medium rounded-lg px-10 py-2.5">
                  Reset
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
