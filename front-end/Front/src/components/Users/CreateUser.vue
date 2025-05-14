<template>
  <div class="card mb-3 shadow-lg" style="border: 2px solid ; background-color: #f9f9f9;">
    <div class="card-body">
      <h5 class="card-title text-center mb-4" style="font-size: 1.75rem; color: dark;">Create New User</h5>
      <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
      <form @submit.prevent="createUser" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input v-model="newUser.name" type="text" class="form-control" id="name" required>
          <div class="invalid-feedback">Please provide a valid name.</div>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input v-model="newUser.email" type="email" class="form-control" id="email" required>
          <div class="invalid-feedback">Please provide a valid email address.</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input v-model="newUser.password" type="password" class="form-control" id="password" required>
          <div class="invalid-feedback">Password is required.</div>
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select v-model="newUser.role" class="form-control" id="role" required>
            <option value="admin">Admin</option>
            <option value="employer">Employer</option>
            <option value="candidate">Candidate</option>
          </select>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success btn-lg" style="width: 48%; transition: 0.3s;">Create</button>
          <router-link to="/users" class="btn btn-secondary btn-lg" style="width: 48%; transition: 0.3s;">Cancel</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      newUser: { name: '', email: '', password: '', role: 'candidate' },
      errorMessage: '',
    };
  },
  methods: {
    async createUser() {
      try {
        await axios.post('/api/admin/users/create', this.newUser);
        this.$router.push({ 
          name: 'users.index', 
          query: { success: 'User created successfully' }
        });
      } catch (error) {
        this.errorMessage = 'Error creating user: ' + (error.response?.data?.message || error.message);
      }
    },
  },
  mounted() {
    // Optional: Bootstrap form validation
    (function () {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
    })();
  }
};
</script>

<style scoped>
.card.mb-3 {
  border-radius: 15px;
  transition: transform 0.3s ease-in-out;
}

.card.mb-3:hover {
  transform: scale(1.03);
}

.card-body {
  padding: 2rem;
}

h5.card-title {
  font-weight: bold;
  color: #333;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.btn-success, .btn-secondary {
  font-weight: bold;
  font-size: 1rem;
  padding: 0.75rem 1.25rem;
  border-radius: 50px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  transition: 0.3s ease-in-out;
}

.btn-primary:hover, .btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
}

.form-control {
  border-radius: 8px;
  border: 1px solid #9aa8b7;
  padding: 0.75rem;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #7892ae;
  box-shadow: 0px 0px 8px rgba(0, 0, 255, 0.2);
}

.invalid-feedback {
  font-size: 0.875rem;
  color: red;
}

.needs-validation .form-control:valid {
  border-color: green;
}

</style>
