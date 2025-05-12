<script setup></script>

<template>
  <div class="d-flex flex-column justify-content-center align-content-center my-5">
    <h1 class="text-center grad mb-1">Candidate Dashboard</h1>
    <h5 class="text-center spacing mt-0 text-muted">Top Tech & Creative Job Listings</h5>
    <router-view></router-view>
  </div>
</template>

<style scoped>
.grad {
  background: linear-gradient(to right, #007bff, #ff4d4d);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.spacing {
  letter-spacing: 2.5px;
}
</style>
