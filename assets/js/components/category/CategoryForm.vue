<template>
<div>
  <form v-on:submit.prevent="httpFunction" name="categoryForm">
    <input class="row" type="text" name="name" :value="categoryInForm.name" @input="updateName">
    <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
    <textarea class="row" name="description" rows="5" cols="50" :value="categoryInForm.description" @input="updateDescription"></textarea>
    <app-error if="errors.response.data.description" v-bind:formErrors="formErrors.description"></app-error>
    <slot name="button"></slot>
  </form>
</div>
</template>

<script>
import axios from 'axios';
import AppError from '../AppError.vue';
import { mapGetters } from 'vuex'

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
    this.$store.dispatch('setCategoryInForm')
  },
  computed: {
    ...mapGetters([
      'hasErrors',
      'formErrors',
      'category',
      'categoryInForm',
    ])
  },
  methods: {
    updateName (e) {
      this.$store.commit('SET_CATEGORY_IN_FORM_NAME', e.target.value)
    },
    updateDescription (e) {
      this.$store.commit('SET_CATEGORY_IN_FORM_DESCRIPTION', e.target.value)
    }
  }
}
</script>

<style scoped>

</style>
