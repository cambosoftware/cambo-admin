# Layout

Core layout components for admin panel structure.

## Components

| Component | Description |
|-----------|-------------|
| [AdminLayout](./admin-layout.md) | Main admin layout wrapper |
| [Sidebar](./sidebar.md) | Side navigation panel |
| [Navbar](./navbar.md) | Top navigation bar |
| [Breadcrumb](./breadcrumb.md) | Page location indicator |
| [PageHeader](./page-header.md) | Page title and actions |
| [Container](./container.md) | Content container |

## Usage

### AdminLayout

The main layout wraps your entire admin interface:

```vue
<template>
  <AdminLayout title="Dashboard">
    <!-- Page content -->
  </AdminLayout>
</template>
```

### With Sidebar Navigation

```vue
<AdminLayout>
  <template #sidebar>
    <Sidebar>
      <NavLink href="/dashboard" :icon="HomeIcon">Dashboard</NavLink>
      <NavLink href="/users" :icon="UsersIcon">Users</NavLink>
      <NavGroup title="Settings" :icon="CogIcon">
        <NavLink href="/settings/general">General</NavLink>
        <NavLink href="/settings/security">Security</NavLink>
      </NavGroup>
    </Sidebar>
  </template>

  <PageHeader title="Dashboard">
    <template #actions>
      <Button variant="primary">Create New</Button>
    </template>
  </PageHeader>

  <Container>
    <!-- Dashboard content -->
  </Container>
</AdminLayout>
```

### Breadcrumb

```vue
<Breadcrumb :items="[
  { label: 'Home', href: '/' },
  { label: 'Users', href: '/users' },
  { label: 'John Doe' },
]" />
```
