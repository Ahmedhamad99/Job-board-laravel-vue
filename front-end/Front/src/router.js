import { createRouter, createWebHistory } from 'vue-router'
import EditProfile from './components/EditProfile.vue'
import ProfileView from './components/ProfileView.vue'

const routes = [
  {
    path: '/edit-profile',
    name: 'EditProfile',
    component: EditProfile
  },
   {
    path: '/profile',
    name: 'ProfileView',
    component: ProfileView
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
