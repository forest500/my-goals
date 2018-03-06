<template>
  <div class="container-fluid">
    <div v-show="stages" class="row">
        <div class="col-md-1">poziom</div>
        <div class="col-md-2">nazwa</div>
        <div class="col-md-2">nagroda</div>
        <div class="col-md-2">planowana data</div>
    </div>
    <div v-if="stage.goalId === goalId" is ="stage-item" v-for="(stage, index) in stages" :stage="stage" :index="index"></div>
    <new-stage :showStageForm="showStageForm" v-if="showStageForm" class="w-100" :goalId="goalId">
      <cancel-button slot="cancel-button" v-show="showStageForm" @click.native="showStageForm = !showStageForm"></cancel-button>
    </new-stage>
    <add-button v-show="!showStageForm" @click.native="showStageForm = !showStageForm" item="nowy poziom" class="btn-sm h-25 mb-4"></add-button>
  </div>
</template>

<script>
import NewStage from './NewStage.vue'
import StageItem from './StageItem.vue'
import DeleteButton from './DeleteButton.vue'
import AddButton from './AddButton.vue'
import CancelButton from './CancelButton.vue'

export default {
  props: {
    goalId: {
      type: Number,
      required: true
    },
  },
  data() {
    return {
      showStageForm: false,
    }
  },
  components: {
    'new-stage': NewStage,
    'stage-item': StageItem,
    'delete-button': DeleteButton,
    'add-button': AddButton,
    'cancel-button': CancelButton,
  },
  computed: {
    stages() {
      return this.$store.getters.stages
    },
  },
  methods: {

  }
}
</script>

<style scoped>

</style>
