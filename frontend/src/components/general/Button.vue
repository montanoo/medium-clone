<template>
  <button class="button__modal" :type="type" :disabled="loading">
    <slot />
    <div v-if="loading" class="button__loader" />
  </button>
</template>

<script>
export default {
  name: 'ButtonComponent',
}
</script>
<script setup>
import { defineProps } from 'vue'
defineProps({
  type: String,
  loading: { type: Boolean, default: false },
})
</script>
