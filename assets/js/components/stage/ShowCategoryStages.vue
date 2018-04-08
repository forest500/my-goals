<template>
  <div class="card-body small-font">
    <div v-show="stages" class="row mb-2">
        <div class="col-md-1">lp</div>
        <div class="col-md-3">nazwa</div>
        <div class="col-md-2">nagroda</div>
        <div class="col-md-2">data</div>
    </div>
    <stage-item v-for="(stage, index) in stages" v-bind:key="stage.id" :stages="stages" :stage="stage" :index="index" :goalId="goalId"></stage-item>
      <new-stage :stages="stages" :showStageForm="showStageForm" v-if="showStageForm" class="w-100" :goalId="goalId">
        <cancel-button class="btn-sm" slot="cancel-button" v-show="showStageForm" @click.native="showStageForm = !showStageForm"></cancel-button>
      </new-stage>
    <add-button v-show="!showStageForm" @click.native="showStageForm = !showStageForm" item="nowy poziom" class="btn-sm h-25 mb-4"></add-button>
    <alert-app v-if="alert.stage === goalId" :class="alert.class" :message="alert.message"></alert-app>
  </div>
</template>

<script>
import NewStage from './NewStage.vue'
import StageItem from './StageItem.vue'
import DeleteButton from '../button/DeleteButton.vue'
import AddButton from '../button/AddButton.vue'
import CancelButton from '../button/CancelButton.vue'
import AlertApp from '../AlertApp.vue'

export default {
  props: {
    stages: {
      type: Array,
      required: true
    },
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
    'alert-app': AlertApp,
  },
  computed: {
    alert() {
      return this.$store.getters.alert
    },
  },
}
</script>

<style scoped>
.small-font {
  font-size: 0.7rem;
}
</style>
