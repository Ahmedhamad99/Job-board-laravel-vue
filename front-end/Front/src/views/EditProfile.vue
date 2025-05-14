<template>
  <div class="profile-container">
    <div class="left-section">
      <h2>Edit Profile</h2>
      <form class="profile-form" @submit.prevent="updateProfile">
        <div class="form-group">
          <label>Name</label>
          <input v-model="user.name" type="text" placeholder="Enter name" />
        </div>

        <div class="form-group">
          <label>Bio</label>
          <input v-model="profile.bio" type="text" placeholder="Enter bio" />
        </div>

        <div class="form-group">
          <label>Phone</label>
          <input v-model="profile.phone" type="text" placeholder="Enter phone number" />
        </div>

        <div class="form-group">
          <label>Address</label>
          <input v-model="profile.address" type="text" placeholder="Enter address" />
        </div>

        <div class="form-group">
          <label>Profile Picture</label>
          <input type="file" @change="handleProfilePic" />
        </div>

        <div class="form-group">
          <label>Resume</label>
          <input type="file" @change="handleResume" />
        </div>
          <div class="form-group">
          <label>Password</label>
          <input v-model="user.password" type="password" placeholder="Enter new password (optional)" />
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input v-model="user.password_confirmation" type="password" placeholder="Confirm new password" />
        </div>

        <button type="submit" class="submit-btn">Save Profile</button>
      </form>
    </div>

   <div class="right-section">
  <div class="branding-box">
    <img src="../assets/imgs/edit.webp" alt="Platform Illustration" class="platform-image" />
    <h2>Manage Your Profile Securely</h2>
    <p>Keep your information up-to-date to get the best job matches tailored for you.</p>
  </div>
</div>

  </div>
</template>



<script>
import axios from 'axios'
export default {
  data() {
    return {
      user: {
        email: '',
        password: ''
      },
      profile: {
        bio: '',
        phone: '',
        address: ''
      },
      profilePicture: null,
      resume: null
    }
  },
  methods: {
    handleProfilePic(e) {
      this.profilePicture = e.target.files[0]
    },
    handleResume(e) {
      this.resume = e.target.files[0]
    },
   async updateProfile() {
  const formData = new FormData()
  formData.append('name', this.user.name)
  formData.append('email', this.user.email)
  formData.append('phone', this.profile.phone)
  formData.append('address', this.profile.address)
  formData.append('bio', this.profile.bio)

  if (this.user.password) {
    formData.append('password', this.user.password)
    formData.append('password_confirmation', this.user.password_confirmation)
  }

  if (this.profilePicture) {
    formData.append('profile_picture', this.profilePicture)
  }

  if (this.resume) {
    formData.append('resume', this.resume)
  }

  try {
    await axios.post('/api/profile', formData)
    alert('Profile updated successfully!')
    this.$router.push('/profile')
  } catch (err) {
    console.error(err.response.data)
    alert('Update failed: ' + JSON.stringify(err.response.data.errors))
  }
}

  },
  async mounted() {
    try {
      const res = await axios.get('/api/profile')
      this.profile = res.data.profile
      this.user.email = res.data.user.email
      this.user.name = res.data.user.name
    } catch (err) {
      console.error('Error fetching profile:', err)
    }
  }
}

</script>

<style scoped>
.profile-container {
  max-width: 1000px;
  margin: 40px auto;
  display: flex;
  gap: 30px;
  padding: 30px;
  background-color: #f7f7f7;
  border-radius: 16px;
  font-family: 'Segoe UI', sans-serif;
  flex-wrap: wrap;
}

.left-section {
  font-family: 'Poppins', sans-serif;
  flex: 1;
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.right-section {
  flex: 1;
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  height: fit-content;
    font-family: 'Poppins', sans-serif;
}

h2 {
  margin-bottom: 25px;
  color: rgb(3, 37, 65);
  font-size: 22px;
}

.profile-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 5px;
  display: block;
  color:rgb(3, 37, 65);
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 14px;
}

.submit-btn {
  background:rgb(3, 37, 65);
  color: white;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s ease;
}

.submit-btn:hover {
  background: #0056b3;
}
.right-section {
  text-align: center;
  padding: 2rem;
  background-color: #f9f9f9;
  border-left: 1px solid #ddd;
  font-weight:bold;
}


.platform-image {
  width: 100%;
  max-width: 200px;
  margin-bottom: 1rem;
  border-radius: 10px;
}


@media (max-width: 768px) {
  .profile-container {
    flex-direction: column;
  }
  .right-section {
    order: -1;
  }
}
</style>

