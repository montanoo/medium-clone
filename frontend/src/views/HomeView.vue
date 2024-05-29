<script setup>
import { computed, ref, watch } from 'vue'
import ArticleCard from '../components/article/ArticleCard.vue'
import ArticleMain from '../components/article/ArticleMain.vue'
import HomeCategories from '@/components/homePage/HomeCategories.vue'
import EditorsPick from '@/components/editorsPick/EditorsPick.vue'
import Article from '@/request/Article.js'
import handleError from '../utils/handleError'
import axios from 'axios'
import { useFilterStore } from '@/stores/filters'

const filterStore = useFilterStore()

const filter = computed(() => filterStore.filter)

const articles = ref([])
const editorsPickArticleResponse = ref([])

const filteredArticles = ref([])

const featuredArticle = computed(() => {
  return articles.value[0]
})

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

watch(
  filter.value,
  async () => {
    if (!filter.value.length) return
    const response = await Article.byTag(filter.value)
    filteredArticles.value = response.data.data
  },
  { immediate: true }
)
</script>

<template>
  <div>
    <ArticleMain :article="featuredArticle" />
    <section class="mainBlogs">
      <div class="mainBlogs__inner">
        <HomeCategories />
        <TransitionGroup name="slide" tag="main" class="mainBlog__cards">
          <ArticleCard
            v-if="!filter.length"
            v-for="article in articles"
            :key="article.id"
            :article="article"
          />
        </TransitionGroup>
        <TransitionGroup name="slide" tag="main" class="mainBlog__cards">
          <ArticleCard
            v-if="filter.length"
            v-for="article in filteredArticles"
            :key="article.id"
            :article="article"
          />
        </TransitionGroup>
      </div>
    </section>
    <EditorsPick :articles="editorsPickArticleResponse" />
  </div>
</template>
