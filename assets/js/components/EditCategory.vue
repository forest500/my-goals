<template>
<div class="w-100 row">
    <category-form v-bind:httpFunction="put">
      <button slot="button" class="btn btn-success">edytuj</button>
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
    put() {
      this.$store.dispatch('editCategory', this.categoryInForm)
        .then(() => {
          if(!this.hasErrors) {
            this.$store.dispatch('loadCategories')
            this.$router.push({ path: `/${this.categoryInForm.name}/${this.categoryInForm.id}` })
          }
        })
    }
  },
  computed: {
    hasErrors () {
      return this.$store.getters.hasErrors
    },
    categoryInForm() {
      return this.$store.getters.categoryInForm
    }
  }
}
</script>

<style scoped>

</style>
