<template>
<header class="mb-5 mt-4">
  <div v-show="loading" class="loading">
    <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
  </div>
  <div v-show ="!loading" class="row">
      <ul class="nav bg-faded nav-pills col-10" role="tablist">
        <li class="nav-item" v-on:click="clearActiveCategory">
          <router-link to="/" exact class="nav-link">
            Wszystkie
          </router-link>
        </li>
        <li class="nav-item" v-for="(category, index) in categories" :key="category.id">
          <router-link :to="{ name: 'category', params: {categoryName: category.name, id: category.id} }" exact class="nav-link">
            {{ category.name }}
          </router-link>
        </li>
      </ul>
    <router-link class="col new-category" to="/nowa_kategoria" exact>
      <button v-on:click="clearActiveCategory" type="button" class="btn btn-success">Nowa kategoria</button>
    </router-link>
  </div>
  <alert-app v-if="alert.category" :class="alert.class" :message="alert.message"></alert-app>
</header>
</template>

<script>
import { mapGetters } from 'vuex'
import AlertApp from '../AlertApp.vue'

export default {
  components: {
    'alert-app': AlertApp,
  },
  created() {
    this.loading = true;
    this.$store.dispatch('loadCategories')
      .then(() => {
        this.loading = false;
      })
  },
  data() {
    return {
      loading: false,
    }
  },
  computed: {
    categories() {
      return this.$store.getters.categories
    },
    alert() {
      return this.$store.getters.alert
    },
  },
  methods: {
    clearActiveCategory() {
      this.$store.commit('SET_CATEGORY', {});
    },
  }
}
</script>

<style scoped>
.loading {
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-right: -50%;
  transform: translate(-50%, -50%)
}
.router-link-active {
  background-color: #778899;
}
a.new-category {
  background-color: inherit;
}


</style>
