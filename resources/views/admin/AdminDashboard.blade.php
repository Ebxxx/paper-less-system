<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <!-- Main Grid Container -->
            <div class="grid grid-cols-4 gap-6">
                <!-- Left Section (3 columns) -->
                <div class="col-span-3">
                    <!-- User Growth Chart Section -->
                    <div class="bg-white rounded-lg p-6 border shadow-lg mb-6">
                        <h2 class="font-semibold text-3xl text-gray-800 leading-tight mb-4">
                            {{ __('User Growth') }}
                        </h2>
                        <form method="get" action="{{ route('admin.AdminDashboard') }}" class="mb-4">
                            <label for="year" class="block text-sm font-medium text-gray-700">Select Year</label>
                            <select name="year" id="year" onchange="this.form.submit()" class="mt-1 block w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <canvas id="userGrowthChart" class="w-full" style="width: 80%; height: 300px;"></canvas>
                    </div>

                    <!-- Message Statistics Chart Section -->
                    <div class="bg-white rounded-lg p-6 border shadow-lg">
                        <h2 class="font-semibold text-3xl text-gray-800 leading-tight mb-4">
                            {{ __('Message Statistics') }}
                        </h2>
                        <canvas id="messageStatsChart" class="w-full" style="width: 80%; height: 300px;"></canvas>
                    </div>
                </div>

                <!-- Right Section (1 column) -->
                <div class="space-y-6">
                    <!-- Recent Users Card -->
                    <div class="bg-white rounded-lg p-6 border shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Recent Users</h3>
                        <div class="space-y-3">
                            @foreach($recentUsers as $user)
                                <div class="flex justify-between items-center">
                                    <span>{{ $user->username }}</span>
                                    <span class="text-sm {{ $user->is_online ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $user->is_online ? 'Online' : 'Offline' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- User Type Distribution -->
                    <div class="bg-white rounded-lg p-6 border shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">User Distribution</h3>
                        <canvas id="userTypeChart" style="width: 100%; height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Pass data to JavaScript -->
    <script>
        const adminCount = {{ $adminCount }};
        const regularUserCount = {{ $regularUserCount }};
        const selectedYear = {{ $selectedYear }};
        const userGrowthLabels = [@foreach($userGrowthData as $data)'{{ $data['month'] }}',@endforeach];
        const userGrowthData = [@foreach($userGrowthData as $data){{ $data['users'] }},@endforeach];
        const messageStats = {
            total: {{ $totalMessages }},
            read: {{ $readMessages }},
            unread: {{ $unreadMessages }},
            urgent: {{ $urgentMessages }},
            important: {{ $importantMessages }},
            attachments: {{ $totalAttachments }}
        };
    </script>
    
    <!-- Include the custom charts script -->
    <script src="{{ asset('js/admin-dashboard-charts.js') }}"></script>
</x-admin-layout>