<template>
  <div class="input__group">
    <label v-if="label" class="input__label">{{ label }}</label>
    <textarea
      :name="name"
      :type="type"
      :placeholder="placeholder"
      :value="value"
      :class="{
        'input-error': errorStore.getErrors('content'),
        input: !isComments,
        commentsList__input: isComments,
      }"
      class="textarea__Default"
      @input="(event) => onType(event, name)"
    />
    <p class="inputError__message" :class="{ absolute: isComments }">
      {{ errorStore.getErrors('content') }}
    </p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { useErrorStore } from '@/stores/error'

const errorStore = useErrorStore()

function onType(event, name) {
  emits('update:value', event.target.value)
  errorStore.deleteErrors(name)
}

const emits = defineEmits(['update:value'])

defineProps({
  label: String,
  name: String,
  type: String,
  placeholder: String,
  required: Boolean,
  isComments: Boolean,
  value: String,
})
</script>

<script>
export default {
  name: 'TextareaComponent',
}
</script>
