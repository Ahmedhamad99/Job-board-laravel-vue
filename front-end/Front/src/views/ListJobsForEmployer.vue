<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import api from '@/axios';
import JobCard from '@/components/JobCard.vue';

const jobs = ref([]);
const loading = ref(true);
const toast = useToast();

const fetchJobs = async () => {
  try {
    const response = await api.get('/jobs');
    console.log('API Response:', response.data);
    
    // Update to match the actual API response structure
    if (response.data && response.data.data) {
      jobs.value = response.data.data;
    } else {
      jobs.value = [];
      console.error('Unexpected API response structure:', response.data);
      toast.warning('Received unexpected data format from server');
    }
  } catch (error) {
    toast.error('Failed to fetch jobs');
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const handleJobDeleted = (jobId) => {
  jobs.value = jobs.value.filter(job => job.id !== jobId);
};

onMounted(fetchJobs);
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>My Posted Jobs</h2>
      <router-link to="/profile/jobs/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Post New Job
      </router-link>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="!jobs || jobs.length === 0" class="text-center py-5">
      <h4 class="text-muted">No jobs posted yet</h4>
      <p class="text-muted">Start by posting your first job!</p>
    </div>

    <div v-else>
      <JobCard
        v-for="job in jobs"
        :key="job.id"
        :job="job"
        @job-deleted="handleJobDeleted"
      />
    </div>
  </div>
</template>

<style scoped>
.btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.bi {
  font-size: 1rem;
}
</style>
