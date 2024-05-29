<script setup>
import CategoriesTag from '@/components/categoriesTag/CategoriesTag.vue'
import { format } from 'date-fns'
import { computed } from 'vue'

const props = defineProps({
  article: { type: Object, required: true },
})

const formattedDate = computed(() => {
  return format(new Date(props.article.created_at), 'MM.dd.yyyy')
})
</script>

<template>
  <section class="mainArticle">
    <img
      :src="article.cover_url ?? '/images/main__image.png'"
      alt=""
      class="mainArticle__image"
    />
    <div class="mainArticle__absolute">
      <div class="mainArticle__inner">
        <ul class="mainArticle__categories mainArticle__category-left">
          <CategoriesTag
            v-for="tag in article?.tags"
            :key="tag.id"
            :tag="tag"
          />
        </ul>
        <h2 class="mainArticle__heading">
          {{ article?.title }}
        </h2>
        <p class="mainArticle__content">
          <time class="mainArticle__content-time">{{ formattedDate }}</time>
          <span class="mainArticle__content-divider"></span>
          <span class="mainArticle__content-text">
            {{ article?.content }}
          </span>
        </p>
        <div class="mainArticle__position">
          <div class="mainArticle__position-item-active"></div>
          <div class="mainArticle__position-item"></div>
          <div class="mainArticle__position-item"></div>
        </div>
      </div>
    </div>
  </section>
</template>
