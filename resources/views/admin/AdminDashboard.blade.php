
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Stats Card -->
                <div class="bg-indigo-100 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Total Users</h3>
                    <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                </div>

                <!-- Recent Users -->
                <div class="bg-white rounded-lg p-6 border">
                    <h3 class="text-lg font-semibold mb-4">Recent Users</h3>
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex justify-between items-center">
                                <span>{{ $user->username }}</span>
                                <span class="text-gray-500 text-sm">
                                    {{ $user->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>