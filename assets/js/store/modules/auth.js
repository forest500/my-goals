import axios from 'axios'
import { AUTH_LOCATION } from "../../config"

const  state = {
  // token: loacalStorage.getItem('user-toke') || '',
  status: '',
}
const  getters = {
  isAuthenticated: state => state.token,
  authStatus: state => state.status,
}
const mutations = {

}
const actions = {
  signIn({commit}, credentials) {
    axios.post(AUTH_LOCATION, credentials)
      .then(response => {
        console.log(response.data)
      }).catch(error => {
        console.log(error)
      })
  },
}

export default {
  state,
  getters,
  actions,
  mutations
}
