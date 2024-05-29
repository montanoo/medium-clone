<script setup>
import EditorsCard from './EditorsCard.vue'

import { defineProps } from 'vue'

defineProps({
  articles: { type: Object, required: true },
})
</script>

<template>
  <section class="editorsPick">
    <div class="editorsPick__inner">
      <h3 class="editorsPick__title">Editor's Pick</h3>
      <div class="editorsPick__cards">
        <EditorsCard
          v-for="article in articles"
          :key="article.id"
          :article="article"
        />
      </div>
    </div>
  </section>
</template>
