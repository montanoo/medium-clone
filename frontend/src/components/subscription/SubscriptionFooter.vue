<template>
  <section class="subscriptionFooter">
    <div class="subscriptionFooter__inner">
      <div class="subscriptionFooter__heading">
        <h3 class="subscriptionFooter__title">My subscription</h3>
        <router-link
          :to="{ name: 'subscription' }"
          class="subscriptionFooter__info-edit"
        >
          {{ history ? 'Add subscription' : 'Manage subscription' }}
        </router-link>
      </div>
      <div class="subscriptionFooter__container">
        <div
          v-if="user.is_premium && history[0]"
          class="subscriptionFooter__container-inner"
        >
          <div class="subscriptionFooter__info">
            <h4 class="subscriptionFooter__info-heading">
              {{
                history[0].type === 'monthly'
                  ? 'Monthly Premium'
                  : 'Yearly Premium'
              }}
            </h4>
            <div class="subscriptionFooter__info-date">
              <span class="subcriptionFooter__info-date-title"
                >Expiry date:
              </span>
              <time>
                {{ format(new Date(history[0].expires_at), 'MM.dd.yyyy') }}
              </time>
            </div>
            <div class="subscriptionFooter__info-date">
              <span class="subcriptionFooter__info-date-title"
                >Start date:
              </span>
              <time>{{
                format(new Date(history[0].created_at), 'MM.dd.yyyy')
              }}</time>
            </div>
            <router-link
              :to="{ name: 'subscription' }"
              class="subscriptionFooter__info-edit"
            >
              Edit payment
            </router-link>
            <a
              @click="modalStore.openModal('cancel')"
              class="subscriptionFooter__info-cancel"
              >Cancel subscription</a
            >
          </div>
          <div class="subscriptionFooter__iconCircle">
            <IconSubscription class="subscriptionFooter__iconDiamond" />
          </div>
        </div>
        <div v-else class="subscriptionFooter__container-inner">
          <p class="subscriptionFooter__noSub">No active subscription.</p>
          <Button
            class="subscriptionFooter__noSub-button"
            type="button"
            :loading="false"
            @click="modalStore.openModal('premium')"
          >
            Go premium
          </Button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import Button from '@/components/general/Button.vue'
import IconSubscription from '@/components/icons/IconSubscription.vue'
import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'
import { computed, ref, watch } from 'vue'
import { format } from 'date-fns'
import History from '@/request/History.js'
import handleError from '@/utils/handleError'

const modalStore = useModalStore()
const userStore = useUserStore()

const user = computed(() => userStore.user)

const history = ref('')
watch(
  user,
  async () => {
    try {
      const response = await History.myActive()
      history.value = response.data.data
      history.value.reverse()
    } catch (error) {
      handleError(error)
    }
  },
  { deep: true, immediate: true }
)

defineProps({
  subscription: Object,
})
</script>
