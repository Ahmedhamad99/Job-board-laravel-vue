<template>
  <div class="profile-container">
    <div class="right-section">
      <img class="side-image" src="../assets/imgs/user-access.png" alt="Profile Art" />
      <h2>Welcome to Your Space</h2>
      <p>Manage your personal information, update your bio, and make your profile professional.</p>
       <div v-if="user.role === 'employer'"  class="employer-section" @click="goToJobs">
              <button class="special-btn">Show My Jobs</button>
      </div>
      <div v-if="user.role === 'admin'"  class="employer-section" @click="goToDashboard">
              <button class="special-btn">Show My Dashboard</button>
      </div>
    </div>

    <div class="profile-card">
      <h2>MY PROFILE</h2>
      <h4>My Details</h4>
      <img class="profile-pic" :src="`http://localhost:8000/storage/${profile.profile_picture}`" alt="Profile Pic" />
      <h2>{{ user.name }}</h2>
      <h5>Basic Details</h5>

      <div class="profile-info">
        <div class="info-box">
          <span class="label">Bio:</span>
          <span class="value">{{ profile.bio }}</span>
        </div>
        <div class="info-box">
          <span class="label">Email:</span>
          <span class="value">{{ user.email }}</span>
        </div>
        <div class="info-box">
          <span class="label">Phone:</span>
          <span class="value">{{ profile.phone }}</span>
        </div>
        <div class="info-box">
          <span class="label">Address:</span>
          <span class="value">{{ profile.address }}</span>
        </div>
      </div>

      <button @click="goToEdit" class="edit-btn">Edit Profile</button>
           

    </div>
  </div>
</template>


<script>
import axios from 'axios'

export default {
 data() {
  return {
    user: {
      name: '',
      email: '',
      role: '',
      password: '',
      password_confirmation: ''
    },
    profile: {
      phone: '',
      address: '',
      bio: ''
    },
    profilePicture: null,
    resume: null
  }
},

  mounted() {
  axios.get('/api/profile')
    .then(res => {
      this.user = res.data.user
      this.profile = res.data.profile
      this.user.name = res.data.user.name;
    })
    .catch(err => {
      console.error('Error fetching profile:', err);
    });
},
  methods: {
    goToEdit() {
      this.$router.push('/edit-profile');
    },
    goToJobs() {
    this.$router.push('/profile/jobs');
  },
  goToDashboard(){
   this.$router.push('/dashboard');
  }
  }
}
</script>

<style scoped>
*{
  padding:0px;
  margin:0px;
 
}
body {
  font-family: 'Poppins', sans-serif;
}

.profile-container {
  display: flex;
  flex-wrap: wrap;
  min-height: 100vh;
  background-color: #f3f7fa;
  padding: 40px;
  gap: 40px;
  justify-content: center;
}

.right-section {
  flex: 1;
  max-width: 400px;
  background-color: #fff;
  padding: 30px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.profile-pic {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  margin-top: 20px;
  display: block;
  transition: transform 0.4s ease-in-out;
  margin-left: auto;
  margin-right: auto;
}

.profile-pic:hover {
  transform: scale(1.05);
}

.profile-card h2 {
  font-size: 26px;
  margin-bottom: 15px;
  color: rgb(3, 37, 65);
  font-family: 'Poppins', sans-serif;
  text-align: center;
}

h4 {
  border-bottom: 2px solid rgb(3, 37, 65);
  color: rgb(3, 37, 65);
  width: fit-content;
  font-family: 'Poppins', sans-serif;
  margin: 0 auto 15px;
}

h5 {
  font-size: 22px;
  text-align: center;
  color: rgb(3, 37, 65);
  margin: 20px 0;
  font-family: 'Poppins', sans-serif;
}

.profile-info {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 40px;
}

.info-box {
  background-color: #f0f4f8;
  border-radius: 10px;
  padding: 15px 20px;
  display: flex;
  flex-direction: column;
  font-family: 'Poppins', sans-serif;
}

.label {
  font-size: 18px;
  color: rgb(3, 37, 65);
  margin-bottom: 5px;
  font-weight: bold;
}

.value {
  color: #333;
  font-size: 15px;
}

.edit-btn {
  background-color: rgb(3, 37, 65);
  color: white;
  padding: 12px 25px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  transition: background-color 0.3s ease;
}

.edit-btn:hover {
  background-color: #0056b3;
}


.right-section h2 {
  color: #032541;
  margin-top: 20px;
  font-family: 'Poppins', sans-serif;
}

.right-section p {
  color: #555;
  margin-top: 10px;
  font-size: 16px;
}

.side-image {
  width: 100%;
  max-height: 220px;
  object-fit: contain;
  border-radius: 10px;
}

.profile-card {
  flex: 2;
  background: white;
  border-radius: 15px;
  padding: 40px 30px;
  max-width: 600px;
  position: relative;
}

.employer-section {
  margin-top: 20px;
  text-align: center;
}

.special-btn {
  background-color: #032541;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
}

@media (max-width: 768px) {
  .profile-container {
    flex-direction: column-reverse;
    align-items: center;
  }

  .right-section,
  .profile-card {
    max-width: 100%;
  }

  .edit-btn {
    position: static;
    transform: none;
    margin-top: 20px;
  }
}

</style>
