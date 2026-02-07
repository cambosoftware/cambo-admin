<template>
  <div class="demo-step-wizard">
    <div class="demo-step-wizard__steps">
      <div
        v-for="(step, index) in steps"
        :key="index"
        :class="[
          'demo-step-wizard__step',
          {
            'demo-step-wizard__step--completed': index < currentStep,
            'demo-step-wizard__step--active': index === currentStep,
            'demo-step-wizard__step--upcoming': index > currentStep
          }
        ]"
      >
        <div class="demo-step-wizard__indicator">
          <span v-if="index < currentStep" class="demo-step-wizard__check">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </span>
          <span v-else>{{ index + 1 }}</span>
        </div>
        <div class="demo-step-wizard__info">
          <div class="demo-step-wizard__title">{{ step.title }}</div>
          <div v-if="step.description" class="demo-step-wizard__description">{{ step.description }}</div>
        </div>
        <div v-if="index < steps.length - 1" class="demo-step-wizard__connector"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  steps: {
    type: Array,
    default: () => [
      { title: 'Account', description: 'Create your account' },
      { title: 'Profile', description: 'Set up your profile' },
      { title: 'Review', description: 'Review and submit' }
    ]
  },
  currentStep: { type: Number, default: 1 }
})
</script>

<style scoped>
.demo-step-wizard__steps {
  display: flex;
  gap: 0.5rem;
}

.demo-step-wizard__step {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex: 1;
  position: relative;
}

.demo-step-wizard__indicator {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.5rem;
  height: 2.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  border-radius: 9999px;
  flex-shrink: 0;
}

.demo-step-wizard__step--upcoming .demo-step-wizard__indicator {
  background: #f3f4f6;
  color: #9ca3af;
}

.demo-step-wizard__step--active .demo-step-wizard__indicator {
  background: #6366f1;
  color: white;
}

.demo-step-wizard__step--completed .demo-step-wizard__indicator {
  background: #10b981;
  color: white;
}

.demo-step-wizard__check svg {
  width: 1rem;
  height: 1rem;
}

.demo-step-wizard__info {
  flex: 1;
}

.demo-step-wizard__title {
  font-size: 0.875rem;
  font-weight: 500;
  color: #111827;
}

.demo-step-wizard__step--upcoming .demo-step-wizard__title {
  color: #9ca3af;
}

.demo-step-wizard__description {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-step-wizard__connector {
  position: absolute;
  left: calc(2.5rem + 0.75rem);
  right: 0;
  top: 50%;
  height: 2px;
  background: #e5e7eb;
  transform: translateY(-50%);
  z-index: -1;
}

.demo-step-wizard__step--completed .demo-step-wizard__connector {
  background: #10b981;
}
</style>
