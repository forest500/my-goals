<template>
  <ul class="list-group">
    <li class="list-group-item list-group-item-action list-group-item-success" v-for="goal in goals" v-bind:key="goal.id">
      <router-link :to="{ name: 'category', params: {categoryName: goal.category, id: goal.categoryId }}" exact>
        {{ goal.name }}
      </router-link>
      <span class="badge badge-default badge-pill">{{ goal.category }}</span>
    </li>
  </ul>
</template>

<script>

export default {
  created() {
    this.$store.dispatch('loadGoals')
  },
  computed: {
    goals() {
      return this.$store.getters.goals
    }
  },
}
</script>

<style scoped>

</style>
