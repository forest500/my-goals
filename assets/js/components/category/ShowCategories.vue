<template>
<header class="mb-5 mt-4">
  <div v-if="errorMsg" class="text-danger text-center">{{ errorMsg }}</div>
  <div v-show="loading" class="loading">
    <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
  </div>
  <div v-show ="!loading" class="container">
    <div class="row">
        <ul class="nav bg-faded nav-pills" role="tablist">
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
       <router-link class="ml-auto new-category" to="/nowa_kategoria" exact>
          <button v-on:click="clearActiveCategory" type="button" class="btn btn-success">Nowa kategoria</button>
        </router-link>
      </div>
  </div>
  <div class="container">
    <div class="row justify-content-end">
        <slot class="" name="logout"></slot>
    </div>
  </div>

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
      .then((response) => {
        if(typeof response !== 'undefined' && response.name === 'Error') {
          this.loading = true
          this.errorMsg = 'Nie udało się wczytać kategorii spróbuj ponownie później'
        } else {
          this.loading = false
        }
      })
  },
  data() {
    return {
      loading: false,
      errorMsg: ''
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

<style>
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
