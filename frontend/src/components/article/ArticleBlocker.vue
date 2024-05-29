<template>
  <div class="articleBlocker">
    <div class="articleBlocker__Inner">
      <div class="articleBlocker__Badge">
        <div class="blockerBadge">
          <ArticlePremiumSVG class="blockerBadge__White" />
        </div>
      </div>
      <div class="articleBlocker__Title">Premium article</div>
      <div class="articleBlocker__Text" v-if="userStore.isUserGuest">
        <p class="articleBlocker__Paragraph">
          This article is available to premium members only.
        </p>
        <p class="articleBlocker__Paragraph">
          If you are a premium member already, you can Log in to view the
          content. If not, you can become a premium user today by registering.
        </p>
      </div>
      <p class="articleBlocker__Text" v-if="userStore.isUserLoggedIn">
        This article is available to premium members only. You can start reading
        without restrictions by becoming a premium user today.
      </p>

      <div class="articleBlocker__Controls">
        <div class="blockerControls__inner" v-if="userStore.isUserGuest">
          <div
            class="blockerControls__button blockerControls__button-logIn"
            @click="modalStore.openModal('login')"
          >
            Log In
          </div>
          <div
            class="blockerControls__button"
            @click="modalStore.openModal('register')"
          >
            Register
          </div>
        </div>
        <div class="blockerControls__inner" v-if="userStore.isUserLoggedIn">
          <div
            class="blockerControls__button"
            @click="modalStore.openModal('premium')"
          >
            Go Premium
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useUserStore } from '@/stores/user.js'
import { useModalStore } from '@/stores/modal'
import ArticlePremiumSVG from '@/components/article/ArticlePremiumSVG.vue'

const modalStore = useModalStore()
const userStore = useUserStore()
</script>
