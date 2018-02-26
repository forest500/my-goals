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
      this.$store.dispatch('postCategory', this.category)
        .then(() => {
          if(!this.hasErrors) this.$router.push({ path: '/' })
          this.$store.dispatch('loadCategories')
        })
    }
  },
  computed: {
    hasErrors () {
      return this.$store.getters.hasErrors
    },
    category() {
      return this.$store.getters.category
    }
  }
}
</script>

<style scoped>

</style>
