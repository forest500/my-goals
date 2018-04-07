<template>
<button type="button" class="btn btn-success">
  {{ item }}
</button>
</template>
<script>
export default {
  props: {
    item: {
      type: String,
      required: false,
    },
  },
  methods: {

  }
}
</script>

<style scoped>

</style>
