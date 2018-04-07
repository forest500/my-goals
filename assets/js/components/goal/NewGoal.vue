<template>
<div>
    <goal-form v-show="showGoalForm" :goal="newGoal" v-bind:httpFunction="post">
      <button slot="button" class="btn btn-success ml-2">dodaj cel</button>
      <cancel-button class="ml-2" slot="button" @click.native="toogleShowGoalForm"></cancel-button>
    </goal-form>
</div>
</template>

<script>
import axios from 'axios';
import GoalForm from './GoalForm.vue';
import CancelButton from '../button/CancelButton.vue';

export default {
  components: {
    'goal-form': GoalForm,
    'cancel-button': CancelButton
  },
  data() {
    return {
      newGoal: {},
    }
  },
  methods: {
    post() {
      this.newGoal.categoryId = this.$route.params.id
      this.$store.dispatch('postGoal', this.newGoal)
    },
    toogleShowGoalForm() {
      this.$store.commit('SET_SHOW_GOAL_FORM', !this.showGoalForm)
    },
  },
  computed: {
    hasErrors () {
      return this.$store.getters.hasErrors
    },
    showGoalForm() {
      return this.$store.getters.showGoalForm
    },
  }
}
</script>

<style scoped>

</style>
