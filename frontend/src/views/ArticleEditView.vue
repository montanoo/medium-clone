<template>
  <div class="articleCreate">
    <CreateArticleHeader :articleTitle="article.title" />
    <ArticleForm :article="article" isEdit />
  </div>
</template>

<script setup>
import CreateArticleHeader from '@/components/createArticles/CreateArticleHeader.vue'
import ArticleForm from '../components/articleForm/ArticleForm.vue'
import { useRoute } from 'vue-router'
import { handleError, ref } from 'vue'
import Article from '@/request/Article.js'

const route = useRoute()

const article = ref([])

try {
  const response = await Article.show(route.params.id)
  article.value = response.data
} catch (err) {
  handleError(err)
}
</script>
