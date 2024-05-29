<template>
  <router-link :to="{ name: 'article.create' }" class="addArticle">
    <div class="addArticle__Inner">
      <AddSvg />
      <p class="addArticle__Text">Add new article</p>
    </div>
  </router-link>
</template>
<script>
export default {
  name: 'AddArticle',
}
</script>

<script setup>
import AddSvg from './AddSvg.vue'
</script>
