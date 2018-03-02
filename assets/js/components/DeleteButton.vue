<template>
<button v-on:click="remove" type="button" class="btn btn-danger">
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
    path: {
      type: String,
      required: true,
    },
    deleteFunction: {
      type: String,
      required: true,
    },
    index: {
      type: Number,
      required: false,
    }
  },
  data() {
    return {
      payload: { itemToDelete: this.itemToDelete, index: this.index }
    }
  },
  methods: {
    remove() {
      console.log(this.payload.index)
      this.$store.dispatch(this.deleteFunction, this.payload)
        .then(() => {
          this.$router.push({ path: this.path })
        })
    }
  }
}
</script>

<style scoped>

</style>
