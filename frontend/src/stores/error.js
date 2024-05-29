import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useErrorStore = defineStore('errors', () => {
  const errors = ref({})

  function setErrors(newErrors) {
    errors.value = newErrors
  }

  function clearErrors() {
    errors.value = {}
  }

  function getErrors(key) {
    return errors.value[key] ? errors?.value[key][0] : null
  }

  function deleteErrors(key) {
    return errors.value[key] ? delete errors?.value[key] : ''
  }

  return {
    errors,
    setErrors,
    clearErrors,
    getErrors,
    deleteErrors,
  }
})
