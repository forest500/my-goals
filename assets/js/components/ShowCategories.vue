<template>
<header class="mb-4 mt-3">
  <div class="row">
    <div v-show="loading" class="loading">
      <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>
    </div>
    <ul v-show ="!loading" class="nav bg-faded nav-pills col-10">
      <li class="nav-item" v-on:click="clearActiveCategory">
        <router-link to="/" exact class="nav-link active">
          Wszystkie
        </router-link>
      </li>
      <li class="nav-item" v-for="(category, index) in categories" v-bind:key="category.id" v-on:click="setActiveCategory(category, index)">
        <router-link class="nav-link" :to="{ name: 'category', params: {categoryName: category.name, id: category.id} }" exact>
          {{ category.name }}
        </router-link>
      </li>
    </ul>
    <router-link class="col" to="/nowa_kategoria" exact>
      <button v-on:click="clearActiveCategory" type="button" class="btn btn-success">Nowa kategoria</button>
    </router-link>
  </div>
</header>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      loading: false,
    }
  },
  created() {
    this.loading = true
    this.$store.dispatch('loadCategories')
      .then(() => {
        this.loading = false
      })
  },
  computed: {
    categories() {
      return this.$store.getters.categories
    }
  },
  methods: {
    setActiveCategory(category, index) {
      this.$store.commit('SET_CATEGORY', category);
      category.index = index;
    },
    clearActiveCategory() {
      this.$store.commit('SET_CATEGORY', {});
    }
  }
}
</script>

<style scoped>

</style>
