<template>
  <div class="articles">
    <div class="mainBlogs__inner mainBlogs__inner-margin">
      <main class="mainBlog__cards">
        <ArticleCard
          v-for="article in articles"
          :key="article.id"
          :article="article"
        />
      </main>
    </div>
    <EditorsPick
      v-if="editorsPickArticleResponse"
      :articles="editorsPickArticleResponse"
    />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import Article from '../request/Article'
import ArticleCard from '@/components/article/ArticleCard.vue'
import EditorsPick from '@/components/editorsPick/EditorsPick.vue'
import handleError from '../utils/handleError'
import axios from 'axios'

const articles = ref([])
const editorsPickArticleResponse = ref([])

const requestHome = Article.index()
const requestEditors = Article.index()

try {
  const [responseHome, responseEditorsPick] = await axios.all([
    requestHome,
    requestEditors,
  ])
  articles.value = responseHome.data.data
  editorsPickArticleResponse.value = responseEditorsPick.data.data.slice(0, 3)
} catch (error) {
  handleError(error)
}
</script>
