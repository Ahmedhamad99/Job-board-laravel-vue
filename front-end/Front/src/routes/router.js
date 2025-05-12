import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../components/Dashboard.vue'
import PendingJobs from '../components/Jobs/PendingJobs.vue'
import UsersList from '../components/Users/UsersList.vue'
import  AllJobs  from '../components/Jobs/AllJobs.vue'
import CreateJob from '../components/Jobs/CreateJob.vue'
import EditJob from '../components/Jobs/EditJob.vue'
import JobDetails from '../components/Jobs/JobDetails.vue'
import CreateUser from '../components/Users/CreateUser.vue'
import UserDetails from '../components/Users/UserDetails.vue'
import EditUser from '../components/Users/EditUser.vue'
import AcceptedJobs from '../components/Jobs/AcceptedJobs.vue'
import RejectedJobs from '../components/Jobs/RejectedJobs.vue'
const routes = [
  { path: '/dashboard', component: Dashboard, name: 'dashboard' },
  { path: '/jobs', component: AllJobs, name: 'jobs.index' },
  { path: '/jobs/pending', component: PendingJobs, name: 'jobs.pending' },
  { path: '/jobs/accepted', component: AcceptedJobs, name: 'jobs.accepted' },
  { path: '/jobs/rejected', component: RejectedJobs, name: 'jobs.rejected' },
  { path: '/users', component: UsersList, name: 'users.index' },
  { path: '/alljobs', component: AllJobs, name: 'alljobs.index' },
  { path: '/jobs/create', component: CreateJob, name: 'jobs.create' },
  { path: '/jobs/:id', component: JobDetails, name: 'jobs.show' },
  { path: '/jobs/:id/edit', component: EditJob, name: 'jobs.edit' },
  {path: '/users/create', component: CreateUser, name: 'users.create' },
  { path: '/users/:id', component:UserDetails, name: 'users.show' },
  {path: '/users/:id/edit', component: EditUser, name: 'users.edit' }
 
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router