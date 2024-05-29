import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useUserStore } from '../stores/user'
import { useUxStore } from '../stores/ux'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
        },
        {
            path: '/article/:id',
            name: 'article.show',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/ShowArticle.vue'),
        },
        {
            path: '/articles',
            name: 'articles.index',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/ArticlesView.vue'),
        },
        {
            path: '/articles/premium',
            name: 'articles.premium',
            component: () => import('../views/PremiumArticlesView.vue'),
        },
        {
            path: '/my-profile',
            name: 'profile.index',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/ProfileView.vue'),
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/my-profile/edit',
            name: 'profile.edit',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/ProfileEdit.vue'),
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/article/create',
            name: 'article.create',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/ArticleCreateView.vue'),
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/article/edit/:id',
            name: 'article.edit',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/ArticleEditView.vue'),
            meta: {
                requiresAuth: true,
            },
        },
        {
          path: '/subscription',
          name: 'subscription',
          // route level code-splitting
          // this generates a separate chunk (About.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import('../views/ManageSubscriptionView.vue'),
          meta: {
            requiresAuth: true,
          },
        },
    ],
    scrollBehavior() {
        return {
            top: 0,
            behavior: 'smooth',
        }
    },
})

router.beforeEach(async (to, from, next) => {
    const userStore = useUserStore()
    const uxStore = useUxStore()

    if (localStorage.getItem('token') && userStore.isUserGuest) {
        uxStore.setLoading(true)
        await userStore.loginUser()
        uxStore.setLoading(false)
    }

    if (to.meta.requiresAuth && userStore.isUserGuest) {
        return next({ name: 'home' })
    }

    return next()
})

export default router
