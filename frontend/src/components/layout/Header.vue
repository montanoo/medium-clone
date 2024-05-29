<template>
  <header class="header">
    <div class="header__inner">
      <h1 class="header__title">RUNO</h1>
      <nav class="header__nav">
        <ul class="header__nav-list">
          <li class="header__nav-item">
            <router-link class="header__nav-item-link" :to="{ name: 'home' }"
              >Home</router-link
            >
          </li>
          <!-- <li class="header__nav-item">
            <router-link class="header__nav-item-link" to="#"
              >About</router-link
            >
          </li> -->
          <li class="header__nav-item">
            <router-link
              class="header__nav-item-link"
              :to="{ name: 'articles.index' }"
              >Articles</router-link
            >
          </li>
          <li class="header__nav-item">
            <router-link
              class="header__nav-item-link"
              :to="{ name: 'articles.premium' }"
              >Premium</router-link
            >
          </li>
          <li v-if="userStore.isUserLoggedIn" class="header__nav-item">
            <router-link
              class="header__nav-item-link"
              :to="{ name: 'profile.index' }"
              >My profile</router-link
            >
          </li>
          <li v-if="userStore.isUserGuest" class="header__nav-item">
            <router-link
              class="header__nav-item-link"
              to="#"
              @click.prevent="modalStore.openModal('login')"
              >Sign In</router-link
            >
          </li>
          <li v-if="userStore.isUserGuest" class="header__nav-item">
            <router-link
              class="header__nav-item-link"
              to="#"
              @click.prevent="modalStore.openModal('register')"
              >Register</router-link
            >
          </li>
          <li v-if="userStore.isUserLoggedIn" class="header__nav-item">
            <router-link
              class="header__nav-item-link"
              to="#"
              @click.prevent="logout"
              >Logout
            </router-link>
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  name: 'HeaderComponent',
}
</script>

<script setup>
import { useUserStore } from '@/stores/user.js'
import { useModalStore } from '@/stores/modal'
import router from '../../router'

const modalStore = useModalStore()
const userStore = useUserStore()

function logout() {
  userStore.logoutUser()
  router.push('/')
}
</script>
