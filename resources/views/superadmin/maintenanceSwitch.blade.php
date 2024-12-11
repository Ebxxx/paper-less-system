@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-lg mx-auto bg-white rounded-md shadow p-6">
        <!-- Header with Toggle -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-semibold text-gray-700">Maintenance Mode</h2>
                <p class="text-sm text-gray-500 mt-1">Control system availability</p>
            </div>
            <!-- Toggle Form -->
            <form action="{{ route('superadmin.maintenance.toggle') }}" method="POST">
                @csrf
                <label for="maintenance-toggle" class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" id="maintenance-toggle" name="maintenance_mode" class="sr-only" 
                               {{ $maintenanceMode ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="block w-14 h-8 rounded-full transition-colors duration-300
                            {{ $maintenanceMode ? 'bg-red-500' : 'bg-green-500' }}"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300
                            {{ $maintenanceMode ? 'transform translate-x-full' : '' }}"></div>
                    </div>
                </label>
            </form>
        </div>

        <!-- Status Indicator -->
        <div class="mb-6">
            <span class="px-3 py-1 rounded-full text-sm font-medium
                {{ $maintenanceMode 
                    ? 'bg-red-100 text-red-800' 
                    : 'bg-green-100 text-green-800' }}">
                Status: {{ $maintenanceMode ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <!-- Info Box -->
        <div class="border-t pt-4">
            <p class="text-sm text-gray-500 mb-2">When maintenance mode is active:</p>
            <ul class="text-sm text-gray-600 space-y-1">
                <li class="flex items-center">
                    <span class="mr-2">•</span>
                    Regular users cannot access the system
                </li>
                <li class="flex items-center">
                    <span class="mr-2">•</span>
                    Only administrators have access
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    /* Toggle switch styles */
    input:checked ~ .dot {
        transform: translateX(100%);
    }
</style>
@endsection 