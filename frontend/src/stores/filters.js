import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useFilterStore = defineStore('filter', () => {
  const filter = ref([])

  const addFilter = (id) => {
    if (filter.value.indexOf(id) != -1) return removeFilter(id)
    filter.value.push(id)
  }

  const removeFilter = (id) => {
    if (filter.value.indexOf(id) == -1) return
    filter.value.splice(filter.value.indexOf(id), 1)
  }

  const isInArray = (id) => {
    if (filter.value.indexOf(id) == -1) return false
    return true
  }

  const clearFilters = () => {
    filter.value.splice(0, filter.value.length)
  }

  const isEmpty = () => {
    return filter.value.length === 0
  }

  return { filter, addFilter, removeFilter, isInArray, clearFilters, isEmpty }
})
