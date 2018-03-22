<template>
  <div>
    <div v-if="errorMsg" class="text-danger text-center">{{ errorMsg }}</div>
    <div v-show="loading" class="loading">
      <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
    </div>
    <ul v-show="!loading" class="list-group">
      <li class="list-group-item list-group-item-action list-group-item-success" v-for="goal in allGoals">
        <router-link :to="{ name: 'category', params: {categoryName: goal.category, id: goal.categoryId }}" exact>
          {{ goal.name }}
        </router-link>
        <span class="badge badge-default badge-pill">{{ goal.category }}</span>
      </li>
    </ul>
  </div>
</template>

<script>

export default {
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
      errorMsg: ''
    }
  },
  computed: {
    allGoals() {
      return this.$store.getters.allGoals
    }
  },
}
</script>

<style scoped>

</style>
