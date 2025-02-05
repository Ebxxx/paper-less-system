<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex" x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'profile-details' }">
                        <button 
                            @click="activeTab = 'profile-details'" 
                            :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'profile-details' }"
                            class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Account Details
                        </button>
                        <!-- <button 
                            @click="activeTab = 'account-security'" 
                            :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'account-security' }"
                            class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Account Security
                        </button> -->
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Profile Details Tab -->
                    <div x-show="activeTab === 'profile-details'" class="space-y-6">
                        <div class="flex">
                            <!-- Left Sidebar -->
                            <div class="w-1/4 pr-8 border-r">
                                <div class="text-center mb-6">
                                    <!-- Profile Picture Display -->
                                    <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full flex items-center justify-center mb-4 overflow-hidden">
                                        @if(auth()->user()->profile_picture)
                                            <img src="{{ asset(auth()->user()->profile_picture) }}" 
                                                 alt="Profile Picture" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-4xl text-gray-400"></i>
                                        @endif
                                    </div>
                                    <h3 class="font-medium">{{ auth()->user()->username }}</h3>
                                    <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="font-medium text-blue-600">About</h4>
                                    <div class="text-sm space-y-1 text-gray-600">
                                        <p>{{ auth()->user()->prefix }} {{ auth()->user()->first_name }} {{ auth()->user()->middle_name }} {{ auth()->user()->last_name }} {{ auth()->user()->order_title }}</p>
                                        <p>{{ auth()->user()->job_title }}</p>
                                        <p>{{ auth()->user()->program }} - {{ auth()->user()->department }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Content Area -->
                            <div class="w-3/4 pl-8">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Account Security Tab -->
                    <div x-show="activeTab === 'account-security'" class="space-y-6">
                        <div class="flex">                  
                            <div class="w-1/4 pr-8 border-r">
                                <div class="space-y-2">                 
                                </div>
                            </div>

                            <!-- Right Content Area -->
                            <div class="w-3/4 pl-8">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle tab persistence through page reloads
        window.addEventListener('hashchange', function() {
            const hash = window.location.hash.substring(1);
            if (['profile-details', 'account-security'].includes(hash)) {
                Alpine.store('activeTab', hash);
            }
        });
    </script>
    @endpush
</x-app-layout>
