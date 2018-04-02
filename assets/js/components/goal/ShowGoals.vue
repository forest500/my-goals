<template>
  <div>
    <div v-if="errorMsg" class="text-danger text-center">{{ errorMsg }}</div>
    <div v-show="loading" class="loading">
      <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
    </div>
    <div v-show="!loading">
      <div class="container">
        <div class="row">
          <div class="form-group mr-auto w-50">
              <input class="form-control" type="text" v-model="search" placeholder="wyszukaj cel...">
          </div>
          <goal-form :categories="categories"></goal-form>
        </div>
      </div>
      <alert-app v-if="alert.goal" :class="alert.class" :message="alert.message"></alert-app>
      <ul class="list-group">
        <li class="list-group-item list-group-item-action list-group-item-success" v-for="goal in filteredGoals">
          <router-link :to="{ name: 'category', params: {categoryName: goal.category, id: goal.categoryId }}" exact>
            {{ goal.name }}
          </router-link>
          <span class="badge badge-default badge-pill">{{ goal.category }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import CategoryGoalForm from './CategoryGoalForm.vue';
import AlertApp from '../AlertApp.vue'

export default {
  components: {
    'goal-form': CategoryGoalForm,
    'alert-app': AlertApp,
  },
  created() {
    this.$store.dispatch('loadGoals')
    .then((response) => {
      if(typeof response !== 'undefined' && response.name === 'Error') {
        this.loading = true
        this.errorMsg = 'Nie udało się wczytać celów spróbuj ponownie później'
      } else {
        this.loading = false
      }
    })
  },
  data() {
    return {
      loading: false,
      errorMsg: '',
      search: ''
    }
  },
  computed: {
    allGoals() {
      return this.$store.getters.allGoals
    },
    categories() {
      return this.$store.getters.categories
    },
    filteredGoals() {
      return this.allGoals.filter((goal) => {
        return goal.name.match(this.search)
      })
    },
    alert() {
      return this.$store.getters.alert
    }   
  },
}
</script>

<style scoped>

</style>
