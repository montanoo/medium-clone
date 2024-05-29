<script setup>
import { defineProps } from 'vue'
import CategoriesTag from '@/components/categoriesTag/CategoriesTag.vue'
import BlogAuthor from '../blogCard/BlogAuthor.vue'
import ArticleDiamond from '@/components/icons/IconArticleDiamond.vue'
import { format } from 'date-fns'

defineProps({
  article: { type: Object, required: true },
})
</script>

<template>
  <article class="mainBlogs__card">
    <section class="mainBlogsCardImage_Tag">
      <router-link :to="{ name: 'article.show', params: { id: article.id } }">
        <img
          :src="article.cover_url ?? 'https://unsplash.it/300/200'"
          alt=""
          class="mainBlogsCard__image"
        />
      </router-link>
      <div class="mainArticle__categories mainArticle__category-absolute">
        <CategoriesTag v-for="tag in article?.tags" :key="tag.id" :tag="tag" />
      </div>
    </section>
    <section class="mainBlogsCard__info">
      <div class="mainBlogsCardInfo__inner">
        <div class="mainBlogsCardInfo__misc">
          <time class="mainBlogsCardInfo__time">{{
            format(new Date(article.created_at), 'MM.dd.yyyy')
          }}</time>
          <div v-if="true" class="mainBlogsCardInfo__feature">
            <ArticleDiamond v-if="article.is_premium"/>
          </div>
        </div>
        <h3 class="mainBlogsCard__title">
          {{ article.title }}
        </h3>
        <p class="mainBlogsCard__summary">
          {{ article.description }}
        </p>
        <BlogAuthor :author="article.author" />
      </div>
    </section>
  </article>
</template>
