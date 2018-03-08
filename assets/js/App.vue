<template>
<div>
  <show-categories></show-categories>
  <router-view></router-view>
</div>
</template>

<script>
import ShowCategories from './components/category/ShowCategories.vue';

export default {
  components: {
    'show-categories': ShowCategories,
  },
  data() {
    return {

    }
  },
  methods: {

  },
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
