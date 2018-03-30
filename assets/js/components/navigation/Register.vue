<template>
  <div class="col-md-6 offset-md-3 mt-5">
    <form v-on:submit.prevent="register" class="form">
      <router-link :to="{ name: 'login' }">Powrót do logowania</router-link>
      <h3 class="text-center mt-2">Rejestracja</h3>
      <div class="form-group">
        <label for="email">email</label>
        <input v-model="credentials.email" type="email" name="email" id="email" class="form-control">
        <app-error if="errors.response.data.email" v-bind:formErrors="formErrors.email"></app-error>
      </div>
      <div class="form-group">
        <label for="plainPassword-first">Hasło</label>
        <input v-model="credentials.plainPassword.first" type="password" name="plainPassword-first" id="plainPassword-first" class="form-control">
        <div if="errors.response.data.plainPassword.first" class="formErrors text-danger m-2" v-for="formError in formErrors">{{ formError.first | match-err }}</div>
      </div>
      <div class="form-group">
        <label for="plainPassword-second">Powtórz hasło</label>
        <input v-model="credentials.plainPassword.second" type="password" name="plainPassword-second" id="plainPassword-second" class="form-control">
      </div>
      <div class="form-group">
        <label for="terms">Zaakceptuj regulamin</label>
        <input v-model="credentials.termsAccepted"  type="checkbox" name="terms" value="false">
        <app-error if="errors.response.data.termsAccepted" v-bind:formErrors="formErrors.termsAccepted"></app-error>
      </div>
      <div class="form-group float-center">
        <input type="submit" value="Zarejestruj się" class="btn btn-large btn-primary">
      </div>
    </form>
  </div>
</template>
<script>
import AppError from '../AppError.vue';

export default {
  components: {
    'app-error': AppError
  },
  data() {
    return {
      credentials: {
        "email": '',
        "plainPassword": {
          "first": "", "second": ""
        },
        "termsAccepted": false
      }
    }
  },
  methods: {
    register() {
      this.$store.dispatch('registerRequest', this.credentials)
        .then(() => {
          this.$router.push('/success_registration')
        })
    }
  },
  computed: {
    formErrors() {
      return this.$store.getters.formErrors
    },
  }

}
</script>

<style scoped>

</style>
