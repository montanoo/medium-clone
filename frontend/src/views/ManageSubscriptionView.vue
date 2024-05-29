<template>
  <main class="manageSubscription">
    <ProfileCard />
    <div class="manageSubscription__Edit">
      <div class="manageSubscription__inner">
        <div class="manageSubscription__title">Manage subscription</div>
        <SubscriptionForm />
        <SubscriptionHistory :history="history" />
      </div>
    </div>
  </main>
</template>
<script setup>
import ProfileCard from '@/components/my-profile/ProfileCard.vue'
import SubscriptionForm from '@/components/subscription/SubscriptionForm.vue'
import handleError from '@/utils/handleError'
import History from '@/request/History.js'
import SubscriptionHistory from '@/components/subscription/SubscriptionHistory.vue'
import { ref, watch } from 'vue'
import { useUserStore } from '@/stores/user'

const user = useUserStore()

const history = ref({})
watch(
  user,
  async () => {
    try {
      const response = await History.myHistory()
      history.value = response.data.data
      history.value.reverse()
    } catch (error) {
      handleError(error)
    }
  },
  { immediate: true, deep: true }
)
</script>
