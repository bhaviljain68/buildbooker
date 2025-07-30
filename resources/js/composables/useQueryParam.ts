// composables/useQueryParam.ts
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

function pascalToLaravelRouteName(str: string): string {
  // Example: ProjectsIndex → projects.index
  return str
    .replace(/([a-z0-9])([A-Z])/g, '$1.$2') // Add dot between words
    .replace(/([A-Z])([A-Z][a-z])/g, '$1.$2') // Also handle ALLCAPS
    .toLowerCase()
}

export function useQueryParam(
  key: string,
  defaultValue: string = '',
  convertToRouteName: boolean = false
) {
  const page = usePage()

  const value = computed(() => {
    const url = page.url
    const queryString = url.split('?')[1]
    if (!queryString) {
      return convertToRouteName
        ? pascalToLaravelRouteName(defaultValue)
        : defaultValue
    }

    const params = new URLSearchParams(queryString)
    const raw = params.get(key) || defaultValue
    return convertToRouteName ? pascalToLaravelRouteName(raw) : raw
  })

  return value
}
