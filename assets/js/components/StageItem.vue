<template>
  <tr>
    <td v-show="!isEditing">{{ stage.number }} </td>
    <td v-show="!isEditing">{{ stage.name }} </td>
    <td v-show="!isEditing">{{ stage.award }}</td>
    <td v-show="!isEditing">{{ stage.endDate }}</td>
    <td v-show="!isEditing"><edit-button @click.native="isEditing=!isEditing"></edit-button></td>
    <edit-stage v-if="isEditing" :stage="stage" :isEditing="isEditing"></edit-stage>
  </tr>
</template>

<script>
import axios from 'axios';
import EditButton from './EditButton.vue'
import EditStage from './EditStage.vue'
var moment = require('moment');

export default {
  created() {
    this.stage.endDate = moment(this.stage.endDate.date).format('YYYY-MM-DD')
  },
  components: {
    'edit-button': EditButton,
    'edit-stage': EditStage,
  },
  props: {
    stage: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      isEditing: false,
    }
  },
  methods: {


  },
  computed: {

  }
}
</script>

<style scoped>

</style>
