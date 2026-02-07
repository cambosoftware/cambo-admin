# Accordion

A collapsible content container that allows users to expand and collapse sections. Use with `AccordionItem` components.

## Import

```vue
<script setup>
import { Accordion, AccordionItem } from '@cambosoftware/cambo-admin'
</script>
```

## Accordion Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `multiple` | `Boolean` | `false` | Allow multiple items to be open simultaneously |
| `bordered` | `Boolean` | `true` | Show border and rounded corners |

## AccordionItem Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | **required** | Header title text |
| `subtitle` | `String` | `null` | Optional subtitle text below the title |
| `defaultOpen` | `Boolean` | `false` | Whether the item starts expanded |
| `disabled` | `Boolean` | `false` | Disable the item (cannot be toggled) |

## Slots

### Accordion Slots

| Slot | Description |
|------|-------------|
| `default` | AccordionItem components |

### AccordionItem Slots

| Slot | Description |
|------|-------------|
| `default` | Collapsible content |

## Events

The Accordion and AccordionItem components manage state internally and do not emit events.

## Examples

### Basic Accordion

```vue
<template>
    <Accordion>
        <AccordionItem title="Section 1">
            <p>Content for section 1.</p>
        </AccordionItem>
        <AccordionItem title="Section 2">
            <p>Content for section 2.</p>
        </AccordionItem>
        <AccordionItem title="Section 3">
            <p>Content for section 3.</p>
        </AccordionItem>
    </Accordion>
</template>
```

### Multiple Open Items

```vue
<template>
    <Accordion multiple>
        <AccordionItem title="Features" defaultOpen>
            <ul class="list-disc list-inside">
                <li>Feature 1</li>
                <li>Feature 2</li>
                <li>Feature 3</li>
            </ul>
        </AccordionItem>
        <AccordionItem title="Pricing">
            <p>Contact us for pricing information.</p>
        </AccordionItem>
        <AccordionItem title="Support">
            <p>24/7 support available.</p>
        </AccordionItem>
    </Accordion>
</template>
```

### With Subtitles

```vue
<template>
    <Accordion>
        <AccordionItem
            title="Personal Information"
            subtitle="Name, email, and contact details"
        >
            <form class="space-y-4">
                <Input label="Name" v-model="form.name" />
                <Input label="Email" v-model="form.email" type="email" />
            </form>
        </AccordionItem>
        <AccordionItem
            title="Billing Address"
            subtitle="Your billing information"
        >
            <form class="space-y-4">
                <Input label="Address" v-model="form.address" />
                <Input label="City" v-model="form.city" />
            </form>
        </AccordionItem>
    </Accordion>
</template>
```

### Default Open Item

```vue
<template>
    <Accordion>
        <AccordionItem title="Getting Started" defaultOpen>
            <p>Welcome! This section is open by default.</p>
        </AccordionItem>
        <AccordionItem title="Advanced Usage">
            <p>Advanced topics covered here.</p>
        </AccordionItem>
    </Accordion>
</template>
```

### Disabled Item

```vue
<template>
    <Accordion>
        <AccordionItem title="Available">
            <p>This item can be toggled.</p>
        </AccordionItem>
        <AccordionItem title="Locked (Pro Plan)" disabled>
            <p>This content requires a Pro subscription.</p>
        </AccordionItem>
    </Accordion>
</template>
```

### Without Border

```vue
<template>
    <Accordion :bordered="false">
        <AccordionItem title="Question 1">
            <p>Answer to question 1.</p>
        </AccordionItem>
        <AccordionItem title="Question 2">
            <p>Answer to question 2.</p>
        </AccordionItem>
    </Accordion>
</template>
```

### FAQ Section

```vue
<template>
    <Card title="Frequently Asked Questions">
        <Accordion multiple>
            <AccordionItem
                v-for="faq in faqs"
                :key="faq.id"
                :title="faq.question"
            >
                <p class="text-gray-600">{{ faq.answer }}</p>
            </AccordionItem>
        </Accordion>
    </Card>
</template>

<script setup>
const faqs = [
    {
        id: 1,
        question: 'How do I get started?',
        answer: 'Sign up for an account and follow the onboarding guide.'
    },
    {
        id: 2,
        question: 'What payment methods do you accept?',
        answer: 'We accept all major credit cards and PayPal.'
    },
    {
        id: 3,
        question: 'Can I cancel my subscription?',
        answer: 'Yes, you can cancel anytime from your account settings.'
    }
]
</script>
```

### Settings Panel

```vue
<template>
    <Accordion>
        <AccordionItem title="Notifications" subtitle="Configure how you receive alerts">
            <div class="space-y-3">
                <Toggle v-model="settings.emailNotifications" label="Email notifications" />
                <Toggle v-model="settings.pushNotifications" label="Push notifications" />
                <Toggle v-model="settings.smsNotifications" label="SMS notifications" />
            </div>
        </AccordionItem>
        <AccordionItem title="Privacy" subtitle="Control your data and visibility">
            <div class="space-y-3">
                <Toggle v-model="settings.publicProfile" label="Public profile" />
                <Toggle v-model="settings.showEmail" label="Show email" />
            </div>
        </AccordionItem>
    </Accordion>
</template>
```

## Styling

The Accordion uses these Tailwind CSS classes:

- Container: `divide-y divide-gray-200`
- Bordered: `border border-gray-200 rounded-xl overflow-hidden`
- Item header: `px-5 py-4 hover:bg-gray-50`
- Item content: `px-5 pb-4 text-sm text-gray-600`
- Disabled state: `opacity-50 cursor-not-allowed`
- Active state: `bg-gray-50`

## Animation

The AccordionItem content uses Vue's `<Transition>` component with:
- Enter: `duration-200 ease-out`
- Leave: `duration-150 ease-in`
- Max height animation from `0` to `max-h-96`

## Accessibility

- Each accordion item header is a `<button>` element
- Headers have proper focus states
- Disabled items have `disabled` attribute set
- Chevron icon rotates 180 degrees when expanded

## Playground

Try the accordion component:

<LiveDemo>
  <DemoAccordion />

  <template #code>

```vue
<Accordion>
  <AccordionItem title="Section 1">
    <p>Content for section 1.</p>
  </AccordionItem>
  <AccordionItem title="Section 2">
    <p>Content for section 2.</p>
  </AccordionItem>
  <AccordionItem title="Section 3">
    <p>Content for section 3.</p>
  </AccordionItem>
</Accordion>
```

  </template>
</LiveDemo>
