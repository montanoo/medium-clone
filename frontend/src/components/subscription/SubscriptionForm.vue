<template>
  <Form v-model:is-processing="isProcessing" :handle-logic="goPremium">
    <PricingTab
      @plan-selected="handlePlanSelected"
      :plan="form.subscriptionType"
    />
    <Input
      v-model:value="form.card"
      name="card"
      label="Card number"
      type="number"
      placeholder="0000-0000-0000-0000"
      pattern="[0-9]{16}"
    />
    <div class="subscriptionForm__divider">
      <div class="subscriptionForm__divider-item">
        <Input
          v-model:value="form.cvv"
          name="cvv"
          label="CVV"
          type="number"
          placeholder="000"
          :min="0"
          :max="999"
        />
      </div>
      <div class="subscriptionForm__divider-item">
        <Input
          v-model:value="form.month"
          name="month"
          label="Month"
          type="number"
          placeholder="00"
          :min="1"
          :max="12"
        />
      </div>
      <div class="subscriptionForm__divider-item">
        <Input
          v-model:value="form.year"
          name="year"
          label="Year"
          type="number"
          placeholder="00"
          :min="1"
          :max="99"
        />
      </div>
    </div>
    <Input
      v-model:value="form.name"
      name="name"
      label="Name"
      type="text"
      placeholder="John"
    />
    <Input
      v-model:value="form.surname"
      name="surname"
      label="Surname"
      type="text"
      placeholder="Doe"
    />
    <Input
      v-model:value="form.email"
      name="email"
      label="Email"
      type="email"
      placeholder="your-email@example.com"
    />
    <Input
      v-model:value="form.address"
      name="address"
      label="Address - optional"
      type="text"
      placeholder=""
    />
    <Button type="submit" :loading="isProcessing">Go Premium</Button>
  </Form>
</template>

<script setup>
import { ref } from 'vue'
import Input from '@/components/general/Input.vue'
import Button from '@/components/general/Button.vue'
import Form from '@/components/general/Form.vue'
import PricingTab from './PricingTab.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { useUserStore } from '@/stores/user.js'
import { useModalStore } from '@/stores/modal'
import Subscription from '@/request/Subscription'

const userStore = useUserStore()
const modalStore = useModalStore()

const form = ref({
  cardNumber: '',
  cvv: '',
  month: '',
  year: '',
  name: '',
  surname: '',
  address: '',
  subscriptionType: 'monthly',
})

const isProcessing = ref(false)

function handlePlanSelected(plan) {
  form.value.subscriptionType = plan
}

const goPremium = async () => {
  const data = mapRequest(form.value)
  const response = await Subscription.subscribe(data)
  toast('Successfully subscribed!', {
    autoClose: 1000,
    theme: 'dark',
    position: toast.POSITION.BOTTOM_RIGHT,
    hideProgressBar: true,
  })
  userStore.loginUser()
  modalStore.closeModal()
}

const mapRequest = (values) => {
  return {
    card: values.card,
    cvv: values.cvv,
    expiration: `${values.month}/${values.year}`,
    name: values.name,
    email: values.email,
    address: values.address,
    type: values.subscriptionType,
  }
}
</script>
