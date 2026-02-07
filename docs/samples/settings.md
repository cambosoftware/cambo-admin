# Settings Page

A comprehensive settings page with tabbed sections for profile, security, notifications, and preferences.

## Preview

<div style="background: #f3f4f6; padding: 20px; border-radius: 12px;">
  <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="border-bottom: 1px solid #e5e7eb; padding: 0 16px; display: flex; gap: 24px;">
      <div style="padding: 16px 0; border-bottom: 2px solid #4f46e5; color: #4f46e5; font-weight: 500; margin-bottom: -1px;">Profile</div>
      <div style="padding: 16px 0; color: #6b7280;">Security</div>
      <div style="padding: 16px 0; color: #6b7280;">Notifications</div>
      <div style="padding: 16px 0; color: #6b7280;">Preferences</div>
    </div>
    <div style="padding: 24px;">
      <h3 style="font-weight: 600; margin-bottom: 16px;">Profile Information</h3>
      <div style="display: grid; gap: 16px; max-width: 500px;">
        <div>
          <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">Full Name</label>
          <input type="text" value="John Doe" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" />
        </div>
        <div>
          <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">Email</label>
          <input type="email" value="john@example.com" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" />
        </div>
      </div>
    </div>
  </div>
</div>

## Full Code

```vue
<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
    AdminLayout,
    PageHeader,
    Card,
    Tabs,
    Tab,
    Form,
    FormGroup,
    Input,
    Textarea,
    Select,
    Switch,
    Checkbox,
    CheckboxGroup,
    Button,
    Avatar,
    ImagePicker,
    PasswordInput,
    Alert
} from '@cambosoftware/cambo-admin'

const props = defineProps({
    user: Object,
    timezones: Array,
    languages: Array
})

const activeTab = ref('profile')

// Profile Form
const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone || '',
    bio: props.user.bio || '',
    avatar: null
})

// Security Form
const securityForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
})

// Notifications Form
const notificationsForm = useForm({
    email_notifications: props.user.settings?.email_notifications ?? true,
    push_notifications: props.user.settings?.push_notifications ?? true,
    sms_notifications: props.user.settings?.sms_notifications ?? false,
    notification_types: props.user.settings?.notification_types ?? ['orders', 'news']
})

// Preferences Form
const preferencesForm = useForm({
    timezone: props.user.settings?.timezone ?? 'UTC',
    language: props.user.settings?.language ?? 'en',
    date_format: props.user.settings?.date_format ?? 'MM/DD/YYYY',
    dark_mode: props.user.settings?.dark_mode ?? false
})

const saveProfile = () => {
    profileForm.post(route('settings.profile.update'), {
        preserveScroll: true
    })
}

const changePassword = () => {
    securityForm.put(route('settings.password.update'), {
        preserveScroll: true,
        onSuccess: () => securityForm.reset()
    })
}

const saveNotifications = () => {
    notificationsForm.put(route('settings.notifications.update'), {
        preserveScroll: true
    })
}

const savePreferences = () => {
    preferencesForm.put(route('settings.preferences.update'), {
        preserveScroll: true
    })
}

const notificationOptions = [
    { value: 'orders', label: 'Order updates' },
    { value: 'news', label: 'News and announcements' },
    { value: 'marketing', label: 'Marketing emails' },
    { value: 'security', label: 'Security alerts' }
]

const dateFormatOptions = [
    { value: 'MM/DD/YYYY', label: 'MM/DD/YYYY' },
    { value: 'DD/MM/YYYY', label: 'DD/MM/YYYY' },
    { value: 'YYYY-MM-DD', label: 'YYYY-MM-DD' }
]
</script>

<template>
    <AdminLayout title="Settings">
        <PageHeader
            title="Settings"
            subtitle="Manage your account settings and preferences"
        />

        <Card>
            <Tabs v-model="activeTab">
                <Tab name="profile" label="Profile">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-6">Profile Information</h3>

                        <Form :form="profileForm" @submit="saveProfile">
                            <!-- Avatar -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Profile Photo
                                </label>
                                <div class="flex items-center gap-6">
                                    <Avatar
                                        :src="user.avatar_url"
                                        :name="user.name"
                                        size="xl"
                                    />
                                    <div>
                                        <ImagePicker
                                            v-model="profileForm.avatar"
                                            :max-size="2"
                                            class="mb-2"
                                        />
                                        <p class="text-xs text-gray-500">
                                            JPG, PNG or GIF. Max 2MB.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl">
                                <FormGroup label="Full Name" :error="profileForm.errors.name" required>
                                    <Input v-model="profileForm.name" />
                                </FormGroup>

                                <FormGroup label="Email" :error="profileForm.errors.email" required>
                                    <Input v-model="profileForm.email" type="email" />
                                </FormGroup>

                                <FormGroup label="Phone" :error="profileForm.errors.phone">
                                    <Input v-model="profileForm.phone" type="tel" />
                                </FormGroup>

                                <div class="md:col-span-2">
                                    <FormGroup label="Bio" :error="profileForm.errors.bio">
                                        <Textarea
                                            v-model="profileForm.bio"
                                            :rows="4"
                                            placeholder="Tell us about yourself..."
                                        />
                                    </FormGroup>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <Button
                                    type="submit"
                                    variant="primary"
                                    :loading="profileForm.processing"
                                >
                                    Save Changes
                                </Button>
                            </div>
                        </Form>
                    </div>
                </Tab>

                <Tab name="security" label="Security">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-6">Change Password</h3>

                        <Form :form="securityForm" @submit="changePassword">
                            <div class="max-w-md space-y-6">
                                <FormGroup
                                    label="Current Password"
                                    :error="securityForm.errors.current_password"
                                    required
                                >
                                    <PasswordInput v-model="securityForm.current_password" />
                                </FormGroup>

                                <FormGroup
                                    label="New Password"
                                    :error="securityForm.errors.password"
                                    required
                                >
                                    <PasswordInput
                                        v-model="securityForm.password"
                                        strength-meter
                                    />
                                </FormGroup>

                                <FormGroup
                                    label="Confirm New Password"
                                    :error="securityForm.errors.password_confirmation"
                                    required
                                >
                                    <PasswordInput v-model="securityForm.password_confirmation" />
                                </FormGroup>

                                <Button
                                    type="submit"
                                    variant="primary"
                                    :loading="securityForm.processing"
                                >
                                    Update Password
                                </Button>
                            </div>
                        </Form>

                        <!-- Two-Factor Authentication -->
                        <div class="mt-10 pt-10 border-t border-gray-200">
                            <h3 class="text-lg font-semibold mb-2">Two-Factor Authentication</h3>
                            <p class="text-gray-600 mb-4">
                                Add an extra layer of security to your account.
                            </p>
                            <Button variant="outline">Enable 2FA</Button>
                        </div>

                        <!-- Sessions -->
                        <div class="mt-10 pt-10 border-t border-gray-200">
                            <h3 class="text-lg font-semibold mb-2">Active Sessions</h3>
                            <p class="text-gray-600 mb-4">
                                Manage your active sessions on other browsers and devices.
                            </p>
                            <Button variant="outline" class="text-red-600 border-red-300 hover:bg-red-50">
                                Log Out Other Sessions
                            </Button>
                        </div>
                    </div>
                </Tab>

                <Tab name="notifications" label="Notifications">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-6">Notification Preferences</h3>

                        <Form :form="notificationsForm" @submit="saveNotifications">
                            <div class="space-y-6 max-w-md">
                                <div class="space-y-4">
                                    <h4 class="font-medium text-gray-900">Channels</h4>

                                    <Switch
                                        v-model="notificationsForm.email_notifications"
                                        label="Email Notifications"
                                        description="Receive notifications via email"
                                    />

                                    <Switch
                                        v-model="notificationsForm.push_notifications"
                                        label="Push Notifications"
                                        description="Receive push notifications in browser"
                                    />

                                    <Switch
                                        v-model="notificationsForm.sms_notifications"
                                        label="SMS Notifications"
                                        description="Receive important alerts via SMS"
                                    />
                                </div>

                                <div class="pt-6 border-t border-gray-200">
                                    <h4 class="font-medium text-gray-900 mb-4">Notification Types</h4>
                                    <CheckboxGroup
                                        v-model="notificationsForm.notification_types"
                                        :options="notificationOptions"
                                    />
                                </div>

                                <Button
                                    type="submit"
                                    variant="primary"
                                    :loading="notificationsForm.processing"
                                >
                                    Save Preferences
                                </Button>
                            </div>
                        </Form>
                    </div>
                </Tab>

                <Tab name="preferences" label="Preferences">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-6">Application Preferences</h3>

                        <Form :form="preferencesForm" @submit="savePreferences">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl">
                                <FormGroup label="Timezone">
                                    <Select
                                        v-model="preferencesForm.timezone"
                                        :options="timezones"
                                    />
                                </FormGroup>

                                <FormGroup label="Language">
                                    <Select
                                        v-model="preferencesForm.language"
                                        :options="languages"
                                    />
                                </FormGroup>

                                <FormGroup label="Date Format">
                                    <Select
                                        v-model="preferencesForm.date_format"
                                        :options="dateFormatOptions"
                                    />
                                </FormGroup>

                                <div class="md:col-span-2">
                                    <Switch
                                        v-model="preferencesForm.dark_mode"
                                        label="Dark Mode"
                                        description="Use dark theme across the application"
                                    />
                                </div>
                            </div>

                            <div class="mt-6">
                                <Button
                                    type="submit"
                                    variant="primary"
                                    :loading="preferencesForm.processing"
                                >
                                    Save Preferences
                                </Button>
                            </div>
                        </Form>
                    </div>
                </Tab>
            </Tabs>
        </Card>

        <!-- Danger Zone -->
        <Card class="mt-6 border-red-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-red-600 mb-2">Danger Zone</h3>
                <p class="text-gray-600 mb-4">
                    Once you delete your account, there is no going back. Please be certain.
                </p>
                <Button variant="danger" outline>Delete Account</Button>
            </div>
        </Card>
    </AdminLayout>
</template>
```

## Laravel Controller

```php
// app/Http/Controllers/SettingsController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use DateTimeZone;

class SettingsController extends Controller
{
    public function index()
    {
        return inertia('Settings', [
            'user' => auth()->user(),
            'timezones' => $this->getTimezones(),
            'languages' => $this->getLanguages(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'notification_types' => 'array',
        ]);

        auth()->user()->settings()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return back()->with('success', 'Notification preferences updated.');
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'timezone' => 'required|string',
            'language' => 'required|string|size:2',
            'date_format' => 'required|string',
            'dark_mode' => 'boolean',
        ]);

        auth()->user()->settings()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return back()->with('success', 'Preferences updated.');
    }

    private function getTimezones(): array
    {
        return collect(DateTimeZone::listIdentifiers())
            ->map(fn($tz) => ['value' => $tz, 'label' => $tz])
            ->values()
            ->toArray();
    }

    private function getLanguages(): array
    {
        return [
            ['value' => 'en', 'label' => 'English'],
            ['value' => 'fr', 'label' => 'French'],
            ['value' => 'es', 'label' => 'Spanish'],
            ['value' => 'de', 'label' => 'German'],
            ['value' => 'zh', 'label' => 'Chinese'],
        ];
    }
}
```

## Routes

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::put('/settings/preferences', [SettingsController::class, 'updatePreferences'])->name('settings.preferences.update');
});
```
