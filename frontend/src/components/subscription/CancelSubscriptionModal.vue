<template>
    <Modal>
      <h2 class="cancelModal__text">Are you sure you want to cancel subscription?</h2>
      <div class="cancelModal">
        <div class="cancelModal__buttons">
            <div class="cancelModal__yes" @click="cancelSubscription">
                Yes
            </div>
            <div class="cancelModal__no"  @click="modalStore.closeModal()">No</div>
        </div>
      </div>
    </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from '@/components/general/Modal.vue'
import Subscription from '@/request/Subscription'
import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const modalStore = useModalStore()
const userStore = useUserStore()

const cancelSubscription = async () => {
    const response = await Subscription.unsubscribe()
    toast('Successfully canceled!', {
        autoClose: 1000,
        theme: 'dark',
        position: toast.POSITION.BOTTOM_RIGHT,
        hideProgressBar: true,
    })
    userStore.loginUser()
    modalStore.closeModal()
}
</script>

<style lang="scss" scoped></style>
