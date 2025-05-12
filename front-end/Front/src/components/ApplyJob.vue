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
                  <label for="resume" class="form-label fw-semibold">Upload Resume</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-paperclip"></i></span>
                    <input
                      type="file"
                      id="resume"
                      @change="handleFileUpload"
                      accept=".pdf,.doc,.docx"
                      class="form-control"
                    />
                  </div>
                </div>

                <div class="d-grid mt-5">
                  <button type="submit" class="btn gradient-btn text-white py-3 rounded-pill fw-bold">
                    <i class="bi bi-send-fill me-2"></i> Submit Application
                  </button>
                </div>
              </div>
            </div>

            <div v-if="errorMessage" class="alert alert-danger mt-4 text-center">
              {{ errorMessage }}
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

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
      errorMessage: null,
    };
  },
  methods: {
    handleFileUpload(event) {
      this.form.resume = event.target.files[0];
    },
    submitApplication() {
      const formData = new FormData();

      const contactDetails = `
        Full Name: ${this.form.full_name}
        Email: ${this.form.email}
        Phone: ${this.form.phone}
        Message: ${this.form.contact_info}
      `.trim();

      formData.append('contact_info', contactDetails);

      if (this.form.resume) {
        formData.append('resume', this.form.resume);
      }

      const jobId = this.$route.params.jobId;
      const token = localStorage.getItem('token');

      axios
        .post(`http://127.0.0.1:8000/api/jobs/${jobId}/apply`, formData, {
          headers: {
            Authorization: `Bearer ${token}`, // الأفضل والأكثر توافقاً مع Laravel Sanctum
            'Content-Type': 'multipart/form-data',
          },
        })
        .then(response => {
          alert(response.data.message);
          this.errorMessage = null;
        })
        .catch(error => {
          this.errorMessage = error.response?.data?.message || 'An error occurred while applying!';
          console.error(error);
        });
    },
    fetchJobData() {
      const jobId = this.$route.params.jobId;
      axios
        .get(`http://127.0.0.1:8000/api/jobs/${jobId}`)
        .then(response => {
          this.job = response.data.data;
        })
        .catch(error => {
          this.errorMessage = 'Job not found!';
          console.error(error);
        });
    },
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
</style>
