<template>
  <img
    :src="
      avatar ??
      'https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png'
    "
    alt=""
    class="userImage__default"
  />
</template>
<script setup>
import { defineProps } from 'vue'

defineProps({
  avatar: { type: String },
})

</script>
<style lang=""></style>
