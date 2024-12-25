<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Inbox</h2>
                        <div class="flex items-center space-x-2" id="bulkActions" style="display: none;">
                            <button onclick="archiveSelected()" class="inline-flex items-center px-3 py-1 text-sm bg-gray-600 text-white rounded hover:bg-gray-700">
                                <i class="fas fa-archive mr-2"></i> Archive
                            </button>
                        </div>
                    </div>

                    @if($messages->isEmpty())
                        <p class="text-gray-500 text-center py-4">Your inbox is empty.</p>
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
                                        <tr class="hover:bg-gray-50 {{ !$message->read_at ? 'font-semibold bg-blue-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <input type="checkbox" name="selected[]" value="{{ $message->id }}" 
                                                           class="message-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    
                                                    <!-- Star Button with Tooltip -->
                                                    <form action="{{ route('mail.toggle-star', $message) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="focus:outline-none group relative">
                                                            <i class="fas fa-star {{ $message->is_starred ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}"></i>
                                                            <!-- Tooltip -->
                                                            <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                                                {{ $message->is_starred ? 'Starred' : 'Not Starred' }}
                                                            </span>
                                                        </button>
                                                    </form>

                                                    <!-- Archive Button with Tooltip -->
                                                    @if(auth()->id() === $message->to_user_id)
                                                        <form action="{{ route('mail.toggle-archive', $message) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-gray-400 hover:text-gray-600 focus:outline-none group relative">
                                                                <i class="fas fa-archive"></i>
                                                                <!-- Tooltip -->
                                                                <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                                                    Archive
                                                                </span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap cursor-pointer" onclick="window.location='{{ route('mail.show', $message->id) }}'">
                                                <div class="text-sm text-gray-900">
                                                    {{ $message->sender->username }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $message->sender->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    @if(optional($message->attachments)->count() > 0)
                                                        <i class="fas fa-paperclip text-gray-400 mr-1"></i>
                                                    @endif
                                                    {{ $message->subject }}
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
                                                    <!-- Read/Unread Status with Icon and Click Handler -->
                                                    <form action="{{ route('mail.toggle-read', $message) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="focus:outline-none group relative">
                                                            <i class="fas {{ $message->read_at ? 'fa-envelope-open' : 'fa-envelope' }} 
                                                                      {{ $message->read_at ? 'text-green-600' : 'text-blue-600' }}">
                                                            </i>
                                                            <!-- Tooltip -->
                                                            <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                                                {{ $message->read_at ? 'Mark as unread' : 'Mark as read' }}
                                                            </span>
                                                        </button>
                                                    </form>
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

        // Show/hide bulk actions when individual checkboxes are clicked
        document.querySelectorAll('.message-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
                const bulkActions = document.getElementById('bulkActions');
                bulkActions.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
            });
        });

        function archiveSelected() {
            const selectedIds = Array.from(document.querySelectorAll('.message-checkbox:checked'))
                                   .map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) return;

            fetch('{{ route("mail.bulk-archive") }}', {
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

        document.querySelectorAll('form[action*="toggle-star"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const starIcon = this.querySelector('.fas.fa-star');
                        if (starIcon.classList.contains('text-yellow-400')) {
                            starIcon.classList.remove('text-yellow-400');
                            starIcon.classList.add('text-gray-300', 'hover:text-yellow-400');
                        } else {
                            starIcon.classList.remove('text-gray-300', 'hover:text-yellow-400');
                            starIcon.classList.add('text-yellow-400');
                        }
                    }
                });
            });
        });

        document.querySelectorAll('form[action*="toggle-read"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const icon = this.querySelector('.fas');
                        const row = this.closest('tr');
                        
                        if (data.read) {
                            icon.classList.remove('fa-envelope', 'text-blue-600');
                            icon.classList.add('fa-envelope-open', 'text-green-600');
                            row.classList.remove('font-semibold', 'bg-blue-50');
                        } else {
                            icon.classList.remove('fa-envelope-open', 'text-green-600');
                            icon.classList.add('fa-envelope', 'text-blue-600');
                            row.classList.add('font-semibold', 'bg-blue-50');
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout> 