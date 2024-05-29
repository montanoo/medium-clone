import Http from './Http'

export default {
  async register(params) {
    return await Http.post('/register', params)
  },
  async login(params) {
    return await Http.post('/authenticate', params)
  },
  async my(params) {
    return await Http.get('/my', params)
  },
  async logout(params) {
    return await Http.get('/logout', params)
  },
  async edit(id, params) {
    return await Http.post(`/users/${id}`, { ...params, _method: 'PATCH' })
  },
}
