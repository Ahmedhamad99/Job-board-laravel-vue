<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/axios';
import { useToast } from 'vue-toastification';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const loading = ref(false);
const categories = ref([]);

const isEditMode = computed(() => route.params.id !== undefined);

const job = ref({
  title: '',
  description: '',
  category_id: '',
  skills: '',
  location: '',
  salary_min: null,
  salary_max: null,
});

const errors = ref({});

onMounted(async () => {
  try {
    // Fetch categories
    const response = await api.get('/jobs/create');
    categories.value = response.data.data.categories;

    // If in edit mode, fetch job details
    if (isEditMode.value) {
      const jobResponse = await api.get(`/jobs/${route.params.id}/edit`);
      console.log(jobResponse.data.data);
      const jobData = jobResponse.data.data.job;
      job.value = {
        ...jobData,
        // Handle skills whether it's a string, array, or null
        skills: Array.isArray(jobData.skills) 
          ? jobData.skills.join(', ') 
          : jobData.skills || ''
      };
    }
  } catch (error) {
    toast.error('Failed to load data');
    console.error(error);
  }
});

const validateForm = () => {
  errors.value = {};
  let isValid = true;

  if (!job.value.title) {
    errors.value.title = 'Title is required';
    isValid = false;
  }
  if (!job.value.description) {
    errors.value.description = 'Description is required';
    isValid = false;
  }
  if (!job.value.category_id) {
    errors.value.category_id = 'Category is required';
    isValid = false;
  }
  if (!job.value.location) {
    errors.value.location = 'Location is required';
    isValid = false;
  }

  return isValid;
};

const handleSubmit = async () => {
  if (!validateForm()) {
    toast.error('Please fill in all required fields');
    return;
  }

  loading.value = true;
  errors.value = {}; // Clear previous errors
  try {
    // Create a regular JSON object for the payload
    const jobData = {
      title: job.value.title,
      description: job.value.description,
      location: job.value.location,
      category_id: job.value.category_id,
      employer_id: 1,
      skills: job.value.skills || '',
    };
    
    // Handle salary fields with a completely different approach
    // Try sending values as strings
    if (job.value.salary_min && job.value.salary_min !== '') {
      // Send as a string with a regular decimal format
      const minValue = parseFloat(job.value.salary_min);
      jobData.salary_min = `${minValue}`;
    }
    
    if (job.value.salary_max && job.value.salary_max !== '') {
      // Send as a string with a regular decimal format
      const maxValue = parseFloat(job.value.salary_max);
      jobData.salary_max = `${maxValue}`;
    }
    
    console.log('Request payload:', jobData);
    
    if (isEditMode.value) {
      await api.put(`/jobs/${route.params.id}`, jobData);
      toast.success('Job updated successfully');
    } else {
      await api.post('/jobs', jobData);
      toast.success('Job created successfully');
    }
    
    router.push('/profile/jobs');
  } catch (error) {
    console.error('API Error:', error.response?.data);
    
    if (error.response?.data?.errors) {
      const apiErrors = error.response.data.errors;
      errors.value = apiErrors;
      
      const firstErrorField = Object.keys(apiErrors)[0];
      const firstErrorMessage = apiErrors[firstErrorField][0];
      toast.error(`${firstErrorField}: ${firstErrorMessage}`);
      
      console.log('Validation errors:', apiErrors);
    } else {
      const message = error.response?.data?.message || 'An error occurred';
      toast.error(message);
    }
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="container py-5">
    <h2 class="mb-4">{{ isEditMode ? 'Edit Job' : 'Add New Job' }}</h2>
    
    <form @submit.prevent="handleSubmit" class="needs-validation">
      <div class="form-outline mb-4">
        <label class="form-label">Job Title *</label>
        <input 
          type="text" 
          class="form-control" 
          :class="{ 'is-invalid': errors.title }"
          v-model="job.title" 
          placeholder="Enter job title"
        />
        <div class="invalid-feedback" v-if="errors.title">{{ Array.isArray(errors.title) ? errors.title[0] : errors.title }}</div>
      </div>
      
      <div class="form-outline mb-4">
        <label class="form-label">Description *</label>
        <textarea 
          class="form-control" 
          :class="{ 'is-invalid': errors.description }"
          v-model="job.description" 
          rows="4"
          placeholder="Enter job description"
        ></textarea>
        <div class="invalid-feedback" v-if="errors.description">{{ Array.isArray(errors.description) ? errors.description[0] : errors.description }}</div>
      </div>

      <div class="form-outline mb-4">
        <label class="form-label">Skills</label>
        <input 
          type="text" 
          class="form-control" 
          :class="{ 'is-invalid': errors.skills }"
          v-model="job.skills" 
          placeholder="Enter required skills (comma-separated)"
        />
        <div class="invalid-feedback" v-if="errors.skills">{{ Array.isArray(errors.skills) ? errors.skills[0] : errors.skills }}</div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <div class="form-outline">
            <label class="form-label">Minimum Salary</label>
            <input 
              type="number" 
              class="form-control" 
              :class="{ 'is-invalid': errors.salary_min }"
              v-model.number="job.salary_min" 
              placeholder="Enter minimum salary"
              step="0.01"
              min="0"
            />
            <div class="invalid-feedback" v-if="errors.salary_min">{{ Array.isArray(errors.salary_min) ? errors.salary_min[0] : errors.salary_min }}</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-outline">
            <label class="form-label">Maximum Salary</label>
            <input 
              type="number" 
              class="form-control" 
              :class="{ 'is-invalid': errors.salary_max }"
              v-model.number="job.salary_max" 
              placeholder="Enter maximum salary"
              step="0.01"
              min="0"
            />
            <div class="invalid-feedback" v-if="errors.salary_max">{{ Array.isArray(errors.salary_max) ? errors.salary_max[0] : errors.salary_max }}</div>
          </div>
        </div>
      </div>

      <div class="form-outline mb-4">
        <label class="form-label">Location *</label>
        <input 
          type="text" 
          class="form-control" 
          :class="{ 'is-invalid': errors.location }"
          v-model="job.location" 
          placeholder="Enter job location"
        />
        <div class="invalid-feedback" v-if="errors.location">{{ Array.isArray(errors.location) ? errors.location[0] : errors.location }}</div>
      </div>

      <div class="form-outline mb-4">
        <label class="form-label">Category *</label>
        <select 
          class="form-select" 
          :class="{ 'is-invalid': errors.category_id }"
          v-model="job.category_id"
        >
          <option value="">Select a category</option>
          <option 
            v-for="category in categories" 
            :key="category.id" 
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
        <div class="invalid-feedback" v-if="errors.category_id">{{ Array.isArray(errors.category_id) ? errors.category_id[0] : errors.category_id }}</div>
      </div>

      <button 
        type="submit" 
        class="btn btn-primary btn-block mb-4" 
        :disabled="loading"
      >
        <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
        {{ isEditMode ? 'Update Job' : 'Add Job' }}
      </button>
    </form>
  </div>
</template>

<style scoped>
.container {
  max-width: 800px;
}
</style>