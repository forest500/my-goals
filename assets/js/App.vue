<template>
<div>
    <nav-bar v-if="isAuthenticated"></nav-bar>
    <router-view></router-view>
</div>
</template>

<script>
import NavBar from './components/navigation/NavBar.vue';
import axios from 'axios'

export default {
  created: function () {
    axios.interceptors.response.use(undefined, function (err) {
      return new Promise(function (resolve, reject) {
        if (err.status === 401) {
          this.$store.dispatch('authLogout')
            .then(() => {
              console.log('error')
              this.$router.push('/login')
            })
        }
        throw err;
      });
    });
  },
  components: {
    'nav-bar': NavBar,
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters.isAuthenticated
    },
  }
}
</script>

<style>
html {
  font-size: 1rem;
}
@include media-breakpoint-up(sm) {
  html {
    font-size: 1.2rem;
  }
}
@include media-breakpoint-up(md) {
  html {
    font-size: 1.4rem;
  }
}
@include media-breakpoint-up(lg) {
  html {
    font-size: 1.6rem;
  }
}
body {
  width: 85%;
  margin-right: auto;
  margin-left: auto;
  background-image: url("./img/metal-background.png");
}
ul {
  list-style-type: none;
}
</style>
