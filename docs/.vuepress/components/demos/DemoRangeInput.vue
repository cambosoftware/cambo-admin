<template>
  <div class="demo-range-showcase">
    <div class="demo-range-item">
      <div class="demo-range-input__header">
        <span class="demo-label">Price Range</span>
        <span class="demo-range-input__value">${{ range[0] }} - ${{ range[1] }}</span>
      </div>
      <div class="demo-range-input">
        <div class="demo-range-input__track">
          <div
            class="demo-range-input__fill"
            :style="{ left: minPercentage + '%', width: (maxPercentage - minPercentage) + '%' }"
          ></div>
          <input
            type="range"
            class="demo-range-input__input"
            :value="range[0]"
            min="0"
            max="1000"
            step="10"
            @input="updateMin($event.target.value)"
          />
          <input
            type="range"
            class="demo-range-input__input"
            :value="range[1]"
            min="0"
            max="1000"
            step="10"
            @input="updateMax($event.target.value)"
          />
        </div>
      </div>
      <div class="demo-range-input__minmax">
        <span>$0</span>
        <span>$1000</span>
      </div>
    </div>

    <div class="demo-range-item">
      <div class="demo-range-input__header">
        <span class="demo-label">Age Range</span>
        <span class="demo-range-input__value">{{ ageRange[0] }} - {{ ageRange[1] }} years</span>
      </div>
      <div class="demo-range-input">
        <div class="demo-range-input__track">
          <div
            class="demo-range-input__fill"
            :style="{ left: ageMinPercentage + '%', width: (ageMaxPercentage - ageMinPercentage) + '%' }"
          ></div>
          <input
            type="range"
            class="demo-range-input__input"
            :value="ageRange[0]"
            min="18"
            max="80"
            @input="updateAgeMin($event.target.value)"
          />
          <input
            type="range"
            class="demo-range-input__input"
            :value="ageRange[1]"
            min="18"
            max="80"
            @input="updateAgeMax($event.target.value)"
          />
        </div>
      </div>
      <div class="demo-range-input__minmax">
        <span>18</span>
        <span>80</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const range = ref([200, 800])
const ageRange = ref([25, 55])

const minPercentage = computed(() => (range.value[0] / 1000) * 100)
const maxPercentage = computed(() => (range.value[1] / 1000) * 100)

const ageMinPercentage = computed(() => ((ageRange.value[0] - 18) / 62) * 100)
const ageMaxPercentage = computed(() => ((ageRange.value[1] - 18) / 62) * 100)

const updateMin = (value) => {
  const numValue = Number(value)
  if (numValue <= range.value[1]) {
    range.value = [numValue, range.value[1]]
  }
}

const updateMax = (value) => {
  const numValue = Number(value)
  if (numValue >= range.value[0]) {
    range.value = [range.value[0], numValue]
  }
}

const updateAgeMin = (value) => {
  const numValue = Number(value)
  if (numValue <= ageRange.value[1]) {
    ageRange.value = [numValue, ageRange.value[1]]
  }
}

const updateAgeMax = (value) => {
  const numValue = Number(value)
  if (numValue >= ageRange.value[0]) {
    ageRange.value = [ageRange.value[0], numValue]
  }
}
</script>

<style scoped>
.demo-range-showcase { display: flex; flex-direction: column; gap: 2rem; width: 100%; max-width: 20rem; }
.demo-range-item { display: flex; flex-direction: column; gap: 0.5rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }

.demo-range-input__header {
  display: flex;
  justify-content: space-between;
}

.demo-range-input__value {
  font-size: 0.875rem;
  font-weight: 600;
  color: #6366f1;
}

.demo-range-input {
  padding: 0.5rem 0;
}

.demo-range-input__track {
  position: relative;
  height: 0.5rem;
  background: #e5e7eb;
  border-radius: 0.25rem;
}

.demo-range-input__fill {
  position: absolute;
  top: 0;
  height: 100%;
  background: #6366f1;
  border-radius: 0.25rem;
  pointer-events: none;
}

.demo-range-input__input {
  position: absolute;
  top: 50%;
  left: 0;
  width: 100%;
  transform: translateY(-50%);
  appearance: none;
  background: transparent;
  cursor: pointer;
  pointer-events: none;
}

.demo-range-input__input::-webkit-slider-thumb {
  appearance: none;
  width: 1.25rem;
  height: 1.25rem;
  background: white;
  border: 2px solid #6366f1;
  border-radius: 50%;
  cursor: pointer;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  pointer-events: all;
}

.demo-range-input__input::-webkit-slider-thumb:hover {
  transform: scale(1.1);
}

.demo-range-input__input::-moz-range-thumb {
  width: 1.25rem;
  height: 1.25rem;
  background: white;
  border: 2px solid #6366f1;
  border-radius: 50%;
  cursor: pointer;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  pointer-events: all;
}

.demo-range-input__minmax {
  display: flex;
  justify-content: space-between;
  font-size: 0.75rem;
  color: #6b7280;
}
</style>
