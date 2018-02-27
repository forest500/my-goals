import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios'
import createPersistedState from 'vuex-persistedstate'
import * as Cookies from 'js-cookie'

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    categories: [],
    category: {},
    categoryInForm: {},
    goals: [],
    stages: [],
    hasErrors: false,
    formErrors: {},
  },
  getters: {
    categories: state => state.categories,
    category: state => state.category,
    categoryInForm: state => state.categoryInForm,
    goals: state => state.goals,
    goal: state => state.goal,
    stages: state => state.stages,
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
    SET_CATEGORY_IN_FORM(state) {
      state.categoryInForm = Object.assign({}, state.category)
    },
    SET_CATEGORY_IN_FORM_NAME(state, categoryName) {
      state.categoryInForm.name = categoryName
    },
    SET_CATEGORY_IN_FORM_DESCRIPTION(state, description) {
      state.categoryInForm.description = description
    },
    SET_CATEGORY_NAME(state, name) {
      state.category.name = name
    },
    SET_GOALS(state, goals) {
      state.goals = goals
    },
    SET_GOAL(state, goal) {
      state.goal = goal
    },
    SET_STAGES(state, stages) {
      state.stages = stages
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
      return axios.get(`http://localhost:8000/get_categories`)
        .then(response => {
          commit('SET_CATEGORIES', response.data)
        }).catch(error => {
          console.log(error)
        });
    },
    loadGoals({commit}) {
      return axios.get(`http://localhost:8000/get_goals`)
        .then(response => {
          commit('SET_GOALS', response.data)
        }).catch(error => {
          console.log(error)
        });
    },
    loadCategoryGoals({commit}, id) {
      return axios.get(`http://localhost:8000/get_category_goals/${id}`)
        .then(response => {
          commit('SET_GOALS', response.data)
        }).catch(error => {
          console.log(error)
        })
    },
    loadCategoryStages({commit}, id) {
      return axios.get(`http://localhost:8000/get_category_stages/${id}`)
        .then(response => {
          commit('SET_STAGES', response.data)
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
          commit('SET_CATEGORY', category)
        })
        .catch(errors => {
          if (errors.response.status === 400) {
            commit('HAS_ERRORS', true)
            commit('FORM_ERRORS', errors.response.data)
          }
        })
    },
    postGoal({commit}, goal) {
      return axios.post(`http://localhost:8000/new_goal/${goal.categoryId}`,{
         name: goal.name,
      })
        .then(response => {
          alert(response.data);
          commit('CLEAR_ERRORS')
          commit('SET_GOAL', goal)
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
          commit('SET_CATEGORY', category)
        })
        .catch(errors => {
          if (errors.response.status === 400) {
            commit('HAS_ERRORS', true)
            commit('FORM_ERRORS', errors.response.data)
          }
        })
    },
    setCategoryInForm({commit}) {
      commit('SET_CATEGORY_IN_FORM')
    },
    deleteCategory({commit}, category) {
      return axios.delete(`http://localhost:8000/delete_category/${category.id}`)
        .then(response => {
          alert(response.data)
          commit('DELETE_CATEGORY', category.index)
        }).catch(error => {
          alert(error.response.data)
        })
    }
  },
  plugins: [
    createPersistedState({ storage: window.sessionStorage })
  ]
})
