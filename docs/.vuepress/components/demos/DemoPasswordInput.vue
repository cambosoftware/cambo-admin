<template>
  <div class="demo-password-showcase">
    <span class="demo-label">Password:</span>
    <div class="demo-password">
      <input :type="showPassword ? 'text' : 'password'" v-model="password" placeholder="Enter password..." />
      <button class="demo-password__toggle" @click="showPassword = !showPassword">
        <svg v-if="showPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
          <line x1="1" y1="1" x2="23" y2="23"/>
        </svg>
        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </button>
    </div>
    <div class="demo-password__strength">
      <div class="demo-password__strength-bars">
        <div v-for="i in 4" :key="i" :class="['demo-password__strength-bar', { 'demo-password__strength-bar--filled': i <= strength }]" :style="{ background: i <= strength ? strengthColor : undefined }"></div>
      </div>
      <span class="demo-password__strength-text" :style="{ color: strengthColor }">{{ strengthText }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const password = ref('MyPass123')
const showPassword = ref(false)

const strength = computed(() => {
  if (!password.value) return 0
  let score = 0
  if (password.value.length >= 8) score++
  if (/[a-z]/.test(password.value) && /[A-Z]/.test(password.value)) score++
  if (/\d/.test(password.value)) score++
  if (/[^a-zA-Z0-9]/.test(password.value)) score++
  return score
})

const strengthText = computed(() => ['', 'Weak', 'Fair', 'Good', 'Strong'][strength.value] || '')
const strengthColor = computed(() => ['', '#ef4444', '#f97316', '#eab308', '#22c55e'][strength.value] || '')
</script>

<style scoped>
.demo-password-showcase { display: flex; flex-direction: column; gap: 0.5rem; width: 100%; max-width: 20rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-password { display: flex; align-items: center; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; }
.demo-password:focus-within { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
.demo-password input { flex: 1; padding: 0.5rem 0.75rem; font-size: 0.875rem; border: none; outline: none; }
.demo-password__toggle { display: flex; padding: 0.5rem; background: none; border: none; cursor: pointer; color: #9ca3af; }
.demo-password__toggle:hover { color: #6b7280; }
.demo-password__toggle svg { width: 1.25rem; height: 1.25rem; }
.demo-password__strength { display: flex; align-items: center; gap: 0.5rem; }
.demo-password__strength-bars { display: flex; gap: 0.25rem; flex: 1; }
.demo-password__strength-bar { height: 0.25rem; flex: 1; background: #e5e7eb; border-radius: 0.125rem; transition: background 0.15s; }
.demo-password__strength-text { font-size: 0.75rem; font-weight: 500; min-width: 3rem; }
</style>
