import Http from './Http'

export default {
  async subscribe(params) {
    return await Http.post(`/history/subscribe`, params)
  },
  async unsubscribe(params) {
    return await Http.post(`/history/unsubscribe`, params)
  },
}
