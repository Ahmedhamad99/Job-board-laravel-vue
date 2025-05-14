<template>
  <div class="container mt-4">
    <h1 class="text-center text-dark mb-4">Pending Jobs</h1>
    <div class="card shadow-lg rounded-4">
      <div class="card-header bg-secondary text-white text-center">
        <h3 class="mb-0">Manage Pending Jobs</h3>
      </div>
      <div class="card-body">
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
            <tr v-for="job in pendingJobs" :key="job.id">
              <td>{{ job.title }}</td>
              <td>{{ job.location }}</td>
              <td>
                <span :class="getStatusClass(job.status)" class="badge rounded-pill px-3 py-2">
                  {{ job.status }}
                </span>
              </td>
              <td>
                <button class="btn btn-success btn-sm me-2" @click="approveJob(job.id)">
                  <i class="fas fa-check-circle"></i> Accept
                </button>
                <button class="btn btn-danger btn-sm" @click="rejectJob(job.id)">
                  <i class="fas fa-times-circle"></i> Reject
                </button>
              </td>
            </tr>
          </tbody>
        </table>

     <nav aria-label="Page navigation" class="mt-3">
  <ul class="pagination justify-content-center">
    <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
      <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">
         Previous
      </a>
    </li>
    
    <li v-for="page in pagination.last_page" :key="page" class="page-item" :class="{ active: page === pagination.current_page }">
      <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
    </li>
    
    <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
      <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">
        Next 
      </a>
    </li>
  </ul>
</nav>

      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      pendingJobs: [],
      pagination: {}
    }
  },
  mounted() {
    this.getPendingJobs()
  },
  methods: {
    getPendingJobs(page = 1) {
      axios.get(`/api/admin/jobs/pending?page=${page}`)
        .then(response => {
          this.pendingJobs = response.data.pendingJobs.data
          this.pagination = response.data.pendingJobs
        })
        .catch(error => console.error(error))
    },
    approveJob(id) {
      axios.patch(`/api/admin/jobs/accepted/${id}`)
        .then(response => {
          alert(response.data.message)
          this.getPendingJobs()
        })
        .catch(error => console.error(error))
    },
    rejectJob(id) {
      axios.patch(`/api/admin/jobs/rejected/${id}`)
        .then(response => {
          alert(response.data.message)
          this.getPendingJobs()
        })
        .catch(error => console.error(error))
    },
    changePage(page) {
      if (page > 0 && page <= this.pagination.last_page) {
        this.getPendingJobs(page)
      }
    },
    getStatusClass(status) {
      switch (status) {
        case 'accepted':
          return 'bg-success';
        case 'pending':
          return 'bg-warning';
        case 'rejected':
          return 'bg-danger';
        default:
          return 'bg-secondary';
      }
    }
  }
}
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
