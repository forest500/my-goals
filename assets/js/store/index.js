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
    allGoals: [],
    goals: [],
    newGoal: {},
    stages: [],
    hasErrors: false,
    formErrors: {},
    showGoalForm: false,
    alert: {},
    alertMessage: '',
  },
  getters: {
    categories: state => state.categories,
    category: state => state.category,
    categoryInForm: state => state.categoryInForm,
    allGoals: state => state.allGoals,
    goals: state => state.goals,
    newGoal: state => state.newGoal,
    stages: state => state.stages,
    hasErrors: state => state.hasErrors,
    formErrors: state => state.formErrors,
    showGoalForm: state => state.showGoalForm,
    alert: state => state.alert,
  },
  mutations: {
    SET_CATEGORIES(state, categories) {
      state.categories = categories
    },
    SET_CATEGORY_INDEXES(state) {
      state.categories.forEach((category, index) => {
        category.index = index
      })
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
    SET_ALLGOALS(state, goals) {
      state.allGoals = goals
    },
    SET_GOALS(state, goals) {
      state.goals = goals
    },
    SET_GOAL(state, goal) {
      state.newGoal = goal
    },
    SET_STAGES(state, stages) {
      state.stages = stages
    },
    SET_SHOW_GOAL_FORM(state, boolean) {
      state.showGoalForm = boolean
    },
    ADD_CATEGORY(state, categoryObject) {
      state.categories.push(categoryObject)
    },
    DELETE_CATEGORY(state, index) {
      state.categories.splice(index, 1)
    },
    DELETE_GOAL(state, index) {
      state.goals.splice(index, 1)
    },
    DELETE_STAGE(state, index) {
      state.stages.splice(index, 1)
    },
    HAS_ERRORS(state, hasErrors) {
      state.hasErrors = hasErrors
    },
    FORM_ERRORS(state, formErrors) {
      state.formErrors = formErrors
    },
    CLEAR_ERRORS(state) {
      state.formErrors = {}
      state.hasErrors = false
    },
    SET_ALERT(state, alert) {
      state.alert = alert
    },
    UNSET_ALERT(state) {
      state.alert = {}
    },
  },
  actions: {
    loadCategories({commit}) {
      return axios.get(`http://localhost:8000/get_categories`)
        .then(response => {
          commit('SET_CATEGORIES', response.data)
          commit('SET_CATEGORY_INDEXES')
        }).catch(error => {
          console.log(error)
        });
    },
    loadGoals({commit}) {
      return axios.get(`http://localhost:8000/get_goals`)
        .then(response => {
          commit('SET_ALLGOALS', response.data)
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
          commit('SET_ALERT', { category: true, message: response.data, class: 'alert-success' } )
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
          commit('SET_ALERT', { goal: true, message: response.data, class: 'alert-success' } )
          commit('CLEAR_ERRORS')
          commit('SET_GOAL', goal)
          commit('SET_SHOW_GOAL_FORM', false)
          console.log(this.state.alert)
          console.log(this.state.alertMessage)
        })
        .catch(errors => {
          if (errors.response.status === 400) {
            commit('HAS_ERRORS', true)
            commit('FORM_ERRORS', errors.response.data)
          }
        })
    },
    postStage({commit}, stage) {
      return axios.post(`http://localhost:8000/new_stage/${stage.goalId}`,{
         name: stage.name,
         award: stage.award,
         endDate: stage.endDate
      })
        .then(response => {
          commit('SET_ALERT', { stage: stage.goalId, message: response.data, class: 'alert-success' } )
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
          commit('SET_ALERT', { category: true, message: response.data, class: 'alert-info' } )
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
    editGoal({commit}, goal) {
      return axios.put(`http://localhost:8000/update_goal/${goal.id}`,{
         name: goal.name,
      })
        .then(response => {
          commit('SET_ALERT', { goal: true, message: response.data, class: 'alert-info' } )
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
    editStage({commit}, stage) {
      return axios.put(`http://localhost:8000/update_stage/${stage.id}`,{
         name: stage.name,
         award: stage.award,
         endDate: stage.endDate
      })
        .then(response => {
          commit('SET_ALERT', { stage: stage.goalId, message: response.data, class: 'alert-info' } )
          commit('CLEAR_ERRORS')
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
      return axios.delete(`http://localhost:8000/delete_category/${category.itemToDelete.id}`)
        .then(response => {
          commit('SET_ALERT', { category: true, message: response.data, class: 'alert-danger' } )
          commit('DELETE_CATEGORY', category.index)
        }).catch(error => {
          alert(error.response.data)
        })
    },
    deleteGoal({commit}, goal) {
      return axios.delete(`http://localhost:8000/delete_goal/${goal.itemToDelete.id}`)
        .then(response => {
          commit('SET_ALERT', { goal: true, message: response.data, class: 'alert-danger' } )
        commit('DELETE_GOAL', goal.index)
        }).catch(error => {
          alert(error.response.data)
        })
    },
    deleteStage({commit}, stage) {
      return axios.delete(`http://localhost:8000/delete_stage/${stage.itemToDelete.id}`)
        .then(response => {
          commit('SET_ALERT', { stage: stage.itemToDelete.goalId, message: response.data, class: 'alert-danger' } )
        commit('DELETE_STAGE', stage.index)
        }).catch(error => {
          alert(error.response.data)
        })
    },
  },
  // plugins: [
  //   createPersistedState({ storage: window.sessionStorage })
  // ]
})
