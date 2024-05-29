<template>
  <Modal>
    <h2 class="modal__title">Sign Up</h2>
    <Form v-model:is-processing="isProcessing" :handle-logic="registerUser">
      <Input
        v-model:value="form.name"
        name="name"
        label="Name"
        type="text"
        placeholder="John Doe"
      />
      <Input
        v-model:value="form.email"
        name="email"
        label="Email"
        type="email"
        placeholder="your-email@example.com"
      />
      <Input
        v-model:value="form.password"
        name="password"
        label="Password"
        type="password"
        placeholder="********"
      />
      <Button type="submit" :loading="isProcessing">Sign Up</Button>
    </Form>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from '@/components/general/Modal.vue'
import Input from '@/components/general/Input.vue'
import Button from '@/components/general/Button.vue'
import Form from '@/components/general/Form.vue'
import User from '../../request/User'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { useUserStore } from '@/stores/user.js'
import { useModalStore } from '@/stores/modal'

const userStore = useUserStore()
const modalStore = useModalStore()

const form = ref({
  name: '',
  email: '',
  password: '',
})

const isProcessing = ref(false)

const registerUser = async () => {
  const response = await User.register(form.value)
  localStorage.setItem('token', response.data.token)
  toast('Successfully created user!', {
    autoClose: 1000,
    theme: 'dark',
    position: toast.POSITION.BOTTOM_RIGHT,
    hideProgressBar: true,
  })
  await userStore.loginUser()
  modalStore.closeModal()
}
</script>
