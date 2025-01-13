
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="text-2xl font-bold mb-1">Developer Sign in</h1>

    <form class="mt-8 space-y-6" action="{{ route('superadmin.login') }}" method="POST">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="username" :value="__('username')" />
            <x-text-input id="username" 
                class="mt-1 w-full p-2.5 rounded-md border border-gray-300 bg-blue-50/50" 
                type="username" 
                name="username" 
                :value="old('username')" 
                placeholder="Enter your username"
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
        <br>
        <x-primary-button class="w-full justify-center bg-blue-700 hover:bg-blue-700">
            {{ __('Sign in') }}
        </x-primary-button>
    </form>
