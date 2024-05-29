import Http from './Http'

export default {
  async store(params) {
    return await Http.post(`/comments`, params)
  },
}
