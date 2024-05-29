<script setup>
import CategoriesTag from '@/components/categoriesTag/CategoriesTag.vue'
import ArticleDiamond from '@/components/icons/IconArticleDiamond.vue'
import { format } from 'date-fns'
import { defineProps } from 'vue'

defineProps({
  article: { type: Object, required: true },
  isEditable: { type: Boolean, default: false },
})
</script>

<template>
  <router-link
    class="editorsPick__card"
    :to="{ name: 'article.show', params: { id: article.id } }"
  >
    <div class="editorsPickCard__inner">
      <img
        :src="article.cover_url ?? '/images/editor__image.jpg'"
        class="editorsPickCard__image"
      />
      <div class="editorsPick__absolute">
        <div class="mainArticle__categories mainArticle__category-absolute">
          <ul class="mainArticle__categories">
            <CategoriesTag
              v-for="tag in article?.tags"
              :key="tag.id"
              :tag="tag"
            />
          </ul>
        </div>
        <router-link
          v-if="isEditable"
          class="mainArticle__editable"
          :to="{ name: 'article.edit', params: { id: article.id } }"
        >
          <svg
            width="30"
            height="30"
            viewBox="0 0 30 30"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M30 15C30 23.2843 23.2843 30 15 30C6.71573 30 0 23.2843 0 15C0 6.71573 6.71573 0 15 0C23.2843 0 30 6.71573 30 15Z"
              fill="white"
            />
            <path
              d="M18.2357 9.32817C18.182 9.2744 18.1103 9.24474 18.0337 9.24474C17.9572 9.24474 17.8855 9.27434 17.8317 9.32817L16.6363 10.5235C16.5249 10.6349 16.5249 10.8162 16.6363 10.9276L18.6745 12.9658C18.7283 13.0195 18.8 13.0492 18.8765 13.0492C18.953 13.0492 19.0248 13.0195 19.0785 12.9658L20.2739 11.7704C20.3853 11.659 20.3853 11.4777 20.2739 11.3663L18.2357 9.32817Z"
              fill="black"
            />
            <path
              d="M22.2044 8.74554L20.8566 7.39771C20.8028 7.34394 20.7311 7.31429 20.6545 7.31429C20.5781 7.31429 20.5063 7.34394 20.4525 7.39771L19.6388 8.21149C19.585 8.26531 19.5553 8.33703 19.5553 8.41349C19.5553 8.49 19.5849 8.56177 19.6388 8.61554L20.9866 9.96337C21.0404 10.0171 21.1121 10.0468 21.1886 10.0468C21.2651 10.0468 21.3369 10.0171 21.3907 9.96337L22.2044 9.1496C22.3158 9.03823 22.3158 8.85691 22.2044 8.74554Z"
              fill="black"
            />
            <path
              d="M15.4695 12.1188C15.4157 12.065 15.344 12.0354 15.2675 12.0354C15.191 12.0354 15.1193 12.065 15.0655 12.1188L7.12466 20.0597C7.01329 20.171 7.01329 20.3523 7.12466 20.4637L7.50757 20.8466L6.87557 22.5198C6.84249 22.6075 6.86009 22.6614 6.88066 22.6912C6.9046 22.7258 6.9442 22.7449 6.99226 22.7449C7.01974 22.7449 7.05009 22.7387 7.08231 22.7265L8.75551 22.0945L9.13843 22.4774C9.1922 22.5312 9.26391 22.5609 9.34043 22.5609C9.41694 22.5609 9.48866 22.5313 9.54243 22.4774L17.4833 14.5366C17.5947 14.4253 17.5947 14.2439 17.4833 14.1326L15.4695 12.1188Z"
              fill="black"
            />
          </svg>
        </router-link>
        <div class="editorsPickCard__information">
          <div class="editorsPickCard__feature" @v-if="true">
            <ArticleDiamond v-if="article.is_premium" />
          </div>
          <time class="editorsPickCard__time">{{
            format(new Date(article.created_at), 'MM.dd.yyyy')
          }}</time>
          <h4 class="editorsPickCard__title">
            {{ article.title }}
          </h4>
          <p class="editorsPickCard__summary">
            {{ article.description }}
          </p>
        </div>
      </div>
    </div>
  </router-link>
</template>
