<template>
  <div>
    <div v-show="loading" class="loading">
      <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
    </div>
    <div v-show="!loading">
      <header>
        <div class="row justify-content-center p-4">
          <h3 class="mr-3">{{ category.name }}</h3>
            <button class="btn btn-info mr-3 h-25">
              <router-link :to="{ name: 'edit_category', params: {categoryName: category.name, id: category.id} }" exact>Edytuj</router-link>
            </button>
          <delete-button class="h-25" path="/" v-bind:itemToDelete="category" :index="category.index" deleteFunction="deleteCategory"></delete-button>
        </div>
        <p>{{ category.description }}</p>
      </header>
      <ul class="" v-for="(goal, index) in goals" v-bind:key="goal.id">
        <div class="container-fluid">
          <edit-goal class="w-100" v-if="isEditing[index]" :goal="goal" :index="index" :isEditing="isEditing"></edit-goal>
          <li class="container-fluid">
            <div class="row">
              <h5 v-if="!isEditing[index]" class="col-md-2" :id="goal.id">{{ goal.name }}</h5>
              <edit-button class="btn-sm mb-2 mr-2 h-25" v-show="!isEditing[index]" @click.native="setIsEditing(index, true)"></edit-button>
              <delete-button class="btn-sm mb-2 mr-2 h-25" v-show="!isEditing[index]" v-bind:index="index" v-bind:itemToDelete="goal" deleteFunction="deleteGoal"></delete-button>
            </div>
            <show-stages :goalId="goal.id"></show-stages>
          </li>
        </div>
      </ul>
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
    '$route.params.id': function (id) {
      this.loading = true;
      this.setActiveCategory()
      this.$store.commit('SET_SHOW_GOAL_FORM', false)
      this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
        .then(() => {
          this.$store.dispatch('loadCategoryStages', this.$route.params.id)
            .then(() => {
              this.loading = false
              this.isEditing = []
            })
        })
    },
  },
  data() {
    return {
      isEditing: [],
      loading: false,
    }
  },
  created() {
    this.loading = true;
    this.setActiveCategory()
    this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
      .then(() => {
        this.$store.dispatch('loadCategoryStages', this.$route.params.id)
          .then(() => {
            this.loading = false;
            })
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
        if(category.id === this.$route.params.id) this.$store.commit('SET_CATEGORY', category)
      })
    },
  }
}
</script>

<style scoped>
.loading {
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-right: -50%;
  transform: translate(-50%, -50%)
}
</style>
