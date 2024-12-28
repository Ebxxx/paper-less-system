<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Archive</h2>
                        <div class="flex items-center space-x-2" id="bulkActions" style="display: none;">
                            <button onclick="moveToInbox()" class="inline-flex items-center px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                <i class="fas fa-inbox mr-2"></i> Move to Inbox
                            </button>
                        </div>
                    </div>

                    @if($messages->isEmpty())
                        <p class="text-gray-500 text-center py-4">No archived messages.</p>
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
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="selected[]" value="{{ $message->id }}" 
                                                       class="message-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap cursor-pointer" onclick="window.location='{{ route('mail.show', $message->id) }}'">
                                                <div class="text-sm text-gray-900">
                                                    @if(request('search'))
                                                        {!! App\Helpers\TextHelper::highlight($message->sender->username, request('search')) !!}
                                                    @else
                                                        {{ $message->sender->username }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @if(request('search'))
                                                        {!! App\Helpers\TextHelper::highlight($message->sender->email, request('search')) !!}
                                                    @else
                                                        {{ $message->sender->email }}
                                                    @endif
                                                </div>
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
                                                    <!-- @if(optional($message->attachments)->count() > 0)
                                                        <i class="fas fa-paperclip text-gray-400 mr-1"></i>
                                                    @endif -->
                                                    @if(request('search'))
                                                        {!! App\Helpers\TextHelper::highlight($message->subject, request('search')) !!}
                                                    @else
                                                        {{ $message->subject }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @if(request('search'))
                                                        {!! App\Helpers\TextHelper::highlight(Str::limit($message->content, 50), request('search')) !!}
                                                    @else
                                                        {{ Str::limit($message->content, 50) }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $message->created_at->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Read/Unread Status with Icon -->
                                                    <span class="group relative">
                                                        <i class="fas {{ $message->read_at ? 'fa-envelope-open' : 'fa-envelope' }} 
                                                          {{ $message->read_at ? 'text-gray-600' : 'text-gray-600' }}">
                                                        </i>
                                                        <!-- Tooltip -->
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
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.getElementsByClassName('message-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            
            Array.from(checkboxes).forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            bulkActions.style.display = this.checked ? 'block' : 'none';
        });

        document.querySelectorAll('.message-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
                const bulkActions = document.getElementById('bulkActions');
                bulkActions.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
            });
        });

        function moveToInbox() {
            const selectedIds = Array.from(document.querySelectorAll('.message-checkbox:checked'))
                                   .map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) return;

            fetch('{{ route("mail.bulk-unarchive") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    </script>
</x-app-layout> 