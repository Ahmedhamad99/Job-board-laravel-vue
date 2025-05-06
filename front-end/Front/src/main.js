import './assets/main.css'

import '../node_modules/bootstrap/dist/css/bootstrap.css'
import { createPinia } from 'pinia'
import Router from './routes/router.js'
import { createApp } from 'vue'
import App from './App.vue'
import Header from './components/Header.vue'
const app = createApp(Header);
const pinia = createPinia();

app.use(pinia);
app.use(Router);
app.mount('#app');


import '../node_modules/bootstrap/dist/js/bootstrap.bundle'