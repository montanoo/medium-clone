<template>
  <div class="articleCreate__Form">
    <div class="articleForm__inner">
      <Form
        v-model:is-processing="isProcessing"
        :handle-logic="!isEdit ? createArticle : editArticle"
      >
        <h1 class="articleCreate__Title articleCreate__Title-grey">
          {{ article ? 'Edit content' : 'Add content' }}
        </h1>
        <div class="articleForm__layout">
          <div class="articleForm__Column">
            <Input
              v-model:value="form.title"
              name="title"
              label="Title"
              type="text"
              placeholder="Set title"
            />
            <Input
              v-model:value="form.description"
              name="description"
              label="Description"
              type="text"
              placeholder="Set description"
            />
            <Input
              v-model:value="form.quote"
              name="quote"
              label="Quote"
              type="text"
              placeholder="Set a quote"
            />
            <div :class="{ inputContent__error: errorStore.errors.content }">
              <label class="input__label">Content</label>
              <QuillEditor
                v-model:content="form.content"
                theme="snow"
                placeholder="Add content"
                content-type="html"
                @text-change="errorStore.deleteErrors('content')"
              />
              <p class="inputError__message">
                {{ errorStore.getErrors('content') }}
              </p>
            </div>
          </div>
          <div class="articleForm__Column">
            <TagSelector v-model:value="tags" />
            <div
              v-if="user.is_premium || article?.is_premium"
              class="articleForm__premium"
            >
              <input
                id="premium"
                v-model="form.is_premium"
                class="articleForm__checkbox"
                type="checkbox"
                name="premium"
              />
              <label for="premium" class="articleForm__checkbox-label"
                >Premium article</label
              >
            </div>
            <div>
              <label class="input__label">Cover</label>
              <div class="articleForm__upload" @click="show = !show">
                <p class="articleUpload__text">
                  {{
                    form.cover_photo
                      ? 'Click here to edit your cover'
                      : 'Click here to add your cover'
                  }}
                </p>
              </div>
            </div>
            <div>
              <label class="input__label">Preview image</label>
              <img
                @click="show = !show"
                v-if="renderedPhoto"
                :src="renderedPhoto"
                class="articleCover__preview"
              />
            </div>
          </div>
        </div>
        <my-upload
          v-model="show"
          field="img"
          :width="1500"
          :height="600"
          lang-type="en"
          img-format="png"
          no-circle
          no-square
          @cropSuccess="cropSuccess"
        ></my-upload>
        <Button type="submit" :loading="isProcessing">
          {{ article ? 'Edit article' : 'Add new' }}
        </Button>
      </Form>
    </div>
  </div>
</template>
<script setup>
import Form from '@/components/general/Form.vue'
import Input from '@/components/general/Input.vue'
import Button from '@/components/general/Button.vue'
import { QuillEditor } from '@vueup/vue-quill'
import myUpload from 'vue-image-crop-upload'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { ref } from 'vue'
import Article from '@/request/Article'
import { toast } from 'vue3-toastify'
import TagSelector from '@/components/tagSelector/TagSelector.vue'
import { useErrorStore } from '@/stores/error.js'
import { useUserStore } from '@/stores/user.js'

const errorStore = useErrorStore()
const userStore = useUserStore()

const user = userStore.user

const isProcessing = ref(false)

const tags = ref(props.article ? props.article.tags : [])

const show = ref(false)

const form = ref({
  title: props.article ? props.article.title : '',
  description: props.article ? props.article.description : '',
  content: props.article ? props.article.content : '',
  quote: props.article ? props.article?.quote : '',
  is_premium: props.article ? props.article?.is_premium : false, //
  cover_photo: '',
})

const renderedPhoto = ref(props.article ? props.article.cover_url : '')

const createArticle = async () => {
  const tagArray = tags.value.map(({ tag }) => tag)
  await Article.create({
    ...form.value,
    tags: tagArray,
  })
  toast('Successfully created article, check it out at your profile!', {
    autoClose: 1000,
    theme: 'dark',
    position: toast.POSITION.BOTTOM_RIGHT,
    hideProgressBar: true,
  })
}

const editArticle = async () => {
  const tagArray = tags.value.map(({ tag }) => tag)
  await Article.edit(
    {
      ...form.value,
      tags: tagArray,
    },
    props.article.id
  )
  toast('Successfully edited article!', {
    autoClose: 1000,
    theme: 'dark',
    position: toast.POSITION.BOTTOM_RIGHT,
    hideProgressBar: true,
  })
}

const cropSuccess = (imageDataUrl) => {
  form.value.cover_photo = imageDataUrl
  renderedPhoto.value = imageDataUrl
}

const props = defineProps({
  article: { type: Object, required: false },
  isEdit: { type: Boolean, default: false },
})
</script>
