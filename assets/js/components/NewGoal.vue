<template>
<div>
    <goal-form :goal="newGoal" v-bind:httpFunction="post">
      <button slot="button" class="btn btn-success ml-2">dodaj cel</button>
    </goal-form>
</div>
</template>

<script>
import axios from 'axios';
import GoalForm from './GoalForm.vue';

export default {
  components: {
    'goal-form': GoalForm
  },
  data() {
    return {
      newGoal: {}
    }
  },
  methods: {
    post() {
      this.newGoal.categoryId = this.$route.params.id
      this.$store.dispatch('postGoal', this.newGoal)
        .then(() => {
          if(!this.hasErrors) {
            this.$store.dispatch('loadCategoryGoals', this.$route.params.id)         
          }
        })
    }
  },
  computed: {
    hasErrors () {
      return this.$store.getters.hasErrors
    },
  }
}
</script>

<style scoped>

</style>
