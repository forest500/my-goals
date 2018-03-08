import ShowCategories from './components/category/ShowCategories.vue';
import ShowGoals from './components/goal/ShowGoals.vue';
import NewCategory from './components/category/NewCategory.vue';
import EditCategory from './components/category/EditCategory.vue';
import ShowCategoryGoals from './components/goal/ShowCategoryGoals.vue';

export default [
  { path: '/', name: 'home', component: ShowGoals,},
  { path: '/nowa_kategoria', name: 'new_category', component: NewCategory},
  { path: '/:categoryName/:id', name: 'category', component: ShowCategoryGoals},
  { path: '/edytuj_kategorie/:categoryName/:id', name: 'edit_category', component: EditCategory},
]
