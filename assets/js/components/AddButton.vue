<template>
<button v-on:click="showForm" type="button" class="btn btn-success">
  Dodaj {{ item }}
</button>
</template>
<script>

export default {
  props: {
    item: {
      type: String,
      required: true,
    },
  },
  methods: {
    showForm() {
      console.log('shit')
    }
  }
}
</script>

<style scoped>

</style>
