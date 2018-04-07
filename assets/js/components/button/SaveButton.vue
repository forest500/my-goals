<template>
<button type="submit" class="btn btn-success">
  <i class="fas fa-check"></i>
</button>
</template>
<script>
export default {
  props: {
    itemToEdit: {
      type: Object,
    },
    index: {
      type: Number,
    }
  },

}
</script>

<style scoped>

</style>
