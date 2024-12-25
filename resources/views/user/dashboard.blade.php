<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Welcome to Your Mail Dashboard</h2>
                    
                    <!-- Mail Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h3 class="font-semibold">Inbox</h3>
                            <p class="text-2xl">{{ $unreadCount ?? 0 }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h3 class="font-semibold">Sent</h3>
                            <p class="text-2xl">{{ $sentCount ?? 0 }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <h3 class="font-semibold">Total Messages</h3>
                            <p class="text-2xl">{{ $totalCount ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="space-x-4">
                        <a href="{{ route('mail.compose') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fas fa-paper-plane mr-2"></i> Compose New Message
                        </a>
                        <a href="{{ route('mail.inbox') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            <i class="fa fa-inbox mr-2"></i> Go to Inbox
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
