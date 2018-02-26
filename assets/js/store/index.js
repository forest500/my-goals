import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios'

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    categories: [],
    category: {},
    goals: [],
    hasErrors: false,
    formErrors: {},
  },
  getters: {
    categories: state => state.categories,
    category: state => state.category,
    goals: state => state.goals,
    hasErrors: state => state.hasErrors,
    formErrors: state => state.formErrors,
  },
  mutations: {
    SET_CATEGORIES(state, categories) {
      state.categories = categories
    },
    SET_CATEGORY(state, category) {
      state.category = category
    },
    SET_GOALS(state, goals) {
      state.goals = goals
    },
    ADD_CATEGORY(state, categoryObject) {
      state.categories.push(categoryObject)
    },
    DELETE_CATEGORY(state, categoryId) {
      state.categories.splice(categoryId, 1)
    },
    HAS_ERRORS(state, hasErrors) {
      state.hasErrors = hasErrors
    },
    FORM_ERRORS(state, formErrors) {
      state.formErrors = formErrors
    },
    CLEAR_ERRORS (state) {
      state.formErrors = {}
      state.hasErrors = false
    }
  },
  actions: {
    loadCategories({commit}) {
      axios.get(`http://localhost:8000/get_categories`)
        .then(response => {
          commit('SET_CATEGORIES', response.data)
        }).catch(error => {
          console.log(error)
        });
    },
    loadGoals({commit}) {
      axios.get(`http://localhost:8000/get_goals`)
        .then(response => {
          commit('SET_GOALS', response.data)
        }).catch(error => {
          console.log(error)
        });
    },
    loadCategoryGoals({commit}, id) {
      axios.get(`http://localhost:8000/get_category_goals/${id}`)
        .then(response => {
          commit('SET_GOALS', response.data)
        }).catch(error => {
          console.log(error)
        })
    },
    postCategory({commit}, category) {
      return axios.post('http://localhost:8000/new_category',{
         name: category.name,
         description: category.description
      })
        .then(response => {
          alert(response.data);
          commit('CLEAR_ERRORS')
        })
        .catch(errors => {
          if (errors.response.status === 400) {
            commit('HAS_ERRORS', true)
            commit('FORM_ERRORS', errors.response.data)
          }
        })
    },
    clearErrors({commit}) {
      commit('CLEAR_ERRORS')
    },
    editCategory({commit}, category) {
      return axios.put(`http://localhost:8000/update_category/${category.id}`,{
         name: category.name,
         description: category.description
      })
        .then(response => {
          alert(response.data);
          commit('CLEAR_ERRORS')
        })
        .catch(errors => {
          if (errors.response.status === 400) {
            commit('HAS_ERRORS', true)
            commit('FORM_ERRORS', errors.response.data)
          }
        })
    },
    deleteCategory({commit}, category) {
      axios.delete(`http://localhost:8000/delete_category/${category.id}`)
        .then(response => {
          alert(response.data)
          commit('DELETE_CATEGORY', category.index)
        }).catch(error => {
          alert(error.response.data)
        })
    }

  }
})
