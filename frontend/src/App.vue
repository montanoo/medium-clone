<script setup>
import { RouterView } from 'vue-router'
import Header from '@/components/layout/Header.vue'
import Footer from '@/components/layout/Footer.vue'
import GlobalLoader from '@/components/layout/GlobalLoader.vue'
import LoginModal from './components/auth/LoginModal.vue'
import RegisterModal from './components/auth/RegisterModal.vue'
import SubscriptionModal from './components/subscription/SubscriptionModal.vue'
import CancelSubscriptionModal from './components/subscription/CancelSubscriptionModal.vue'
import { useModalStore } from './stores/modal'
import { useUxStore } from './stores/ux'

const modalStore = useModalStore()
const uxStore = useUxStore()
</script>

<template>
  <Header />
  <RouterView v-slot="{ Component }">
    <template v-if="Component">
      <Transition mode="out-in">
        <Suspense>
          <!-- main content -->
          <component :is="Component" :key="$route.fullPath"></component>

          <!-- loading state -->
          <template #fallback>
            <GlobalLoader />
          </template>
        </Suspense>
      </Transition>
    </template>
  </RouterView>
  <GlobalLoader v-if="uxStore.isLoading" />
  <LoginModal v-if="modalStore.modal === 'login'" />
  <RegisterModal v-if="modalStore.modal === 'register'" />
  <SubscriptionModal v-if="modalStore.modal === 'premium'" />
  <CancelSubscriptionModal v-if="modalStore.modal === 'cancel'" />
  <Footer />
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
