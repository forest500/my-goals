<template>
<div>
  <form v-on:submit.prevent="httpFunction" name="categoryForm">
    <input class="row" type="text" name="name" :value="goal.name" @input="updateName">
    <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
    <slot name="button"></slot>
  </form>
</div>
</template>

<script>
import axios from 'axios';
import AppError from './AppError.vue';

export default {
  components: {
    'app-error': AppError
  },
  props: {
    httpFunction: {
      type: Function,
      required: true
    }
  },
  created() {
    this.$store.dispatch('clearErrors')
  },
  computed: {
    hasErrors () {
      return this.$store.getters.hasErrors
    },
    formErrors() {
      return this.$store.getters.formErrors
    },
    goal() {
      return this.$store.getters.goal
    },
  },
  methods: {
    updateName (e) {
      this.$store.commit('SET_GOAL', e.target.value)
    },
  }
}
</script>

<style scoped>

</style>
