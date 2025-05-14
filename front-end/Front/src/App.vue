<script setup>
import Navbar from './components/Navbar.vue';
</script>

<template>
  <div>
    <Navbar />
    <main class="container">
      <router-view></router-view>
    </main>
  </div>
</template>

<style>
.container {
  max-width: 1200px;
}
</style>