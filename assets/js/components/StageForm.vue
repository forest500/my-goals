<template>
    <form class="form-group form-row stage-form" v-on:submit.prevent="httpFunction" name="categoryForm">
      <div class="form-group">
        <input class="form-control" type="text" name="name" v-model="stage.name" placeholder="nazwa">
        <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
      </div>
      <div class="form-group mx-2">
        <input class="form-control" type="text" name="award" v-model="stage.award" placeholder="nagroda">
        <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.award"></app-error>
      </div>
      <div class="form-group">
        <date-picker name="endDate" format="yyyy-MM-dd" v-model="stage.endDate" @input="changeDateFormat" :bootstrap-styling="true" language="pl"></date-picker>
        <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.endDate"></app-error>
      </div>
      <slot name="button"></slot>
    </form>
</template>

<script>
import axios from 'axios';
import AppError from './AppError.vue';
import Datepicker from 'vuejs-datepicker';
var moment = require('moment');

export default {
  components: {
    'app-error': AppError,
    'date-picker': Datepicker
  },
  props: {
    httpFunction: {
      type: Function,
      required: true
    },
    stage: {
      type: Object,
      required: true
    },
  },
  created() {
    this.$store.dispatch('clearErrors')
    this.stage.endDate = moment(new Date).format('YYYY-MM-DD')
  },
  computed: {
    hasErrors () {
      return this.$store.getters.hasErrors
    },
    formErrors() {
      return this.$store.getters.formErrors
    },
  },
  methods: {
    changeDateFormat() {
      this.stage.endDate = moment(this.stage.endDate).format('YYYY-MM-DD')
    }
  }
}
</script>

<style scoped>
div{
  background-color: red
}
</style>
