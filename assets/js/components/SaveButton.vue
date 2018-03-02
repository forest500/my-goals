<template>
<button type="submit" class="btn btn-success">
  zapisz
</button>
</template>
<script>

export default {
  props: {
    itemToEdit: {
      type: Object,
      required: true,
    },
    index: {
      type: Number,
      required: false,
    }
  },

}
</script>

<style scoped>

</style>
