<template>
    <div>
      <!-- Full-Width Bootstrap Carousel -->
      <div id="carouselExampleDark" class="carousel carousel-dark slide w-100">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
  
        <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
            <img src="https://media.istockphoto.com/id/1018429902/photo/above-the-top.jpg?s=612x612&w=0&k=20&c=U4JLMNGIJ-GkepMM8Jep6z-075VVAXSHXgNIlDyLNvo="  class="d-block w-100 h-100 .fixed-size-img " alt="Slide 1">
            <div class="carousel-caption d-none d-md-block">
              <h5>First Slide</h5>
              <p>Some representative placeholder content for the first slide.</p>
            </div>
          </div>
          <div class="carousel-item" data-bs-interval="2000">
            <img src="https://media.istockphoto.com/id/1419087231/photo/architectural-modern-buildings-design-colorless-black-designs-of-houses-business-centers.jpg?s=612x612&w=0&k=20&c=9MFQXMIxOtRCxhNR5QHmxc0DOx11bajmLfo3DRIDC9w="  class="d-block w-100 h-100 .fixed-size-img " alt="Slide 2">
            <div class="carousel-caption d-none d-md-block">
              <h5>Second Slide</h5>
              <p>Some representative placeholder content for the second slide.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://media.istockphoto.com/id/1427959731/photo/part-of-modern-urban-architecture-from-a-low-angle.jpg?s=612x612&w=0&k=20&c=ELHyB7njDEBBdlGu1dL3cmy7Reu3-vwsaDEuV-_ZTJk=" class="d-block w-100 h-100 .fixed-size-img " style="height:  700px;" alt="Slide 3">
            <div class="carousel-caption d-none d-md-block">
              <h5>Third Slide</h5>
              <p>Some representative placeholder content for the third slide.</p>
            </div>
          </div>
        </div>
  
        <!-- Navigation Buttons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </template>
  
<script>
export default {

}
</script>
<style scoped>

.fixed-size-img {
  height: 500px;
  object-fit: cover !important; 
}
</style>