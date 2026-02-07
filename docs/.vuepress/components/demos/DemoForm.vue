<template>
  <div class="demo-form-showcase">
    <form class="demo-form" @submit.prevent="handleSubmit">
      <div class="demo-form-group">
        <label class="demo-form-label">Name</label>
        <input v-model="name" type="text" class="demo-input" placeholder="Your name" />
      </div>
      <div class="demo-form-group">
        <label class="demo-form-label">Email</label>
        <input v-model="email" type="email" class="demo-input" placeholder="email@example.com" />
      </div>
      <button type="submit" class="demo-submit-btn" :disabled="isSubmitting">
        {{ isSubmitting ? 'Submitting...' : 'Submit' }}
      </button>
    </form>
    <div v-if="submitted" class="demo-form-result">
      Form submitted with: {{ name }} ({{ email }})
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const isSubmitting = ref(false)
const submitted = ref(false)

const handleSubmit = () => {
  isSubmitting.value = true
  setTimeout(() => {
    isSubmitting.value = false
    submitted.value = true
    setTimeout(() => submitted.value = false, 3000)
  }, 1000)
}
</script>

<style scoped>
.demo-form-showcase {
  width: 100%;
  max-width: 20rem;
}

.demo-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.demo-form-group {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.demo-form-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.demo-input {
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  outline: none;
}

.demo-input:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-submit-btn {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: white;
  background: #6366f1;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
}

.demo-submit-btn:hover {
  background: #4f46e5;
}

.demo-submit-btn:disabled {
  opacity: 0.6;
  cursor: wait;
}

.demo-form-result {
  margin-top: 1rem;
  padding: 0.75rem;
  font-size: 0.875rem;
  color: #065f46;
  background: #d1fae5;
  border-radius: 0.375rem;
}
</style>
