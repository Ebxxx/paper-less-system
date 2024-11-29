<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/bootstrap.js'])
    @vite(['resources/css/user-sidebar.css', 'resources/js/admin.js'])
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="logo">Welcome {{ auth()->user()->username}}</a>
            </div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" 
                   class="nav-item {{ request()->routeIs(' ') ? 'active' : '' }}">
                    <i class="fa fa-inbox mr-2"></i> Inbox
                </a>
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs(' ') ? 'active' : '' }}">
                    <i class="fas fa-paper-plane mr-2"></i> Send a letter
                </a>
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs(' ') ? 'active' : '' }}">
                    <i class="fas fa-envelope mr-2"></i> Sent
                </a>
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs(' ') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn mr-2"></i> Announcement
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

               <!-- Main Content -->
               <div class="main-content" id="main-content">
            <div class="top-bar">
                <button class="toggle-btn" id="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->username }}</div>
                          

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        

      <!-- Main Content -->
      <!-- <div class="main-content" id="main-content">
            <div class="top-bar">
                <button class="toggle-btn" id="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                    <div class="admin-username mr-4 text-lg">
                    {{ auth()->user()->username }}
                    <div class="username">{{ auth()->user()->role }}</div>
                </div>     
            </div> -->
            

            <!-- Page Content -->
            <main>
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
