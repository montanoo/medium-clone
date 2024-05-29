import Http from './Http'

export default {
  myHistory() {
    return Http.get('/history/my')
  },
  myActive() {
    return Http.get('/history/my/active')
  },
}
