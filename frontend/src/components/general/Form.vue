<template lang="">
  <form class="form" @submit.prevent="submitForm"><slot /></form>
</template>
<script>
export default {
  name: 'FormComponent',
}
</script>
<script setup>
import { defineEmits, defineProps } from 'vue'
import { useErrorStore } from '@/stores/error'
import handleError from '../../utils/handleError'

const errorStore = useErrorStore()

const props = defineProps({
  errors: Object,
  isProcessing: Boolean,
  handleLogic: Function,
})

const emits = defineEmits(['handleLogic', 'update:isProcessing'])

const submitForm = async () => {
  errorStore.clearErrors()
  emits('update:isProcessing', true)
  try {
    await props.handleLogic()
  } catch (error) {
    handleError(error, errorStore)
    // errorStore.setErrors(error.response.data.errors)
  } finally {
    emits('update:isProcessing', false)
  }
}
</script>
