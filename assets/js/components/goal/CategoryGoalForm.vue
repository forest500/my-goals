<template>
<div class="mb-4">
  <form class="form-inline" v-on:submit.prevent="post" name="goalForm">
    <div class="form-group">
      <input class="form-control" placeholder="nowy cel..." type="text" name="name" v-model="goal.name">
    </div>
    <div class="form-group">
      <select class="form-control ml-1" v-model="goal.categoryId" required>
        <option disabled value="">Wybierz kategorię</option>
        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
      </select>
    </div>
    <save-button class="ml-1"></save-button>
  </form>
  <app-error class="" if="errors.response.data.name" v-bind:formErrors="formErrors.name"></app-error>
</div>
</template>

<script>
import AppError from '../AppError.vue';
import SaveButton from '../button/SaveButton.vue';

export default {
  components: {
    'app-error': AppError,
    'save-button': SaveButton,
  },
  props: {
    categories: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      goal: {
        name: '',
        categoryId: ''
      },
    }
  },
  computed: {
    hasErrors() {
      return this.$store.getters.hasErrors
    },
    formErrors() {
      return this.$store.getters.formErrors
    },
  },
  methods: {
    post() {
      this.$store.dispatch('postGoal', this.goal)
        .then(() => {
          if (!this.hasErrors) {
            this.$store.dispatch('loadGoals')
          }
        })
    },
  }
}
</script>

<style scoped>

</style>
