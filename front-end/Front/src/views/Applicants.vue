<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { Modal } from 'bootstrap';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const toast = useToast();

const job = ref(null);
const applicants = ref([]);
const loading = ref(true);
const error = ref(null);

// For status update modal
const selectedApplication = ref(null);
const statusLoading = ref(false);
let statusModal = null;

// Get job ID from route params
const jobId = computed(() => route.params.jobId);

// Mock data for applicants
const mockApplicants = [
  {
    id: 1,
    job_id: 2,
    candidate: { id: 1, name: 'John Doe', email: 'john@example.com' },
    resume: null,
    status: 'pending',
    created_at: '2023-07-15T10:30:00.000Z'
  },
  {
    id: 2,
    job_id: 2,
    candidate: { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
    resume: null,
    status: 'accepted',
    created_at: '2023-07-14T09:15:00.000Z'
  },
  {
    id: 3,
    job_id: 2,
    candidate: { id: 3, name: 'Mike Johnson', email: 'mike@example.com' },
    resume: null,
    status: 'rejected',
    created_at: '2023-07-13T14:45:00.000Z'
  }
];

// Mock job data
const mockJobs = {
  2: { id: 2, title: 'Software Developer', company: 'Tech Solutions' }
};

const fetchApplicants = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    // Try to get the real job data first
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/jobs`);
      if (response.data && (response.data.data || response.data.jobs)) {
        const jobs = response.data.data || response.data.jobs;
        const foundJob = jobs.find(j => j.id == jobId.value);
        if (foundJob) {
          job.value = foundJob;
        } else {
          job.value = mockJobs[jobId.value] || { title: 'Job #' + jobId.value };
        }
      } else {
        job.value = mockJobs[jobId.value] || { title: 'Job #' + jobId.value };
      }
    } catch (err) {
      console.log('Could not fetch job data, using mock data');
      job.value = mockJobs[jobId.value] || { title: 'Job #' + jobId.value };
    }
    
    // Use mock applicants data
    // In a real app, this would come from the API
    applicants.value = mockApplicants.filter(app => app.job_id == jobId.value);
    
    // Simulate a short delay to mimic API call
    await new Promise(resolve => setTimeout(resolve, 500));
    
    toast.success('Applicants loaded successfully');
  } catch (err) {
    console.error('Error loading applicants:', err);
    error.value = 'Failed to load applicants. Please try again.';
    toast.error(error.value);
  } finally {
    loading.value = false;
  }
};

const openStatusModal = (application) => {
  selectedApplication.value = application;
  statusModal.show();
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getStatusBadgeClass = (status) => {
  switch (status?.toLowerCase()) {
    case 'accepted':
      return 'bg-success';
    case 'rejected':
      return 'bg-danger';
    case 'in review':
      return 'bg-warning';
    default:
      return 'bg-secondary';
  }
};

const updateStatus = async (status) => {
  if (!selectedApplication.value) return;
  
  statusLoading.value = true;
  
  try {
    // Simulate API call with a delay
    await new Promise(resolve => setTimeout(resolve, 800));
    
    // Update the status in the local array
    const index = applicants.value.findIndex(app => app.id === selectedApplication.value.id);
    if (index !== -1) {
      applicants.value[index].status = status;
    }
    
    toast.success(`Application ${status}`);
    statusModal.hide();
  } catch (err) {
    console.error('Error updating status:', err);
    toast.error('Failed to update status');
  } finally {
    statusLoading.value = false;
  }
};

const downloadResume = (resumePath) => {
  // In a mock implementation, just show a message
  toast.info('Resume download is not available in demo mode');
};

const goBack = () => {
  router.go(-1);
};

onMounted(() => {
  fetchApplicants();
  
  // Initialize modal
  statusModal = new Modal(document.getElementById('statusModal'));
});
</script>

<template>
  <div class="container py-5">
    <div class="d-flex align-items-center mb-4">
      <button @click="goBack" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i> Back
      </button>
      <h2 class="mb-0">
        Job Applicants
        <span v-if="job" class="text-muted fs-5">for {{ job.title }}</span>
      </h2>
    </div>
    
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-3 text-muted">Loading applicants...</p>
    </div>
    
    <!-- Error State -->
    <div v-else-if="error" class="alert alert-danger" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      {{ error }}
    </div>
    
    <!-- Empty State -->
    <div v-else-if="applicants.length === 0" class="text-center py-5">
      <div class="empty-state p-5 bg-light rounded">
        <i class="bi bi-people display-1 text-muted"></i>
        <h3 class="mt-3 text-muted">No Applicants Yet</h3>
        <p class="text-muted">This job hasn't received any applications yet.</p>
      </div>
    </div>
    
    <!-- Applicants List -->
    <div v-else>
      <div class="card mb-4">
        <div class="card-header bg-light">
          <div class="row fw-bold">
            <div class="col-md-3">Applicant</div>
            <div class="col-md-2">Applied On</div>
            <div class="col-md-2">Status</div>
            <div class="col-md-2">Resume</div>
            <div class="col-md-3">Actions</div>
          </div>
        </div>
        <div class="list-group list-group-flush">
          <div 
            v-for="application in applicants" 
            :key="application.id"
            class="list-group-item"
          >
            <div class="row align-items-center">
              <!-- Applicant Info -->
              <div class="col-md-3">
                <h5 class="mb-1">{{ application.candidate?.name || 'Unknown' }}</h5>
                <p class="mb-0 text-muted small">{{ application.candidate?.email }}</p>
              </div>
              
              <!-- Applied Date -->
              <div class="col-md-2">
                {{ formatDate(application.created_at) }}
              </div>
              
              <!-- Status -->
              <div class="col-md-2">
                <span 
                  class="badge"
                  :class="getStatusBadgeClass(application.status)"
                >
                  {{ application.status || 'Pending' }}
                </span>
              </div>
              
              <!-- Resume -->
              <div class="col-md-2">
                <button 
                  v-if="application.resume" 
                  @click="downloadResume(application.resume)"
                  class="btn btn-sm btn-outline-primary"
                >
                  <i class="bi bi-download me-1"></i> Resume
                </button>
                <span v-else class="text-muted">Not provided</span>
              </div>
              
              <!-- Actions -->
              <div class="col-md-3">
                <div class="btn-group">
                  <button 
                    @click="openStatusModal(application)"
                    class="btn btn-sm btn-outline-secondary"
                  >
                    <i class="bi bi-pencil-square me-1"></i> Update Status
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Application Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p v-if="selectedApplication">
              Update status for <strong>{{ selectedApplication.candidate?.name }}</strong>'s application.
            </p>
            
            <div class="d-grid gap-2">
              <button 
                @click="updateStatus('accepted')" 
                class="btn btn-success"
                :disabled="statusLoading"
              >
                <i class="bi bi-check-circle-fill me-2"></i> Accept
              </button>
              
              <button 
                @click="updateStatus('rejected')" 
                class="btn btn-danger"
                :disabled="statusLoading"
              >
                <i class="bi bi-x-circle-fill me-2"></i> Reject
              </button>
              
              <button 
                @click="updateStatus('pending')" 
                class="btn btn-warning"
                :disabled="statusLoading"
              >
                <i class="bi bi-clock-fill me-2"></i> Set as Pending
              </button>
            </div>
            
            <div v-if="statusLoading" class="text-center mt-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.badge {
  font-size: 0.85rem;
  padding: 0.5rem 0.75rem;
  font-weight: 500;
  text-transform: capitalize;
}

.btn {
  display: inline-flex;
  align-items: center;
}

.empty-state {
  padding: 3rem;
  border-radius: 1rem;
}

.list-group-item {
  transition: background-color 0.2s;
}

.list-group-item:hover {
  background-color: #f8f9fa;
}
</style> 