<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Page</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/bootstrap.js'])
    @vite(['resources/css/admin-sidebar.css', 'resources/js/admin.js'])
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('superadmin.dashboard') }}" class="logo">Welcome dev!</a>
            </div>
            <nav class="nav-menu">
                <a href="{{ route('superadmin.dashboard') }}" 
                   class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('superadmin.create-admin') }}"
                   class="nav-item {{ request()->routeIs('superadmin.create-admin') ? 'active' : '' }}">
                    <i class="fas fa-users mr-2"></i> Create Admin
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="nav-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                    <i class="fas fa-user mr-2"></i> Manage Users
                </a>
                <a href="{{ route('admin.mail.message') }}"
                   class="nav-item {{ request()->routeIs('admin.mail.message') ? 'active' : '' }}">
                    <i class="fas fa-envelope mr-2"></i> Mail
                </a>

                <a href="#" onclick="event.preventDefault(); document.getElementById('maintenance-form').submit();"
                class="nav-item">
                    <i class="fas fa-wrench mr-2"></i> 
                    Maintenance Mode: <span class="ml-1 {{ auth()->user()->maintenance_mode ? 'text-green-500' : 'text-red-500' }}">
                        {{ auth()->user()->maintenance_mode ? 'ON' : 'OFF' }}
                    </span>
                </a>

                <form id="maintenance-form" action="{{ route('superadmin.maintenance.toggle') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <form method="POST" action="{{ route('superadmin.logout') }}" class="mt-auto">
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
                    <div class="admin-username mr-4 text-lg">
                    {{ auth()->user()->fullname }}
                    <div class="username">{{ auth()->user()->username }}</div>
                </div>             
            </div>

       <!-- Page Content -->
       <main>
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <!-- Success or Error Messages -->
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
                        @yield('content') <!-- This is where the page-specific content will be injected -->
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
