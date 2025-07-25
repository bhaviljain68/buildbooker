// composables/useQueryParam.ts
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useQueryParam(key: string, defaultValue: string = '') {
  const page = usePage()

  const value = computed(() => {
    const url = page.url
    const queryString = url.split('?')[1]
    if (!queryString) return defaultValue

    const params = new URLSearchParams(queryString)
    return params.get(key) || defaultValue
  })

  return value
}
