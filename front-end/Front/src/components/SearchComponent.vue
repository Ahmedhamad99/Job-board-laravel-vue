<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import instance from "@/axios";

const router = useRouter();
const route = useRoute();

function goToApplyPage(jobId) {
  router.push({ name: "applyJob", params: { jobId } });
}

console.log("query", route.query);
const jobs = ref([]);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
  per_page: 10,
  pages_no: 0,
});
const filters = ref({
  titledesc: "",
  location: "",
  category: "",
  salary_min: null,
  salary_max: null,
  from_date: "",
});

async function search(page) {
  const keys = Object.keys(filters.value);
  const keysValues = {};
  keys.forEach((key) => {
    if (filters.value[key] !== "" && filters.value[key] !== null)
      keysValues[key] = filters.value[key];
  });
  keysValues.page = page;
  console.log(keysValues);

  router.push({
    path: "",
    query: { ...keysValues, page: page > 1 ? page : 1 },
  });
  try {
    const response = await instance.get("/jobs/search", { params: keysValues });
    console.log("API Response:", response.data);
    
    if (response.data && response.data.data) {
      // Handle nested data structure
      const responseData = response.data.data;
      
      // Check if data is an array directly or contained in a 'data' property
      if (Array.isArray(responseData)) {
        jobs.value = responseData;
        pagination.value = {
          current_page: 1,
          last_page: 1,
          total: responseData.length,
          per_page: responseData.length,
          pages_no: 1,
        };
      } else if (responseData.data) {
        // Standard pagination format
        jobs.value = responseData.data;
        pagination.value = {
          current_page: responseData.current_page,
          last_page: responseData.last_page,
          total: responseData.total,
          per_page: responseData.per_page,
          pages_no: Math.ceil(responseData.total / responseData.per_page) || 1,
        };
      } else {
        console.error("Unexpected data format", responseData);
        jobs.value = [];
        pagination.value = {
          current_page: 1,
          last_page: 1,
          total: 0,
          per_page: 10,
          pages_no: 0,
        };
      }
    } else {
      console.error("Invalid API response format", response.data);
      jobs.value = [];
    }
  } catch (err) {
    console.error(`Failed fetching data:`, err);
    jobs.value = [];
  }
}

function reset() {
  filters.value = {
    titledesc: "",
    location: "",
    category: "",
    salary_min: null,
    salary_max: null,
    from_date: "",
  };
  search(1);
}

const initUrlFilters = () => {
  filters.value.titledesc = route.query.titledesc || "";
  filters.value.location = route.query.location || "";
  filters.value.category = route.query.category || "";
  filters.value.salary_min = route.query.salary_min
    ? Number(route.query.salary_min)
    : null;
  filters.value.salary_max = route.query.salary_max
    ? Number(route.query.salary_max)
    : null;
  filters.value.from_date = route.query.from_date || "";
};

// monitor query params and callback to be applied if it changed
// watch(source, callback, options)
watch(
  () => route.query,
  (newQuery) => {
    initUrlFilters();
    search(newQuery.page ? Number(newQuery.page) : 1);
  }
);

onMounted(async () => {
  // set filters from the query params
  initUrlFilters();
  // search happens according to filters applied
  search(route.query.page);
});

function getSkillsArray(skills) {
  if (!skills) {
    return [];
  }
  
  // First try to parse as JSON
  if (typeof skills === 'string') {
    try {
      // Check if the string starts with [ to determine if it might be JSON
      if (skills.trim().startsWith('[')) {
        return JSON.parse(skills);
      } else {
        // If not JSON format, treat as comma-separated string
        return skills.split(',').map(skill => skill.trim()).filter(skill => skill !== '');
      }
    } catch (e) {
      console.error("Error parsing skills:", e);
      // Fallback to comma-separated handling if JSON parsing fails
      return skills.split(',').map(skill => skill.trim()).filter(skill => skill !== '');
    }
  } else if (Array.isArray(skills)) {
    return skills;
  } else {
    console.error("Unknown skills format", skills);
    return [];
  }
}
</script>

<template>
  <div class="container-fluid gradient-bg-light">

  <div class="container w-75 mt-5 mb-0">
    <!-- Search Part -->
    <form @submit.prevent="search(1)">
      <div class="mb-3 position-relative search-container">
        <input
          v-model="filters.titledesc"
          type="text"
          class="form-control main-input text-white shadow"
          placeholder="Search for Jobs ..."
          aria-label="Search for Jobs ..."
        />
        <div class="position-absolute icon-container">
          <span class=""><i class="fa-brands fa-searchengin fs-3"></i></span>
        </div>
      </div>
      <div class="container shadow p-5 rounded-4 mt-0">
        <h5 class="text-muted pb-3 text-uppercase fw-normal">
          Advanced Search
        </h5>
        <div class="d-flex justify-content-center align-items-center flex-wrap">
          <input
            v-model="filters.location"
            type="text"
            placeholder="Location"
            class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2"
          />
          <input
            v-model="filters.category"
            type="text"
            placeholder="Category"
            class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2"
          />
          <input
            v-model="filters.salary_min"
            type="number"
            placeholder="Minimum Salary"
            class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2"
          />
          <input
            v-model="filters.salary_max"
            type="number"
            placeholder="Maximum Salary"
            class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2"
          />
          <input
            v-model="filters.from_date"
            type="date"
            placeholder="From Date"
            class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2"
          />
        </div>
        <div class="d-flex justify-content-between align-content-center mt-4">
          <p class="text-muted">
            <span class="fw-semibold">{{ pagination.total }}</span> results
          </p>
          <div>
            <button
              type="reset"
              @click="reset"
              class="btn btn-light mx-2 rounded-5 text-uppercase text-muted shadow-sm"
            >
              Reset
            </button>
            <button
              type="submit"
              class="search-button btn text-white px-4 py-2 shadow-sm text-uppercase mx-2"
            >
              Search
            </button>
          </div>
        </div>
      </div>
    </form>
    <!-- <p class="text-center mt-4 alert alert-light">{{ filters }}</p> -->

    <!-- Jobs Part (should be replaced) -->
    <section class="job-section py-5">
      <div class="container">
        <!-- Job Cards Grid -->
        <div class="row g-4">
          <div
            v-for="(job, index) in jobs"
            :key="job.id"
            class="col-xl-4 col-lg-4 col-md-6 col-sm-12"
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
                <p class="text-muted small mb-3">{{ job.description.substring(0,50) }} ...</p>

                <!-- Info Badges -->
                <div class="mb-3 d-flex flex-wrap gap-2">
                  <span class="badge bg-light text-dark border"
                    >📍 {{ job.location }}</span
                  >
                  <span class="badge bg-light text-dark border"
                    >💰 ${{ job.salary_min }} - ${{ job.salary_max }}</span
                  >
                </div>

                <!-- Skills -->
                <div class="mb-4">
                  <strong class="text-dark">Skills:</strong>
                  <div v-if="getSkillsArray(job.skills).length > 0" class="d-flex flex-wrap mt-2 gap-2">
                    <span
                      v-for="skill in getSkillsArray(job.skills)"
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
    <div class="d-flex justify-content-center align-items-center">
      <button
        :disabled="pagination.current_page === 1"
        class="btn btn-primary mx-2"
        @click="search(pagination.current_page - 1)"
      >
        <i class="fa-solid fa-arrow-left"></i>
      </button>
      <button
        v-for="page in pagination.pages_no"
        :key="page"
        :class="[
          `btn mx-2`,
          pagination.current_page == page
            ? `btn-page text-white`
            : `btn-primary`,
        ]"
        @click="search(page)"
      >
        {{ page }}
      </button>
      <button
        :disabled="pagination.current_page === pagination.last_page"
        class="btn btn-primary mx-2"
        @click="search(pagination.current_page + 1)"
      >
        <i class="fa-solid fa-arrow-right"></i>
      </button>
    </div>
  </div>
  </div>
</template>

<style scoped>
.gradient-bg-light {
  background: linear-gradient(to bottom right, #f8f9fc, #ffffff);
}

.main-input {
  background: linear-gradient(to right, #007bff, #ff4d4d);
  border-radius: 50px;
  padding: 22px;
  border: none;
}

.main-input:focus {
  background: linear-gradient(to right, #007bffea, #ff4d4deb);
}

.search-container i {
  transition: 0.3s;
}

.search-container:hover i {
  transform: rotateY(180deg);
}

.main-input::placeholder {
  color: rgba(255, 255, 255, 0.786);
}

.icon-container {
  top: 50%;
  transform: translateY(-50%);
  right: 30px;
  background: #ffffff00;
}

input:not(.main-input),
select {
  background: rgb(244, 244, 244);
}

select option {
  background: rgb(56, 56, 56);
  color: #fff;
}

.icon-container i {
  color: rgba(255, 255, 255, 0.956);
}

.search-button {
  background: linear-gradient(to right, #007bff, #ff4d4d);
  transition: 0.5s;
  border-radius: 50px;
  border: none;
}

.search-button:hover {
  background: linear-gradient(to right, #ff4d4d, #007bff);
}

.btn-page {
  background-color: #0062cb;
  transition: 0.3s;
  font-weight: bolder;
}

.btn-page:hover {
  background-color: #0062cb;
}

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
