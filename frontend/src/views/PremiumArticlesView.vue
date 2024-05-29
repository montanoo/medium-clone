<script setup>
import { computed, ref, watch } from 'vue'
import ArticleCard from '../components/article/ArticleCard.vue'
import PremiumCategories from '@/components/premiumPage/PremiumCategories.vue'
import PremiumArticleMain from '@/components/premiumPage/PremiumArticleMain.vue'
import Article from '@/request/Article.js'
import handleError from '../utils/handleError'
import { useFilterStore } from '@/stores/filters'

const filterStore = useFilterStore()

const filter = computed(() => filterStore.filter)

const articles = ref([])

const filteredArticles = ref([])

try {
  const responsePremium = await Article.premium();
  articles.value = responsePremium.data.data;
} catch (error) {
  handleError(error)
}

watch(
  filter.value,
  async () => {
    if (!filter.value.length) return
    const response = await Article.byPremiumTag(filter.value)
    filteredArticles.value = response.data.data;
  },
  { immediate: true }
)
</script>

<template>
  <div class="premiumBlog">
    <PremiumArticleMain />
    <section class="mainBlogs">
      <div class="mainBlogs__inner">
        <PremiumCategories />
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
  </div>
</template>
