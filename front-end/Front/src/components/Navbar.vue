<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import instance from '@/axios';

const router = useRouter();
const user = ref(null);
const loading = ref(true);

const isAuthenticated = computed(() => !!user.value);
const userRole = computed(() => user.value?.role || '');

// Check if user is logged in
onMounted(async () => {
  try {
    const token = localStorage.getItem('token');
    if (token) {
      loading.value = true;
      const response = await instance.get('/user');
      user.value = response.data;
    }
  } catch (error) {
    console.error('Authentication error:', error);
    localStorage.removeItem('token');
  } finally {
    loading.value = false;
  }
});

// Logout function
const logout = () => {
  localStorage.removeItem('token');
  user.value = null;
  router.push('/');
};
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      <!-- Logo/Brand -->
      <router-link class="navbar-brand d-flex align-items-center" to="/">
        <i class="bi bi-briefcase-fill text-primary me-2"></i>
        <span class="fw-bold">Job Board</span>
      </router-link>
      
      <!-- Hamburger button for mobile -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarContent"
        aria-controls="navbarContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <!-- Left side navigation -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link class="nav-link" to="/home">Home</router-link>
          </li>
        </ul>
        
        <!-- Right side based on authentication status -->
        <div v-if="loading" class="spinner-border spinner-border-sm text-primary me-2" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        
        <ul v-else class="navbar-nav">
          <!-- Not authenticated - Show login button -->
          <li v-if="!isAuthenticated" class="nav-item">
            <router-link class="btn btn-primary" to="/">Login</router-link>
          </li>
          
          <!-- Admin role navigation -->
          <template v-else-if="userRole === 'admin'">
            <li class="nav-item">
              <router-link class="nav-link" to="/dashboard">Dashboard</router-link>
            </li>
            <li class="nav-item">
              <button @click="logout" class="btn btn-outline-danger ms-2">Logout</button>
            </li>
          </template>
          
          <!-- Employer role navigation -->
          <template v-else-if="userRole === 'employer'">
            <li class="nav-item">
              <router-link class="nav-link" to="/profile">Profile</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/profile/jobs">My Jobs</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/profile/jobs/create">Post a Job</router-link>
            </li>
            <li class="nav-item">
              <button @click="logout" class="btn btn-outline-danger ms-2">Logout</button>
            </li>
          </template>
          
          <!-- Candidate role navigation -->
          <template v-else-if="userRole === 'candidate'">
            <li class="nav-item">
              <router-link class="nav-link" to="/profile">Profile</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/applications">My Applications</router-link>
            </li>
            <li class="nav-item">
              <button @click="logout" class="btn btn-outline-danger ms-2">Logout</button>
            </li>
          </template>
          
          <!-- Default authenticated user (role not specified) -->
          <template v-else>
            <li class="nav-item">
              <router-link class="nav-link" to="/profile">Profile</router-link>
            </li>
            <li class="nav-item">
              <button @click="logout" class="btn btn-outline-danger ms-2">Logout</button>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  padding: 0.75rem 1rem;
}

.navbar-brand {
  font-size: 1.3rem;
}

.nav-link {
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  transition: background-color 0.3s, color 0.3s;
}

.nav-link:hover {
  background-color: rgba(0, 123, 255, 0.1);
  color: #007bff;
}

.router-link-active {
  color: #007bff !important;
  font-weight: 600;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
  .navbar-nav .nav-link {
    padding: 0.5rem;
  }
  
  .navbar-nav .btn {
    margin: 0.5rem 0;
    display: block;
    width: 100%;
  }
}
</style> 