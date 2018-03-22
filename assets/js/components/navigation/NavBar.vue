<template>
  <nav>
    <show-categories><logout slot="logout"></logout></show-categories>
  </nav>
</template>
<script>
import axios from 'axios'
import ShowCategories from '../category/ShowCategories'
import Logout from './Logout'

export default {
  components: {
    'show-categories': ShowCategories,
    'logout': Logout
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
