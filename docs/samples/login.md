# Login Page

A modern login page with email/password authentication, remember me option, and social login buttons.

## Preview

<div style="max-width: 400px; margin: 0 auto; padding: 40px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
  <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
    <div style="text-align: center; margin-bottom: 24px;">
      <div style="width: 48px; height: 48px; background: #4f46e5; border-radius: 12px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">CA</div>
      <h2 style="margin: 0; font-size: 20px; font-weight: 600;">Welcome back</h2>
      <p style="margin: 4px 0 0; color: #6b7280; font-size: 14px;">Sign in to your account</p>
    </div>
    <div style="margin-bottom: 16px;">
      <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">Email</label>
      <input type="email" placeholder="you@example.com" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;" />
    </div>
    <div style="margin-bottom: 16px;">
      <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">Password</label>
      <input type="password" placeholder="••••••••" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;" />
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 14px;">
      <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
        <input type="checkbox" style="width: 16px; height: 16px;" />
        Remember me
      </label>
      <a href="#" style="color: #4f46e5; text-decoration: none;">Forgot password?</a>
    </div>
    <button style="width: 100%; padding: 10px 16px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">Sign in</button>
    <p style="text-align: center; margin-top: 16px; font-size: 14px; color: #6b7280;">Don't have an account? <a href="#" style="color: #4f46e5; text-decoration: none;">Sign up</a></p>
  </div>
</div>

## Full Code

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import { Card, Form, FormGroup, Input, PasswordInput, Checkbox, Button, AppLink } from '@cambosoftware/cambo-admin'

defineProps({
    canResetPassword: Boolean,
    status: String
})

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password')
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 py-12 px-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-white rounded-xl shadow-lg mx-auto flex items-center justify-center">
                    <span class="text-indigo-600 font-bold text-xl">CA</span>
                </div>
            </div>

            <Card class="shadow-2xl">
                <div class="p-8">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                        <p class="text-gray-600 mt-1">Sign in to your account</p>
                    </div>

                    <!-- Status Message -->
                    <div v-if="status" class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                        {{ status }}
                    </div>

                    <!-- Login Form -->
                    <Form :form="form" @submit="submit">
                        <FormGroup label="Email" :error="form.errors.email" required>
                            <Input
                                v-model="form.email"
                                type="email"
                                placeholder="you@example.com"
                                autofocus
                                autocomplete="username"
                            />
                        </FormGroup>

                        <FormGroup label="Password" :error="form.errors.password" required>
                            <PasswordInput
                                v-model="form.password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                            />
                        </FormGroup>

                        <div class="flex items-center justify-between mb-6">
                            <Checkbox v-model="form.remember" label="Remember me" />
                            <AppLink
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                variant="primary"
                            >
                                Forgot password?
                            </AppLink>
                        </div>

                        <Button
                            type="submit"
                            variant="primary"
                            class="w-full"
                            :loading="form.processing"
                        >
                            Sign in
                        </Button>
                    </Form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <!-- Social Login -->
                    <div class="grid grid-cols-2 gap-3">
                        <Button variant="outline" class="w-full">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Google
                        </Button>
                        <Button variant="outline" class="w-full">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            GitHub
                        </Button>
                    </div>

                    <!-- Sign Up Link -->
                    <p class="mt-6 text-center text-sm text-gray-600">
                        Don't have an account?
                        <AppLink :href="route('register')" variant="primary" class="font-medium">
                            Sign up
                        </AppLink>
                    </p>
                </div>
            </Card>
        </div>
    </div>
</template>
```

## Variations

### Simple Login (No Social)

Remove the social login section for a simpler form:

```vue
<template>
    <Card class="max-w-md mx-auto">
        <div class="p-6">
            <h2 class="text-xl font-bold text-center mb-6">Sign In</h2>

            <Form :form="form" @submit="submit">
                <FormGroup label="Email" :error="form.errors.email">
                    <Input v-model="form.email" type="email" />
                </FormGroup>

                <FormGroup label="Password" :error="form.errors.password">
                    <PasswordInput v-model="form.password" />
                </FormGroup>

                <Button type="submit" class="w-full" :loading="form.processing">
                    Sign In
                </Button>
            </Form>
        </div>
    </Card>
</template>
```

### Dark Theme Login

```vue
<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <Card class="max-w-md w-full bg-gray-800 border-gray-700">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-white text-center mb-6">Welcome Back</h2>

                <Form :form="form" @submit="submit">
                    <FormGroup label="Email" :error="form.errors.email" class="text-gray-300">
                        <Input
                            v-model="form.email"
                            type="email"
                            class="bg-gray-700 border-gray-600 text-white"
                        />
                    </FormGroup>

                    <FormGroup label="Password" :error="form.errors.password" class="text-gray-300">
                        <PasswordInput
                            v-model="form.password"
                            class="bg-gray-700 border-gray-600 text-white"
                        />
                    </FormGroup>

                    <Button type="submit" class="w-full" :loading="form.processing">
                        Sign In
                    </Button>
                </Form>
            </div>
        </Card>
    </div>
</template>
```

## Laravel Backend

```php
// routes/web.php
Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store']);

// app/Http/Controllers/AuthController.php
public function create()
{
    return inertia('Auth/Login', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status')
    ]);
}

public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    throw ValidationException::withMessages([
        'email' => 'These credentials do not match our records.'
    ]);
}
```
