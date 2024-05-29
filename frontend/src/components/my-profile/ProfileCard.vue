<template>
  <div v-if="user" class="profileCard">
    <div class="profileCard__inner">
      <div class="profileCard__image" @click="show = !show">
        <UserImage class="profileCard__image" :avatar="profileImg" />
        <div class="profileCard__premium" v-if="user.is_premium">
          <UserDiamond />
        </div>
      </div>
      <my-upload
        v-model="show"
        field="img"
        :width="300"
        :height="300"
        :url="uploadUrl"
        :headers="uploadHeaders"
        lang-type="en"
        img-format="png"
        @crop-upload-success="cropUploadSuccess"
      ></my-upload>
      <div class="profileCard__name">{{ user.name }}</div>
      <div class="profileCard__email">{{ user.email }}</div>
      <div class="profileCard__subscription"></div>
      <div class="profileCard__options">
        <router-link
          v-if="route.fullPath === '/my-profile'"
          :to="{ name: 'profile.edit' }"
          class="profileCard__option profileCard__edit"
          >Edit profile</router-link
        >
        <router-link
          v-if="route.fullPath !== '/my-profile'"
          :to="{ name: 'profile.index' }"
          class="profileCard__option profileCard__edit"
          >Back to profile</router-link
        >
        <router-link
            v-if="route.fullPath !== '/subscription'"
            :to="{ name: 'subscription' }"
            class="profileCard__option profileCard__request"
          >Manage subscription</router-link
        >
      </div>
    </div>
  </div>
</template>
<script setup>
import { computed, ref } from 'vue'
import { useUserStore } from '@/stores/user.js'
import { useRoute } from 'vue-router'
import myUpload from 'vue-image-crop-upload'
import UserImage from '@/components/userImage/UserImage.vue'
import UserDiamond from '@/components/article/ArticlePremiumSVG.vue'

const uploadUrl = ref(`${import.meta.env.VITE_API_URL}/my/upload-url`)
const show = ref(false)
const route = useRoute()
const userStore = useUserStore()

const user = computed(() => userStore.user)
const profileImg = computed(() => user.value?.avatar_url)

const uploadHeaders = ref({
    Authorization: `Bearer ${localStorage.getItem('token')}`,
})

const cropUploadSuccess = (jsonData) => {
    userStore.setUser(jsonData)
}
</script>
