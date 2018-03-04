import Vue from 'vue';
import App from './App.vue';
import Vuex from 'vuex';
import axios from 'axios'
import { store } from './store/index';
import VueRouter from 'vue-router';
import Routes from './routes'

Vue.use(Vuex);
Vue.use(VueRouter);

const router = new VueRouter({
  routes: Routes
});

//Filters
Vue.filter('match-date', (value) => {
  return value.match(/\d{4}-\d{2}-\d{2}/)[0]
})

window.onload = function () {
  new Vue({
    store: store,
    router: router,
    el: '#app',
    render: h => h(App)
  })
}
