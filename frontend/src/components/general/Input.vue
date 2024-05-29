<template>
  <div class="input__group">
    <label v-if="label" class="input__label">{{ label }}</label>
    <input
      :name="name"
      :type="type"
      :value="value"
      :placeholder="placeholder"
      :max="max"
      :min="min"
      :pattern="pattern"
      :class="{
          'input-error': errorStore.getErrors(name),
          input: !isComments,
          commentsList__input: isComments,
      }"
      @input="(event) => onType(event, name)"
    />
    <p class="inputError__message" :class="{ absolute: isComments }">
      {{ errorStore.getErrors(name) }}
    </p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { useErrorStore } from '@/stores/error'

const errorStore = useErrorStore()

const emits = defineEmits(['update:value'])

function onType(event, name) {
    emits('update:value', event.target.value)
    errorStore.deleteErrors(name)
}

defineProps({
    label: String,
    name: String,
    type: String,
    placeholder: String,
    required: Boolean,
    isComments: Boolean,
    value: String,
    min: Number,
    max: Number,
    pattern: String
})
</script>

<script>
export default {
    name: 'InputComponent',
}
</script>
