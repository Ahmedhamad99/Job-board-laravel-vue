<template>
    <div class="d-flex flex-column min-vh-100">
      <nav class="navbar navbar-expand-lg bg-dark text-light fixed-top w-100">
        <div class="container-fluid">
          <a class="navbar-brand text-light" href="#">Oragin</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
                  aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
              <router-link to="/" class="text-light nav-link">Home</router-link>
              <router-link to="/home" class="text-light nav-link">App</router-link>
              <router-link to="/body" class="text-light nav-link">Body</router-link>
              <a class="nav-link disabled text-light" aria-disabled="true">We are</a>
            </div>
          </div>
        </div>
      </nav>
  
      <div class="content-container">
        <RouterView class="d-flex-grow w-100 h-100"></RouterView>
      </div>
    </div>
  </template>
  
  <style scoped>
 
  .content-container {
    width: 1000px; 
   margin-top: 50px;
  height: 100vh;
    overflow: hidden; 
  }
  </style>
  