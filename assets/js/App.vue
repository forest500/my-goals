<template>
<div>
    <nav-bar v-if="isAuthenticated"></nav-bar>
    <router-view class="margin-footer"></router-view>
    <my-footer></my-footer>
</div>
</template>

<script>
import NavBar from './components/navigation/NavBar.vue';
import Footer from './components/navigation/Footer.vue';
import axios from 'axios'

export default {
  components: {
    'nav-bar': NavBar,
    'my-footer': Footer,
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters.isAuthenticated
    },
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
  background-image: url("./img/metal-background.png");
  font-family: 'Lato', sans-serif;
}
ul {
  list-style-type: none;
}
.margin-footer {
  margin-bottom: 7rem;
}
</style>
