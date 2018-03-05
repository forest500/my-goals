<template>
<div>
    <stage-form :stage="stage" v-bind:httpFunction="put">
      <save-button slot="button" v-bind:itemToEdit="stage" class="btn-sm ml-2 mb-2 mr-2 h-25"></save-button>
      <cancel-button slot="button" class="btn-sm mb-2 mr-2 h-25"></cancel-button>
    </stage-form>
</div>
</template>

<script>
import axios from 'axios';
import StageForm from './StageForm.vue';
import SaveButton from './SaveButton.vue'
import CancelButton from './CancelButton.vue'

export default {
  components: {
    'stage-form': StageForm,
    'save-button': SaveButton,
    'cancel-button': CancelButton,
  },
  props: {
    stage: {
      type: Object,
      required: true
    },
    // index: {
    //   type: Number,
    //   required: true
    // },
    isEditing: {
      type: Boolean,
      required: true
    }
  },
  methods: {
    put() {
      this.$store.dispatch('editStage', this.stage)
        .then(() => {
          if(!this.hasErrors) {
            this.isEditing = false
          }
        })
    },
    // setIsEditing(index, value) {
    //   this.$set(this.isEditing, index, value);
    // }
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
