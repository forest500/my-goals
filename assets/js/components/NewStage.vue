<template>
<div>
    <stage-form :stage="newStage" v-bind:httpFunction="post">
      <div slot="button">
        <save-button class="mx-2"></save-button><slot name="cancel-button"></slot>
      </div>
    </stage-form>

</div>
</template>

<script>
import axios from 'axios';
import StageForm from './StageForm.vue';
import SaveButton from './SaveButton.vue'

export default {
  components: {
    'stage-form': StageForm,
    'save-button': SaveButton,
  },
  props: {
    goalId: {
      type: Number,
      required: true
    },
  },
  data() {
    return {
      newStage: {},
    }
  },
  methods: {
    post() {
      this.newStage.goalId = this.goalId
      this.$store.dispatch('postStage', this.newStage)
        .then(() => {
          if(!this.hasErrors) this.$store.dispatch('loadCategoryStages', this.$route.params.id)
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
