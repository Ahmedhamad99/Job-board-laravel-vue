<template>
  <div class="card mb-3 shadow-lg rounded-4" style="border: 2px solid ; background-color: rgba(0, 123, 255, 0.15);">
    <div class="card-header bg-gradient text-dark rounded-top-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(45deg, #007bff, #6610f2);">
      <h5 class="mb-0 fw-bold">Job Details</h5>
      <router-link to="/alljobs" class="btn btn-outline-secondary text-dark ">Back</router-link>
    </div>
    <div class="card-body p-5 text-dark">
      <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
      <div v-if="job" class="bg-white p-4 rounded-3 shadow-sm">
        <div class="d-flex justify-content-between">
          <div>
            <h6 class="text-muted">Basic Information</h6>
            <!-- <p class="mb-2"><strong>ID:</strong> {{ job.id }}</p> -->
            <p class="mb-2"><strong>Title:</strong> {{ job.title }}</p>
            <p class="mb-2"><strong>Location:</strong> {{ job.location }}</p>
          </div>
          <div>
            <h6 class="text-muted">Financials</h6>
            <p class="mb-2"><strong>Minimum Salary:</strong> ${{ job.salary_min }}</p>
            <p class="mb-2"><strong>Maximum Salary:</strong> ${{ job.salary_max }}</p>
          </div>
        </div>
        <div class="mt-3">
          <h6 class="text-muted">Details</h6>
          <p class="mb-2"><strong>Description:</strong> {{ job.description }}</p>
          <p class="mb-2"><strong>Employer:</strong> {{ job.employer ? job.employer.name : 'N/A' }}</p>
          <p class="mb-2"><strong>Category:</strong> {{ job.category ? job.category.name : 'N/A' }}</p>
          <p class="mb-2"><strong>Status:</strong> <span :class="getStatusClass(job.status)" class="badge px-3 py-1 rounded-pill">{{ job.status }}</span></p>
        </div>
      </div>
      <div v-else class="alert alert-warning">No job data available.</div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      job: null,
      errorMessage: '',
    };
  },
  async mounted() {
    try {
      await this.getJob(this.$route.params.id);
    } catch (error) {
      this.errorMessage = 'Error fetching job details: ' + (error.response?.data?.message || error.message);
    }
  },
  methods: {
    async getJob(id) {
      const response = await axios.get(`/api/admin/jobs/show/${id}`);
      this.job = response.data;
    },
    getStatusClass(status) {
      switch (status) {
        case 'accepted':
          return 'badge bg-success';
        case 'pending':
          return 'badge bg-warning';
        case 'rejected':
          return 'badge bg-danger';
        default:
          return 'badge bg-secondary';
      }
    },
  },
};
</script>
