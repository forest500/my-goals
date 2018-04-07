<template>
<div class="container">
  <form class="w-50" v-on:submit.prevent="httpFunction" name="categoryForm">
    <div class="form-group">
      <input placeholder="nazwa kategorii..." class="row form-control" type="text" name="name" :value="categoryInForm.name" @input="updateName">
      <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
    </div>
    <div class="form-group">
      <textarea placeholder="opis kategorii ..." class="row form-control" name="description" rows="5" cols="50" :value="categoryInForm.description" @input="updateDescription"></textarea>
      <app-error if="errors.response.data.description" v-bind:formErrors="formErrors.description"></app-error>
    </div>
    <div class="form-group float-right mr-3">
        <slot name="button"></slot>
    </div>
  </form>
</div>
</template>

<script>
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
