<template>
  <div class="card mb-3 shadow-lg rounded-4" style="background-color:white;" >
    <div class="card-header text-white;">
    <div class="card-header bg-dark text-white rounded-top-4 d-flex justify-content-between align-items-center" >
      <h5 class="mb-0">Edit User</h5>
      <router-link to="/users" class="btn btn-light text-dark fw-bold">Back</router-link>
    </div>
    <div class="card-body p-5 text-dark">
      <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
      <form @submit.prevent="updateUser" v-if="editUserData">
        <div class="mb-3">
          <label for="edit-name" class="form-label">Name</label>
          <input v-model="editUserData.name" type="text" class="form-control" id="edit-name" required>
        </div>
        <div class="mb-3">
          <label for="edit-email" class="form-label">Email</label>
          <input v-model="editUserData.email" type="email" class="form-control" id="edit-email" required>
        </div>
        <div class="mb-3">
          <label for="edit-password" class="form-label">Password (leave blank to keep unchanged)</label>
          <input v-model="editUserData.password" type="password" class="form-control" id="edit-password">
        </div>
        <div class="mb-3">
          <label for="edit-role" class="form-label">Role</label>
          <select v-model="editUserData.role" class="form-control" id="edit-role" required>
            <option value="admin">Admin</option>
            <option value="employer">Employer</option>
            <option value="candidate">Candidate</option>
          </select>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary btn-lg">Update</button>
          <router-link to="/users" class="btn btn-secondary btn-lg">Cancel</router-link>
        </div>
      </form>
    </div>
  </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      editUserData: null,
      errorMessage: '',
    };
  },
  async mounted() {
    try {
      await this.getUser(this.$route.params.id);
    } catch (error) {
      this.errorMessage = 'Error loading user data: ' + (error.response?.data?.message || error.message);
    }
  },
  methods: {
    async getUser(id) {
      const response = await axios.get(`/api/admin/users/show/${id}`);
      this.editUserData = {
        id: response.data.id,
        name: response.data.name,
        email: response.data.email,
        password: '',
        role: response.data.role || 'candidate',
      };
    },
    async updateUser() {
      const updateData = {
        name: this.editUserData.name,
        email: this.editUserData.email,
        role: this.editUserData.role,
      };
      if (this.editUserData.password) {
        updateData.password = this.editUserData.password;
      }
      try {
        await axios.put(`/api/admin/users/update/${this.editUserData.id}`, updateData);
        this.$router.push({
          name: 'users.index',
          query: { success: 'User updated successfully' }
        });
      } catch (error) {
        this.errorMessage = 'Error updating user: ' + (error.response?.data?.message || error.message);
      }
    },
  },
};
</script>
