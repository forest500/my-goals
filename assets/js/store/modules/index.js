import axios from 'axios'
import { API_LOCATION } from "../../config"
import message from "../../messages"

const state = {
  categories: [],
  category: {},
  categoryInForm: {},
  allGoals: [],
  goals: [],
  newGoal: {},
  hasErrors: false,
  formErrors: {},
  showGoalForm: false,
  alert: {},
  alertMessage: '',
}

const getters = {
  categories: state => state.categories,
  category: state => state.category,
  categoryInForm: state => state.categoryInForm,
  allGoals: state => state.allGoals,
  goals: state => state.goals,
  newGoal: state => state.newGoal,
  hasErrors: state => state.hasErrors,
  formErrors: state => state.formErrors,
  showGoalForm: state => state.showGoalForm,
  alert: state => state.alert,
  loadingError: state => state.loadingError
}

const mutations = {
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
  SET_SHOW_GOAL_FORM(state, boolean) {
    state.showGoalForm = boolean
  },
  ADD_CATEGORY(state, category) {
    category.index = Object.keys(state.categories).length
    state.categories.push(category)
    state.category = category
  },
  DELETE_CATEGORY(state, index) {
    state.categories.splice(index, 1)
  },
  ADD_GOAL(state, goal) {
    state.goal = goal
    state.goals.push(goal)
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
}

const actions = {
  loadCategories({commit}) {
    return axios.get(`${API_LOCATION}get_categories`)
      .then(response => {
        commit('SET_CATEGORIES', response.data.categories)
        commit('SET_CATEGORY_INDEXES')
      }).catch(error => {
        return error
      });
  },
  loadGoals({commit}) {
    return axios.get(`${API_LOCATION}get_goals`)
      .then(response => {
        commit('SET_ALLGOALS', response.data.goals)
      }).catch(error => {
        return error
      });
  },
  loadCategoryGoals({commit}, id) {
    return axios.get(`${API_LOCATION}get_category_goals/${id}`)
      .then(response => {
        commit('SET_GOALS', response.data.goals)
      }).catch(error => {
        return error
      })
  },
  postCategory({commit}, category) {
    return axios.post(`${API_LOCATION}new_category`,{
       name: category.name,
       description: category.description
    })
      .then(response => {
        commit('CLEAR_ERRORS')
        commit('ADD_CATEGORY', response.data)
        commit('SET_ALERT', { category: true, message: message.CATEGORY_CREATE, class: 'alert-success' } )
      })
      .catch(errors => {
        if (errors.response.status === 400 && errors.response.data.type === "validation_error") {
          commit('HAS_ERRORS', true)
          commit('FORM_ERRORS', errors.response.data.errors)
        } else if (errors.response.status === 400 && errors.response.data.type === "unique_name_error") {
          commit('HAS_ERRORS', true)
          commit('SET_ALERT', { category: true, message: errors.response.data.message, class: 'alert-danger' } )
        }
      })
  },
  postGoal({commit}, goal) {
    return axios.post(`${API_LOCATION}new_goal/${goal.categoryId}`,{
       name: goal.name,
    })
      .then(response => {
        commit('CLEAR_ERRORS')
        commit('ADD_GOAL', response.data)
        commit('SET_SHOW_GOAL_FORM', false)
        commit('SET_ALERT', { goal: true, message: message.GOAL_CREATE, class: 'alert-success' } )
      })
      .catch(errors => {
        if (errors.response.status === 400 && errors.response.data.type === "validation_error") {
          commit('HAS_ERRORS', true)
          commit('FORM_ERRORS', errors.response.data.errors)
        } else if (errors.response.status === 400 && errors.response.data.type === "unique_name_error") {
          commit('HAS_ERRORS', true)
          commit('SET_ALERT', { category: true, message: errors.response.data.message, class: 'alert-danger' } )
        }
      })
  },
  postStage({commit}, stage) {
    return axios.post(`${API_LOCATION}new_stage/${stage.goalId}`,{
       name: stage.name,
       award: stage.award,
       endDate: stage.endDate
    })
      .then(response => {
        commit('CLEAR_ERRORS')
        commit('SET_ALERT', { stage: stage.goalId, message: message.STAGE_CREATE, class: 'alert-success' } )
      })
      .catch(errors => {
        if (errors.response.status === 400 && errors.response.data.type === "validation_error") {
          commit('HAS_ERRORS', true)
          commit('FORM_ERRORS', errors.response.data.errors)
        } else if (errors.response.status === 400 && errors.response.data.type === "unique_name_error") {
          commit('HAS_ERRORS', true)
          commit('SET_ALERT', { category: true, message: errors.response.data.message, class: 'alert-danger' } )
        }
      })
  },
  clearErrors({commit}) {
    commit('CLEAR_ERRORS')
  },
  editCategory({commit}, category) {
    return axios.put(`${API_LOCATION}update_category/${category.id}`,{
       name: category.name,
       description: category.description
    })
      .then(response => {
        commit('CLEAR_ERRORS')
        commit('SET_CATEGORY', category)
        commit('SET_ALERT', { category: true, message: message.CATEGORY_EDIT, class: 'alert-info' } )
      })
      .catch(errors => {
        if (errors.response.status === 400 && errors.response.data.type === "validation_error") {
          commit('HAS_ERRORS', true)
          commit('FORM_ERRORS', errors.response.data.errors)
        }
      })
  },
  editGoal({commit}, goal) {
    return axios.put(`${API_LOCATION}update_goal/${goal.id}`,{
       name: goal.name,
    })
      .then(response => {
        commit('CLEAR_ERRORS')
        commit('SET_GOAL', goal)
        commit('SET_ALERT', { goal: true, message: message.GOAL_EDIT, class: 'alert-info' } )
      })
      .catch(errors => {
        if (errors.response.status === 400 && errors.response.data.type === "validation_error") {
          commit('HAS_ERRORS', true)
          commit('FORM_ERRORS', errors.response.data.errors)
        }
      })
  },
  editStage({commit}, stage) {
    return axios.put(`${API_LOCATION}update_stage/${stage.id}`,{
       name: stage.name,
       award: stage.award,
       endDate: stage.endDate
    })
      .then(response => {
        commit('CLEAR_ERRORS')
        commit('SET_ALERT', { stage: stage.goalId, message: message.STAGE_EDIT, class: 'alert-info' } )
      })
      .catch(errors => {
        if (errors.response.status === 400 && errors.response.data.type === "validation_error") {
          commit('HAS_ERRORS', true)
          commit('FORM_ERRORS', errors.response.data.errors)
        }
      })
  },
  setCategoryInForm({commit}) {
    commit('SET_CATEGORY_IN_FORM')
  },
  deleteCategory({commit}, category) {
    return axios.delete(`${API_LOCATION}delete_category/${category.itemToDelete.id}`)
      .then(response => {
        commit('DELETE_CATEGORY', category.index)
        commit('SET_ALERT', { category: true, message: message.CATEGORY_DELETE, class: 'alert-danger' } )
      }).catch(error => {
        commit('SET_ALERT', { category: true, message: error.response.data.error, class: 'alert-danger' } )
      })
  },
  deleteGoal({commit}, goal) {
    return axios.delete(`${API_LOCATION}delete_goal/${goal.itemToDelete.id}`)
      .then(response => {
        commit('DELETE_GOAL', goal.index)
        commit('SET_ALERT', { goal: true, message: message.GOAL_DELETE, class: 'alert-danger' } )
      }).catch(error => {
        commit('SET_ALERT', { goal: true, message: error.response.data.error, class: 'alert-danger' } )
      })
  },
  deleteStage({commit}, stage) {
    return axios.delete(`${API_LOCATION}delete_stage/${stage.itemToDelete.id}`)
      .then(response => {
        commit('DELETE_STAGE', stage.index)
        commit('SET_ALERT', { stage: stage.itemToDelete.goalId, message: message.STAGE_DELETE, class: 'alert-danger' } )
      }).catch(error => {
        alert(error.response.data)
      })
  },
}

export default {
  state,
  getters,
  actions,
  mutations
}
