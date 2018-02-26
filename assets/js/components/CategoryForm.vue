<template>
<div>
  <form v-on:submit.prevent="httpFunction" name="categoryForm">
    <input class="row" type="text" name="name" v-model="category.name">
    <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
    <textarea class="row" name="description" rows="5" cols="50" v-model="category.description"></textarea>
    <app-error if="errors.response.data.description" v-bind:formErrors="formErrors.description"></app-error>
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
    category() {
      return this.$store.getters.category
    }
  }
}
</script>

<style scoped>

</style>
