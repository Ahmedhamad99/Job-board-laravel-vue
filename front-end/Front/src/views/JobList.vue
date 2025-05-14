<template>
  <section class="job-section py-5 gradient-bg-light">
    <div class="container">
      <!-- Header -->
      <div class="text-center mb-5">
        <h2 class="display-4 fw-bold fancy-heading animate-title">
          Explore Your Future Career
        </h2>
        <p class="lead text-secondary animate-sub">
          Find job opportunities tailored for you, and start your journey today.
        </p>
        
        <!-- Advanced Search Button -->
        <div class="mt-4">
          <button 
            @click="goToSearchPage" 
            class="btn btn-primary btn-lg search-button px-4 py-2 shadow-sm"
          >
            <i class="bi bi-search me-2"></i> Advanced Job Search
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Loading available jobs...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="alert alert-danger text-center" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ error }}
      </div>
      
      <!-- Empty State -->
      <div v-else-if="jobs.length === 0" class="text-center py-5">
        <i class="bi bi-briefcase-fill display-1 text-muted"></i>
        <h3 class="mt-3 text-muted">No jobs available</h3>
        <p class="text-muted">Check back later for new opportunities</p>
      </div>

      <!-- Job Cards Grid -->
      <div v-else class="row g-4">
        <div
          v-for="(job, index) in jobs"
          :key="job.id"
          class="col-xl-4 col-md-6"
        >
          <div
            class="card job-card h-100 shadow-sm animate-fade-up"
            :style="{ animationDelay: `${index * 0.1}s` }"
          >
            <!-- Ribbon -->
            <div class="ribbon"></div>

            <div class="card-body d-flex flex-column p-4">
              <!-- Header -->
              <div class="d-flex align-items-center mb-3">
                <div class="icon-circle me-3">
                  <i class="bi bi-briefcase-fill text-white"></i>
                </div>
                <h5 class="mb-0 fw-semibold text-dark">{{ job.title }}</h5>
              </div>

              <!-- Description -->
              <p class="text-muted small mb-3">{{ job.description }}</p>

              <!-- Info Badges -->
              <div class="mb-3 d-flex flex-wrap gap-2">
                <span class="badge bg-light text-dark border"
                  ><i class="bi bi-geo-alt-fill me-1"></i>{{ job.location }}</span
                >
                <span v-if="job.salary_min || job.salary_max" class="badge bg-light text-dark border">
                  <i class="bi bi-currency-dollar me-1"></i>
                  {{ job.salary_min || 'N/A' }} - {{ job.salary_max || 'N/A' }}
                </span>
              </div>

              <!-- Skills -->
              <div class="mb-4">
                <strong class="text-dark">Skills:</strong>
                <div v-if="splitSkills(job.skills).length > 0" class="d-flex flex-wrap mt-2 gap-2">
                  <span
                    v-for="skill in splitSkills(job.skills)"
                    :key="skill"
                    class="badge skill-badge"
                  >
                    <i class="bi bi-check-circle-fill me-1"></i> {{ skill }}
                  </span>
                </div>
                <p v-else class="text-muted small mt-1">No specific skills mentioned</p>
              </div>

              <!-- Apply Button -->
              <button
                @click="goToApplyPage(job.id)"
                class="btn gradient-btn text-white fw-bold rounded-pill mt-auto w-100"
              >
                <i class="bi bi-send-fill me-2"></i> Apply Now
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";
import { useToast } from 'vue-toastification';
import api from '@/axios';

export default {
  data() {
    return {
      jobs: [],
      loading: false,
      error: null
    };
  },
  mounted() {
    this.fetchJobs();
  },
  methods: {
    fetchJobs() {
      this.loading = true;
      this.error = null;
      
      // Use the API instance to maintain consistency with other components
      api.get("/jobs")
        .then((response) => {
          if (response.data && response.data.data) {
            this.jobs = response.data.data;
          } else {
            this.jobs = [];
            console.error("Unexpected API response structure:", response.data);
          }
        })
        .catch((error) => {
          this.error = "Failed to load jobs. Please try again later.";
          console.error("Error fetching jobs:", error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    goToApplyPage(jobId) {
      this.$router.push({ name: "applyJob", params: { jobId } });
    },
    goToSearchPage() {
      this.$router.push({ name: "CandidateDashboard" });
    },
    splitSkills(skills) {
      // Handle null, undefined, or empty skills
      if (!skills) {
        return [];
      }
      return skills.split(",").map((s) => s.trim()).filter(skill => skill !== '');
    },
  },
};
</script>

<style scoped>
.fancy-heading {
  background: linear-gradient(135deg, #667eea, #77707e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
}

.gradient-btn {
  background: linear-gradient(to right, #667eea, #764ba2);
  border: none;
  transition: all 0.3s ease;
}
.gradient-btn:hover {
  transform: scale(1.04);
  opacity: 0.95;
  box-shadow: 0 8px 18px rgba(67, 84, 161, 0.966);
}

.gradient-bg-light {
  background: linear-gradient(to bottom right, #f8f9fc, #ffffff);
}

.search-button {
  background: linear-gradient(to right, #007bff, #ff4d4d);
  transition: 0.5s;
  border-radius: 50px;
  border: none;
  color: white;
  font-weight: 600;
}

.search-button:hover {
  background: linear-gradient(to right, #ff4d4d, #007bff);
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.job-card {
  border-radius: 1.5rem;
  position: relative;
  overflow: hidden;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}
.job-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.ribbon {
  position: absolute;
  top: 0;
  right: 0;
  background: linear-gradient(to right, #151f15, #ff7eb3);
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
  padding: 6px 14px;
  border-bottom-left-radius: 12px;
  z-index: 2;
}

.icon-circle {
  width: 50px;
  height: 50px;
  background: linear-gradient(to right, #5c6aaa, #764ba2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.skill-badge {
  background: linear-gradient(to right, #100d14, #2575fc);
  color: white;
  padding: 6px 12px;
  border-radius: 50rem;
  font-size: 0.8rem;
  display: inline-flex;
  align-items: center;
  box-shadow: 0 3px 6px rgba(172, 3, 96, 0.945);
}

.animate-fade-up {
  animation: fadeUp 0.6s ease forwards;
  opacity: 0;
}
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-title {
  animation: slideIn 0.7s ease-out;
}
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-25px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-sub {
  animation: fadeIn 1.2s ease;
}
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
