<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="fw-bold text-dark animate__animated animate__fadeIn">
        <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
      </h1>
      <div class="dropdown">
        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
          Sort By: {{ sortOption }}
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" @click="sortOption = 'Count'">Count</a></li>
          <li><a class="dropdown-item" @click="sortOption = 'Title'">Title</a></li>
        </ul>
      </div>
    </div>
    <div v-if="isLoading" class="text-center my-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div v-else-if="hasError" class="alert alert-danger">
      <p><strong>Error:</strong> {{ errorMessage }}</p>
      <button class="btn btn-primary mt-2" @click="fetchStats">Try Again</button>
    </div>
    <div v-else>
      <div class="row g-4 mb-5">
        <div v-for="item in sortedItems" :key="item.title" class="col-md-6 col-lg-3">
          <div class="card h-100 text-center shadow border-0 animate__animated animate__fadeIn">
            <div class="card-body">
              <i :class="item.icon" class="text-secondary mb-3" style="font-size: 2.5rem;"></i>
              <h5 class="card-title fw-semibold text-dark">{{ item.title }}</h5>
              <p class="display-4 fw-bold text-primary">{{ item.count }}</p>
              <router-link :to="item.link" class="btn btn-outline-dark btn-sm">View Details</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/axios'; // Import the custom API instance
import Chart from 'chart.js/auto';

export default {
  data() {
    return {
      dashboardItems: [
        { title: 'Total Users', link: '/users', count: 0, icon: 'bi bi-people-fill' },
        { title: 'Total Jobs', link: '/jobs', count: 0, icon: 'bi bi-briefcase-fill' },
        { title: 'Pending Jobs', link: '/jobs/pending', count: 0, icon: 'bi bi-hourglass-split' },
        { title: 'Accepted Jobs', link: '/jobs/accepted', count: 0, icon: 'bi bi-check-circle-fill' },
        { title: 'Rejected Jobs', link: '/jobs/rejected', count: 0, icon: 'bi bi-x-circle-fill' }
      ],
      isLoading: false,
      hasError: false,
      errorMessage: '',
      sortOption: 'Count',
      chart: null
    };
  },
  computed: {
    sortedItems() {
      return [...this.dashboardItems].sort((a, b) => {
        return this.sortOption === 'Count' ? b.count - a.count : a.title.localeCompare(b.title);
      });
    }
  },
  mounted() {
    this.fetchStats();
  },
  methods: {
    async fetchStats() {
      this.isLoading = true;
      this.hasError = false;
      this.errorMessage = '';
      
      try {
        // Check if user is authenticated
        const token = localStorage.getItem('token');
        if (!token) {
          this.hasError = true;
          this.errorMessage = 'You need to be logged in to access this dashboard';
          this.isLoading = false;
          return;
        }
        
        // Set default values in case the API calls fail
        this.dashboardItems.forEach(item => {
          item.count = 0;
        });
        
        // Try to get the counts individually with better error handling
        try {
          const usersResponse = await api.get('/admin/users');
          if (usersResponse.data && usersResponse.data.users) {
            this.dashboardItems[0].count = usersResponse.data.users.total || usersResponse.data.users.length || 0;
          }
        } catch (error) {
          console.error('Error fetching users:', error.response?.status, error.message);
        }
        
        try {
          const jobsResponse = await api.get('/admin/jobs');
          if (jobsResponse.data && jobsResponse.data.jobs) {
            this.dashboardItems[1].count = jobsResponse.data.jobs.total || jobsResponse.data.jobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching jobs:', error.response?.status, error.message);
        }
        
        try {
          const pendingResponse = await api.get('/admin/jobs/pending');
          if (pendingResponse.data && pendingResponse.data.pendingJobs) {
            this.dashboardItems[2].count = pendingResponse.data.pendingJobs.total || pendingResponse.data.pendingJobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching pending jobs:', error.response?.status, error.message);
        }
        
        try {
          const acceptedResponse = await api.get('/admin/jobs/accepted');
          if (acceptedResponse.data && acceptedResponse.data.acceptedJobs) {
            this.dashboardItems[3].count = acceptedResponse.data.acceptedJobs.total || acceptedResponse.data.acceptedJobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching accepted jobs:', error.response?.status, error.message);
        }
        
        try {
          const rejectedResponse = await api.get('/admin/jobs/rejected');
          if (rejectedResponse.data && rejectedResponse.data.rejectedJobs) {
            this.dashboardItems[4].count = rejectedResponse.data.rejectedJobs.total || rejectedResponse.data.rejectedJobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching rejected jobs:', error.response?.status, error.message);
        }
        
      } catch (error) {
        console.error('Error fetching stats:', error);
        this.hasError = true;
        this.errorMessage = 'Failed to load dashboard data. ' + 
          (error.response?.status === 401 ? 'You are not authorized to view this data.' : 
           'There might be an issue with the server.');
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>

<style scoped>
.card {
  border-radius: 16px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background: linear-gradient(145deg, #f8f9fa, #e9ecef);
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.card-body {
  padding: 1.5rem;
}

h1 {
  font-size: 2rem;
  color: #343a40;
}

.btn {
  transition: all 0.2s ease;
}

.btn:hover {
  color: #fff;
  background-color: #343a40;
}
</style>
