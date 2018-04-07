<template>
<div>
  <category-form v-bind:httpFunction="post">
    <button slot="button" class="btn btn-success">dodaj kategorię</button>
  </category-form>
</div>
</template>

<script>
import axios from 'axios';
import CategoryForm from './CategoryForm.vue';

export default {
  components: {
    'category-form': CategoryForm
  },
  methods: {
    post() {
      this.$store.dispatch('postCategory', this.categoryInForm)
        .then(() => {
          if (!this.hasErrors) {
            this.$router.push({
              path: `/${this.category.name}/${this.category.id}`
            })
          }
        })
    }
  },
  computed: {
    hasErrors() {
      return this.$store.getters.hasErrors
    },
    categoryInForm() {
      return this.$store.getters.categoryInForm
    },
    category() {
      return this.$store.getters.category
    }
  }
}
</script>

<style scoped>

</style>
