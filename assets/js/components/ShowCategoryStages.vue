<template>
    <ol>
        <li v-for="stage in stages" v-bind:key="stage.id" li v-if="stage.goalId === goalId">{{ stage.name }}</li>
    </ol>
</template>

<script>
import DeleteButton from './DeleteButton.vue'

export default {
  props: {
    goalId: {
      type: Number,
      required: true
    },
  },
  components: {
    'delete-button': DeleteButton,
  },
  computed: {
    stages() {
      return this.$store.getters.stages
    },
  },
}
</script>

<style scoped>

</style>
