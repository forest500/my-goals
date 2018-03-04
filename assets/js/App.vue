<template>
<div>
  <show-categories></show-categories>
  <router-view></router-view>
</div>
</template>

<script>
import ShowCategories from './components/ShowCategories.vue';

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
