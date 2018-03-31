<template>
  <button v-on:click="logout" class="btn btn-primary">
    <i class="fas fa-sign-out-alt"></i>
  </button>
</template>
<script>
import axios from 'axios'
export default {
  data() {
    return {
      credentials: {
        'email': '',
        'password': ''
      }
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('authLogout')
        .then(() => {
          this.$router.push('/login')
        })
    }
  }

}
</script>

<style scoped>

</style>
