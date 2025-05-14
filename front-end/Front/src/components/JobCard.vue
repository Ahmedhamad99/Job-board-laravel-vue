<script setup>
import { useToast } from 'vue-toastification';
import api from '@/axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  job: {
    type: Object,
    required: true,
    validator: (value) => {
      return value && typeof value === 'object';
    }
  }
});

const emit = defineEmits(['jobDeleted']);

const toast = useToast();
const showDeleteModal = ref(false);
const isDeleting = ref(false);
const router = useRouter();

const openDeleteModal = () => {
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
};

const confirmDelete = async () => {
  isDeleting.value = true;
  try {
    await api.delete(`/jobs/${props.job.id}`);
    toast.success('Job deleted successfully');
    emit('jobDeleted', props.job.id);
    closeDeleteModal();
  } catch (error) {
    toast.error(error.response?.data?.message || 'Failed to delete job');
  } finally {
    isDeleting.value = false;
  }
};

const truncateText = (text, maxLength = 150) => {
  if (!text) return '';
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
};

const viewApplicants = () => {
  router.push(`/jobs/${props.job.id}/applicants`);
};
</script>

<template>
  <div class="card mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <h5 class="card-title">{{ job?.title || 'Untitled Job' }}</h5>
        <span :class="{
          'badge bg-success': job?.status === 'accepted',
          'badge bg-warning': job?.status === 'pending',
          'badge bg-danger': job?.status === 'rejected',
          'badge bg-secondary': !job?.status
        }">
          {{ job?.status || 'Unknown' }}
        </span>
      </div>
      
      <p class="card-text text-muted mb-2">
        <i class="bi bi-geo-alt"></i> {{ job?.location || 'Location not specified' }}
      </p>
      
      <p class="card-text text-muted mb-2">
        <i class="bi bi-currency-dollar"></i> 
        {{ job?.salary_min ? `$${job.salary_min}` : 'Not specified' }} 
        {{ job?.salary_max ? `- $${job.salary_max}` : '' }}
      </p>

      <p class="card-text">
        {{ truncateText(job?.description) }}
      </p>

      <div class="d-flex flex-wrap gap-2">
        <router-link 
          :to="`/profile/jobs/${job?.id}`" 
          class="btn btn-primary"
        >
          <i class="bi bi-eye"></i> Details
        </router-link>
        
        <router-link 
          :to="`/profile/jobs/edit/${job?.id}`" 
          class="btn btn-warning"
        >
          <i class="bi bi-pencil"></i> Edit
        </router-link>
        
        <button 
          @click="openDeleteModal" 
          class="btn btn-danger"
        >
          <i class="bi bi-trash"></i> Delete
        </button>
        
        <!-- Applicants button - only visible for accepted jobs -->
        <router-link 
          v-if="job?.status === 'accepted'"
          :to="`/jobs/${job?.id}/applicants`" 
          class="btn btn-success"
        >
          <i class="bi bi-people-fill"></i> Applicants
        </router-link>
      </div>
    </div>
  </div>
  
  <!-- Delete Confirmation Modal -->
  <div class="modal fade" :class="{ 'show': showDeleteModal }" tabindex="-1" 
       :style="{ display: showDeleteModal ? 'block' : 'none' }"
       aria-modal="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close" @click="closeDeleteModal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete the job <strong>{{ job?.title }}</strong>?</p>
          <p class="text-danger">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeDeleteModal">Cancel</button>
          <button type="button" class="btn btn-danger" @click="confirmDelete" :disabled="isDeleting">
            <span v-if="isDeleting" class="spinner-border spinner-border-sm me-2" role="status"></span>
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal-backdrop fade show" v-if="showDeleteModal"></div>
</template>

<style scoped>
.card {
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
  text-transform: capitalize;
}

.btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.bi {
  font-size: 1rem;
}

/* Modal Styles */
body.modal-open {
  overflow: hidden;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1055;
  width: 100%;
  height: 100%;
  overflow-x: hidden;
  overflow-y: auto;
  outline: 0;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050;
  width: 100vw;
  height: 100vh;
  background-color: #000;
  opacity: 0.5;
}

.modal.fade .modal-dialog {
  transition: transform 0.3s ease-out;
  transform: translate(0, -50px);
}

.modal.show .modal-dialog {
  transform: none;
}
</style> 