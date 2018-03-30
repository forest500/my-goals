<template>
  <div class="mt-3 jumbotron text-center">
    <h1 class="display-3">Dziękujemy za rejestrację</h1>
    <p>Na Twoją skrzynkę wysłaliśmy link aktywujący konto</p>
    <router-link :to="{ name: 'login'}">Powrót do logowania</router-link>
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

    }
  },
  methods: {

  },
  computed: {

  }

}
</script>

<style scoped>

</style>
