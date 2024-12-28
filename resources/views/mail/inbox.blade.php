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

                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a href="#" class="tab-link border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active" data-tab="primary">
                                <i class="fas fa-inbox mr-2"></i>Primary
                            </a>
                            <a href="#" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="priority">
                                <i class="fas fa-tag mr-2"></i>Priority
                            </a>
                        </nav>
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
                                        <tr class="hover:bg-gray-50 {{ !$message->read_at ? 'font-semibold bg-blue-50' : '' }}" 
                                            onclick="window.location='{{ route('mail.show', $message) }}'" 
                                            style="cursor: pointer;"
                                            data-message-id="{{ $message->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                                <div class="flex items-center space-x-2">
                                                    <input type="checkbox" name="selected[]" value="{{ $message->id }}" 
                                                           class="message-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    
                                                    <!-- Star Button with Tooltip -->
                                                    <form action="{{ route('mail.toggle-star', $message) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="focus:outline-none group relative">
                                                            <i class="fas fa-star {{ $message->is_starred ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}"></i>
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
                                                                <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                                                    Archive
                                                                </span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                                    <span class="font-medium">
                                                        @if(request('search'))
                                                            {!! App\Helpers\TextHelper::highlight($message->subject, request('search')) !!}
                                                        @else
                                                            {{ $message->subject }}
                                                        @endif
                                                    </span>
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

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Debug flag
        const DEBUG = true;
        
        function log(...args) {
            if (DEBUG) console.log(...args);
        }

        // Initialize Pusher with debug logging
        Pusher.logToConsole = true;
        
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            encrypted: true,
            authEndpoint: '/broadcasting/auth'
        });

        log('Initializing Pusher with key:', '{{ config('broadcasting.connections.pusher.key') }}');
        log('Cluster:', '{{ config('broadcasting.connections.pusher.options.cluster') }}');

        // Subscribe to the private channel
        const channelName = `private-messages.{{ auth()->id() }}`;
        const channel = pusher.subscribe(channelName);
        log('Subscribing to channel:', channelName);

        // Connection status debugging
        pusher.connection.bind('connecting', () => {
            log('Connecting to Pusher...');
        });

        pusher.connection.bind('connected', () => {
            log('Successfully connected to Pusher');
        });

        pusher.connection.bind('unavailable', () => {
            log('Network issues, Pusher unavailable');
        });

        pusher.connection.bind('failed', () => {
            log('Failed to connect to Pusher');
        });

        pusher.connection.bind('error', error => {
            console.error('Pusher connection error:', error);
        });

        // Channel subscription debugging
        channel.bind('pusher:subscription_succeeded', () => {
            log('Successfully subscribed to channel:', channelName);
        });

        channel.bind('pusher:subscription_error', error => {
            console.error('Channel subscription error:', error);
        });

        // Listen for new messages
        channel.bind('new.message', function(data) {
            log('New message received:', data);
            log('Message mark data:', data.mark); // Debug log
            
            const tbody = document.querySelector('tbody');
            if (!tbody) {
                console.error('Table body not found');
                return;
            }

            try {
                const tr = createMessageRow(data);
                
                // Insert at the top of the table
                if (tbody.firstChild) {
                    tbody.insertBefore(tr, tbody.firstChild);
                } else {
                    tbody.appendChild(tr);
                }

                // Remove empty inbox message if it exists
                const emptyMessage = document.querySelector('.text-gray-500.text-center');
                if (emptyMessage) {
                    emptyMessage.remove();
                }

                // Update the checkbox event listeners
                updateCheckboxListeners();

                // Update tab display immediately
                updateTabDisplay();

                // Show notification
                showNotification(data);
            } catch (error) {
                console.error('Error creating message row:', error);
                console.error('Message data:', data);
            }
        });

        // Helper function to create message row
        function createMessageRow(data) {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 font-semibold bg-blue-50';
            tr.setAttribute('data-message-id', data.id);
            tr.style.cursor = 'pointer';
            tr.onclick = () => window.location = `/mail/${data.id}`;

            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="selected[]" value="${data.id}" 
                               class="message-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        
                        <form action="/mail/${data.id}/star" method="POST" class="inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="focus:outline-none group relative">
                                <i class="fas fa-star text-gray-300 hover:text-yellow-400"></i>
                                <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                    Not Starred
                                </span>
                            </button>
                        </form>

                        <form action="/mail/${data.id}/archive" method="POST" class="inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="text-gray-400 hover:text-gray-600 focus:outline-none group relative">
                                <i class="fas fa-archive"></i>
                                <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                    Archive
                                </span>
                            </button>
                        </form>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${data.sender.username}</div>
                    <div class="text-sm text-gray-500">${data.sender.email}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-2">
                        ${data.mark && data.mark.is_important ? `
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i>Important
                            </span>
                        ` : ''}
                        ${data.mark && data.mark.is_urgent ? `
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                            </span>
                        ` : ''}
                    </div>
                    <div class="text-sm text-gray-900 mt-1">
                        ${data.attachments && data.attachments.length > 0 ? '<i class="fas fa-paperclip text-gray-400 mr-1"></i>' : ''}
                        <span class="font-medium">${data.subject}</span>
                    </div>
                    <div class="text-sm text-gray-500">${data.content.length > 100 ? data.content.substring(0, 100) + '...' : data.content}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${new Date(data.created_at).toLocaleString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <span class="group relative">
                            <i class="fas fa-envelope text-gray-600"></i>
                            <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-10">
                                Unread
                            </span>
                        </span>
                    </div>
                </td>
            `;

            return tr;
        }

        // Helper function to add event listeners to a row
        function addRowEventListeners(row) {
            // Star toggle
            const starForm = row.querySelector('form[action*="star"]');
            if (starForm) {
                starForm.addEventListener('submit', handleStarToggle);
            }

            // Archive toggle
            const archiveForm = row.querySelector('form[action*="archive"]');
            if (archiveForm) {
                archiveForm.addEventListener('submit', handleArchiveToggle);
            }

            // Checkbox
            const checkbox = row.querySelector('.message-checkbox');
            if (checkbox) {
                checkbox.addEventListener('change', handleCheckboxChange);
            }
        }

        // Event handler functions
        function handleStarToggle(e) {
            e.preventDefault();
            // ... existing star toggle code ...
        }

        function handleArchiveToggle(e) {
            e.preventDefault();
            // ... existing archive toggle code ...
        }

        function handleCheckboxChange() {
            const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            bulkActions.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
        }

        // Helper function to show notification
        function showNotification(data) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('New mail From ' + data.sender.username, {
                    icon: '/path/to/icon.png' // Optional: Add your notification icon
                });
            }
        }

        // Request notification permission
        if ('Notification' in window) {
            Notification.requestPermission();
        }

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

        // Helper function to update checkbox listeners
        function updateCheckboxListeners() {
            document.querySelectorAll('.message-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
                    const bulkActions = document.getElementById('bulkActions');
                    bulkActions.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
                });
            });
        }

        // Initialize checkbox listeners for existing checkboxes
        updateCheckboxListeners();

        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-link');
            let currentTab = 'primary';

            function updateTabDisplay() {
                const currentTab = document.querySelector('.tab-link.border-blue-500').dataset.tab;
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    if (currentTab === 'primary') {
                        row.style.display = '';
                    } else {
                        const hasMarks = row.querySelector('.bg-yellow-100, .bg-red-100');
                        row.style.display = hasMarks ? '' : 'none';
                    }
                });
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentTab = this.dataset.tab;
                    
                    // Update active tab styling
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-blue-500', 'text-blue-600');

                    updateTabDisplay();
                });
            });

            // Update display when new messages arrive
            channel.bind('new.message', function(data) {
                // Your existing new message code...
                
                // Update tab display after adding new message
                setTimeout(updateTabDisplay, 100);
            });
        });
    </script>
</x-app-layout> 