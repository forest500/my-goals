<template>
<button v-on:click="" type="button" class="btn btn-primary">
  Edytuj
</button>
</template>
<script>

export default {
  props: {
    itemToDelete: {
      type: Object,
      required: true,
    },
    deleteFunction: {
      type: String,
      required: true,
    },
  },
  methods: {
    remove() {
      this.$store.dispatch(this.deleteFunction, this.itemToDelete)
    }
  }
}
</script>

<style scoped>

</style>
