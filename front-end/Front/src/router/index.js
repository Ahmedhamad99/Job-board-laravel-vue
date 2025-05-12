import { createRouter, createWebHistory } from "vue-router";

// Import all components at the beginning of the file
import AddJob from "@/components/views/AddJob.vue";
import ListJobsForEmployer from "@/components/views/ListJobsForEmployer.vue";
import JobDetails from "@/components/views/JobDetails.vue";
// import Home from "@/components/views/Home.vue";
// import Login from "@/components/views/Login.vue";
// import Register from "@/components/views/Register.vue";
// import Jobs from "@/components/views/Jobs.vue";
// import Profile from "@/components/views/Profile.vue";
// import Applications from "@/components/views/Applications.vue";
// import NotFound from "@/components/views/NotFound.vue";

const routes = [
  // {
  //   path: "/",
  //   name: "Home",
  //   component: Home,
  // },
  // {
  //   path: "/login",
  //   name: "Login",
  //   component: Login,
  //   meta: { guest: true },
  // },
  // {
  //   path: "/register",
  //   name: "Register",
  //   component: Register,
  //   meta: { guest: true },
  // },
  // {
  //   path: "/jobs",
  //   name: "Jobs",
  //   component: Jobs,
  // },
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
  // {
  //   path: "/profile",
  //   name: "Profile",
  //   component: Profile,
  //   meta: { requiresAuth: true },
  // },
  // {
  //   path: "/applications",
  //   name: "Applications",
  //   component: Applications,
  //   meta: { requiresAuth: true },
  // },
  // {
  //   path: "/:pathMatch(.*)*",
  //   name: "NotFound",
  //   component: NotFound,
  // },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guards
// router.beforeEach((to, from, next) => {
//   const isAuthenticated = !!localStorage.getItem("token");

//   // Check if route requires authentication
//   if (to.matched.some((record) => record.meta.requiresAuth)) {
//     if (!isAuthenticated) {
//       next({
//         path: "/login",
//         query: { redirect: to.fullPath },
//       });
//     } else {
//       next();
//     }
//   }
//   // Check if route is for guests only (login/register)
//   else if (to.matched.some((record) => record.meta.guest)) {
//     if (isAuthenticated) {
//       next({ path: "/" });
//     } else {
//       next();
//     }
//   } else {
//     next();
//   }
// });

export default router;
