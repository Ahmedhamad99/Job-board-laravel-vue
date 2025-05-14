import { createRouter, createWebHistory } from "vue-router";
import LoginRegister from "../views/LoginRegister.vue";
import EditProfile from "@/views/EditProfile.vue";
import ProfileView from "@/views/ProfileView.vue";
import JobDetails from "@/views/JobDetails.vue";
import ListJobsForEmployer from "@/views/ListJobsForEmployer.vue";
import AddJob from "@/views/AddJob.vue";
import Dashboard from "@/components/Dashboard.vue";
import AllJobs from "@/components/Jobs/AllJobs.vue";
import PendingJobs from "@/components/Jobs/PendingJobs.vue";
import AcceptedJobs from "@/components/Jobs/AcceptedJobs.vue";
import RejectedJobs from "@/components/Jobs/RejectedJobs.vue";
import UsersList from "@/components/Users/UsersList.vue";
import CreateJob from "@/components/Jobs/CreateJob.vue";
import JobDetailsAdmin from "@/components/Jobs/JobDetailsAdmin.vue";
import EditJob from "@/components/Jobs/EditJob.vue";
import CreateUser from "@/components/Users/CreateUser.vue";
import UserDetails from "@/components/Users/UserDetails.vue";
import EditUser from "@/components/Users/EditUser.vue";
import JobList from "@/views/JobList.vue";
import ApplyJob from "@/views/ApplyJob.vue";
import Applications from "@/views/Applications.vue";
import SearchComponent from "@/components/SearchComponent.vue";
import CandidateLayout from "@/views/CandidateLayout.vue";
import Applicants from "@/views/Applicants.vue";
import NotFound from "@/views/NotFound.vue";

const routes = [
  { path: "/", component: LoginRegister },
  {
    path: "/edit-profile",
    name: "EditProfile",
    component: EditProfile,
  },
  {
    path: "/profile",
    name: "ProfileView",
    component: ProfileView,
  },
  {
    path: "/profile/jobs/create",
    name: "CreateJob",
    component: AddJob,
    meta: { requiresAuth: true },
  },
  {
    path: "/profile/jobs/edit/:id",
    name: "EditJob",
    component: AddJob,
    meta: { requiresAuth: true },
  },
  {
    path: "/profile/jobs",
    name: "EmployerJobs",
    component: ListJobsForEmployer,
    meta: { requiresAuth: true },
  },
  {
    path: "/profile/jobs/:id",
    name: "JobDetails",
    component: JobDetails,
    meta: { requiresAuth: true },
  },
  {
    path: "/jobs/:jobId/applicants",
    name: "Applicants",
    component: Applicants,
  },
  { path: "/dashboard", component: Dashboard, name: "dashboard" },
  { path: "/jobs", component: AllJobs, name: "jobs.index" },
  { path: "/jobs/pending", component: PendingJobs, name: "jobs.pending" },
  { path: "/jobs/accepted", component: AcceptedJobs, name: "jobs.accepted" },
  { path: "/jobs/rejected", component: RejectedJobs, name: "jobs.rejected" },
  { path: "/users", component: UsersList, name: "users.index" },
  { path: "/alljobs", component: AllJobs, name: "alljobs.index" },
  { path: "/jobs/create", component: CreateJob, name: "jobs.create" },
  { path: "/jobs/:id", component: JobDetailsAdmin, name: "jobs.show" },
  { path: "/jobs/:id/edit", component: EditJob, name: "jobs.edit" },
  { path: "/users/create", component: CreateUser, name: "users.create" },
  { path: "/users/:id", component: UserDetails, name: "users.show" },
  { path: "/users/:id/edit", component: EditUser, name: "users.edit" },
  {
    path: "/home",
    component: JobList,
    name: "home",
  },
  {
    path: "/jobs/:jobId/apply",
    component: ApplyJob,
    name: "applyJob",
  },
  {
    path: "/applications",
    component: Applications,
    name: "applications",
    meta: { requiresAuth: true },
  },
  {
    path: "/candidate",
    name: "Candidate",
    component: CandidateLayout,
    children: [
      {
        path: "jobs-search",
        component: SearchComponent,
        name: "CandidateDashboard",
      },
    ],
  },
  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
