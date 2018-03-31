<template>
  <form class="form-group form-row stage-form w-100" v-on:submit.prevent="httpFunction" name="stageForm">
    <div class="form-group col-md-3">
      <input class="form-control form-control-sm" type="text" name="name" v-model="stage.name" placeholder="nazwa">
      <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
    </div>
    <div class="form-group mx-2 col-md-3">
      <input class="form-control form-control-sm" type="text" name="award" v-model="stage.award" placeholder="nagroda">
      <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.award"></app-error>
    </div>
    <div class="form-group col-md-3">
      <date-picker calendar-class="text-dark" input-class="form-control form-control-sm" name="endDate" format="yyyy-MM-dd" v-model="stage.endDate" @input="changeDateFormat" :bootstrap-styling="true" language="pl"></date-picker>
      <app-error if="errors.response.data.name" v-bind:formErrors="formErrors.endDate"></app-error>
    </div>
    <slot name="button" class="ml-2 col-md-3"></slot>
  </form>
</template>

<script>
import axios from 'axios';
import AppError from '../AppError.vue';
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
    if(!this.stage.endDate) this.stage.endDate = moment(new Date).format('YYYY-MM-DD')
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
/* .stage-form {
  width: 100%;
} */
</style>
