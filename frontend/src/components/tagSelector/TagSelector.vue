<template>
  <div>
    <div>
      <label class="input__label">Tags</label>
      <multiselect
        v-model="selectedTags"
        tag-placeholder="Add this as new tag"
        placeholder="Type to search or add tag"
        open-direction="bottom"
        label="tag"
        track-by="id"
        :options="tags"
        :multiple="true"
        :searchable="true"
        :loading="isLoading"
        :internal-search="false"
        :clear-on-select="false"
        :close-on-select="false"
        :options-limit="300"
        :max="3"
        :max-height="600"
        :show-no-results="false"
        :hide-selected="true"
        @tag="addTag"
        :taggable="true"
        @search-change="asyncFind"
        :value="value"
        class="tagSelector__multiselect"
      >
        <template #maxElements>
          Maximum of 3 tags selected. Remove a selected tag to select another.
        </template>
      </multiselect>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue'
import Multiselect from 'vue-multiselect'
import Tag from '@/request/Tag.js'
import debounce from 'lodash.debounce'

const selectedTags = ref(props.value)
const tags = ref([])

const emits = defineEmits(['update:value'])

const isLoading = ref(false)

watch(selectedTags, () => {
  emits('update:value', selectedTags.value)
})

const asyncFind = debounce(async (query) => {
  // avoid making empty request to backend
  if (!query) return

  // it would be nice to add a debounce!
  isLoading.value = true
  const response = await Tag.show(query)
  tags.value = response.data.tags
  isLoading.value = false
}, 250)

const addTag = async (tag) => {
  const response = await Tag.create({ tag })
  selectedTags.value.push(response.data)
}

const props = defineProps({
  value: { type: Object },
})
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
