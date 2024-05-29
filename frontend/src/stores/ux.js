import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useUxStore = defineStore('ux', () => {
  const isLoading = ref(false)

  const setLoading = (value) => {
    isLoading.value = value
  }

  return { isLoading, setLoading }
})
