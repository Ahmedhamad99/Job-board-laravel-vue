import { createRouter, createWebHistory } from 'vue-router';
import JobList from '../components/JobList.vue';
import ApplyJob from '../components/ApplyJob.vue';

const routes = [
  {
    path: '/',
    component: JobList,
    name: 'home',
  },
  {
    path: '/jobs/:jobId/apply',
    component: ApplyJob,
    name: 'applyJob',
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
