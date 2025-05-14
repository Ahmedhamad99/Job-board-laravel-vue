<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import instance from '@/axios';

const router = useRouter();
const user = ref(null);
const loading = ref(true);

onMounted(async () => {
  try {
    loading.value = true;
    // Check if user is authenticated
    const response = await instance.get('/user');
    user.value = response.data;
  } catch (error) {
    console.error('Authentication error:', error);
    // Optional: Redirect to login if not authenticated
    // router.push('/login');
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="container-fluid gradient-bg-light py-5">
    <div
      class="d-flex flex-column justify-content-center align-content-center"
    >
      <h1 class="text-center grad mb-1">Candidate Dashboard</h1>
      <h5 class="text-center spacing mt-0 text-muted">
        Top Tech & Creative Job Listings
      </h5>
      <div v-if="loading" class="text-center my-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <router-view></router-view>
    </div>
  </div>
</template>

<style scoped>
.gradient-bg-light {
  background: linear-gradient(to bottom right, #f8f9fc, #ffffff);
}

.grad {
  background: linear-gradient(to right, #007bff, #ff4d4d);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.spacing {
  letter-spacing: 2.5px;
}
</style>
