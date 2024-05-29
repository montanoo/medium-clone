<template>
  <div class="subscriptionHistory">
    <div class="subscriptionHistory__Inner">
      <div class="subscriptionHistory__Header" @click="accordion = !accordion">
        <h1 class="subscriptionHistory__Title">Purchase History</h1>
        <div class="subscriptionHistory__Dropdown">
          <svg
            width="9"
            height="7"
            viewBox="0 0 9 7"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M9 1.88966L4.49993 6.48218L-5.52184e-07 1.88966L1.40497 0.517603L4.49993 3.67618L7.59503 0.517603L9 1.88966Z"
              fill="#818181"
            />
          </svg>
        </div>
      </div>
      <div
        class="subscriptionHistory__Content-closed"
        :class="{ subscriptionHistory__Content_open: accordion }"
      >
        <div
          class="subscriptionHistory__Content subscriptionHistory__Content-header"
        >
          <h2 class="subscriptionContent__Header">Transaction Id</h2>
          <h2 class="subscriptionContent__Header">Start</h2>
          <h2 class="subscriptionContent__Header">End</h2>
          <h2 class="subscriptionContent__Header">Status</h2>
        </div>
        <div
          v-for="(subscription, idx) in history"
          :key="idx"
          class="subscriptionHistory__Content"
        >
          <div class="subscriptionContent__Cell">
            {{ subscription.id.toString().padStart(5, '0') }}
          </div>
          <div
            class="subscriptionContent__Cell subscriptionContent__Date subscriptionContent__Date-start"
          >
            {{ format(new Date(subscription.created_at), 'MM.dd.yyyy') }}
          </div>
          <div
            class="subscriptionContent__Cell subscriptionContent__Date subscriptionContent__Date-end"
          >
            {{ format(new Date(subscription.expires_at), 'MM.dd.yyyy') }}
          </div>
          <div
            class="subscriptionContent__Cell"
            :class="{ subscriptionContent__active: !subscription.is_expired }"
          >
            {{ subscription.is_expired ? 'Ended' : 'Active' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { defineProps, ref } from 'vue'
import { format } from 'date-fns'

const accordion = ref(false)

defineProps({
  history: { type: Object, required: false },
})
</script>
