<template>
<button v-on:click="remove" type="button" class="btn btn-danger">
  <i class="far fa-trash-alt"></i>
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
      required: false,
    },
    deleteFunction: {
      type: String,
      required: true,
    },
    index: {
      type: Number,
      required: true,
    }
  },
  data() {
    return {
      payload: {
        itemToDelete: this.itemToDelete,
        index: this.index
      }
    }
  },
  methods: {
    remove() {
      this.$store.dispatch(this.deleteFunction, this.payload)
        .then(() => {
          if (this.path) this.$router.push({
            path: this.path
          })
        })
    }
  }
}
</script>

<style scoped>

</style>
