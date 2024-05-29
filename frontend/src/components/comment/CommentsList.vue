<script setup>
import { useRoute } from 'vue-router'
import * as CommentRequest from '../../request/Comment'
import { ref, defineProps, defineEmits, computed } from 'vue'
import Comment from './Comment.vue'
import Textarea from '@/components/general/Textarea.vue'
import Form from '@/components/general/Form.vue'
import { useUserStore } from '@/stores/user.js'
import Button from '@/components/general/Button.vue'
import UserImage from '@/components/userImage/UserImage.vue'

const userStore = useUserStore()

const user = computed(() => userStore.user)

defineProps({
  comments: { type: Array },
})

const emits = defineEmits(['newCommentAdded'])

const route = useRoute()
const commentText = ref('')
// const errors = ref({})
const isProcessing = ref(false)

const submitComment = async () => {
  const response = await CommentRequest.default.store({
    content: commentText.value,
    article_id: route.params.id,
  })
  emits('newCommentAdded', response.data)
  commentText.value = ''
}
</script>

<template>
  <div class="commentsList">
    <h3 class="commentsList__heading">Comments:</h3>

    <div class="commentList__inner">
      <div class="commentList__form">
        <UserImage
          :src="
            user?.avatar_url ??
            'https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png'
          "
          alt=""
          class="commentList__image"
        />
        <Form
          v-model:is-processing="isProcessing"
          :handle-logic="submitComment"
        >
          <Textarea
            v-model:value="commentText"
            is-comments
            name="comment"
            placeholder="Write a comment"
          />
          <Button type="submit" class="button__comments" :loading="isProcessing"
            >Send</Button
          >
        </Form>
      </div>
      <Comment
        v-for="comment in comments"
        :key="comment.id"
        :comment="comment"
      />
    </div>
  </div>
</template>
