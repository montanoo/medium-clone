<script setup>
import CategoriesTag from '@/components/categoriesTag/CategoriesTag.vue'
import BlogAuthor from '@/components/blogCard/BlogAuthor.vue'
import SocialIcons from '@/components/socialIcons/SocialIcons.vue'
import EditorsPick from '@/components/editorsPick/EditorsPick.vue'
import CommentsList from '@/components/comment/CommentsList.vue'
import ArticleBlocker from '@/components/article/ArticleBlocker.vue'
import ArticleDiamond from '@/components/article/ArticlePremiumSVG.vue'
import { onMounted, ref, computed } from 'vue'
import Article from '../request/Article'
import { useRoute } from 'vue-router'
import handleError from '../utils/handleError'
import { useUserStore } from '@/stores/user.js'
import axios from 'axios'

const userStore = useUserStore()
const article = ref(null)
const editorsPickArticleResponse = ref([])

const route = useRoute()

const requestShow = Article.show(route.params.id)
const requestEditors = Article.index()

try {
  const [responseHome, responseEditorsPick] = await axios.all([
    requestShow,
    requestEditors,
  ])

  article.value = responseHome.data
  article.value.comments.reverse()
  editorsPickArticleResponse.value = responseEditorsPick.data.data.slice(0, 3)
} catch (error) {
  handleError(error)
}

const showBlockCondition = computed(() => {
  return (
    (article.value.is_premium && !userStore.isUserLoggedIn) ||
    (article.value.is_premium &&
      userStore.isUserLoggedIn &&
      !userStore.isUserPremium)
  )
})
</script>

<template>
  <main class="showArticle">
    <section class="mainArticle">
      <img
        :src="article.cover_url ?? '/images/article__main.jpg'"
        alt=""
        class="mainArticle__image mainArticle__image-smaller"
      />
      <div class="mainArticle__absolute">
        <div class="mainArticle__inner mainArticle__inner-centered">
          <div class="mainArticle__premium" v-if="article.is_premium">
            <ArticleDiamond />
          </div>
          <h2 class="mainArticle__heading mainArticle__heading-spaced">
            {{ article?.title }}
          </h2>
          <p class="mainArticle__content mainArticle__heading-spaced">
            <span class="mainArticle__content-text">
              {{ article?.description }}
            </span>
          </p>
          <p class="mainArticle__content mainArticle__content-bold">
            By {{ article?.author?.name }}
          </p>
        </div>
      </div>
    </section>
    <section class="articleText">
      <div class="articleText__inner">
        <p
          class="articleTextContent__paragraph"
          :class="{ articleTextContent__paragraphGradient: showBlockCondition }"
          v-html="article?.content"
        ></p>
        <div v-if="showBlockCondition" class="articlePremium__blocker">
          <ArticleBlocker />
        </div>
        <div v-else>
          <blockquote class="articleTextContent__quote" v-if="article?.quote">
            “ {{ article.quote }} ”
          </blockquote>
        </div>

        <ul class="mainArticle__categories mainArticle__categories-dark">
          <CategoriesTag
            v-for="tag in article?.tags"
            :key="tag.id"
            :tag="tag"
          />
        </ul>
        <div class="showArticle__footer">
          <BlogAuthor
            :author="article.author"
            class="mainBlogsCard__footer--centered"
          />
          <SocialIcons class="showArticleFooter__icons-inverted" />
        </div>
        <CommentsList
          v-if="!showBlockCondition"
          :comments="article?.comments"
          @new-comment-added="
            (newComment) => article.comments.unshift(newComment)
          "
        />
      </div>
    </section>

    <EditorsPick :articles="editorsPickArticleResponse" />
  </main>
</template>
