<template>
  <li class="mainArticle__category">
    <router-link
      :to="{ name: 'home' }"
      @click.prevent="handleTagChange(tag.id)"
      class="mainArticle__category-link"
      :class="{ mainArticle__category_active: filterStore.isInArray(tag.id) }"
      >{{ tag?.tag }}</router-link
    >
  </li>
</template>

<script setup>
import { defineProps } from 'vue'
import { useFilterStore } from '@/stores/filters'
import { useRouter } from 'vue-router'

const router = useRouter()

const filterStore = useFilterStore()

defineProps({
  tag: { type: Object, required: false },
})

const handleTagChange = (id) => {
  filterStore.addFilter(id)
  //   router.push('/')
}
</script>
