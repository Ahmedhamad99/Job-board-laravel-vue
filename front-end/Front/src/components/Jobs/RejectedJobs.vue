<template>
  <div class="container mt-5">
    <h1 class="text-center text-dark mb-4">Rejected Jobs</h1>
    <div class="card shadow-lg rounded-4">
      <div class="card-header bg-secondary text-white text-center">
        <h3 class="mb-0">Rejected Jobs Management</h3>
      </div>
      <div class="card-body">
        <div v-if="errorMessage" class="alert alert-danger text-center">{{ errorMessage }}</div>
        <div v-if="successMessage" class="alert alert-success text-center">{{ successMessage }}</div>

        <table class="table table-striped table-hover">
          <thead class="table-dark">
            <tr>
              <th>Title</th>
              <th>Location</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="job in rejectedJobs" :key="job.id">
              <td>{{ job.title }}</td>
              <td>{{ job.location }}</td>
              <td>
                <span class="badge bg-danger rounded-pill px-3 py-2">
                  {{ job.status }}
                </span>
              </td>
              <td>
                <router-link :to="{ name: 'jobs.show', params: { id: job.id } }" class="btn btn-info btn-sm me-1">
                  <i class="fas fa-eye"></i> View
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
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
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      rejectedJobs: [],
      pagination: {},
      errorMessage: '',
      successMessage: '',
    };
  },
  mounted() {
    this.getRejectedJobs();
  },
  methods: {
    async getRejectedJobs(page = 1) {
      try {
        const response = await axios.get(`/api/admin/jobs/rejected?page=${page}`);
        this.rejectedJobs = response.data.rejectedJobs.data;
        this.pagination = response.data.rejectedJobs;
        this.errorMessage = '';
      } catch (error) {
        this.errorMessage = 'Error fetching rejected jobs: ' + (error.response?.data?.message || error.message);
      }
    },
    async changePage(page) {
      if (page > 0 && page <= this.pagination.last_page) {
        await this.getRejectedJobs(page);
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