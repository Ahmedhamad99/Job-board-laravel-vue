<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import instance from "@/axios";

// onMounted >> get query params according to url >> search function (get search inputs and page and fetch)
// use search >> search function (get search inputs and page and fetch)
// reset >> reset inputs and search function (get search inputs and page and fetch)
// watch >> watch query params if changed get them and set them in inputs and then search (get search inputs and page and fetch)

// benefit >> change the route (query params or paths) and navigate
const router = useRouter();
// access the current route object which contains >> path, query, params, name, fullPath >> read-only
const route = useRoute();
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

  // adding params to the current path
  router.push({
    path: "",
    query: { ...keysValues, page: page > 1 ? page : 1 },
  });
  try {
    const response = await instance.get("/jobs", { params: keysValues });
    jobs.value = response.data.data.data;
    pagination.value = {
      current_page: response.data.data.current_page,
      last_page: response.data.data.last_page,
      total: response.data.data.total,
      per_page: response.data.data.per_page,
      pages_no: Math.ceil(
        response.data.data.total / response.data.data.per_page
      ),
    };
  } catch (err) {
    console.error(`Failed fetching data: ${err}`);
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
</script>

<template>
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
    <div class="row my-5">
      <div v-for="job in jobs" :key="job.id" class="col-4">
        <div class="shadow-sm p-4 rounded-5 my-2 position-relative">
          <small class="small-size position-absolute date-container">
            {{ new Date(job.created_at).toLocaleDateString() }}
          </small>
          <div class="d-flex justify-content-center align-items-center">
            <div class="pt-2">
              <i class="fa-solid fa-briefcase fs-5 pe-3 text-primary"></i>
              <h6 class="d-inline-block text-primary">{{ job.title }}</h6>
            </div>
          </div>
          <div class="text-muted job-desc mb-2 desc-container">
            {{ job.description.substring(0, 80) }} ...
          </div>
          <span class="badge bg-light text-dark rounded-5 me-2"
            ><i class="fa-solid fa-location-dot pe-2"></i
            >{{ job.location }}</span
          >
          <span class="badge bg-light text-dark rounded-5">
            <i class="fa-solid fa-sack-dollar pe-2"></i>
            ${{ job.salary_min || "N/A" }} - ${{ job.salary_max || "N/A" }}
          </span>
          <div class="pt-3 pb-2">
            <strong>Skills:</strong>
          </div>
          <div class="skills-container">
            <span
              v-for="(skill, index) in JSON.parse(job.skills)"
              :key="index"
              class="badge bg-light text-dark rounded-5 me-2"
              >{{ skill }}</span
            >
          </div>
          <div class="d-flex justify-content-center align-items-center">
            <button class="btn btn-primary rounded-5 w-100 py-1 mt-4 mx-2">
              Apply Now
            </button>
            <button class="btn btn-primary rounded-5 w-100 py-1 mt-4 mx-2">
              See More
            </button>
          </div>

          <!-- <small class="text-muted d-block pb-2"
            >created by : {{ job.employer.name }}</small
          > -->
          <!-- <p>
            <strong>Category:</strong>
            {{ job.category.name || "Not specified" }}
          </p> -->
          <!-- <router-link :to="`/jobs/${job.id}`" class="details-link">View Details</router-link> -->
        </div>
      </div>
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

.job-desc {
  font-size: 0.85rem;
}

.small-size {
  font-size: 0.7rem;
}

.desc-container {
  height: 60px;
}

.skills-container {
  height: 40px;
}

.date-container {
  top: 2%;
  right: 4%;
  color: rgba(128, 128, 128, 0.837);
}

.btn-page {
  background-color: #0062cb;
  transition: 0.3s;
  font-weight: bolder;
}

.btn-page:hover {
  background-color: #0062cb;
}
</style>
