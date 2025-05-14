<template>
  <section class="applications-section py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold text-gradient">My Job Applications</h2>
        <p class="text-muted">Track the status of your job applications</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Loading your applications...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="alert alert-danger text-center" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ error }}
      </div>

      <!-- Empty State -->
      <div v-else-if="applications.length === 0" class="text-center py-5">
        <div class="empty-state">
          <i class="bi bi-clipboard-x display-1 text-muted"></i>
          <h3 class="mt-3 text-muted">No Applications Yet</h3>
          <p class="text-muted mb-4">You haven't applied to any jobs yet.</p>
          <router-link to="/home" class="btn btn-primary">
            <i class="bi bi-search me-2"></i> Browse Jobs
          </router-link>
        </div>
      </div>

      <!-- Applications List -->
      <div v-else class="row">
        <div class="col-12">
          <div class="card shadow-sm mb-4" v-for="app in applications" :key="app.id">
            <div class="card-body p-4">
              <div class="row">
                <div class="col-md-8">
                  <h5 class="card-title fw-bold">{{ app.job.title }}</h5>
                  <p class="card-text text-muted mb-2">{{ app.job.description }}</p>
                  
                  <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light text-dark border">
                      <i class="bi bi-geo-alt-fill me-1"></i> 
                      {{ app.job.location }}
                    </span>
                    <span class="badge bg-light text-dark border">
                      <i class="bi bi-calendar-date me-1"></i> 
                      Applied on {{ formatDate(app.created_at) }}
                    </span>
                  </div>
                </div>
                
                <div class="col-md-4 d-flex flex-column justify-content-center align-items-end">
                  <span 
                    class="status-badge mb-3" 
                    :class="getStatusClass(app.status)"
                  >
                    {{ app.status || 'Pending' }}
                  </span>
                  
                  <div class="d-flex gap-2">
                    <button 
                      class="btn btn-outline-primary btn-sm"
                      @click="viewJobDetails(app.job.id)"
                    >
                      <i class="bi bi-eye-fill me-1"></i> View Job
                    </button>
                    <button 
                      class="btn btn-outline-danger btn-sm"
                      @click="cancelApplication(app.id)"
                      :disabled="app.status?.toLowerCase() === 'accepted'"
                    >
                      <i class="bi bi-x-circle-fill me-1"></i> Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Confirmation Modal -->
      <div class="modal fade" id="cancelModal" tabindex="-1" ref="cancelModal">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cancel Application</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to cancel this application? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button 
                type="button" 
                class="btn btn-danger" 
                @click="confirmCancel"
                :disabled="cancelLoading"
              >
                <span v-if="cancelLoading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                Confirm Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import api from '@/axios';
import { Modal } from 'bootstrap';

export default {
  setup() {
    const applications = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const router = useRouter();
    const toast = useToast();
    const cancelModal = ref(null);
    const selectedApplicationId = ref(null);
    const cancelLoading = ref(false);
    let modalInstance = null;

    const fetchApplications = async () => {
      loading.value = true;
      error.value = null;
      
      try {
        // First try the authenticated endpoint
        const response = await api.get('/applications');
        
        if (response.data && response.data.data) {
          applications.value = response.data.data;
        } else {
          applications.value = [];
          console.warn('Unexpected API response structure:', response.data);
          toast.warning('Received unexpected data format from server');
        }
      } catch (err) {
        console.error('Failed to fetch applications:', err);
        
        // If the main endpoint fails, try the test endpoint
        try {
          console.log('Attempting to use test endpoint...');
          const testResponse = await api.get('/test/applications');
          
          if (testResponse.data && testResponse.data.data) {
            applications.value = testResponse.data.data;
            toast.info('Using test endpoint for applications');
          } else {
            error.value = 'Failed to load your applications. Please try again later.';
            toast.error('Failed to load your applications');
          }
        } catch (testErr) {
          console.error('Test endpoint also failed:', testErr);
          error.value = 'Failed to load your applications. Server may be unavailable.';
          toast.error('Server connection issue. Please try again later.');
        }
      } finally {
        loading.value = false;
      }
    };

    const viewJobDetails = (jobId) => {
      router.push({ name: 'JobDetails', params: { id: jobId } });
    };

    const cancelApplication = (applicationId) => {
      selectedApplicationId.value = applicationId;
      modalInstance.show();
    };

    const confirmCancel = async () => {
      if (!selectedApplicationId.value) {
        return;
      }
      
      cancelLoading.value = true;
      
      try {
        const response = await api.delete(`/applications/${selectedApplicationId.value}`);
        
        if (response.data && response.data.success) {
          // Remove the application from the list
          applications.value = applications.value.filter(app => app.id !== selectedApplicationId.value);
          toast.success('Application cancelled successfully');
          modalInstance.hide();
        } else {
          toast.error('Failed to cancel application');
        }
      } catch (err) {
        console.error('Error cancelling application:', err);
        toast.error('Failed to cancel application. Please try again.');
      } finally {
        cancelLoading.value = false;
      }
    };

    const formatDate = (dateString) => {
      if (!dateString) return 'N/A';
      
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const getStatusClass = (status) => {
      switch (status?.toLowerCase()) {
        case 'accepted':
          return 'status-success';
        case 'rejected':
          return 'status-danger';
        case 'in review':
          return 'status-warning';
        default:
          return 'status-pending';
      }
    };

    onMounted(() => {
      fetchApplications();
      
      // Initialize the Bootstrap modal
      modalInstance = new Modal(document.getElementById('cancelModal'));
    });

    return {
      applications,
      loading,
      error,
      cancelModal,
      cancelLoading,
      viewJobDetails,
      cancelApplication,
      confirmCancel,
      formatDate,
      getStatusClass
    };
  }
};
</script>

<style scoped>
.text-gradient {
  background: linear-gradient(135deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.empty-state {
  padding: 3rem;
  border-radius: 1rem;
  background-color: #f8f9fa;
}

.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 2rem;
  font-weight: 600;
  font-size: 0.85rem;
}

.status-success {
  background-color: #d4edda;
  color: #0f5132;
}

.status-danger {
  background-color: #f8d7da;
  color: #842029;
}

.status-warning {
  background-color: #fff3cd;
  color: #664d03;
}

.status-pending {
  background-color: #e2e3e5;
  color: #41464b;
}
</style> 