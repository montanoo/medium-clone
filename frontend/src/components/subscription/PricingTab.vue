<template>
  <div class="pricingTab">
    <div class="pricingTab__tabs">
      <div
        class="pricingTab__tab"
        :class="{ pricingTab__tabActive: plan === 'monthly' }"
        @click="setActiveTab('monthly')"
      >
        Monthly
      </div>
      <div
        class="pricingTab__tab"
        :class="{ pricingTab__tabActive: plan === 'yearly' }"
        @click="setActiveTab('yearly')"
      >
        Yearly
      </div>
    </div>

    <div class="pricingTab__content">
      <div v-if="plan === 'monthly'" class="pricingTab__plan">
        <div>
            <span class="princingTab_price">20€ </span>per month
        </div>
        <div>
            Total: <span class="princingTab_price">20€ </span>
        </div>
      </div>
      <div v-else-if="plan === 'yearly'" class="pricingTab__plan">
        <div>
            <span class="princingTab_price">15€ </span>per month
        </div>
        <div>
            Total: <span class="princingTab_price">180€ </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"

const emits = defineEmits(['plan-selected'])

defineProps({
    plan: String,
})

function setActiveTab(tab) {
    emits('plan-selected', tab)
}
</script>

<style lang="scss" scoped></style>
