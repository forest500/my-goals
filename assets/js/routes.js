import ShowCategories from './components/ShowCategories.vue';
import ShowGoals from './components/ShowGoals.vue';
import NewCategory from './components/NewCategory.vue';
import EditCategory from './components/EditCategory.vue';
import ShowCategoryGoals from './components/ShowCategoryGoals.vue';

export default [
  { path: '/', component: ShowGoals,},
  { path: '/nowa_kategoria', component: NewCategory},
  { path: '/:categoryName/:id', name: 'category', component: ShowCategoryGoals},
  { path: '/edytuj_kategorie/:categoryName/:id', name: 'edit_category', component: EditCategory},
]