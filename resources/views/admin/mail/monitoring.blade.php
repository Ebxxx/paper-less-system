<x-admin-layout>
    <div class="bg-white shadow rounded-lg p-6">
        <!-- <h2 class="text-2xl font-bold mb-4">Mail Monitoring</h2> -->
        
        <!-- Search and Filter Section -->
        <div class="mb-6">
            <form method="GET" class="flex items-center gap-4">
            <h2 class="text-2xl font-bold mb-4">Mail Monitoring</h2>
                <!-- Search Bar with Icon -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Search mail"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    >
                </div>

                <!-- Filter Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button 
                        type="button"
                        @click="open = !open"
                        class="flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none"
                    >
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Filters</span>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                    >
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Filter by Status</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="status[]" value="read" 
                                        {{ in_array('read', (array)request('status')) ? 'checked' : '' }}
                                        class="rounded text-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Read</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="status[]" value="unread"
                                        {{ in_array('unread', (array)request('status')) ? 'checked' : '' }}
                                        class="rounded text-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Unread</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="status[]" value="important"
                                        {{ in_array('important', (array)request('status')) ? 'checked' : '' }}
                                        class="rounded text-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Important</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="status[]" value="urgent"
                                        {{ in_array('urgent', (array)request('status')) ? 'checked' : '' }}
                                        class="rounded text-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Urgent</span>
                                </label>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Date Range</h3>
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">From</label>
                                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">To</label>
                                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end gap-2">
                                <a href="{{ route('admin.mail.monitoring') }}" 
                                    class="px-3 py-1.5 text-sm text-gray-700 hover:text-gray-900">
                                    Clear
                                </a>
                                <button type="submit" class="px-3 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Show Active Filters -->
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <div class="mt-2 flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-600">Active filters:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Search: {{ request('search') }}
                        </span>
                    @endif
                    @foreach((array)request('status') as $status)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Status: {{ ucfirst($status) }}
                        </span>
                    @endforeach
                    @if(request('date_from'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            From: {{ request('date_from') }}
                        </span>
                    @endif
                    @if(request('date_to'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            To: {{ request('date_to') }}
                        </span>
                    @endif
                    <a href="{{ route('admin.mail.monitoring') }}" 
                        class="text-sm text-red-600 hover:text-red-800">
                        Clear all
                    </a>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">From</th>
                        <th class="px-4 py-2">To</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                    <tr class="border-b hover:bg-gray-50"> 
                        <td class="px-4 py-2">{!! TextHelper::highlight($message->sender->username, request('search')) !!}</td>
                        <td class="px-4 py-2">{!! TextHelper::highlight($message->recipient->username, request('search')) !!}</td>
                        <td class="px-4 py-2">
                            @php
                                $formattedDate = $message->created_at->format('Y-m-d H:i');
                                $searchDate = request('search');
                                // Check if search is a date format (YYYY-MM-DD)
                                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $searchDate) && str_contains($formattedDate, $searchDate)) {
                                    echo '<mark class="bg-yellow-200 rounded px-1">' . $formattedDate . '</mark>';
                                } else {
                                    echo $formattedDate;
                                }
                            @endphp
                        </td>
                        <td class="px-4 py-2">{!! TextHelper::highlight($message->subject, request('search')) !!}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                @if($message->read_at)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Read</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Unread</span>
                                @endif
                                @if($message->marks->first()?->is_important)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Important</span>
                                @endif
                                @if($message->marks->first()?->is_urgent)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Urgent</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <button 
                                onclick="viewMessage({{ $message->id }})" 
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition"
                            >
                                View Details
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[80vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="border-b px-6 py-4 flex items-center justify-between sticky top-0 bg-white">
                    <h3 class="text-xl font-bold" id="modalSubject"></h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Message Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">From:</p>
                                <p class="font-medium" id="modalFrom"></p>
                                <p class="text-sm text-gray-500 whitespace-pre-line" id="modalFromDetails"></p>
                            </div>
                            <div>
                                <p class="text-gray-600">To:</p>
                                <p class="font-medium" id="modalTo"></p>
                                <p class="text-sm text-gray-500 whitespace-pre-line" id="modalToDetails"></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Sent Date:</p>
                                <p class="font-medium" id="modalDate"></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status:</p>
                                <div class="flex flex-wrap gap-2" id="modalStatus"></div>
                            </div>
                            <div id="modalDeadlineContainer" class="hidden">
                                <p class="text-gray-600">Deadline:</p>
                                <p class="font-medium text-red-600" id="modalDeadline"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="mb-4">
                        <h4 class="font-bold mb-2">Message Content:</h4>
                        <div class="bg-white border rounded-lg p-4 whitespace-pre-wrap" id="modalContent"></div>
                    </div>

                    <!-- Attachments Section -->
                    <div id="attachmentsSection" class="hidden">
                        <h4 class="font-bold mb-2">Attachments:</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <ul class="space-y-2" id="modalAttachments"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function viewMessage(messageId) {
            fetch(`/admin/mail/message/${messageId}`)
                .then(response => response.json())
                .then(data => {
                    // Basic message details
                    document.getElementById('modalSubject').textContent = data.subject;
                    document.getElementById('modalContent').textContent = data.content;
                    document.getElementById('modalDate').textContent = data.created_at;

                    // Sender and recipient details
                    document.getElementById('modalFrom').textContent = data.from_user.username + ' ' + '<' + data.from_user.email + '>';
                    document.getElementById('modalFromDetails').textContent = 
                        `${data.from_user.full_name}\n${data.from_user.job_title ? '  ' + data.from_user.job_title : ''}${data.from_user.department ? ' (' + data.from_user.department + ')' : ''}`;

                    document.getElementById('modalTo').textContent = data.to_user.username + ' ' + '<' + data.to_user.email + '>';
                    document.getElementById('modalToDetails').textContent = 
                        `${data.to_user.full_name}\n${data.to_user.job_title ? ' ' + data.to_user.job_title : ''}${data.to_user.department ? ' (' + data.to_user.department + ')' : ''}`;

                    // Status badges
                    let statusHtml = '';
                    if (data.read_at) {
                        statusHtml += `<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Read at ${data.read_at}</span>`;
                    } else {
                        statusHtml += '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Unread</span>';
                    }
                    
                    if (data.marks.is_important) {
                        statusHtml += ' <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Important</span>';
                    }
                    if (data.marks.is_urgent) {
                        statusHtml += ' <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Urgent</span>';
                    }
                    
                    document.getElementById('modalStatus').innerHTML = statusHtml;

                    // Handle deadline
                    const deadlineContainer = document.getElementById('modalDeadlineContainer');
                    if (data.marks.deadline) {
                        document.getElementById('modalDeadline').textContent = data.marks.deadline;
                        deadlineContainer.classList.remove('hidden');
                    } else {
                        deadlineContainer.classList.add('hidden');
                    }

                    // Handle attachments
                    const attachmentsSection = document.getElementById('attachmentsSection');
                    const attachmentsList = document.getElementById('modalAttachments');
                    
                    if (data.attachments && data.attachments.length > 0) {
                        attachmentsList.innerHTML = data.attachments.map(attachment => `
                            <li class="flex items-center justify-between p-2 hover:bg-gray-100 rounded">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-${getFileIcon(attachment.mime_type)}"></i>
                                    <span class="text-sm">${attachment.original_filename}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <span>${formatFileSize(attachment.file_size)}</span>
                                    <a href="/admin/mail/attachment/${attachment.id}/download" 
                                       class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </li>
                        `).join('');
                        attachmentsSection.classList.remove('hidden');
                    } else {
                        attachmentsSection.classList.add('hidden');
                    }

                    // Update content with highlighted search terms if search is active
                    const searchTerm = '{{ request('search') }}';
                    if (searchTerm) {
                        document.getElementById('modalContent').innerHTML = highlightText(data.content, searchTerm);
                        document.getElementById('modalSubject').innerHTML = highlightText(data.subject, searchTerm);
                        document.getElementById('modalFrom').innerHTML = highlightText(data.from_user.username, searchTerm);
                        document.getElementById('modalTo').innerHTML = highlightText(data.to_user.username, searchTerm);
                        
                        // Highlight date if search term is a date
                        if (/^\d{4}-\d{2}-\d{2}$/.test(searchTerm)) {
                            const dateElement = document.getElementById('modalDate');
                            const dateText = dateElement.textContent;
                            if (dateText.includes(searchTerm)) {
                                dateElement.innerHTML = `<mark class="bg-yellow-200 rounded px-1">${dateText}</mark>`;
                            }
                        }
                    } else {
                        document.getElementById('modalContent').textContent = data.content;
                        document.getElementById('modalSubject').textContent = data.subject;
                        document.getElementById('modalFrom').textContent = data.from_user.username;
                        document.getElementById('modalTo').textContent = data.to_user.username;
                        document.getElementById('modalDate').textContent = data.created_at;
                    }

                    // Show modal
                    document.getElementById('messageModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading message details');
                });
        }

        function getFileIcon(mimeType) {
            if (mimeType.startsWith('image/')) return 'image';
            if (mimeType.includes('pdf')) return 'file-pdf';
            if (mimeType.includes('word')) return 'file-word';
            if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'file-excel';
            return 'paperclip';
        }

        function closeModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('messageModal').classList.contains('hidden')) {
                closeModal();
            }
        });

        // Add highlight function for client-side highlighting
        function highlightText(text, search) {
            if (!search) return text;
            // Check if search is a date format
            if (/^\d{4}-\d{2}-\d{2}$/.test(search)) {
                return text.includes(search) ? `<mark class="bg-yellow-200 rounded px-1">${text}</mark>` : text;
            }
            const escapedSearch = search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`(${escapedSearch})`, 'gi');
            return text.replace(regex, '<mark class="search-highlight">$1</mark>');
        }
    </script>
    @endpush

    <!-- Add Alpine.js for dropdown functionality -->
    <script src="//unpkg.com/alpinejs" defer></script>
</x-admin-layout> 



