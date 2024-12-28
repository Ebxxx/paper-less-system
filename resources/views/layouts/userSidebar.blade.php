<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/bootstrap.js'])
    @vite(['resources/css/user-sidebar.css', 'resources/js/admin.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="logo">{{ auth()->user()->username }}</span>
            </div>
            <nav class="nav-menu">
                <a href="{{ route('mail.compose') }}"
                   class="nav-item {{ request()->routeIs('mail.compose') ? 'active' : '' }}">
                    <i class="fas fa-paper-plane mr-2"></i> Compose
                </a>
                <a href="{{ route('mail.inbox') }}" 
                   class="nav-item {{ request()->routeIs('mail.inbox') ? 'active' : '' }} relative"
                   id="inbox-link">
                    <i class="fa fa-inbox mr-2"></i> Inbox
                    @if(auth()->user()->unreadMessages()->count() > 0)
                        <span class="absolute top-0 right-0 transform translate-x-1 -translate-y-1 h-3 w-3 bg-red-600 rounded-full" id="unread-dot"></span>
                    @endif
                </a>
                <a href="{{ route('mail.starred') }}"
                   class="nav-item {{ request()->routeIs('mail.starred') ? 'active' : '' }}">
                    <i class="fas fa-star mr-2"></i> Starred
                </a>
                <a href="{{ route('mail.sent') }}"
                   class="nav-item {{ request()->routeIs('mail.sent') ? 'active' : '' }}">
                    <i class="fas fa-envelope mr-2"></i> Sent
                </a>
                <a href="{{ route('mail.archive') }}"
                   class="nav-item {{ request()->routeIs('mail.archive') ? 'active' : '' }}">
                    <i class="fas fa-archive mr-2"></i> Archive
                </a>
                <!-- <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form> -->
            </nav>
        </aside>

               <!-- Main Content -->
               <div class="main-content" id="main-content">
            <div class="top-bar">
                <button class="toggle-btn" id="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex-1 px-4">
                    <form action="{{ request()->url() }}" method="GET" class="max-w-lg relative">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search-input"
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                                   placeholder="Search in {{ request()->segment(2) ?? 'messages' }}..."
                                   autocomplete="off">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                            @if(request('search'))
                                <button type="button" 
                                        onclick="window.location.href='{{ request()->url() }}'"
                                        class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Search Suggestions Dropdown -->
                        <div id="search-suggestions" class="absolute w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50">
                            <!-- Results Container -->
                            <div id="search-results" class="max-h-64 overflow-y-auto">
                                <!-- Results will be populated here -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                        <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->username }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                             @click="open = false">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('Profile') }}
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main>
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enable Pusher logging for debugging
            Pusher.logToConsole = true;

            // Initialize Pusher
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true,
                authEndpoint: '/broadcasting/auth'
            });

            // Subscribe to the private channel
            const channelName = 'private-messages.{{ auth()->id() }}';
            const channel = pusher.subscribe(channelName);

            console.log('Subscribing to channel:', channelName);

            // Debug connection status
            pusher.connection.bind('connected', () => {
                console.log('Connected to Pusher');
            });

            pusher.connection.bind('error', error => {
                console.error('Pusher connection error:', error);
            });

            // Function to add/show red dot
            function showUnreadDot() {
                const inboxLink = document.getElementById('inbox-link');
                let redDot = document.getElementById('unread-dot');
                
                if (!redDot) {
                    redDot = document.createElement('span');
                    redDot.id = 'unread-dot';
                    redDot.className = 'absolute top-0 right-0 transform translate-x-1 -translate-y-1 h-3 w-3 bg-red-600 rounded-full';
                    inboxLink.appendChild(redDot);
                }
            }

            // Function to remove/hide red dot
            function hideUnreadDot() {
                const redDot = document.getElementById('unread-dot');
                if (redDot) {
                    redDot.remove();
                }
            }

            // Listen for new messages
            channel.bind('new.message', function(data) {
                console.log('New message received:', data);
                showUnreadDot();
                
                // Show notification
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('New mail From ' + data.sender.username);
                }
            });

            // Listen for subscription succeeded
            channel.bind('pusher:subscription_succeeded', () => {
                console.log('Successfully subscribed to channel');
            });

            // Listen for subscription error
            channel.bind('pusher:subscription_error', error => {
                console.error('Subscription error:', error);
            });

            // Check unread messages periodically (as backup)
            function checkUnreadMessages() {
                fetch('/api/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            showUnreadDot();
                        } else {
                            hideUnreadDot();
                        }
                    })
                    .catch(error => console.error('Error checking unread messages:', error));
            }

            // Check unread messages every 30 seconds as a fallback
            setInterval(checkUnreadMessages, 30000);

            // Initial check for unread messages
            checkUnreadMessages();

            // Request notification permission if not granted
            if ('Notification' in window && Notification.permission !== 'granted') {
                Notification.requestPermission();
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove the auto-submit functionality
            const searchForm = document.querySelector('form[action*="search"]');
            const searchInput = document.querySelector('input[name="search"]');
            
            if (searchForm && searchInput) {
                // Prevent form submission on Enter key
                searchForm.addEventListener('submit', function(e) {
                    if (!searchInput.value.trim()) {
                        e.preventDefault();
                    }
                });

                // Clear search when clicking the clear button
                const clearButton = searchForm.querySelector('button[type="button"]');
                if (clearButton) {
                    clearButton.addEventListener('click', function() {
                        window.location.href = searchForm.getAttribute('action');
                    });
                }
            }
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const suggestionsPanel = document.getElementById('search-suggestions');
        const resultsContainer = document.getElementById('search-results');
        let searchTimeout;

        // Show suggestions panel when input is focused
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.length > 0) {
                suggestionsPanel.classList.remove('hidden');
                fetchSearchSuggestions(searchInput.value);
            }
        });

        // Hide suggestions panel when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !suggestionsPanel.contains(e.target)) {
                suggestionsPanel.classList.add('hidden');
            }
        });

        // Handle input changes
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length > 0) {
                suggestionsPanel.classList.remove('hidden');
                searchTimeout = setTimeout(() => {
                    fetchSearchSuggestions(query);
                }, 300);
            } else {
                suggestionsPanel.classList.add('hidden');
            }
        });

        function fetchSearchSuggestions(query) {
            resultsContainer.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Searching...
                </div>
            `;

            fetch(`/api/search-suggestions?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                resultsContainer.innerHTML = '';

                // Add "Search for" option
                const searchForDiv = document.createElement('div');
                searchForDiv.className = 'p-2 hover:bg-gray-50 cursor-pointer flex items-center justify-between border-b border-gray-200';
                searchForDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-search text-gray-400 mr-3"></i>
                        <div class="text-sm">Search for "${query}"</div>
                    </div>
                    <div class="text-xs text-gray-500">Press Enter</div>
                `;
                searchForDiv.onclick = () => searchInput.closest('form').submit();
                resultsContainer.appendChild(searchForDiv);

                // Add message results
                if (data.length > 0) {
                    data.forEach(message => {
                        const div = document.createElement('div');
                        div.className = 'p-2 hover:bg-gray-50 cursor-pointer flex items-center justify-between search-result-item';
                        div.innerHTML = `
                            <div class="flex items-center flex-1">
                                <i class="fas ${message.is_unread ? 'fa-envelope' : 'fa-envelope-open'} text-gray-400 mr-3"></i>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium truncate">${message.subject}</div>
                                    <div class="text-xs text-gray-500 truncate">
                                        ${message.sender} - ${message.preview}
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex items-center">
                                ${message.has_attachments ? '<i class="fas fa-paperclip text-gray-400 mr-2"></i>' : ''}
                                <span class="text-xs text-gray-500">${message.date}</span>
                            </div>
                        `;
                        div.onclick = () => window.location.href = `/mail/${message.id}`;
                        resultsContainer.appendChild(div);
                    });
                } else if (query.length > 0) {
                    resultsContainer.innerHTML += `
                        <div class="p-4 text-center text-gray-500">
                            No results found for "${query}"
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                resultsContainer.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        An error occurred while searching
                    </div>
                `;
            });
        }

        // Function to add search filters
        function addFilter(filter) {
            const currentValue = searchInput.value;
            const filters = currentValue.split(' ').filter(f => !f.includes(':'));
            const newValue = [...filters, filter].join(' ');
            searchInput.value = newValue;
            searchInput.focus();
        }

        // Add keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const items = suggestionsPanel.querySelectorAll('.hover\\:bg-gray-50');
            const currentIndex = Array.from(items).findIndex(item => item.classList.contains('bg-gray-50'));
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < items.length - 1) {
                        items[currentIndex]?.classList.remove('bg-gray-50');
                        items[currentIndex + 1]?.classList.add('bg-gray-50');
                    }
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        items[currentIndex]?.classList.remove('bg-gray-50');
                        items[currentIndex - 1]?.classList.add('bg-gray-50');
                    }
                    break;
                case 'Enter':
                    e.preventDefault();
                    const selectedItem = items[currentIndex];
                    if (selectedItem) {
                        selectedItem.click();
                    } else {
                        this.closest('form').submit();
                    }
                    break;
            }
        });
    });
    </script>
    <style>
    .search-suggestion {
        @apply p-2 hover:bg-gray-50 cursor-pointer;
    }

    .search-suggestion:not(:last-child) {
        @apply border-b border-gray-100;
    }

    #search-suggestions {
        @apply shadow-lg;
        max-height: 400px;
    }

    #search-results {
        scrollbar-width: thin;
        scrollbar-color: #CBD5E0 #EDF2F7;
    }

    #search-results::-webkit-scrollbar {
        width: 6px;
    }

    #search-results::-webkit-scrollbar-track {
        background: #EDF2F7;
    }

    #search-results::-webkit-scrollbar-thumb {
        background-color: #CBD5E0;
        border-radius: 3px;
    }
    </style>
</body>
</html>
