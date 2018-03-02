<template>
  <div>
    <div v-show="loading" class="loading">
      <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
    </div>
    <div v-show="!loading">
      <header>
        <div class="row">
          <h3 class="mr-3">{{ category.name }}</h3>
            <button class="btn btn-info mr-3 h-25">
              <router-link :to="{ name: 'edit_category', params: {categoryName: category.name, id: category.id} }" exact>Edytuj</router-link>
            </button>
          <delete-button class="h-25" path="/" v-bind:itemToDelete="category" deleteFunction="deleteCategory"></delete-button>
        </div>
        <p>{{ category.description }}</p>
      </header>
      <ul class="">
        <div class="row" v-for="(goal, index) in goals" v-bind:key="goal.id">
          <li v-if="!isEditing[index]" class="col-2">
            {{ goal.name }}
          </li>
          <edit-goal v-if="isEditing[index]" :goal="goal" :index="index" :isEditing="isEditing"></edit-goal>
          <edit-button v-show="!isEditing[index]" @click.native="setIsEditing(index, true)" class="btn-sm mb-2 mr-2 h-25"></edit-button>
          <delete-button v-show="!isEditing[index]" :index="index" :path="$route.path" class="btn-sm mb-2 mr-2 h-25" v-bind:itemToDelete="goal" deleteFunction="deleteGoal"></delete-button>
          <add-button v-show="!isEditing[index]" item="nowy poziom" class="btn-sm h-25"></add-button>
        </div>
      </ul>
      <div v-show="!showGoalForm" v-on:click="toogleShowGoalForm">
          <add-button item="nowy cel"></add-button>
      </div>
      <new-goal v-if="showGoalForm"></new-goal>
    </div>
  </div>
</template>

<script>
import DeleteButton from './DeleteButton.vue'
import AddButton from './AddButton.vue'
import EditButton from './EditButton.vue'
import ShowCategoryStages from './ShowCategoryStages.vue'
import NewGoal from './NewGoal.vue'
import EditGoal from './EditGoal.vue'

export default {
  components: {
    'delete-button': DeleteButton,
    'add-button': AddButton,
    'edit-button': EditButton,
    'show-stages': ShowCategoryStages,
    'new-goal': NewGoal,
    'edit-goal': EditGoal,
  },
  watch: {
    '$route.params.id': function (id) {
      this.loading = true;
      this.$store.commit('SET_SHOW_GOAL_FORM', false)
      this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
        .then(() => {
          this.$store.dispatch('loadCategoryStages', this.$route.params.id)
            .then(() => {
              this.loading = false
              this.isEditing = []
            })
        })
    }
  },
  data() {
    return {
      isEditing: [],
      loading: false
    }
  },
  created() {
    this.loading = true;
    this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
      .then(() => {
        this.goals.forEach((goal, index) => {
          this.isEditing[index] = false;
        })
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
    newGoal() {
      return this.$store.getters.newGoal
    },
    category() {
      return this.$store.getters.category
    },
    showGoalForm() {
      return this.$store.getters.showGoalForm
    },
  },
  methods: {
    toogleShowGoalForm() {
      this.$store.commit('SET_SHOW_GOAL_FORM', !this.showGoalForm)
    },
    setIsEditing(index, value) {
      this.$set(this.isEditing, index, value);
    }
  }
}
</script>

<style scoped>

</style>
