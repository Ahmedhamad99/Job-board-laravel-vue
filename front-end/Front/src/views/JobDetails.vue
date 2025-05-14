<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/axios';
import { useToast } from 'vue-toastification';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const job = ref(null);
const category = ref('');
const loading = ref(true);
const user = ref(null);
const isAllowedToEdit = ref(false);

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
};

const checkUserPermissions = async () => {
  try {
    // Check if user is logged in by looking for token
    const token = localStorage.getItem('token');
    if (!token) {
      isAllowedToEdit.value = false;
      return;
    }
    
    // Get current user details including role
    const response = await api.get('/user');
    user.value = response.data;
    
    // Check if user is an employer or admin
    if (user.value) {
      const role = user.value.role?.toLowerCase();
      isAllowedToEdit.value = role === 'employer' || role === 'admin';
    }
  } catch (error) {
    console.error('Error checking user permissions:', error);
    isAllowedToEdit.value = false;
  }
};

onMounted(async () => {
  try {
    // Load job details and check user permissions in parallel
    const jobId = route.params.id;
    
    const [jobResponse] = await Promise.all([
      api.get(`/jobs/${jobId}`),
      checkUserPermissions()
    ]);
    
    job.value = jobResponse.data.data.job;
    category.value = jobResponse.data.data.category;
  } catch (error) {
    console.error('Error fetching job details:', error);
    toast.error('Failed to load job details');
  } finally {
    loading.value = false;
  }
});

const navigateToEdit = () => {
  router.push(`/profile/jobs/edit/${job.value.id}`);
};

const goBack = () => {
  router.push('/profile/jobs');
};
</script>

<template>
  <div class="container py-5">
    <div class="d-flex align-items-center mb-4">
      <button @click="goBack" class="btn btn-outline-secondary me-2">
        <i class="bi bi-arrow-left"></i> Back to Jobs
      </button>
      <h2 class="mb-0">Job Details</h2>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="!job" class="text-center py-5">
      <h4 class="text-muted">Job not found</h4>
      <p>The job you're looking for does not exist or has been removed.</p>
      <button @click="goBack" class="btn btn-primary mt-3">
        <i class="bi bi-arrow-left"></i> Back to Jobs
      </button>
    </div>

    <div v-else class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">{{ job.title }}</h3>
        <span :class="{
          'badge bg-success': job.status === 'accepted',
          'badge bg-warning': job.status === 'pending',
          'badge bg-danger': job.status === 'rejected'
        }">
          {{ job.status || 'Unknown' }}
        </span>
      </div>

      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-6">
            <h5>Job Information</h5>
            <table class="table">
              <tbody>
                <tr>
                  <th scope="row" class="w-25">Location</th>
                  <td>{{ job.location || 'Not specified' }}</td>
                </tr>
                <tr>
                  <th scope="row">Category</th>
                  <td>{{ category || 'Uncategorized' }}</td>
                </tr>
                <tr>
                  <th scope="row">Salary Range</th>
                  <td>
                    <span v-if="job.salary_min || job.salary_max">
                      {{ job.salary_min ? `$${job.salary_min}` : 'Not specified' }}
                      {{ job.salary_max ? ` - $${job.salary_max}` : '' }}
                    </span>
                    <span v-else>Not specified</span>
                  </td>
                </tr>
                <tr>
                  <th scope="row">Posted Date</th>
                  <td>{{ formatDate(job.created_at) }}</td>
                </tr>
                <tr>
                  <th scope="row">Last Updated</th>
                  <td>{{ formatDate(job.updated_at) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <h5>Skills Required</h5>
            <div v-if="job.skills && job.skills.length > 0" class="mb-3">
              <span 
                v-for="(skill, index) in typeof job.skills === 'string' ? job.skills.split(',') : job.skills"
                :key="index"
                class="badge bg-info text-dark me-2 mb-2 p-2"
              >
                {{ skill.trim() }}
              </span>
            </div>
            <p v-else class="text-muted">No specific skills mentioned</p>
          </div>
        </div>

        <h5>Job Description</h5>
        <div class="mb-4">
          <p style="white-space: pre-line;">{{ job.description }}</p>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button 
            v-if="isAllowedToEdit"
            @click="navigateToEdit" 
            class="btn btn-warning"
          >
            <i class="bi bi-pencil"></i> Edit Job
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.badge {
  text-transform: capitalize;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.badge.bg-info {
  font-weight: normal;
  font-size: 0.9rem;
}
</style> 