<template>
  <div class="w-100">
    <table class="table mt-4 table-responsive">
      <thead>
        <tr>
          <th>poziom</th>
          <th>nazwa</th>
          <th>nagroda</th>
          <th>planowana data</th>
        </tr>
      </thead>
      <tbody>
        <tr is ="stage-item" :stage="stage" v-for="stage in stages" li v-if="stage.goalId === goalId">
          <!-- <stage-item :stage="stage"></stage-item> -->
          <!-- <td>{{ stage.number }} </td>
          <td>{{ stage.name }} </td>
          <td>{{ stage.award }}</td>
          <td>{{ stage.endDate.date | match-date }}</td> -->
        </tr>
      </tbody>

    </table>
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
