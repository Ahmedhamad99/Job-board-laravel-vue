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
import axios from 'axios';
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
      try {
        const [usersRes, jobsRes, pendingRes, acceptedRes, rejectedRes] = await Promise.all([
          axios.get('/api/admin/users'),
          axios.get('/api/admin/jobs'),
          axios.get('/api/admin/jobs/pending'),
          axios.get('/api/admin/jobs/accepted'),
          axios.get('/api/admin/jobs/rejected')
        ]);
        this.dashboardItems[0].count = usersRes.data.users.total;
        this.dashboardItems[1].count = jobsRes.data.jobs.total;
        this.dashboardItems[2].count = pendingRes.data.pendingJobs.total;
        this.dashboardItems[3].count = acceptedRes.data.acceptedJobs.total;
        this.dashboardItems[4].count = rejectedRes.data.rejectedJobs.total;
      } catch (error) {
        console.error('Error fetching stats:', error);
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
