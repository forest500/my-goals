<template>
<div>
  <div v-if="errorMsg" class="text-danger text-center">{{ errorMsg }}</div>
  <div v-show="loading" class="loading">
    <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
  </div>
  <div v-show="!loading">
    <header>
      <div class="row justify-content-center mt-4">
        <h4 class="mr-3">{{ category.name }}</h4>
        <router-link tag="button" class="btn btn-info mr-3 h-25" :to="{ name: 'edit_category', params: {categoryName: category.name, id: category.id} }" exact><i class="fas fa-edit"></i></router-link>
        <delete-button class="h-25" path="/" v-bind:itemToDelete="category" :index="category.index" deleteFunction="deleteCategory"></delete-button>
      </div>
      <div class="row justify-content-center p-4">
        <p>{{ category.description }}</p>
      </div>
    </header>
    <div class="d-flex flex-wrap">
      <div class="col-md-6" v-for="(goal, index) in goals" v-bind:key="goal.id">
        <div class="card border-secondary mb-3">
          <edit-goal class="w-100" v-if="isEditing[index]" :goal="goal" :index="index" :isEditing="isEditing"></edit-goal>
          <div class="card-header row w-100 m-auto">
            <h5 v-if="!isEditing[index]" class="col-md-9" :id="goal.id">{{ goal.name }}</h5>
            <edit-button class="btn-sm mr-2 h-25 col-md-1" v-show="!isEditing[index]" @click.native="setIsEditing(index, true)"></edit-button>
            <delete-button class="btn-sm h-25 col-md-1" v-show="!isEditing[index]" v-bind:index="index" v-bind:itemToDelete="goal" deleteFunction="deleteGoal"></delete-button>
          </div>
          <show-stages :goalId="goal.id" :stages="goal.stages"></show-stages>
        </div>
      </div>
    </div>
    <alert-app v-if="alert.goal" :class="alert.class" :message="alert.message"></alert-app>
    <div v-show="!showGoalForm" v-on:click="toogleShowGoalForm">
      <add-button item="nowy cel"></add-button>
    </div>
    <new-goal v-if="showGoalForm"></new-goal>
  </div>
</div>
</template>

<script>
import DeleteButton from '../button/DeleteButton.vue'
import AddButton from '../button/AddButton.vue'
import EditButton from '../button/EditButton.vue'
import ShowCategoryStages from '../stage/ShowCategoryStages.vue'
import NewGoal from './NewGoal.vue'
import EditGoal from './EditGoal.vue'
import AlertApp from '../AlertApp.vue'

export default {
  components: {
    'delete-button': DeleteButton,
    'add-button': AddButton,
    'edit-button': EditButton,
    'show-stages': ShowCategoryStages,
    'new-goal': NewGoal,
    'edit-goal': EditGoal,
    'alert-app': AlertApp,
  },
  watch: {
    '$route.params.id': function(id) {
      this.loading = true;
      this.setActiveCategory()
      this.$store.commit('SET_SHOW_GOAL_FORM', false)
      this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
        .then(() => {
          if (typeof response !== 'undefined' && response.name === 'Error') {
            this.loading = true
          } else {
            this.loading = false
            this.isEditing = []
          }
        })
    },
  },
  data() {
    return {
      isEditing: [],
      loading: false,
      errorMsg: '',
    }
  },
  created() {
    this.loading = true;
    this.setActiveCategory()
    this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
      .then(() => {
        if (typeof response !== 'undefined' && response.name === 'Error') {
          this.loading = true
        } else {
          this.loading = false
          this.isEditing = []
        }
      })
  },
  computed: {
    goals() {
      return this.$store.getters.goals
    },
    categories() {
      return this.$store.getters.categories
    },
    newGoal() {
      return this.$store.getters.newGoal
    },
    category() {
      return this.$store.getters.category
    },
    showGoalForm() {
      return this.$store.getters.showGoalForm
    },
    alert() {
      return this.$store.getters.alert
    },
  },
  methods: {
    toogleShowGoalForm() {
      this.$store.commit('SET_SHOW_GOAL_FORM', !this.showGoalForm)
    },
    setIsEditing(index, value) {
      this.$set(this.isEditing, index, value);
    },
    setActiveCategory() {
      this.categories.forEach(category => {
        if (category.id === this.$route.params.id) this.$store.commit('SET_CATEGORY', category)
      })
    },
  },
}
</script>

<style scoped>
.loading {
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-right: -50%;
  transform: translate(-50%, -50%);
  z-index: 2;
}
</style>
