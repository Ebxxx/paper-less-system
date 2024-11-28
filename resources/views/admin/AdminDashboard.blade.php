<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                {{ __('User Growth') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- User Growth Card -->
                <div class="bg-white-100 rounded-lg p-6 border shadow-lg">
                <form method="get" action="{{ route('admin.AdminDashboard') }}">
                    <label for="year" class="block text-sm font-medium text-gray-700">Select Year</label>
                    <select name="year" id="year" onchange="this.form.submit()" class="mt-1 block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>
                    <!-- <h3 class="text-lg font-semibold mb-4">User Growth in {{ $selectedYear }}</h3> -->
                    <canvas id="userGrowthChart" class="w-full"></canvas>
                </div>

                <!-- Recent Users -->
                <div class="bg-white rounded-lg p-6 border shadow-lg">
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

            <!-- Charts Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 shadow-lg">
                <div class="bg-white rounded-lg p-6 border">
                    <h3 class="text-2xl font-bold mb-2">User statistic</h3>
                    <br>
                    <p class="text-xl font-semibold">Total Users: {{ $totalUsers }}</p>
                    <br>
                    <div class="mb-4 flex justify-between">
                        <span>Admins: {{ $adminCount }}</span>
                        <span>Regular Users: {{ $regularUserCount }}</span>
                    </div>
                    <canvas id="userTypeChart" class="w-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Pass data to JavaScript -->
    <script>
            const adminCount = {{ $adminCount }};
            const regularUserCount = {{ $regularUserCount }};
            const selectedYear = {{ $selectedYear }};
            const userGrowthLabels = [
                @foreach($userGrowthData as $data)
                    '{{ $data['month'] }}',
                @endforeach
            ];
            const userGrowthData = [
                @foreach($userGrowthData as $data)
                    {{ $data['users'] }},
                @endforeach
            ];
    </script>
    
    <!-- Include the custom charts script -->
    <script src="{{ asset('js/admin-dashboard-charts.js') }}"></script>
</x-admin-layout>