<template>
  <div class="demo-table-wrapper">
    <table :class="['demo-table', { 'demo-table--striped': striped, 'demo-table--hoverable': hoverable }]">
      <thead>
        <tr>
          <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, index) in data" :key="index">
          <td v-for="col in columns" :key="col.key">{{ row[col.key] }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  columns: {
    type: Array,
    default: () => [
      { key: 'name', label: 'Name' },
      { key: 'email', label: 'Email' },
      { key: 'role', label: 'Role' }
    ]
  },
  data: {
    type: Array,
    default: () => [
      { name: 'John Doe', email: 'john@example.com', role: 'Admin' },
      { name: 'Jane Smith', email: 'jane@example.com', role: 'User' },
      { name: 'Bob Wilson', email: 'bob@example.com', role: 'Editor' }
    ]
  },
  striped: { type: Boolean, default: false },
  hoverable: { type: Boolean, default: true }
})
</script>

<style scoped>
.demo-table-wrapper {
  overflow-x: auto;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
}

.demo-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.875rem;
}

.demo-table th {
  padding: 0.75rem 1rem;
  text-align: left;
  font-weight: 600;
  color: #374151;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.demo-table td {
  padding: 0.75rem 1rem;
  color: #6b7280;
  border-bottom: 1px solid #e5e7eb;
}

.demo-table tbody tr:last-child td {
  border-bottom: none;
}

.demo-table--striped tbody tr:nth-child(even) {
  background: #f9fafb;
}

.demo-table--hoverable tbody tr:hover {
  background: #f3f4f6;
}
</style>
