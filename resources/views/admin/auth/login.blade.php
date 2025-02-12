<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="text-2xl font-bold mb-1">Admin sign in</h1>

    <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" 
                class="mt-1 w-full p-2.5 rounded-md border border-gray-300 bg-blue-50/50" 
                type="email" 
                name="email" 
                :value="old('email')" 
                placeholder="Enter your email address"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" 
                class="mt-1 w-full p-2.5 rounded-md border border-gray-300 bg-blue-50/50"
                type="password"
                name="password"
                placeholder="Enter your password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <x-primary-button class="w-full justify-center bg-blue-700 hover:bg-blue-700">
            {{ __('Sign in') }}
        </x-primary-button>
    </form>

    <div class="text-center mt-4">
        <a class="text-sm text-blue-600 hover:text-blue-800" href="{{ route('login') }}">
            {{ __('Client?') }}
        </a>
    </div>
</x-guest-layout>