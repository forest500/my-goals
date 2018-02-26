<template>
<div>
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
      this.$store.dispatch('editCategory', this.category)
        .then(() => {
          if(!this.hasErrors) this.$router.push({ path: '/' + this.category.name })
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
