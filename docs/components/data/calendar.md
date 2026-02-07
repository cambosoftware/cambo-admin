# Calendar

A full-featured calendar component for displaying and managing events with month navigation, event display, and add event functionality.

## Import

```js
import { Calendar } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `events` | `Array` | `[]` | Array of events: `{ id, title, date (YYYY-MM-DD), endDate?, color?, allDay? }` |
| `initialDate` | `Date\|String` | `new Date()` | Initial date to display |
| `weekStartsOn` | `Number` | `1` | First day of week: `0` (Sunday) or `1` (Monday) |
| `locale` | `String` | `'fr-FR'` | Locale for date formatting |
| `allowAdd` | `Boolean` | `true` | Show add event button on cells |
| `showWeekNumbers` | `Boolean` | `false` | Show week numbers column |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `date-click` | `{ date, dateStr, events }` | Emitted when a date cell is clicked |
| `event-click` | `{ event, date }` | Emitted when an event is clicked |
| `month-change` | `{ year, month }` | Emitted when navigating to a different month |
| `add-event` | `{ date, dateStr }` | Emitted when add button is clicked |

## Basic Example

```vue
<template>
  <Calendar
    :events="events"
    @date-click="handleDateClick"
    @event-click="handleEventClick"
  />
</template>

<script setup>
import { ref } from 'vue'
import { Calendar } from '@cambosoftware/cambo-admin'

const events = ref([
  { id: 1, title: 'Team Meeting', date: '2024-02-05', color: 'blue' },
  { id: 2, title: 'Project Deadline', date: '2024-02-15', color: 'red' },
  { id: 3, title: 'Conference', date: '2024-02-20', endDate: '2024-02-22', color: 'green' },
])

const handleDateClick = ({ date, dateStr, events }) => {
  console.log('Clicked date:', dateStr, 'Events:', events)
}

const handleEventClick = ({ event, date }) => {
  console.log('Clicked event:', event)
}
</script>
```

## With Event Colors

```vue
<template>
  <Calendar :events="events" />
</template>

<script setup>
const events = [
  // Named colors
  { id: 1, title: 'Meeting', date: '2024-02-05', color: 'blue' },
  { id: 2, title: 'Deadline', date: '2024-02-10', color: 'red' },
  { id: 3, title: 'Holiday', date: '2024-02-14', color: 'pink' },
  { id: 4, title: 'Training', date: '2024-02-20', color: 'green' },

  // Custom hex colors
  { id: 5, title: 'Custom Event', date: '2024-02-25', color: '#8B5CF6' },
]

// Available named colors: red, orange, amber, green, teal, blue, indigo, purple, pink
</script>
```

## Multi-Day Events

```vue
<template>
  <Calendar :events="events" />
</template>

<script setup>
const events = [
  {
    id: 1,
    title: 'Conference',
    date: '2024-02-10',
    endDate: '2024-02-13',
    color: 'indigo'
  },
  {
    id: 2,
    title: 'Vacation',
    date: '2024-02-20',
    endDate: '2024-02-25',
    color: 'teal'
  }
]
</script>
```

## With Week Numbers

```vue
<template>
  <Calendar
    :events="events"
    show-week-numbers
  />
</template>
```

## Week Starting on Sunday

```vue
<template>
  <Calendar
    :events="events"
    :week-starts-on="0"
    locale="en-US"
  />
</template>
```

## With Add Event Handler

```vue
<template>
  <Calendar
    :events="events"
    allow-add
    @add-event="openAddModal"
  />

  <Modal v-model:show="showModal" title="Add Event">
    <Form @submit="createEvent">
      <FormGroup label="Title">
        <Input v-model="newEvent.title" required />
      </FormGroup>
      <FormGroup label="Date">
        <DatePicker v-model="newEvent.date" disabled />
      </FormGroup>
      <FormGroup label="Color">
        <Select v-model="newEvent.color" :options="colorOptions" />
      </FormGroup>
    </Form>
    <template #footer>
      <Button variant="secondary" @click="showModal = false">Cancel</Button>
      <Button @click="createEvent">Add Event</Button>
    </template>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'

const events = ref([])
const showModal = ref(false)
const newEvent = ref({ title: '', date: '', color: 'blue' })

const colorOptions = [
  { value: 'blue', label: 'Blue' },
  { value: 'red', label: 'Red' },
  { value: 'green', label: 'Green' },
  { value: 'purple', label: 'Purple' },
]

const openAddModal = ({ dateStr }) => {
  newEvent.value = { title: '', date: dateStr, color: 'blue' }
  showModal.value = true
}

const createEvent = () => {
  events.value.push({
    id: Date.now(),
    ...newEvent.value
  })
  showModal.value = false
}
</script>
```

## Disable Add Button

```vue
<template>
  <Calendar
    :events="events"
    :allow-add="false"
  />
</template>
```

## Handle Month Navigation

```vue
<template>
  <Calendar
    :events="events"
    :initial-date="currentMonth"
    @month-change="loadEventsForMonth"
  />
</template>

<script setup>
import { ref } from 'vue'

const events = ref([])
const currentMonth = ref(new Date())

const loadEventsForMonth = async ({ year, month }) => {
  // Fetch events from API for the new month
  const response = await fetch(`/api/events?year=${year}&month=${month + 1}`)
  events.value = await response.json()
}
</script>
```

## Custom Locale

```vue
<template>
  <!-- French -->
  <Calendar :events="events" locale="fr-FR" />

  <!-- English US -->
  <Calendar :events="events" locale="en-US" />

  <!-- German -->
  <Calendar :events="events" locale="de-DE" />
</template>
```

## Event Management Example

```vue
<template>
  <div class="space-y-4">
    <div class="flex justify-between items-center">
      <h2 class="text-xl font-semibold">Team Calendar</h2>
      <Button @click="showAddModal = true">
        <PlusIcon class="h-4 w-4 mr-2" />
        Add Event
      </Button>
    </div>

    <Calendar
      :events="events"
      show-week-numbers
      @event-click="openEventDetail"
      @date-click="handleDateClick"
    />

    <!-- Event Detail Modal -->
    <Modal v-model:show="showDetailModal" :title="selectedEvent?.title">
      <DescriptionList :items="eventDetails" />
      <template #footer>
        <Button variant="danger" @click="deleteEvent">Delete</Button>
        <Button @click="editEvent">Edit</Button>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const events = ref([
  { id: 1, title: 'Sprint Planning', date: '2024-02-05', color: 'blue' },
  { id: 2, title: 'Code Review', date: '2024-02-07', color: 'green' },
  { id: 3, title: 'Release v2.0', date: '2024-02-15', color: 'red' },
])

const showDetailModal = ref(false)
const selectedEvent = ref(null)

const eventDetails = computed(() => {
  if (!selectedEvent.value) return []
  return [
    { label: 'Title', value: selectedEvent.value.title },
    { label: 'Date', value: selectedEvent.value.date },
    { label: 'End Date', value: selectedEvent.value.endDate || 'N/A' },
  ]
})

const openEventDetail = ({ event }) => {
  selectedEvent.value = event
  showDetailModal.value = true
}

const deleteEvent = () => {
  events.value = events.value.filter(e => e.id !== selectedEvent.value.id)
  showDetailModal.value = false
}
</script>
```

## Playground

Try the Calendar component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
    <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
      <button style="padding: 4px 8px; border: 1px solid #e2e8f0; border-radius: 4px; background: white;">&larr;</button>
      <span style="font-weight: 600;">February 2024</span>
      <button style="padding: 4px 8px; border: 1px solid #e2e8f0; border-radius: 4px; background: white;">&rarr;</button>
    </div>
    <div style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: 12px; color: #64748b; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
      <div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div><div>Sun</div>
    </div>
    <div style="display: grid; grid-template-columns: repeat(7, 1fr); font-size: 14px;">
      <div style="padding: 8px; text-align: center; color: #cbd5e1;">29</div>
      <div style="padding: 8px; text-align: center; color: #cbd5e1;">30</div>
      <div style="padding: 8px; text-align: center; color: #cbd5e1;">31</div>
      <div style="padding: 8px; text-align: center;">1</div>
      <div style="padding: 8px; text-align: center;">2</div>
      <div style="padding: 8px; text-align: center;">3</div>
      <div style="padding: 8px; text-align: center;">4</div>
      <div style="padding: 8px; text-align: center; position: relative;">5<div style="background: #3b82f6; color: white; font-size: 10px; padding: 2px 4px; border-radius: 2px; margin-top: 2px;">Meeting</div></div>
      <div style="padding: 8px; text-align: center;">6</div>
      <div style="padding: 8px; text-align: center; background: #eff6ff; border-radius: 4px; font-weight: 600; color: #3b82f6;">7</div>
      <div style="padding: 8px; text-align: center;">8</div>
      <div style="padding: 8px; text-align: center;">9</div>
      <div style="padding: 8px; text-align: center;">10</div>
      <div style="padding: 8px; text-align: center;">11</div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Calendar
    :events="events"
    @date-click="handleDateClick"
    @event-click="handleEventClick"
  />
</template>

<script setup>
import { ref } from 'vue'
import { Calendar } from '@cambosoftware/cambo-admin'

const events = ref([
  { id: 1, title: 'Team Meeting', date: '2024-02-05', color: 'blue' },
  { id: 2, title: 'Project Deadline', date: '2024-02-15', color: 'red' },
  { id: 3, title: 'Conference', date: '2024-02-20', color: 'green' }
])

const handleDateClick = ({ date, dateStr }) => {
  console.log('Clicked:', dateStr)
}

const handleEventClick = ({ event }) => {
  console.log('Event:', event.title)
}
</script>
```

  </template>
</LiveDemo>
