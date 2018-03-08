<template>
<div>
    <goal-form :goal="goal" v-bind:httpFunction="put">
      <save-button slot="button" :index="index" v-bind:itemToEdit="goal" class="btn-sm ml-2 mb-2 mr-2 h-25"></save-button>
      <cancel-button slot="button" class="btn-sm mb-2 mr-2 h-25" @click.native="setIsEditing(index, false)"></cancel-button>
    </goal-form>
</div>
</template>

<script>
import axios from 'axios';
import GoalForm from './GoalForm.vue';
import SaveButton from '../button/SaveButton.vue'
import CancelButton from '../button/CancelButton.vue'

export default {
  components: {
    'goal-form': GoalForm,
    'save-button': SaveButton,
    'cancel-button': CancelButton,
  },
  props: {
    goal: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      required: true
    },
    isEditing: {
      type: Array,
      required: true
    }
  },
  methods: {
    put() {
      this.$store.dispatch('editGoal', this.goal)
        .then(() => {
          if(!this.hasErrors) {
            this.setIsEditing(this.index, false)
          }
        })
    },
    setIsEditing(index, value) {
      this.$set(this.isEditing, index, value);
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
