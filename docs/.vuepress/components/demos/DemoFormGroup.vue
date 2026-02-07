<template>
  <div class="demo-form-group-showcase">
    <!-- Standard FormGroup -->
    <div class="demo-form-group">
      <label class="demo-form-group__label">
        Full Name
        <span class="demo-form-group__required">*</span>
      </label>
      <input
        v-model="name"
        type="text"
        class="demo-input"
        placeholder="Enter your name"
      />
    </div>

    <!-- FormGroup with hint -->
    <div class="demo-form-group">
      <label class="demo-form-group__label">Email</label>
      <input
        v-model="email"
        type="email"
        class="demo-input"
        placeholder="email@example.com"
      />
      <p class="demo-form-group__hint">We'll never share your email</p>
    </div>

    <!-- FormGroup with error -->
    <div class="demo-form-group demo-form-group--error">
      <label class="demo-form-group__label">Password</label>
      <input
        type="password"
        class="demo-input demo-input--error"
        placeholder="Enter password"
        value="123"
      />
      <p class="demo-form-group__error-text">Password must be at least 8 characters</p>
    </div>

    <!-- Inline FormGroup -->
    <div class="demo-form-group demo-form-group--inline">
      <label class="demo-form-group__label">Company</label>
      <input
        v-model="company"
        type="text"
        class="demo-input"
        placeholder="Company name"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const company = ref('')
</script>

<style scoped>
.demo-form-group-showcase {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  width: 100%;
  max-width: 24rem;
}

.demo-form-group {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.demo-form-group--inline {
  flex-direction: row;
  align-items: center;
  gap: 1rem;
}

.demo-form-group--inline .demo-form-group__label {
  min-width: 5rem;
  text-align: right;
}

.demo-form-group__label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.demo-form-group__required {
  color: #ef4444;
  margin-left: 0.125rem;
}

.demo-input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  outline: none;
  transition: border-color 0.15s, box-shadow 0.15s;
}

.demo-input:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-input--error {
  border-color: #ef4444;
}

.demo-input--error:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.demo-form-group__hint {
  font-size: 0.75rem;
  color: #6b7280;
  margin: 0;
}

.demo-form-group__error-text {
  font-size: 0.75rem;
  color: #ef4444;
  margin: 0;
}
</style>
