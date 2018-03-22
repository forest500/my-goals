import ShowCategories from './components/category/ShowCategories.vue'
import ShowGoals from './components/goal/ShowGoals.vue'
import NewCategory from './components/category/NewCategory.vue'
import EditCategory from './components/category/EditCategory.vue'
import ShowCategoryGoals from './components/goal/ShowCategoryGoals.vue'
import Login from './components/navigation/Login.vue'
import {store} from './store/index'

const ifNotAuthenticated = (to, from, next) => {
  if (!store.getters.isAuthenticated) {
    next()
    return
  }
  next('/')
}

const ifAuthenticated = (to, from, next) => {
  if(store.getters.isAuthenticated) {
    next()
    return
  }
  next('/login')
}

export default[
  {
    path: '/login',
    name: 'login',
    component: Login,
    beforeEnter: ifNotAuthenticated,
  }, {
    path : '/',
    name: 'home',
    component: ShowGoals,
    beforeEnter: ifAuthenticated,
  }, {
    path : '/nowa_kategoria',
    name: 'new_category',
    component: NewCategory,
    beforeEnter: ifAuthenticated,
  }, {
    path : '/:categoryName/:id',
    name: 'category',
    component: ShowCategoryGoals,
    beforeEnter: ifAuthenticated,
  }, {
    path : '/edytuj_kategorie/:categoryName/:id',
    name: 'edit_category',
    component: EditCategory,
    beforeEnter: ifAuthenticated,
  }
]
