<template>
  <div>
    <h1 class="mb-4 text-center">All Jobs</h1>
    <div class="mb-3">
      <input
        v-model="searchQuery"
        @input="searchJobs"
        type="text"
        class="form-control form-control-lg"
        placeholder="Search by title"
        aria-label="Search Jobs"
      >
    </div>
    <router-link to="/jobs/create" class="btn btn-primary mb-3" :disabled="!isDataLoaded">Create Job</router-link>
    <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ errorMessage }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
      {{ successMessage }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div v-if="!isDataLoaded" class="alert alert-info">Loading employers and categories...</div>

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
        <tr v-for="job in jobs" :key="job.id">
          <td>{{ job.title }}</td>
          <td>{{ job.location }}</td>
          <td>{{ job.status }}</td>
          <td>
            <router-link :to="{ name: 'jobs.show', params: { id: job.id } }" class="btn btn-success btn-sm me-1">View</router-link>
            <router-link :to="{ name: 'jobs.edit', params: { id: job.id } }" class="btn btn-warning btn-sm me-1">Edit</router-link>
            <button class="btn btn-danger btn-sm" @click="deleteJob(job.id)">Delete</button>
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
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      jobs: [],
      pagination: {},
      searchQuery: '',
      errorMessage: '',
      successMessage: '',
      isDataLoaded: false,
      employers: [],
      categories: []
    };
  },
  computed: {
    pageNumbers() {
      const maxPagesToShow = 10;
      const currentPage = this.pagination.current_page || 1;
      const totalPages = this.pagination.last_page || 1;

      // Calculate the start and end page numbers
      let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
      let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

      // Adjust startPage if endPage is at the maximum
      if (endPage - startPage + 1 < maxPagesToShow) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
      }

      // Generate array of page numbers
      const pages = [];
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
      }
      return pages;
    }
  },
  async mounted() {
    try {
      await Promise.all([this.getJobs(), this.getEmployers(), this.getCategories()]);
      this.isDataLoaded = true;
    } catch (error) {
      this.errorMessage = 'Error loading initial data: ' + (error.response?.data?.message || error.message);
    }
  },
  methods: {
    async getJobs(page = 1) {
      try {
        const response = await axios.get(`/api/admin/jobs?page=${page}`);
        this.jobs = response.data.jobs.data;
        this.pagination = response.data.jobs;
        this.errorMessage = '';
      } catch (error) {
        this.errorMessage = 'Error fetching jobs: ' + (error.response?.data?.message || error.message);
      }
    },
    async getEmployers() {
      try {
        const response = await axios.get('/api/admin/employers');
        this.employers = Array.isArray(response.data.employers?.data)
          ? response.data.employers.data.filter(employer => employer && employer.id && employer.name)
          : [];
        if (!this.employers.length) {
          this.errorMessage = 'No valid employers found.';
        }
      } catch (error) {
        this.errorMessage = 'Error fetching employers: ' + (error.response?.data?.message || error.message);
      }
    },
    async getCategories() {
      try {
        const response = await axios.get('/api/admin/categories');
        const categoriesData = Array.isArray(response.data.categories.data) ? response.data.categories.data : [];
        const uniqueCategories = [];
        const namesSet = new Set();
        categoriesData.forEach(category => {
          if (!namesSet.has(category.name)) {
            namesSet.add(category.name);
            uniqueCategories.push(category);
          }
        });
        this.categories = uniqueCategories;
        if (!this.categories.length) {
          this.errorMessage = 'No valid categories found.';
        }
      } catch (error) {
        this.errorMessage = 'Error fetching categories: ' + (error.response?.data?.message || error.message);
      }
    },
    async searchJobs() {
      if (this.searchQuery) {
        try {
          const response = await axios.get(`/api/admin/jobs/search?query=${this.searchQuery}`);
          this.jobs = response.data.jobs.data;
          this.pagination = response.data.jobs;
          this.errorMessage = '';
        } catch (error) {
          this.errorMessage = 'Error searching jobs: ' + (error.response?.data?.message || error.message);
        }
      } else {
        await this.getJobs();
      }
    },
    async deleteJob(id) {
      if (confirm('Are you sure you want to delete this job?')) {
        try {
          const response = await axios.delete(`/api/admin/jobs/delete/${id}`);
          this.successMessage = response.data.message;
          this.errorMessage = '';
          await this.getJobs();
        } catch (error) {
          this.errorMessage = 'Error deleting job: ' + (error.response?.data?.message || error.message);
        }
      }
    },
    async changePage(page) {
      if (page > 0 && page <= this.pagination.last_page) {
        await this.getJobs(page);
      }
    }
  }
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

.page-link:hover,
.page-item.active .page-link {
  background-color: #343a40;
  color: #fff;
}

h1 {
  font-size: 1.75rem;
  color: #343a40;
}
</style>