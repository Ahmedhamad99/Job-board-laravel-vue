<template>
  <div class="card mb-3 shadow-lg " style="border: 2px solid ; background-color: #f8f9fa;">
    <div class="card-header bg-dark text-white ">
      <h5 class="mb-0 text-center">Create New Job</h5>
    </div>
    <div class="card-body p-4">
      <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
      <form @submit.prevent="createJob">
        <div class="mb-3">
          <label for="title" class="form-label fw-bold">Title</label>
          <input v-model="newJob.title" type="text" class="form-control" id="title" placeholder="Enter job title" required>
        </div>
        <div class="mb-3">
          <label for="location" class="form-label fw-bold">Location</label>
          <input v-model="newJob.location" type="text" class="form-control" id="location" placeholder="Enter location" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label fw-bold">Description</label>
          <textarea v-model="newJob.description" class="form-control" id="description" rows="4" placeholder="Enter job description" required></textarea>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="salary_min" class="form-label fw-bold">Minimum Salary</label>
            <input v-model.number="newJob.salary_min" type="number" class="form-control" id="salary_min" placeholder="Min salary" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="salary_max" class="form-label fw-bold">Maximum Salary</label>
            <input v-model.number="newJob.salary_max" type="number" class="form-control" id="salary_max" placeholder="Max salary" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="employer_id" class="form-label fw-bold">Employer</label>
          <select v-model="newJob.employer_id" class="form-select" id="employer_id" required>
            <option v-for="employer in employers" :key="employer.id" :value="employer.id">{{ employer.name }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="category_id" class="form-label fw-bold">Category</label>
          <select v-model="newJob.category_id" class="form-select" id="category_id" required>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label fw-bold">Status</label>
          <select v-model="newJob.status" class="form-select" id="status" required>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success me-2">Create</button>
          <router-link to="/alljobs" class="btn btn-secondary">Cancel</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      newJob: {
        title: '',
        location: '',
        description: '',
        salary_min: null,
        salary_max: null,
        employer_id: null,
        category_id: null,
        status: 'pending',
      },
      employers: [],
      categories: [],
      errorMessage: '',
    };
  },
  async mounted() {
    try {
      await Promise.all([this.getEmployers(), this.getCategories()]);
    } catch (error) {
      this.errorMessage = 'Error loading data: ' + (error.response?.data?.message || error.message);
    }
  },
  methods: {
    async getEmployers() {
      const response = await axios.get('/api/admin/employers');
      this.employers = response.data.employers?.data || [];
    },
    async getCategories() {
      const response = await axios.get('/api/admin/categories');
      this.categories = response.data.categories?.data || [];
    },
    async createJob() {
      try {
        await axios.post('/api/admin/jobs/create', this.newJob);
        this.$router.push({ name: 'alljobs.index' });
      } catch (error) {
        this.errorMessage = 'Error creating job: ' + (error.response?.data?.message || error.message);
      }
    },
  },
};
</script>
