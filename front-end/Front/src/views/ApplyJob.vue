<template>
  <section class="apply-section py-5 bg-light min-vh-100">
    <div class="container">
      <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-gradient">Apply for the Job: {{ job.title }}</h1>
        <p class="text-muted">Submit your application by filling out the form below.</p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-10">
          <form @submit.prevent="submitApplication" class="p-5 rounded-5 shadow bg-white">
            <div class="row g-4">
              <!-- Left Side -->
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="full_name" class="form-label fw-semibold">Full Name</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input
                      type="text"
                      id="full_name"
                      v-model="form.full_name"
                      required
                      class="form-control"
                      placeholder="Your full name"
                    />
                  </div>
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label fw-semibold">Email</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input
                      type="email"
                      id="email"
                      v-model="form.email"
                      required
                      class="form-control"
                      placeholder="you@example.com"
                    />
                  </div>
                </div>

                <div class="mb-3">
                  <label for="phone" class="form-label fw-semibold">Phone</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input
                      type="tel"
                      id="phone"
                      v-model="form.phone"
                      required
                      class="form-control"
                      placeholder="0123456789"
                    />
                  </div>
                </div>

                <div class="mb-3">
                  <label for="contact_info" class="form-label fw-semibold">Message</label>
                  <textarea
                    id="contact_info"
                    v-model="form.contact_info"
                    class="form-control"
                    placeholder="Cover letter, notes, or any extra information"
                    rows="4"
                  ></textarea>
                </div>
              </div>

              <!-- Right Side -->
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="resume" class="form-label fw-semibold">Upload Resume <small class="text-muted">(Max 2MB)</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-paperclip"></i></span>
                    <input
                      type="file"
                      id="resume"
                      @change="handleFileUpload"
                      accept=".pdf,.doc,.docx"
                      class="form-control"
                      :class="{ 'is-invalid': fileError }"
                    />
                  </div>
                  <div v-if="fileError" class="invalid-feedback d-block">
                    {{ fileError }}
                  </div>
                  <div v-if="selectedFileName" class="mt-2 text-muted small">
                    <i class="bi bi-file-earmark me-1"></i> {{ selectedFileName }} ({{ formatFileSize(selectedFileSize) }})
                  </div>
                </div>

                <div class="d-grid mt-5">
                  <button 
                    type="submit" 
                    class="btn gradient-btn text-white py-3 rounded-pill fw-bold"
                    :disabled="loading || fileError"
                  >
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                    <i v-else class="bi bi-send-fill me-2"></i>
                    {{ loading ? 'Submitting...' : 'Submit Application' }}
                  </button>
                </div>
              </div>
            </div>

            <div v-if="errorMessage" class="alert alert-danger mt-4 text-center">
              {{ errorMessage }}
              <div v-if="showLoginHelp" class="mt-3 p-3 border rounded bg-light">
                <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Authentication Required</h5>
                <p>You need to be logged in as a candidate to apply for jobs.</p>
                <router-link to="/" class="btn btn-primary btn-sm me-2">
                  <i class="bi bi-box-arrow-in-right me-1"></i>Login
                </router-link>
                <button @click="checkAuthStatus" class="btn btn-outline-secondary btn-sm">
                  <i class="bi bi-arrow-repeat me-1"></i>Check Login Status
                </button>
              </div>
              <div v-else-if="showTroubleshooting" class="mt-3">
                <h5 class="mb-3"><i class="bi bi-tools me-2"></i>Troubleshooting Options</h5>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                  <button @click="debugApi" class="btn btn-outline-secondary btn-sm" :disabled="debugLoading">
                    <span v-if="debugLoading" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <i v-else class="bi bi-bug-fill me-1"></i> Debug API
                  </button>
                  <button @click="debugStorageConfig" class="btn btn-outline-secondary btn-sm" :disabled="debugLoading">
                    <i class="bi bi-folder-check me-1"></i> Check Storage
                  </button>
                  <button @click="tryWithoutFile" class="btn btn-outline-info btn-sm" :disabled="loading">
                    <i class="bi bi-file-earmark-x me-1"></i> Try Without Resume
                  </button>
                  <button @click="tryDirectApplication" class="btn btn-outline-primary btn-sm" :disabled="loading">
                    <i class="bi bi-send-check me-1"></i> Direct API Test
                  </button>
                </div>
                <div class="mt-3 small text-muted">
                  <p>The error is likely due to a server-side file storage issue. Try applying without a resume or contact the administrator.</p>
                </div>
              </div>
            </div>
            
            <div v-if="successMessage" class="alert alert-success mt-4 text-center">
              {{ successMessage }}
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import api from '@/axios';
import { useToast } from 'vue-toastification';

export default {
  data() {
    return {
      job: {},
      form: {
        full_name: '',
        email: '',
        phone: '',
        contact_info: '',
        resume: null,
      },
      loading: false,
      errorMessage: null,
      successMessage: null,
      fileError: null,
      selectedFileName: '',
      selectedFileSize: 0,
      maxFileSize: 2 * 1024 * 1024, // 2MB in bytes
      showTroubleshooting: false,
      debugLoading: false,
      showLoginHelp: false,
    };
  },
  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0];
      this.fileError = null;
      
      if (!file) {
        this.form.resume = null;
        this.selectedFileName = '';
        this.selectedFileSize = 0;
        return;
      }
      
      // Check file size
      if (file.size > this.maxFileSize) {
        this.fileError = `File is too large. Maximum size is ${this.formatFileSize(this.maxFileSize)}.`;
        this.form.resume = null;
        this.selectedFileName = '';
        this.selectedFileSize = 0;
        return;
      }
      
      this.form.resume = file;
      this.selectedFileName = file.name;
      this.selectedFileSize = file.size;
    },
    
    formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },
    
    submitApplication() {
      if (this.loading) return;
      
      this.loading = true;
      this.errorMessage = null;
      this.successMessage = null;
      
      // Try the primary submission method
      this.trySubmitApplication(1);
    },
    
    trySubmitApplication(attempt) {
      const jobId = this.$route.params.jobId;
      let formData = new FormData();
      
      // Different formats for different attempts
      if (attempt === 1) {
        // First attempt: Send all fields separately
        formData.append('job_id', jobId);
        const userId = localStorage.getItem('user_id') || '1';
        formData.append('user_id', userId);
        formData.append('full_name', this.form.full_name);
        formData.append('email', this.form.email);
        formData.append('phone', this.form.phone);
        formData.append('message', this.form.contact_info);

      const contactDetails = `
        Full Name: ${this.form.full_name}
        Email: ${this.form.email}
        Phone: ${this.form.phone}
        Message: ${this.form.contact_info}
      `.trim();
        formData.append('contact_info', contactDetails);
      } 
      else if (attempt === 2) {
        // Second attempt: Use only contact_info as a combined string
        const contactDetails = `
          Full Name: ${this.form.full_name}
          Email: ${this.form.email}
          Phone: ${this.form.phone}
          Message: ${this.form.contact_info}
        `.trim();
        formData.append('contact_info', contactDetails);
      }
      else if (attempt === 3) {
        // Third attempt: Use a JSON object for contact details
        const contactJson = JSON.stringify({
          full_name: this.form.full_name,
          email: this.form.email,
          phone: this.form.phone,
          message: this.form.contact_info
        });
        formData.append('contact_info', contactJson);
      }
      else if (attempt === 4) {
        // Fourth attempt: Use a direct API approach without file upload
        // This tests if the issue is with file handling
        const contactDetails = `
          Full Name: ${this.form.full_name}
          Email: ${this.form.email}
          Phone: ${this.form.phone}
          Message: ${this.form.contact_info}
        `.trim();
      formData.append('contact_info', contactDetails);
        // Skip file upload in this attempt
        this.form.resume = null;
      }

      // Add resume for all attempts except #4
      if (this.form.resume && attempt !== 4) {
        formData.append('resume', this.form.resume);
      }

      // Log what we're sending to help with debugging
      console.log(`Attempt ${attempt} - Submitting application for job ID:`, jobId);
      console.log(`Attempt ${attempt} - Form data keys:`, [...formData.keys()]);
      if (this.form.resume && attempt !== 4) {
        console.log(`Attempt ${attempt} - Resume file:`, this.form.resume.name, 'Size:', this.formatFileSize(this.form.resume.size));
      }
      
      // Check if token exists
      const token = localStorage.getItem('token');
      if (!token) {
        this.errorMessage = 'You must be logged in to apply for jobs. Please log in and try again.';
        this.showLoginHelp = true;
        this.loading = false;
        const toast = useToast();
        toast.error(this.errorMessage);
        return;
      }
      
      api.post(`/jobs/${jobId}/apply`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json' // Add Accept header for clearer error responses
          },
        timeout: 30000
        })
        .then(response => {
        this.successMessage = response.data.message || 'Application submitted successfully!';
          this.errorMessage = null;
        
        // Reset form
        this.form = {
          full_name: '',
          email: '',
          phone: '',
          contact_info: '',
          resume: null,
        };
        this.selectedFileName = '';
        this.selectedFileSize = 0;
        
        // Use toast notification
        const toast = useToast();
        toast.success(this.successMessage);
        })
        .catch(error => {
        console.error(`Attempt ${attempt} - Error submitting application:`, error);
        
        if (error.response) {
          console.error(`Attempt ${attempt} - Server response error:`, {
            status: error.response.status,
            statusText: error.response.statusText,
            data: error.response.data
          });
          
          // Check for authentication errors first
          if (error.response.status === 401) {
            this.errorMessage = 'Your session has expired or you are not logged in. Please log in again to apply for jobs.';
            this.showLoginHelp = true;
            this.showTroubleshooting = false;
            this.loading = false;
            const toast = useToast();
            toast.error(this.errorMessage);
            return;
          }
          
          // If we have attempts left, try the next format
          if (attempt < 4) {
            console.log(`Trying submission with format ${attempt + 1}...`);
            this.trySubmitApplication(attempt + 1);
            return; // Exit early to avoid showing error message
          }
          
          // If all attempts fail, try direct application as a last resort
          console.log('All attempts failed, trying direct application as last resort...');
          this.tryDirectApplication(true); // Pass true to indicate this is an automatic fallback
          return;
          
          // Show troubleshooting options for general errors
          this.showTroubleshooting = true;
          this.showLoginHelp = false;
          
          if (error.response.status === 413) {
            this.errorMessage = 'The file you uploaded is too large. Please upload a smaller file (max 2MB).';
          } else if (error.response.status === 500) {
            this.errorMessage = 'A server error occurred. This is likely due to a file storage permission issue on the server. Try without uploading a resume or contact the administrator.';
          } else {
            this.errorMessage = error.response.data?.message || `Error (${error.response.status}): ${error.response.statusText}`;
          }
        } else if (error.request) {
          console.error(`Attempt ${attempt} - No response received:`, error.request);
          this.errorMessage = 'No response received from the server. Please check your internet connection and try again.';
        } else {
          console.error(`Attempt ${attempt} - Request setup error:`, error.message);
          this.errorMessage = 'An error occurred while setting up the request: ' + error.message;
        }
        
        // Use toast notification
        const toast = useToast();
        toast.error(this.errorMessage);
      })
      .finally(() => {
        if (attempt === 4 || this.successMessage) {
          this.loading = false;
        }
        });
    },
    
    fetchJobData() {
      const jobId = this.$route.params.jobId;
      
      api.get(`/jobs/${jobId}`)
        .then(response => {
          if (response.data && response.data.data) {
          this.job = response.data.data;
          } else {
            this.errorMessage = 'Unexpected response format when fetching job details.';
            console.error('Unexpected job data format:', response.data);
          }
        })
        .catch(error => {
          this.errorMessage = 'Job not found!';
          console.error('Error fetching job:', error);
          
          // Use toast notification
          const toast = useToast();
          toast.error(this.errorMessage);
        });
    },
    
    debugApi() {
      this.debugLoading = true;
      const jobId = this.$route.params.jobId;
      
      // Make a GET request to check the API endpoint
      api.get(`/jobs/${jobId}`)
        .then(response => {
          console.log('API Debug - Job Details:', response.data);
          
          // Try to get the API structure documentation
          return api.options(`/jobs/${jobId}/apply`).catch(() => ({ data: 'No OPTIONS support' }));
        })
        .then(response => {
          console.log('API Debug - OPTIONS response:', response.data);
          alert('Debug information has been logged to the console. Please check the browser console.');
        })
        .catch(error => {
          console.error('API Debug - Error:', error);
          alert('Failed to debug API. Check console for details.');
        })
        .finally(() => {
          this.debugLoading = false;
        });
    },
    
    tryWithoutFile() {
      // Try submitting without a resume file
      this.form.resume = null;
      this.selectedFileName = '';
      this.selectedFileSize = 0;
      this.submitApplication();
    },
    
    checkAuthStatus() {
      const token = localStorage.getItem('token');
      
      if (!token) {
        this.errorMessage = 'No authentication token found. Please log in.';
        return;
      }
      
      api.get('/user', {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      })
      .then(response => {
        if (response.data && response.data.user) {
          // Show user details
          const toast = useToast();
          toast.success(`Logged in as ${response.data.user.name}`);
          this.errorMessage = `You are authenticated as ${response.data.user.name}. Please try submitting the application again.`;
          this.showLoginHelp = false;
        } else {
          this.errorMessage = 'Authentication error: Valid token but no user data returned.';
        }
      })
      .catch(error => {
        if (error.response && error.response.status === 401) {
          this.errorMessage = 'Authentication failed. Your token may have expired. Please log in again.';
          // Optionally redirect to login
          // this.$router.push('/');
        } else {
          this.errorMessage = 'Failed to verify authentication status: ' + (error.response?.data?.message || error.message);
        }
      });
    },
    
    tryDirectApplication(isAutoFallback = false) {
      // This is a direct API call without file upload to test if the backend works at all
      this.loading = true;
      if (!isAutoFallback) {
        this.errorMessage = null;
        this.successMessage = null;
      }
      
      const jobId = this.$route.params.jobId;
      const token = localStorage.getItem('token');
      
      if (!token) {
        this.errorMessage = 'You must be logged in to apply for jobs.';
        this.loading = false;
        return;
      }
      
      // Create a simple JSON payload instead of FormData
      const payload = {
        contact_info: `Application from ${this.form.full_name} (${this.form.email})\nPhone: ${this.form.phone}\n\n${this.form.contact_info || ''}`,
        job_id: jobId
      };
      
      // Make the request with JSON instead of FormData
      api.post(`/jobs/${jobId}/apply`, payload, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      })
      .then(response => {
        this.successMessage = (isAutoFallback ? 'Applied successfully without resume! ' : 'Application sent! ') + (response.data.message || '');
        this.errorMessage = null;
        
        // Reset form
        this.form = {
          full_name: '',
          email: '',
          phone: '',
          contact_info: '',
          resume: null,
        };
        this.selectedFileName = '';
        this.selectedFileSize = 0;
        
        const toast = useToast();
        toast.success(this.successMessage);
      })
      .catch(error => {
        console.error('Direct application error:', error);
        
        if (isAutoFallback) {
          // If even the fallback fails, show troubleshooting options
          this.showTroubleshooting = true;
          this.showLoginHelp = false;
        }
        
        if (error.response) {
          this.errorMessage = `Server error: ${error.response.status} - ${error.response.statusText}. ${isAutoFallback ? 'All application methods failed.' : 'This indicates the backend API has issues.'}`;
        } else {
          this.errorMessage = 'Failed to send application: ' + error.message;
        }
        const toast = useToast();
        toast.error(this.errorMessage);
      })
      .finally(() => {
        this.loading = false;
      });
    },
    
    debugStorageConfig() {
      this.debugLoading = true;
      api.get('/config/storage-check', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      .then(response => {
        console.log('Storage configuration:', response.data);
        alert('Storage configuration check completed. See console for details.');
      })
      .catch(error => {
        console.error('Failed to check storage configuration:', error);
        alert('Failed to check storage configuration. This endpoint might not exist.');
      })
      .finally(() => {
        this.debugLoading = false;
      });
    }
  },
  mounted() {
    this.fetchJobData();
  },
};
</script>

<style scoped>
.text-gradient {
  background: linear-gradient(90deg, #390afc, #2948ff);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
.gradient-btn {
  background: linear-gradient(to right, #667eea, #764ba2);
  border: none;
  transition: 0.3s ease;
}
.gradient-btn:hover {
  opacity: 0.95;
}
.gradient-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.invalid-feedback {
  display: block;
  color: #dc3545;
  font-size: 0.875em;
  margin-top: 0.25rem;
}
</style>
