<template>
  <div class="mainBlogsTitle">
    <div class="mainBlogsTitle__wrapper">
      <h2 class="mainBlogs__title">Premium</h2>
    </div>
    <div class="mainBlogs__tags">
      <div class="mainBlogsTags__leftside">
        <div
          :to="{ name: 'home' }"
          class="mainBlogsTags__item"
          @click.prevent="filterStore.clearFilters()"
          :class="{ mainBlogsTags__item_active: filters.length === 0 }"
        >
          All
        </div>
        <div
          v-for="tag in tags"
          :key="tag.tag.id"
          class="mainBlogsTags__item"
          @click="selectTag(tag)"
          :class="{ mainBlogsTags__item_active: filterStore.isInArray(tag.id) }"
        >
          {{ tag.tag }}
        </div>
      </div>
      <a href="" class="mainBlogsTags__item">View All</a>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import Tag from '@/request/Tag.js'
import handleError from '@/utils/handleError'
import { useFilterStore } from '@/stores/filters'

const tags = ref([])

const filterStore = useFilterStore()

const filters = computed(() => filterStore.filter)

function selectTag(tag) {
  filterStore.addFilter(tag.id)
}

try {
  const response = await Tag.premiumTags()
  tags.value = response.data.data
} catch (error) {
  handleError(error)
}
</script>
