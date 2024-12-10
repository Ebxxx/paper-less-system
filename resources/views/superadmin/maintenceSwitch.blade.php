@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6">System Maintenance Control</h2>
        
        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-lg">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Maintenance Mode</h3>
                <p class="text-gray-600">When enabled, users will be redirected to the maintenance page</p>
            </div>
            
            <div class="flex items-center">
                <span class="mr-3 text-sm {{ auth()->user()->maintenance_mode ? 'text-green-600' : 'text-red-600' }}">
                    {{ auth()->user()->maintenance_mode ? 'Enabled' : 'Disabled' }}
                </span>
                
                <button onclick="toggleMaintenance()" 
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 
                        {{ auth()->user()->maintenance_mode ? 'bg-green-500' : 'bg-gray-200' }}"
                        role="switch"
                        aria-checked="{{ auth()->user()->maintenance_mode ? 'true' : 'false' }}">
                    <span class="sr-only">Toggle Maintenance Mode</span>
                    <span aria-hidden="true" 
                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                          {{ auth()->user()->maintenance_mode ? 'translate-x-5' : 'translate-x-0' }}">
                    </span>
                </button>
            </div>
        </div>

        <!-- Status Card -->
        <div class="mt-6 p-4 rounded-lg {{ auth()->user()->maintenance_mode ? 'bg-yellow-50' : 'bg-green-50' }}">
            <div class="flex items-center">
                <i class="fas {{ auth()->user()->maintenance_mode ? 'fa-exclamation-triangle text-yellow-400' : 'fa-check-circle text-green-400' }} text-xl mr-3"></i>
                <div>
                    <h4 class="font-semibold {{ auth()->user()->maintenance_mode ? 'text-yellow-800' : 'text-green-800' }}">
                        System Status: {{ auth()->user()->maintenance_mode ? 'Under Maintenance' : 'Operational' }}
                    </h4>
                    <p class="text-sm {{ auth()->user()->maintenance_mode ? 'text-yellow-600' : 'text-green-600' }}">
                        {{ auth()->user()->maintenance_mode 
                            ? 'Users are currently being redirected to the maintenance page.' 
                            : 'The system is running normally and accessible to all users.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMaintenance() {
    fetch('{{ route('superadmin.maintenance.toggle') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        // Reload the page to reflect the new state
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while toggling maintenance mode');
    });
}
</script>
@endsection