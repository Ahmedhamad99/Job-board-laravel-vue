<template>
  <div class="card mb-3 shadow-lg rounded-4" style="border: 2px solid ; background-color: white;">
    <div class="card-header  text-white rounded-top-4 d-flex justify-content-between align-items-center" style="background: rgba(0, 0, 0, 0.1);">
      <h5 class="mb-0 text-dark">User Details</h5>
      <router-link to="/users" class="btn btn-light text-dark fw-bold">Back</router-link>
    </div>
    <div class="card-body p-5 text-dark">
      <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
      <div v-if="user">
        <p><strong>Name:</strong> {{ user.name }}</p>
        <p><strong>Email:</strong> {{ user.email }}</p>
        <p><strong>Role:</strong> {{ user.role }}</p>
      </div>
      <div v-else class="alert alert-warning">No user data available.</div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      user: null,
      errorMessage: '',
    };
  },
  async mounted() {
    try {
      await this.getUser(this.$route.params.id);
    } catch (error) {
      this.errorMessage = 'Error fetching user details: ' + (error.response?.data?.message || error.message);
    }
  },
  methods: {
    async getUser(id) {
      const response = await axios.get(`/api/admin/users/show/${id}`);
      this.user = response.data;
    },
  },
};
</script>
