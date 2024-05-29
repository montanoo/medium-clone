import Http from './Http'

export default {
    index() {
        return Http.get('/tags')
    },
    show(like) {
        return Http.get(`/tag?search=${like}`)
    },
    create(tag) {
        return Http.post('/tags', tag)
    },
    premiumTags() {
        return Http.get('/premium-tags')
    }
}
