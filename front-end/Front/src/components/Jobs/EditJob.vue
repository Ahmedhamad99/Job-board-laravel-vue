<template>
  <div class="card mb-3 shadow-lg rounded-3" style="border: 2px solid yellow; background-color: #f8f9fa;">
    <div class="card-header bg-warning text-white rounded-top-3">
      <h5 class="mb-0 text-center">Edit Job</h5>
    </div>
    <div class="card-body p-4">
      <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
      <form @submit.prevent="updateJob" v-if="editJobData">
        <div class="mb-3">
          <label for="edit-title" class="form-label fw-bold">Title</label>
          <input v-model="editJobData.title" type="text" class="form-control" id="edit-title" placeholder="Enter job title" required>
        </div>
        <div class="mb-3">
          <label for="edit-location" class="form-label fw-bold">Location</label>
          <input v-model="editJobData.location" type="text" class="form-control" id="edit-location" placeholder="Enter location" required>
        </div>
        <div class="mb-3">
          <label for="edit-description" class="form-label fw-bold">Description</label>
          <textarea v-model="editJobData.description" class="form-control" id="edit-description" rows="4" placeholder="Enter job description" required></textarea>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="edit-salary_min" class="form-label fw-bold">Minimum Salary</label>
            <input v-model.number="editJobData.salary_min" type="number" class="form-control" id="edit-salary_min" placeholder="Min salary" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="edit-salary_max" class="form-label fw-bold">Maximum Salary</label>
            <input v-model.number="editJobData.salary_max" type="number" class="form-control" id="edit-salary_max" placeholder="Max salary" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="edit-employer_id" class="form-label fw-bold">Employer</label>
          <select v-model="editJobData.employer_id" class="form-select" id="edit-employer_id" required>
            <option v-for="employer in employers" :key="employer.id" :value="employer.id">{{ employer.name }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="edit-category_id" class="form-label fw-bold">Category</label>
          <select v-model="editJobData.category_id" class="form-select" id="edit-category_id" required>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="edit-status" class="form-label fw-bold">Status</label>
          <select v-model="editJobData.status" class="form-select" id="edit-status" required>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-warning me-2">Update</button>
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
      editJobData: null,
      employers: [],
      categories: [],
      errorMessage: '',
    };
  },
  async mounted() {
    try {
      await Promise.all([
        this.getJob(this.$route.params.id),
        this.getEmployers(),
        this.getCategories(),
      ]);
    } catch (error) {
      this.errorMessage = 'Error loading data: ' + (error.response?.data?.message || error.message);
    }
  },
  methods: {
    async getJob(id) {
      const response = await axios.get(`/api/admin/jobs/show/${id}`);
      this.editJobData = response.data;
    },
    async getEmployers() {
      const response = await axios.get('/api/admin/employers');
      this.employers = response.data.employers?.data || [];
    },
    async getCategories() {
      const response = await axios.get('/api/admin/categories');
      this.categories = response.data.categories?.data || [];
    },
    async updateJob() {
      try {
        await axios.put(`/api/admin/jobs/update/${this.editJobData.id}`, this.editJobData);
        this.$router.push({ name: 'alljobs.index' });
      } catch (error) {
        this.errorMessage = 'Error updating job: ' + (error.response?.data?.message || error.message);
      }
    },
  },
};
</script>
