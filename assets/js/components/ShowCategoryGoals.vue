<template>
  <div>
    <header>

      <div class="row">
        <h3>{{ category.name }}</h3>
          <button class="btn btn-info btn-sm">
            <router-link class="nav-link" :to="{ name: 'edit_category', params: {category: category.name, id: category.id} }" exact>Edytuj</router-link>
          </button>
        <delete-button v-bind:itemToDelete="category" deleteFunction="deleteCategory"></delete-button>
      </div>
      <p>{{ category.description }}</p>
    </header>
    <ol class="">
      <li class="" v-for="goal in goals" v-bind:key="goal.id">
        {{ goal.name }}
      </li>
    </ol>
  </div>
</template>

<script>
import DeleteButton from './DeleteButton.vue'

export default {
  components: {
    'delete-button': DeleteButton,
  },
  watch: {
    '$route.params.id': function (id) {
      this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
    }
  },
  created() {
    this.$store.dispatch('loadCategoryGoals', this.$route.params.id)
  },
  computed: {
    goals() {
      return this.$store.getters.goals
    },
    category() {
      return this.$store.getters.category
    }
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
