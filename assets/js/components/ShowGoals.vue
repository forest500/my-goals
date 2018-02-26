<template>
  <ol class="">
    <li class="" v-for="goal in goals" v-bind:key="goal.id">
      {{ goal.name }}
    </li>
  </ol>
</template>

<script>

export default {
  created() {
    this.$store.dispatch('loadGoals')
  },
  methods: {

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
