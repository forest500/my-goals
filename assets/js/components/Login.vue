<template>
  <div class="col-md-6 col-md-offset-3">
    <form v-on:submit.prevent="login" class="form">
      <div class="bd-danger"></div>
      <h3 class="text-center">Zaloguj się</h3>
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="credentials.email" type="email" name="email" id="email" class="form-control">
      </div>
      <div class="form-group">
        <label for="password">Hasło</label>
        <input v-model="credentials.password" type="password" name="password" id="password" class="form-control">
      </div>
      <div class="form-group">
        <input type="submit" value="Login" class="btn btn-large btn-primary">
      </div>
    </form>
  </div>
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
    login() {
      this.$store.dispatch('signIn', this.credentials)
    }
  }

}
</script>

<style scoped>

</style>
