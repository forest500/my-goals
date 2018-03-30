import axios from 'axios'
import { AUTH_LOCATION } from "../../config"
import { REGISTER_LOCATION } from "../../config"

const  state = {
  token: localStorage.getItem('user-token') || '',
  refreshToken: '',
  status: '',
  authError: ''
}
const  getters = {
  isAuthenticated: state => !!state.token,
  refreshToken: state => state.refreshToken,
  authStatus: state => state.status,
  authError: state => state.authError
}
const mutations = {
  AUTH_REQUEST(state) {
    state.status = 'loading'
  },
  AUTH_SUCCESS(state, tokens)  {
    state.status = 'success'
    state.token = tokens.token
    state.refreshToken = tokens.refresh
    state.authError = ''
  },
  AUTH_ERROR(state, error) {
    state.authError = error
  },
  AUTH_LOGOUT(state) {
    state.status = ''
    state.token =''
  }
}
const actions = {
  authRequest({commit}, user) {
    return new Promise((resolve, reject) => {
      axios({url: AUTH_LOCATION, data: user, method: 'POST' })
        .then(response => {
          const token = response.data.token
          const refreshToken = response.data.refresh_token
          const tokens = { token: token, refresh: refreshToken }

          localStorage.setItem('user-token', token)
          commit('AUTH_SUCCESS', tokens)

          axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
          resolve(response)
        })
      .catch(errors => {
        commit('AUTH_ERROR', errors.response.data.message)
        localStorage.removeItem('user-token')
        reject(errors)
      })
    })
  },
  registerRequest({commit}, credentials) {
    return new Promise((resolve, reject) => {
      axios({url: REGISTER_LOCATION, data: credentials, method: 'POST' })
        .then(response => {
          commit('CLEAR_ERRORS')
          resolve(response)
        })
      .catch(errors => {
        if (errors.response.status === 400 && errors.response.data.type === "validation_error") {
          commit('HAS_ERRORS', true)
          commit('FORM_ERRORS', errors.response.data.errors)
        }
        reject(errors)
      })
    })
  },
  authLogout: ({commit}) => {
    return new Promise((resolve, reject) => {
      commit('AUTH_LOGOUT')
      localStorage.removeItem('user-token')
      delete axios.defaults.headers.common['Authorization']
      resolve()
    })
  },
}

export default {
  state,
  getters,
  actions,
  mutations
}
