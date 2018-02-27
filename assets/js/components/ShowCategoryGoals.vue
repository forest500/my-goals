<template>
  <div>
    <div v-show="loading" class="loading">
      <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
    </div>
    <header>
      <div class="row">
        <h3 class="mr-3">{{ category.name }}</h3>
          <button class="btn btn-info btn-sm mr-4">
            <router-link class="nav-link" :to="{ name: 'edit_category', params: {categoryName: category.name, id: category.id} }" exact>Edytuj</router-link>
          </button>
        <delete-button v-bind:itemToDelete="category" deleteFunction="deleteCategory"></delete-button>
      </div>
      <p>{{ category.description }}</p>
    </header>
    <ul v-show="!loading" class="">
      <div class="row" v-for="goal in goals" v-bind:key="goal.id">
        <li class="col-2">
          {{ goal.name }}
          <show-stages :goalId="goal.id"></show-stages>
        </li>
        <add-button item="poziom"></add-button>
      </div>
    </ul>
    <add-button item="cel"></add-button>
  </div>
</template>

<script>
import DeleteButton from './DeleteButton.vue'
import AddButton from './AddButton.vue'
import ShowCategoryStages from './ShowCategoryStages.vue'

export default {
  data() {
    return {
      loading: false,
    }
  },
  components: {
    'delete-button': DeleteButton,
    'add-button': AddButton,
    'show-stages': ShowCategoryStages,
  },
  watch: {
    '$route.params.id': function (id) {
      this.loading = true
      this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
        .then(() => {
          this.$store.dispatch('loadCategoryStages', this.$route.params.id)
            .then(() => {
              this.loading = false;
            })
        })
    }
  },
  created() {
    this.loading = true
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
    category() {
      return this.$store.getters.category
    },
  },
  methods: {
    remove() {
      this.$store.dispatch(this.deleteFunction, this.itemToDelete)
    }
  }
}
</script>

<style scoped>

</style>
