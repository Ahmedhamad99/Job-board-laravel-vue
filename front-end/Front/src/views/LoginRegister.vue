<template>
  <div class="container m-5  d-flex justify-content-center align-items-center min-vh-100 position-relative">
    <div class="card shadow-lg p-4 w-100 position-relative" style="max-width: 500px;">
      <!-- الرسائل الناجحة أو الفاشلة -->
      <div v-if="successMessage" class="toast-custom toast-success">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="toast-custom toast-error">
        {{ errorMessage }}
      </div>

      <h3 class="text-center mb-4">{{ isLogin ? 'Login' : 'Register' }}</h3>

      <form @submit.prevent="isLogin ? handleLogin() : handleRegister()">
        <div v-if="!isLogin" class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" v-model="form.name" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" v-model="form.email" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" v-model="form.password" required />
        </div>

        <div v-if="!isLogin" class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" class="form-control" v-model="form.password_confirmation" required />
        </div>

        <div v-if="!isLogin" class="mb-3">
          <label class="form-label">Role</label>
          <select class="form-select" v-model="form.role" required>
            <option disabled value="">Select Role</option>
            <option value="employer">Employer</option>
            <option value="candidate">Candidate</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          {{ isLogin ? 'Login' : 'Register' }}
        </button>
      </form>

      <div class="text-center mt-3">
        <span>
          {{ isLogin ? "Don't have an account?" : "Already have an account?" }}
        </span>
        <button class="btn btn-link" @click="toggleForm">
          {{ isLogin ? 'Register here' : 'Login here' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      isLogin: true,
      successMessage: '',
      errorMessage: '',
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
      }
    };
  },
  methods: {
    toggleForm() {
      this.isLogin = !this.isLogin;
      this.successMessage = '';
      this.errorMessage = '';
      this.form = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
      };
    },
    async handleRegister() {
      try {
        const res = await axios.post('/api/register', this.form);
        this.successMessage = res.data.message || 'Registration successful';
        this.errorMessage = '';
        this.toggleForm();
        setTimeout(() => this.successMessage = '', 3000);
      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'Registration failed';
        this.successMessage = '';
        setTimeout(() => this.errorMessage = '', 3000);
      }
    },
    async handleLogin() {
      try {
        const res = await axios.post('/api/login', {
          email: this.form.email,
          password: this.form.password,
        });
        localStorage.setItem('token', res.data.token);
        localStorage.setItem('userRole', res.data.user.role);
        this.successMessage = res.data.message || 'Login successful';
        this.errorMessage = '';

        const userRole = res.data.user.role;
        let redirectPath = '/dashboard';
        if (userRole === 'admin') redirectPath = '/dashboard';
        else if (userRole === 'employer' || userRole === 'candidate') redirectPath = '/profile';

        this.$router.push(redirectPath);
      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'Login failed';
        this.successMessage = '';
        setTimeout(() => this.errorMessage = '', 3000);
      }
    }
  }
};
</script>

<style scoped>
.card {
  border-radius: 25px;
  background: linear-gradient(135deg, #f1f1f1, #ffffff);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
  z-index: 2;
}

h3 {
  font-weight: 700;
  color: #4b0082;
}

.form-label {
  font-weight: 600;
  color: #333;
}

input.form-control,
select.form-select {
  border-radius: 12px;
  padding: 10px 15px;
  border: 1px solid #ccc;
  transition: border-color 0.3s, box-shadow 0.3s;
  background-color: #fff;
}

input.form-control:focus,
select.form-select:focus {
  border-color: #6c63ff;
  box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
}

button.btn-primary {
  border-radius: 12px;
  padding: 10px;
  font-weight: 600;
  background: linear-gradient(to right, #6c63ff, #7f53ac);
  border: none;
  transition: background 0.3s ease;
}

button.btn-primary:hover {
  background: linear-gradient(to right, #5a4fcf, #6d43b6);
}

button.btn-link {
  color: #6c63ff;
  text-decoration: none;
  font-weight: 600;
}

button.btn-link:hover {
  text-decoration: underline;
  color: #5a4fcf;
}

.toast-custom {
  position: absolute;
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 500;
  font-size: 14px;
  color: #fff;
  animation: fadeSlide 0.4s ease;
  z-index: 1000;
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.toast-success {
  background: linear-gradient(to right, #00c9a7, #92fe9d);
}

.toast-error {
  background: linear-gradient(to right, #ff6a6a, #ff4757);
}

@keyframes fadeSlide {
  from {
    opacity: 0;
    top: -40px;
  }
  to {
    opacity: 1;
    top: -20px;
  }
}


body {
  background: linear-gradient(135deg, #6c63ff, #7f53ac);
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  margin: 0;
}
</style>
