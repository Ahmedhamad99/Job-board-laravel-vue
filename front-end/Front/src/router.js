import { createRouter, createWebHistory } from "vue-router";
import CandidateLayout from "@/layouts/CandidateLayout.vue";
import SearchComponent from "@/components/SearchComponent.vue";

const routes = [
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
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
