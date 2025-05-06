<script setup>
import HelloWorld from './components/HelloWorld.vue'
import TheWelcome from './components/TheWelcome.vue'
import Bodycomponent from './components/Bodycomponent.vue';
import { useCounterStore } from './stores/CounterStore';
import Header from './components/Header.vue';
const counter = useCounterStore();
</script>

<template>
  <div>
    
    <p>Coun :{{ counter.count }}</p>
    <button class="btn btn-primary" @click="counter.increment">+</button>
    <button class="btn btn-danger" @click="counter.decrement">-</button>
  </div>
  
</template>

<style scoped>
header {
  line-height: 1.5;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }
}
</style>
