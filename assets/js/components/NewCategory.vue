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
          if(!this.hasErrors) {
            this.$store.dispatch('loadCategories')
              .then(() => {
                this.$router.push({ path:`/` })
              })
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
