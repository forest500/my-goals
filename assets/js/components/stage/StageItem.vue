<template>
  <div class="row my-2">
      <div class="col-md-1" v-show="!isEditing">{{ stage.number }}</div>
      <div class="col-md-3" v-show="!isEditing">{{ stage.name }} </div>
      <div class="col-md-2" v-show="!isEditing">{{ stage.award }}</div>
      <div class="col-md-3" v-show="!isEditing">{{ stage.endDate }}</div>
      <div class="ml-2"><edit-button class="btn-sm" v-show="!isEditing" @click.native="isEditing=!isEditing"></edit-button></div>
      <div>
        <delete-button v-show="!isEditing" class="ml-2 btn-sm" :index="index" v-bind:itemToDelete="stage" deleteFunction="deleteStage"></delete-button>
      </div>

    <div class="" v-if="isEditing">
      <edit-stage :stage="stage" :isEditing.sync="isEditing">
        <cancel-button slot="cancel-button" class="btn-sm mb-2 mr-2" @click.native="isEditing=!isEditing"></cancel-button>
      </edit-stage>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import EditButton from '../button/EditButton.vue'
import EditStage from './EditStage.vue'
import CancelButton from '../button/CancelButton.vue'
import DeleteButton from '../button/DeleteButton.vue'

var moment = require('moment');

export default {
  created() {
    this.stage.endDate = moment(this.stage.endDate).format('YYYY-MM-DD')
  },
  watch: {
    stage: function() {
      this.stage.endDate = moment(this.stage.endDate).format('YYYY-MM-DD')
    }
  },
  components: {
    'edit-button': EditButton,
    'edit-stage': EditStage,
    'cancel-button': CancelButton,
    'delete-button': DeleteButton,
  },
  props: {
    stage: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
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
