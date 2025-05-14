import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import "./style.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import axios from "axios";

// Configure axios globally
axios.defaults.baseURL = "http://localhost:8000";

// Add a request interceptor to include the token in every request
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Handle 401 responses globally
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    // Don't redirect if we're on the applicants page
    if (
      error.response &&
      error.response.status === 401 &&
      !window.location.href.includes("/jobs/") &&
      !window.location.href.includes("/applicants")
    ) {
      console.log("Unauthorized request. Redirecting to login...");
      router.push("/login");
    }
    return Promise.reject(error);
  }
);

// Add navigation guard for auth and role checking
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");
  const userRole = localStorage.getItem("userRole");

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    // Check if user is authenticated
    if (!token) {
      next({ path: "/" });
    } else if (
      to.matched.some((record) => record.meta.employerOnly) &&
      userRole !== "employer"
    ) {
      // Check if route requires employer role
      next({ path: "/profile" });
    } else {
      next();
    }
  } else {
    next();
  }
});

createApp(App).use(router).mount("#app");
