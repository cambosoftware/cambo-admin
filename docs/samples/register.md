# Register Page

A user registration page with name, email, password fields, and terms acceptance.

## Preview

<div style="max-width: 450px; margin: 0 auto; padding: 40px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px;">
  <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
    <div style="text-align: center; margin-bottom: 24px;">
      <h2 style="margin: 0; font-size: 20px; font-weight: 600;">Create an account</h2>
      <p style="margin: 4px 0 0; color: #6b7280; font-size: 14px;">Start your 14-day free trial</p>
    </div>
    <div style="margin-bottom: 16px;">
      <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">Full Name</label>
      <input type="text" placeholder="John Doe" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;" />
    </div>
    <div style="margin-bottom: 16px;">
      <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">Email</label>
      <input type="email" placeholder="you@example.com" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;" />
    </div>
    <div style="margin-bottom: 16px;">
      <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">Password</label>
      <input type="password" placeholder="••••••••" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;" />
    </div>
    <div style="margin-bottom: 20px;">
      <label style="display: flex; align-items: flex-start; gap: 8px; font-size: 14px; cursor: pointer;">
        <input type="checkbox" style="width: 16px; height: 16px; margin-top: 2px;" />
        <span>I agree to the <a href="#" style="color: #10b981;">Terms of Service</a> and <a href="#" style="color: #10b981;">Privacy Policy</a></span>
      </label>
    </div>
    <button style="width: 100%; padding: 10px 16px; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">Create account</button>
    <p style="text-align: center; margin-top: 16px; font-size: 14px; color: #6b7280;">Already have an account? <a href="#" style="color: #10b981; text-decoration: none;">Sign in</a></p>
  </div>
</div>

## Full Code

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import { Card, Form, FormGroup, Input, PasswordInput, Checkbox, Button, AppLink } from '@cambosoftware/cambo-admin'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false
})

const submit = () => {
    form.post(route('register'))
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-500 to-emerald-700 py-12 px-4">
        <div class="w-full max-w-md">
            <Card class="shadow-2xl">
                <div class="p-8">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Create an account</h2>
                        <p class="text-gray-600 mt-1">Start your 14-day free trial</p>
                    </div>

                    <!-- Registration Form -->
                    <Form :form="form" @submit="submit">
                        <FormGroup label="Full Name" :error="form.errors.name" required>
                            <Input
                                v-model="form.name"
                                placeholder="John Doe"
                                autofocus
                                autocomplete="name"
                            />
                        </FormGroup>

                        <FormGroup label="Email" :error="form.errors.email" required>
                            <Input
                                v-model="form.email"
                                type="email"
                                placeholder="you@example.com"
                                autocomplete="email"
                            />
                        </FormGroup>

                        <FormGroup label="Password" :error="form.errors.password" required>
                            <PasswordInput
                                v-model="form.password"
                                placeholder="Minimum 8 characters"
                                strength-meter
                                autocomplete="new-password"
                            />
                        </FormGroup>

                        <FormGroup label="Confirm Password" :error="form.errors.password_confirmation" required>
                            <PasswordInput
                                v-model="form.password_confirmation"
                                placeholder="Re-enter your password"
                                autocomplete="new-password"
                            />
                        </FormGroup>

                        <div class="mb-6">
                            <Checkbox v-model="form.terms">
                                <template #label>
                                    I agree to the
                                    <AppLink href="/terms" variant="primary">Terms of Service</AppLink>
                                    and
                                    <AppLink href="/privacy" variant="primary">Privacy Policy</AppLink>
                                </template>
                            </Checkbox>
                            <p v-if="form.errors.terms" class="text-sm text-red-600 mt-1">
                                {{ form.errors.terms }}
                            </p>
                        </div>

                        <Button
                            type="submit"
                            variant="primary"
                            class="w-full"
                            :loading="form.processing"
                        >
                            Create account
                        </Button>
                    </Form>

                    <!-- Sign In Link -->
                    <p class="mt-6 text-center text-sm text-gray-600">
                        Already have an account?
                        <AppLink :href="route('login')" variant="primary" class="font-medium">
                            Sign in
                        </AppLink>
                    </p>
                </div>
            </Card>

            <!-- Features -->
            <div class="mt-8 text-center text-white/90 text-sm">
                <p class="font-medium mb-2">What's included:</p>
                <ul class="space-y-1">
                    <li>✓ Unlimited projects</li>
                    <li>✓ 10 team members</li>
                    <li>✓ Advanced analytics</li>
                    <li>✓ Priority support</li>
                </ul>
            </div>
        </div>
    </div>
</template>
```

## Laravel Backend

```php
// routes/web.php
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// app/Http/Controllers/Auth/RegisterController.php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return inertia('Auth/Register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
```
