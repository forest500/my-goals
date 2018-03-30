<template>
  <div class="col-md-6 offset-md-3 mt-5">
    <router-link :to="{ name: 'register', params: {} }">Nie masz konta? Zarejestruj się</router-link>
    <h3 class="text-center mt-2">Zaloguj się</h3>
    <form v-on:submit.prevent="login" class="form">
      <div class="bd-danger"></div>
      <div class="text-danger">{{ authError }}</div>
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="credentials.email" type="email" name="email" id="email" class="form-control">
      </div>
      <div class="form-group">
        <label for="password">Hasło</label>
        <input v-model="credentials.password" type="password" name="password" id="password" class="form-control">
      </div>
      <div class="form-group float-center">
        <input type="submit" value="Zaloguj się" class="btn btn-large btn-primary">
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
      this.$store.dispatch('authRequest', this.credentials)
        .then((response) => {
          this.$router.push('/')
        })
    }
  },
  computed: {
    authError() {
      return this.$store.getters.authError
    }
  }

}
</script>

<style scoped>

</style>
