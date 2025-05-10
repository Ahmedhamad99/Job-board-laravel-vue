<template>
  <div class="profile-view">
    <div class="profile-card">
      <img class="profile-pic" :src="`http://localhost:8000/storage/${profile.profile_picture}`" alt="Profile Pic" />
      <h2>{{ user.name }}</h2>
       
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
  axios.get('http://localhost:8000/api/profile')
    .then(res => {
      this.user = res.data.user
      this.profile = res.data.profile
      this.user.name = res.data.user.name;


    })
},
  methods: {
    goToEdit() {
      this.$router.push('/edit-profile')
    }
  }
}
</script>

<style scoped>
.profile-view {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f3f7fa;
  padding: 20px;
}

.profile-card {
  background: white;
  border-radius: 15px;
  padding: 40px 30px;
  width: 100%;
  max-width: 600px;
  text-align: left;
  position: relative;
}

.profile-pic {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  margin: 0 auto 30px;
  display: block;
  transition: transform 0.4s ease-in-out;
}
.profile-pic:hover {
  transform: scale(1.05);
}

h2 {
  font-size: 26px;
  margin-bottom: 25px;
  color: #333;
  text-align: center;
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
}

.label {
  font-weight: bold;
  color: #444;
  margin-bottom: 5px;
}

.value {
  color: #333;
  font-size: 15px;
}

.edit-btn {
  background-color: #007bff;
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
</style>
