<template>
  <div class="not-found-container">
    <div class="not-found-content">
      <h1 class="error-code">404</h1>
      <h2 class="error-title">Page Not Found</h2>
      <p class="error-message">The page you are looking for doesn't exist or has been moved.</p>
      <router-link to="/" class="back-button">Back to Home</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>

<style scoped>
.not-found-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f8f9fa;
}

.not-found-content {
  text-align: center;
  padding: 2rem;
  max-width: 500px;
}

.error-code {
  font-size: 8rem;
  font-weight: 700;
  color: #dc3545;
  margin: 0;
  line-height: 1;
}

.error-title {
  font-size: 2rem;
  font-weight: 600;
  margin: 1rem 0;
}

.error-message {
  font-size: 1.2rem;
  color: #6c757d;
  margin-bottom: 2rem;
}

.back-button {
  display: inline-block;
  background-color: #0d6efd;
  color: white;
  padding: 0.75rem 1.5rem;
  text-decoration: none;
  border-radius: 4px;
  font-weight: 500;
  transition: background-color 0.2s;
}

.back-button:hover {
  background-color: #0b5ed7;
}
</style> 