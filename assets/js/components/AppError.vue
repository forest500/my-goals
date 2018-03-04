<template>
<div class="">
    <div class="formErrors text-danger m-2" v-for="formError in formErrors">{{ formError}}</div>
</div>
</template>
<script>

export default {
  props: {
    formErrors: {
      type: Array,
    }
  }
}
</script>

<style scoped>

</style>
