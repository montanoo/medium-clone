<template lang="">
  <div class="createArticleHeader">
    <div class="articleCreate__inner">
      <div class="articleCreateTitle__Wrapper">
        <h1 class="articleCreate__Title articleCreate__Title-white">
          {{ articleTitle ?? 'Article editor' }}
        </h1>
        <div class="profileCard__subscription"></div>
        <router-link
          :to="{ name: 'profile.index' }"
          class="profileCard__option profileCard__edit"
          >Back to profile</router-link
        >
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

defineProps({
  articleTitle: { type: String, required: false },
})
</script>
