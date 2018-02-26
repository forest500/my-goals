<template>
<button v-on:click="remove" type="button" class="btn btn-danger btn-sm">
  Usuń
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
      this.$router.push({ path: '/' })
    }
  }
}
</script>

<style scoped>

</style>
