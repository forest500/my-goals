import Vue from 'vue';
import App from './App.vue';
import Vuex from 'vuex';
import axios from 'axios'
import {store} from './store/index';
import VueRouter from 'vue-router';
import Routes from './routes'

Vue.use(Vuex);
Vue.use(VueRouter);

const router = new VueRouter({routes: Routes});
const token = localStorage.getItem('user-token');

if (token) {
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
}

axios.interceptors.response.use(undefined, function(err) {
  return new Promise(function(resolve, reject) {
    let res = err.response;
    if (res.status === 401 && res.config && !res.config.__isRetryRequest) {
      store.dispatch('authLogout').then(() => {
        router.push('/login')
      })
    }
    throw err
  })
})

//Filters
Vue.filter('match-err', (value) => {
  if(value) {
    return value.toString()
  }
})

new Vue({
  store: store,
  router: router,
  el: '#app',
  render: h => h(App)
})
