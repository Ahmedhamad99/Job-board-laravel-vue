<template>
  <div id="app">
    <router-view></router-view>  
  </div>
</template>

<script>
import JobList from './components/JobList.vue';
import ApplyJob from './components/ApplyJob.vue';

export default {
  name: 'App',
  components: {
    JobList,
    ApplyJob,
  }
}
</script>
