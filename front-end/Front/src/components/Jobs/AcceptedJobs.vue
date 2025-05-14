<template>
  <div class="container py-4">
    <h1 class="fw-bold text-dark mb-4 animate__animated animate__fadeIn text-center">
      <i class="bi bi-check-circle me-2"></i>Accepted Jobs
    </h1>
    <div v-if="errorMessage" class="alert alert-danger animate__animated animate__fadeIn">{{ errorMessage }}</div>
    <div v-if="successMessage" class="alert alert-success animate__animated animate__fadeIn">{{ successMessage }}</div>
    <div class="table-responsive">
      <table class="table  table-hover rounded shadow-sm">
        <thead class="table-secondary">
          <tr>
            <th>Title</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="job in acceptedJobs" :key="job.id" class="table-light">
            <td>{{ job.title }}</td>
            <td>{{ job.location }}</td>
            <td>{{ job.status }}</td>
            <td>
              <router-link :to="{ name: 'jobs.show', params: { id: job.id } }" class="btn btn-warning btn-sm vew-btn" >View</router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <nav aria-label="Page navigation" class="mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
          <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">Previous</a>
        </li>
        <li v-for="page in pagination.last_page" :key="page" class="page-item" :class="{ active: page === pagination.current_page }">
          <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
          <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">Next</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      acceptedJobs: [],
      pagination: {},
      errorMessage: '',
      successMessage: '',
    };
  },
  mounted() {
    this.getAcceptedJobs();
  },
  methods: {
    async getAcceptedJobs(page = 1) {
      try {
        const response = await axios.get(`/api/admin/jobs/accepted?page=${page}`);
        this.acceptedJobs = response.data.acceptedJobs.data;
        this.pagination = response.data.acceptedJobs;
        this.errorMessage = '';
      } catch (error) {
        this.errorMessage = 'Error fetching accepted jobs: ' + (error.response?.data?.message || error.message);
      }
    },
    async changePage(page) {
      if (page > 0 && page <= this.pagination.last_page) {
        await this.getAcceptedJobs(page);
      }
    },
  },
};
</script>

<style scoped>
.table {
  border-radius: 8px;
  overflow: hidden;
}

.table thead {
  background-color: #343a40;
  color: #fff;
}

.table-hover tbody tr:hover {
  background-color: #f1f3f5;
}

.page-link {
  color: #343a40;
  transition: background-color 0.2s ease;
}

.page-link:hover, .page-item.active .page-link {
  background-color: #343a40;
  color: #fff;
}

h1 {
  font-size: 1.75rem;
  color: #343a40;
}

</style>
