<template>
  <div class="w-100">
    <table class="table mt-4">
        <tr>
          <th>poziom</th>
          <th>nazwa</th>
          <th>nagroda</th>
          <th>planowana data</th>
        </tr>
        <tr v-for="stage in stages" v-bind:key="stage.id" li v-if="stage.goalId === goalId">
          <td>{{ stage.number }} </td>
          <td>{{ stage.name }} </td>
          <td>{{ stage.award }}</td>
          <td>{{ stage.endDate.date | match-date }}</td>
        </tr>
    </table>
    <new-stage :showStageForm="showStageForm" v-if="showStageForm" class="w-100" :goalId="goalId">
      <cancel-button slot="cancel-button" v-show="showStageForm" @click.native="showStageForm = !showStageForm"></cancel-button>
    </new-stage>
    <add-button v-show="!showStageForm" @click.native="showStageForm = !showStageForm" item="nowy poziom" class="btn-sm h-25"></add-button>
  </div>
</template>

<script>
import DeleteButton from './DeleteButton.vue'
import NewStage from './NewStage.vue'
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
    'delete-button': DeleteButton,
    'new-stage': NewStage,
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
table {
  background-color: red;
}
</style>
