import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import User from '@/request/User'
import handleError from '../utils/handleError'

export const useUserStore = defineStore('user', () => {
    const user = ref(undefined)

    const isUserLoggedIn = computed(() => user.value !== undefined)
    const isUserGuest = computed(() => user.value === undefined)
    const isUserPremium = computed(() => user.value?.is_premium)

    function setUser(newUser) {
        user.value = newUser
    }

    async function loginUser() {
        try {
            const response = await User.my()
            user.value = response.data
        } catch (error) {
            handleError(error)
            user.value = undefined
        }
    }

    async function logoutUser() {
        await User.logout()
        localStorage.removeItem('token')
        user.value = undefined
    }

    return { user, isUserLoggedIn, isUserGuest, setUser, logoutUser, loginUser, isUserPremium }
})
