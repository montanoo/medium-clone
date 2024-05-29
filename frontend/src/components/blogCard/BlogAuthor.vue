<script setup>
import UserImage from '@/components/userImage/UserImage.vue'
defineProps({
  author: { type: Object, required: true },
})
</script>

<template>
  <div class="mainBlogsCard__footer">
    <UserImage :avatar="author.avatar_url" class="mainBlogsCardFooter__img" />
    <div class="mainBlogsCardFooter__author">
      <h3 class="mainBlogsCardFooter__name">By {{ author.name }}</h3>
      <p class="mainBlogsCardFooter__position">{{ author.email }}</p>
    </div>
  </div>
</template>
