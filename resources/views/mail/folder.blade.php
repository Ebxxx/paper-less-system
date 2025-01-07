<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">
                            <i class="fas fa-folder mr-2"></i>{{ $folder->name }}
                        </h2>
                        <div class="flex items-center space-x-2" id="bulkActions" style="display: none;">
                            <button onclick="archiveSelected()" class="inline-flex items-center px-3 py-1 text-sm bg-gray-600 text-white rounded hover:bg-gray-700">
                                <i class="fas fa-archive mr-2"></i> Archive
                            </button>
                        </div>
                    </div>

                    @if($messages->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-folder-open text-4xl mb-4"></i>
                            <p>No messages in this folder</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($messages as $message)
                                        <tr class="hover:bg-gray-50 {{ !$message->read_at ? 'font-semibold bg-blue-50' : '' }}" 
                                            onclick="window.location='{{ route('mail.show', $message) }}'" 
                                            style="cursor: pointer;">
                                            <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                                <div class="flex items-center space-x-2">
                                                    <input type="checkbox" name="selected[]" value="{{ $message->id }}" 
                                                           class="message-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    
                                                    <!-- Star Button -->
                                                    <button onclick="toggleStar({{ $message->id }})" 
                                                            class="text-gray-400 hover:text-yellow-400 {{ $message->is_starred ? 'text-yellow-400' : '' }}">
                                                        <i class="fas fa-star"></i>
                                                    </button>

                                                    <!-- Remove from folder button -->
                                                    <button onclick="removeFromFolder({{ $message->id }}, {{ $folder->id }})" 
                                                            class="text-gray-400 hover:text-red-500">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $message->sender->username }}</div>
                                                <div class="text-sm text-gray-500">{{ $message->sender->email }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2">
                                                    @if($message->mark?->is_important)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-exclamation-circle mr-1"></i>Important
                                                        </span>
                                                    @endif
                                                    @if($message->mark?->is_urgent)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-900 mt-1">
                                                    <span class="font-medium">{{ $message->subject }}</span>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($message->content, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $message->created_at->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <span class="group relative">
                                                        <i class="fas {{ $message->read_at ? 'fa-envelope-open text-gray-400' : 'fa-envelope text-gray-600' }}"></i>
                                                        <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                                            {{ $message->read_at ? 'Read' : 'Unread' }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleStar(messageId) {
            fetch(`/mail/${messageId}/star`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }

        function removeFromFolder(messageId, folderId) {
            if (!confirm('Remove this message from the folder?')) return;

            fetch(`/folders/${folderId}/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    </script>

    <style>
        [x-cloak] { 
            display: none !important; 
        }

        .relative {
            position: relative !important;
        }

        .absolute {
            position: absolute !important;
        }

        .z-50 {
            z-index: 50 !important;
        }
    </style>
</x-app-layout> 