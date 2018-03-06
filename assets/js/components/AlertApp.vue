<template>
  <div class="alert alert-dismissible fade show" role="alert">
    <strong class="text-center mx-auto">{{ message }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>
<script>

export default {
  props: {
    message: {
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
