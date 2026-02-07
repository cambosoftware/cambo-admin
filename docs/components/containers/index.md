# Containers

Layout containers for organizing content.

## Components

| Component | Description |
|-----------|-------------|
| [Card](./card.md) | Content container with header/footer |
| [CardGrid](./card-grid.md) | Responsive card grid layout |
| [Accordion](./accordion.md) | Collapsible content sections |
| [Tabs](./tabs.md) | Tabbed content navigation |
| [Collapse](./collapse.md) | Animated show/hide |
| [Panel](./panel.md) | Bordered content panel |

## Usage

### Cards

```vue
<Card>
  <template #header>
    <h3>Card Title</h3>
  </template>

  <p>Card content goes here.</p>

  <template #footer>
    <Button>Action</Button>
  </template>
</Card>
```

### Card Grid

```vue
<CardGrid :columns="3" gap="md">
  <Card v-for="item in items" :key="item.id">
    {{ item.title }}
  </Card>
</CardGrid>
```

### Tabs

```vue
<Tabs v-model="activeTab">
  <Tab name="general" label="General">
    General settings content
  </Tab>
  <Tab name="security" label="Security">
    Security settings content
  </Tab>
  <Tab name="notifications" label="Notifications">
    Notification preferences
  </Tab>
</Tabs>
```

### Accordion

```vue
<Accordion>
  <AccordionItem title="Section 1">
    Content for section 1
  </AccordionItem>
  <AccordionItem title="Section 2">
    Content for section 2
  </AccordionItem>
</Accordion>
```
