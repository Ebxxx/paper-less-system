@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-6">Superadmin Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- User Statistics Card -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">User Statistics</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-blue-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Total Admins</p>
                    <p class="text-2xl font-bold">{{ $adminCount }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Regular Users</p>
                    <p class="text-2xl font-bold">{{ $userCount }}</p>
                </div>
            </div>
            <canvas id="userChart" class="mt-6" height="300"></canvas>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('userChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Regular Users', 'Admins'],
                datasets: [{
                    data: [{{ $userCount }}, {{ $adminCount }}],
                    backgroundColor: ['#3B82F6', '#10B981']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'User Type Distribution'
                    }
                }
            }
        });
    });
</script>
@endsection