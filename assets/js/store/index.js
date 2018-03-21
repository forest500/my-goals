import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate'
import index from "./modules/index"
import auth from "./modules/auth"

Vue.use(Vuex);

export const store = new Vuex.Store({
  modules: {
    index,
    auth
  },
  // plugins: [
  //   createPersistedState({ storage: window.sessionStorage })
  // ]
})
