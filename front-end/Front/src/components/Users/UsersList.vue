<template>
  <div>
    <h1 class="text-center mb-4">Manage Users</h1>
    <div class="mb-3 d-flex justify-content-between align-items-center">
      <input v-model="searchQuery" @input="searchUsers" type="text" class="form-control w-50" placeholder="Search by name">
      <router-link to="/users/create" class="btn btn-success mb-3">Create New User <i class="fas fa-user-plus"></i></router-link>
    </div>

    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

    <table class="table table-hover table-striped  shadow-sm">
      <thead class="bg-dark text-white">
        <tr class="bg-dark">
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.role }}</td>
          <td>
            <router-link :to="{ name: 'users.show', params: { id: user.id } }" class="btn btn-warning btn-sm me-2">
              <i class="fas fa-eye"></i> View
            </router-link>
            <router-link :to="{ name: 'users.edit', params: { id: user.id } }" class="btn btn-success btn-sm me-2">
              <i class="fas fa-pencil-alt"></i> Edit
            </router-link>
            <button class="btn btn-danger btn-sm" @click="openDeleteModal(user.id)">
              <i class="fas fa-trash-alt"></i> Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
          <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">
            <i class="fas fa-arrow-left"></i> Previous
          </a>
        </li>

        <li v-for="page in pageNumbers" :key="page" class="page-item" :class="{ active: page === pagination.current_page }">
          <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
        </li>

        <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
          <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">
            Next <i class="fas fa-arrow-right"></i>
          </a>
        </li>
      </ul>
    </nav>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this user?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" @click="deleteUser">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Modal } from 'bootstrap';

export default {
  data() {
    return {
      users: [],
      pagination: {},
      pageNumbers: [],
      searchQuery: '',
      errorMessage: '',
      successMessage: '',
      deleteUserId: null,
      deleteModal: null,
    };
  },
  mounted() {
    this.getUsers();
    this.deleteModal = new Modal(document.getElementById('deleteModal'));
    if (this.$route.query.success) {
      this.successMessage = this.$route.query.success;
      this.$router.replace({ name: 'users.index' });
    }
  },
  methods: {
    async getUsers(page = 1) {
      try {
        const response = await axios.get(`/api/admin/users?page=${page}`);
        this.users = response.data.users.data;
        this.pagination = response.data.users;
        this.errorMessage = '';
        this.updatePageNumbers();
      } catch (error) {
        this.errorMessage = 'Error fetching users: ' + (error.response?.data?.message || error.message);
      }
    },
    async searchUsers() {
      if (this.searchQuery) {
        try {
          const response = await axios.get(`/api/admin/users/search?query=${this.searchQuery}`);
          this.users = response.data.users.data;
          this.pagination = response.data.users;
          this.errorMessage = '';
          this.updatePageNumbers();
        } catch (error) {
          this.errorMessage = 'Error searching users: ' + (error.response?.data?.message || error.message);
        }
      } else {
        await this.getUsers();
      }
    },
    openDeleteModal(id) {
      this.deleteUserId = id;
      this.deleteModal.show();
    },
    async deleteUser() {
      try {
        const response = await axios.delete(`/api/admin/users/delete/${this.deleteUserId}`);
        this.successMessage = response.data.message;
        this.errorMessage = '';
        this.deleteModal.hide();
        await this.getUsers();
      } catch (error) {
        this.errorMessage = 'Error deleting user: ' + (error.response?.data?.message || error.message);
      }
    },
    updatePageNumbers() {
      let startPage = Math.max(1, this.pagination.current_page - 5);
      let endPage = Math.min(this.pagination.last_page, startPage + 9);
      this.pageNumbers = [];
      for (let i = startPage; i <= endPage; i++) {
        this.pageNumbers.push(i);
      }
    },
    async changePage(page) {
      if (page > 0 && page <= this.pagination.last_page) {
        await this.getUsers(page);
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
