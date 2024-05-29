import Http from './Http'

export default {
    index() {
        return Http.get('/articles')
    },
    show(id) {
        return Http.get(`/articles/${id}`)
    },
    byAuthor(authorId) {
        return Http.get(`/articles${authorId ? `?author_id=${authorId}` : ''}`)
    },
    create(params) {
        return Http.post('/articles', params)
    },
    edit(params, id) {
        return Http.post(`/articles/${id}`, { _method: 'PATCH', ...params })
    },
    byTag(tag) {
        if (tag.length == 1) {
            return Http.get(`/articles${tag ? `?tag_ids[]=${tag}` : ''}`)
        }
        return Http.get(`/articles${tag ? `?tag_ids=${tag}` : ''}`)
    },
    premium() {
        return Http.get('/premium-articles')
    },
    byPremiumTag(tag) {
        return Http.get(`/premium-articles${tag ? `?tag_ids[]=${tag}` : ''}`)
    }
}
