import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useModalStore = defineStore('modal', () => {
  const modal = ref(null)

  function openModal(value) {
    modal.value = value
  }

  function closeModal() {
    modal.value = null
  }

  return { modal, openModal, closeModal }
})
