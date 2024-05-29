<template>
  <div class="comment">
    <div class="comment__inner">
      <div class="commentImage__wrapper">
        <UserImage
          :avatar="comment.author?.avatar_url"
          alt=""
          class="commentList__image"
        />
      </div>
      <div class="commentInformation__wrapper">
        <div class="comment__name">{{ comment?.author?.name }}</div>
        <div class="comment__timestamps">
          {{ format(new Date(comment?.created_at), 'MM.dd.yyyy') }} -
          {{ formattedDate }} ago
        </div>
        <div class="comment__content">{{ comment?.content }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CommentComponent',
}
</script>

<script setup>
import { computed, defineProps } from 'vue'
import { format, formatDistanceToNow } from 'date-fns'
import UserImage from '@/components/userImage/UserImage.vue'

const props = defineProps({
  comment: { type: Object },
})

const formattedDate = computed(() => {
  return formatDistanceToNow(new Date(props.comment?.created_at))
})
</script>
