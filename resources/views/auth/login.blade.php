<x-guest-layout>
    <div class="flex min-h-screen">
        <!-- Left Section with Background Image -->
        <div class="hidden lg:flex lg:w-1/2 bg-blue-600 p-16 flex-col justify-center relative overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/site_cover/maxresdefault.jpg') }}" 
                     alt="Building" 
                     class="w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-blue-600/80"></div>
            </div>
            
          <!-- Content -->
          <div class="header-container relative z-10">
                <h1 class="welcome-text">Welcome to</h1>
                <div class="logo-container">
                    <span class="logo-login-container">
                        <span class="logo-edu-login-container">Edu</span><span class="logo-mail-login-container">MAIL</span>
                    </span>
                </div>
                <p class="subheading text-left ">Streamlined Educational Email Management System</p>
            </div>
        </div>

        <!-- Right Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg p-8">
                    <h2 class="text-2xl font-semibold mb-1">Sign in</h2>
                    <p class="text-gray-600 mb-6">Access your account</p>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-800 mb-1">Email Address</label>
                            <input id="email" 
                                class="w-full p-3 rounded-md border border-gray-300 bg-gray-50/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                placeholder="Enter your email address"
                                required autofocus autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-800">Password</label>
                            <div class="relative mt-1">
                                <input id="password" 
                                    class="w-full p-3 rounded-md border border-gray-300 bg-gray-50/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    type="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    required autocomplete="current-password" />
                                <button type="button" 
                                    onclick="togglePasswordVisibility()"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" 
                                        class="w-5 h-5" id="passwordToggleIcon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600" name="remember">
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>

                        <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-md hover:bg-blue-500 transition-colors font-medium text-sm uppercase">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('passwordToggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        // Change to "hide" icon
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        passwordInput.type = 'password';
        // Change back to "show" icon
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>