<template>
    <Modal>
      <h2 class="modal__title">Choose plan</h2>
      <SubscriptionForm/>
    </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from '@/components/general/Modal.vue'
import SubscriptionForm from '@/components/subscription/SubscriptionForm.vue';
</script>

<style lang="scss" scoped></style>
