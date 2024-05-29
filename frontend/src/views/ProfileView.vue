<template>
  <main class="profileView">
    <ProfileCard />
    <div class="profileView__Articles">
      <div class="profileArticles__inner">
        <h1 class="profileArticles__Title">My articles</h1>
        <div class="profileArticles__Layout">
          <AddArticle />
          <EditorsCard
            v-for="article in userArticles"
            :key="article.id"
            :article="article"
            is-editable
          />
        </div>
      </div>
    </div>
    <SubscriptionFooter  />
  </main>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import ProfileCard from '@/components/my-profile/ProfileCard.vue'
import AddArticle from '@/components/my-profile/AddArticle.vue'
import Article from '@/request/Article.js'
import { useUserStore } from '@/stores/user.js'
import EditorsCard from '@/components/editorsPick/EditorsCard.vue'
import SubscriptionFooter from '../components/subscription/SubscriptionFooter.vue'
import History from '@/request/History.js'

const userStore = useUserStore()

const userArticles = ref([])

onMounted(() => {
  Article.byAuthor(userStore.user.id).then((response) => {
    userArticles.value = response.data.data
  })
})
</script>
