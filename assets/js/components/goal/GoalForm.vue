<template>
<div class="w-50">
  <form class="row" v-on:submit.prevent="httpFunction" name="goalForm">
    <div class="form-group col-md-9">
      <input class="form-control" type="text" name="name" v-model="goal.name">
      <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
    </div>
    <slot class="col-md-2" name="button"></slot>
  </form>
</div>
</template>

<script>
import axios from 'axios';
import AppError from '../AppError.vue';

export default {
  components: {
    'app-error': AppError
  },
  props: {
    httpFunction: {
      type: Function,
      required: true
    },
    goal: {
      type: Object,
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
  },
}
</script>

<style scoped>

</style>
