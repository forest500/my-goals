<template>
  <div class="row alert alert-dismissible fade show" role="alert">
    <strong class="col text-center">{{ message }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>
<script>

export default {
  created() {
    console.log('alert created')
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
