<template>
  <div class="row alert alert-dismissible fade show" role="alert">
    <strong class="col text-center">{{ message }}</strong>
  </div>
</template>
<script>

export default {
  created() {
    setTimeout(this.closeAlert, 1200)
  },
  props: {
    message: {
      type: String,
      required: false,
    },
  },
  methods: {
    closeAlert() {
      $().alert('close')
      this.$store.commit('UNSET_ALERT');
    }
  }
}
</script>

<style scoped>

</style>
