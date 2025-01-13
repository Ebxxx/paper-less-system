<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <!-- Add SweetAlert2 directly in head -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <h3 class="text-white">
                    <span class="logo">
                        <span class="logo-edu">Edu</span><span class="logo-mail">MAIL</span>
                    </span>
                </h3>
            </div>
            <nav class="nav-menu flex flex-col h-full">
                <!-- Fixed top section -->
                <div class="flex-none">
                    <a href="{{ route('mail.compose') }}"
                       class="nav-item compose-btn">
                        <div class="flex items-center">
                            <i class="fas fa-pen mr-2"></i> Compose
                        </div>
                    </a>
                    <a href="{{ route('mail.inbox') }}" 
                        class="nav-item {{ request()->routeIs('mail.inbox') ? 'active' : '' }} relative"
                        id="inbox-link">
                        <div class="flex justify-between items-center w-full">
                            <div class="flex items-center">
                                <i class="fa fa-inbox mr-3"></i> Inbox
                            </div>
                            <span class="text-sm">
                                {{ auth()->user()->receivedMessages()->where('is_archived', false)->count() }}
                            </span>
                        </div>
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
                </div>

                <!-- Scrollable folders section -->
                <div class="flex-1 overflow-hidden mt-4">
                    <div class="border-t border-gray-700 pt-4">
                        <div class="flex items-center justify-between px-3 mb-2">
                            <h4 class="text-sm font-medium text-gray-400">Folders</h4>
                            <button onclick="createFolder()" 
                                    class="text-gray-400 hover:text-white focus:outline-none">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div id="folders-list" class="overflow-y-auto" style="max-height: calc(100vh - 350px);">
                            @forelse(auth()->user()->folders ?? [] as $folder)
                                <div class="folder-item group relative px-3 py-2 hover:bg-gray-700">
                                    <a href="{{ route('mail.folder', $folder->id) }}" 
                                       class="flex items-center justify-between text-sm text-gray-300 hover:text-white">
                                        <div class="flex items-center">
                                            <i class="fas fa-folder mr-2"></i>
                                            <span class="truncate">{{ $folder->name }}</span>
                                        </div>
                                        <!-- <span class="text-xs text-gray-500">{{ $folder->messages_count ?? 0 }}</span> -->
                                    </a>
                                    <div class="folder-actions absolute right-2 top-1/2 transform -translate-y-1/2 opacity-0 transition-opacity duration-200 ease-in-out group-hover:opacity-100">
                                        <button onclick="event.preventDefault(); editFolder({{ $folder->id }}, '{{ $folder->name }}')" 
                                                class="p-1 text-gray-400 hover:text-white focus:outline-none">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-gray-500 text-sm px-3 py-2">No folders yet</div>
                            @endforelse
                        </div>
                    </div>
                </div>
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
                            <div class="absolute left-2 top-2 text-gray-400">
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
                    <!-- Add Help & Guide Button -->
                    <div class="relative mr-4" x-data="{ showHelp: false }" @click.away="showHelp = false">
                        <button @click="showHelp = !showHelp" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <i class="fas fa-question-circle mr-2"></i>
                            Help & Guide
                        </button>

                        <!-- Help Dropdown -->
                        <div x-show="showHelp"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 mt-2 w-72 rounded-md shadow-lg origin-top-right right-0"
                             @click="showHelp = false">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-800">Quick Guide</h3>
                                    <p class="text-sm text-gray-500">Click it to see the full documentation</p>
                                </div>
                                
                                <div class="px-4 py-2">
                                    <div class="space-y-3">
                                        <div class="flex items-start cursor-pointer hover:bg-gray-50 p-2 rounded-lg" 
                                             x-data="{ showComposeGuide: false }"
                                             @click="showComposeGuide = true">
                                            <i class="fas fa-envelope mt-1 text-blue-500 w-5"></i>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-800">Compose Message</p>
                                                <p class="text-xs text-gray-500">Click the Compose button to create a new message</p>
                                            </div>

                                            <!-- Compose Message Guide Modal -->
                                            <template x-teleport="body">
                                                <div x-show="showComposeGuide" 
                                                     class="fixed inset-0 z-50 overflow-y-auto"
                                                     x-cloak>
                                                    <div class="flex items-center justify-center min-h-screen px-4">
                                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                                             @click="showComposeGuide = false"></div>

                                                        <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl">
                                                            <!-- Header -->
                                                            <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                                                                <h3 class="text-lg font-medium text-gray-900">
                                                                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                                                    Compose Message Guide
                                                                </h3>
                                                                <button @click="showComposeGuide = false" 
                                                                        class="text-gray-400 hover:text-gray-500">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>

                                                            <!-- Content -->
                                                            <div class="px-6 py-4">
                                                                <div class="space-y-4">
                                                                    <!-- Getting Started -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Getting Started</h4>
                                                                        <p class="text-sm text-gray-600">
                                                                            To compose a new message, click the "Compose" button located at the top of the sidebar. 
                                                                            This will open the message composition interface.
                                                                        </p>
                                                                    </div>

                                                                    <!-- Recipients -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Adding Recipients</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Type the recipient's email address in the "To" field</li>
                                                                            <li>For multiple recipients, click the check box to add multiple recipients</li>
                                                                            
                                                                        </ul>
                                                                    </div>

                                                                    <!-- Composing Content -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Message Content</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Enter a clear subject line</li>
                                                                            <li>Use the rich text editor to format your message</li>
                                                                            <li>You can include bold, italic, and underlined text</li>
                                                                            <li>Create bulleted or numbered lists</li>
                                                                            <li>Add hyperlinks to your text</li>
                                                                        </ul>
                                                                    </div>
                                                                         <!-- Message Marking -->
                                                                        <div class="bg-blue-50 p-4 rounded-lg">
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Message Marking</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>
                                                                                <span class="font-medium">Important Mark</span>
                                                                                <ul class="ml-6 mt-1 list-circle">
                                                                                    <li>Use the <i class="fas fa-exclamation-circle text-yellow-500"></i> checkbox to mark messages as important</li>
                                                                                    <li>Important messages are highlighted in the recipient's inbox</li>
                                                                                    <li>Useful for high-priority communications</li>
                                                                                </ul>
                                                                            </li>
                                                                            <li>
                                                                                <span class="font-medium">Urgent Mark</span>
                                                                                <ul class="ml-6 mt-1 list-circle">
                                                                                    <li>Use the <i class="fas fa-exclamation-triangle text-red-500"></i> checkbox for urgent messages</li>
                                                                                    <li>Urgent messages appear with special indicators</li>
                                                                                    <li>Recipients receive immediate notifications</li>
                                                                                </ul>
                                                                            </li>
                                                                        </ul>
                                                                    </div>

                                                                    <!-- Attachments -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Adding Attachments</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Click the attachment icon or drag and drop files</li>
                                                                            <li>Maximum file size: 25MB per file</li>
                                                                            <li>Supported file types: documents, images, PDFs</li>
                                                                        </ul>
                                                                    </div>


                                                                    <!-- Deadlines -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Setting Deadlines</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Enable the "Set Deadline" checkbox to add a deadline to your message</li>
                                                                            <li>Choose date and time using the datetime picker</li>
                                                                            <li>Recipients will see:
                                                                                <ul class="ml-6 mt-1 list-circle">
                                                                                    <li>Countdown timer until deadline</li>
                                                                                    <li>Visual indicators for approaching deadlines</li>
                                                                                    <li>Notifications before deadline expiration</li>
                                                                                </ul>
                                                                            </li>
                                                                            <li>Best practices:
                                                                                <ul class="ml-6 mt-1 list-circle">
                                                                                    <li>Set realistic deadlines with buffer time</li>
                                                                                    <li>Consider recipient time zones</li>
                                                                                    <li>Use with Important/Urgent marks for critical tasks</li>
                                                                                </ul>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Footer -->
                                                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                                                                <button @click="showComposeGuide = false"
                                                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                    Got it
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                        </div>
                                        
                                        <div class="flex items-start cursor-pointer hover:bg-gray-50 p-2 rounded-lg" 
                                             x-data="{ showFolderGuide: false }"
                                             @click="showFolderGuide = true">
                                            <i class="fas fa-folder mt-1 text-blue-500 w-5"></i>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-800">Manage Folder</p>
                                                <p class="text-xs text-gray-500">Create and organize folder to categorize your messages</p>
                                            </div>

                                            <!-- Folder Management Guide Modal -->
                                            <template x-teleport="body">
                                                <div x-show="showFolderGuide" 
                                                     class="fixed inset-0 z-50 overflow-y-auto"
                                                     x-cloak>
                                                    <div class="flex items-center justify-center min-h-screen px-4">
                                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                                             @click="showFolderGuide = false"></div>

                                                        <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl">
                                                            <!-- Header -->
                                                            <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                                                                <h3 class="text-lg font-medium text-gray-900">
                                                                    <i class="fas fa-folder mr-2 text-blue-500"></i>
                                                                    Folder Management Guide
                                                                </h3>
                                                                <button @click="showFolderGuide = false" 
                                                                        class="text-gray-400 hover:text-gray-500">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>

                                                            <!-- Content -->
                                                            <div class="px-6 py-4">
                                                                <div class="space-y-4">
                                                                    <!-- Creating Folders -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Creating New Folders</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Click the <i class="fas fa-plus text-gray-400"></i> icon next to "Folders" in the sidebar</li>
                                                                            <li>Enter a unique name for your folder</li>
                                                                            <li>Click "Create" to add the new folder</li>
                                                                            <li>New folders appear immediately in your sidebar</li>
                                                                        </ul>
                                                                    </div>

                                                                    <!-- Managing Folders -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Managing Existing Folders</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Hover over any folder to reveal management options</li>
                                                                            <li>Click the <i class="fas fa-ellipsis-v text-gray-400"></i> menu to:
                                                                                <ul class="ml-6 mt-1 list-circle">
                                                                                    <li>Rename folder</li>
                                                                                    <li>Delete folder</li>
                                                                                </ul>
                                                                            </li>
                                                                            <li>Message count appears next to each folder</li>
                                                                        </ul>
                                                                    </div>

                                                                    <!-- Organizing Messages -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Organizing Messages</h4>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Move messages to folders by:
                                                                                <ul class="ml-6 mt-1 list-circle">
                                                                                    <li>Dragging and dropping messages</li>
                                                                                    <li>Using the "Move to folder" option in message actions</li>
                                                                                    <li>Selecting multiple messages for bulk organization</li>
                                                                                </ul>
                                                                            </li>
                                                                            <li>Messages can be in multiple folders simultaneously</li>
                                                                        </ul>
                                                                    </div>

                                                                    <!-- Best Practices -->
                                                                    <div class="bg-blue-50 p-4 rounded-lg">
                                                                        <h4 class="text-md font-medium text-blue-900 mb-2">Best Practices</h4>
                                                                        <ul class="list-disc list-inside text-sm text-blue-800 space-y-2">
                                                                            <li>Create folders for specific projects or categories</li>
                                                                            <li>Use clear, descriptive folder names</li>
                                                                            <li>Regularly review and clean up unused folders</li>
                                                                            <li>Consider using a naming convention for better organization</li>
                                                                        </ul>
                                                                    </div>

                                                            <!-- Footer -->
                                                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                                                                <button @click="showFolderGuide = false"
                                                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                    Got it
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                        </div>
                                        
                                        <div class="flex items-start cursor-pointer hover:bg-gray-50 p-2 rounded-lg" 
                                             x-data="{ showSearchGuide: false }"
                                             @click="showSearchGuide = true">
                                            <i class="fas fa-search mt-1 text-blue-500 w-5"></i>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-800">Search Messages</p>
                                                <p class="text-xs text-gray-500">Learn how to effectively search through your messages</p>
                                            </div>

                                            <!-- Search Guide Modal -->
                                            <template x-teleport="body">
                                                <div x-show="showSearchGuide" 
                                                     class="fixed inset-0 z-50 overflow-y-auto"
                                                     x-cloak>
                                                    <div class="flex items-center justify-center min-h-screen px-4">
                                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                                             @click="showSearchGuide = false"></div>

                                                        <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl">
                                                            <!-- Header -->
                                                            <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                                                                <h3 class="text-lg font-medium text-gray-900">
                                                                    <i class="fas fa-search mr-2 text-blue-500"></i>
                                                                    Search Messages Guide
                                                                </h3>
                                                                <button @click="showSearchGuide = false" 
                                                                        class="text-gray-400 hover:text-gray-500">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>

                                                            <!-- Content -->
                                                            <div class="px-6 py-4">
                                                                <div class="space-y-4">
                                                                    <!-- Getting Started -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Getting Started</h4>
                                                                        <p class="text-sm text-gray-600 mb-2">
                                                                            The search bar is located at the top of your screen. As you type, results will appear in real-time.
                                                                        </p>
                                                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                                                            <li>Type your search terms in the search bar</li>
                                                                            <li>Results update automatically as you type</li>
                                                                            <li>Use the <i class="fas fa-times"></i> icon to clear your search</li>
                                                                            <li>Press Enter to see full search results</li>
                                                                        </ul>
                                                                    </div>

                                                                    <!-- What You Can Search -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">What You Can Search</h4>
                                                                        <div class="grid grid-cols-2 gap-4">
                                                                            <div class="bg-gray-50 p-3 rounded">
                                                                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                                                                    <li>Message subjects</li>
                                                                                    <li>Message content</li>
                                                                                    <li>Sender names</li>
                                                                                    <li>Recipient names</li>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="bg-gray-50 p-3 rounded">
                                                                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                                                                    <li>Email addresses</li>
                                                                                    <li>Attachment names</li>
                                                                                    <li>Dates</li>
                                                                                    <li>Message status</li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Search Features -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Search Features</h4>
                                                                        <div class="bg-blue-50 p-4 rounded-lg">
                                                                            <ul class="list-disc list-inside text-sm text-gray-700 space-y-2">
                                                                                <li>Real-time suggestions as you type</li>
                                                                                <li>Filter by:
                                                                                    <ul class="ml-6 mt-1 list-circle">
                                                                                        <li>Date range</li>
                                                                                        <li>Message status (read/unread)</li>
                                                                                        <li>Importance level</li>
                                                                                        <li>Has attachments</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li>Search within specific folders</li>
                                                                                <li>Advanced search operators</li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Search Results -->
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 mb-2">Understanding Search Results</h4>
                                                                        <div class="space-y-3">
                                                                            <p class="text-sm text-gray-600">Search results show:</p>
                                                                            <div class="bg-white border rounded-lg p-4">
                                                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                                                    <div>
                                                                                        <p class="font-medium mb-2">Message Information:</p>
                                                                                        <ul class="space-y-1 text-gray-600">
                                                                                            <li><i class="fas fa-user mr-2"></i>Sender name</li>
                                                                                            <li><i class="fas fa-envelope mr-2"></i>Subject line</li>
                                                                                            <li><i class="fas fa-clock mr-2"></i>Date and time</li>
                                                                                            <li><i class="fas fa-paperclip mr-2"></i>Attachment indicator</li>
                                                                                        </ul>
                                                                                    </div>
                                                                                    <div>
                                                                                        <p class="font-medium mb-2">Status Indicators:</p>
                                                                                        <ul class="space-y-1 text-gray-600">
                                                                                            <li><i class="fas fa-envelope text-blue-500 mr-2"></i>Unread</li>
                                                                                            <li><i class="fas fa-star text-yellow-500 mr-2"></i>Starred</li>
                                                                                            <li><i class="fas fa-exclamation-circle text-red-500 mr-2"></i>Important</li>
                                                                                            <li><i class="fas fa-clock text-orange-500 mr-2"></i>Deadline</li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Pro Tips -->
                                                                    <div class="bg-green-50 p-4 rounded-lg">
                                                                        <h4 class="text-md font-medium text-green-900 mb-2">Pro Tips</h4>
                                                                        <ul class="list-disc list-inside text-sm text-green-800 space-y-2">
                                                                            <li>Use keyboard shortcut <kbd>/</kbd> to quickly focus the search bar</li>
                                                                            <li>Press <kbd>↑</kbd> and <kbd>↓</kbd> to navigate through results</li>
                                                                            <li>Press <kbd>Enter</kbd> to open the selected result</li>
                                                                            <li>Press <kbd>Esc</kbd> to clear the search or close results</li>
                                                                            <li>Click the folder icon to search within specific folders</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Footer -->
                                                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                                                                <button @click="showSearchGuide = false"
                                                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                    Got it
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 mt-2">
                                    <a href="#" class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">
                                        View Full Documentation
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing User Dropdown -->
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
                                <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('Profile') }}
                                </a>
                               
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit-logout" class="block w-full px-4 py-2 text-left text-sm leading-5 text-red-600 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:bg-red-50 transition duration-150 ease-in-out">
                                        {{ __('Log out') }}
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
            // Initialize Pusher
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true,
                authEndpoint: '/broadcasting/auth'
            });

            // Subscribe to the private channel
            const channelName = `private-messages.{{ auth()->id() }}`;
            const channel = pusher.subscribe(channelName);

            // Get DOM elements
            const inboxCountElement = document.querySelector('#inbox-link .text-sm');
            const unreadDot = document.getElementById('unread-dot');

            // Function to update counts
            function updateCounts(unreadCount, inboxCount) {
                // Update inbox count
                if (inboxCountElement) {
                    inboxCountElement.textContent = inboxCount;
                }

                // Update unread indicator
                if (unreadCount > 0) {
                    showUnreadDot();
                } else {
                    hideUnreadDot();
                }
            }

            // Function to show notification
            function showNotification(data) {
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notification = new Notification('New Message', {
                        body: `${data.sender.username}: ${data.subject}`,
                        icon: '/path/to/your/icon.png'
                    });

                    // Optional: Click on notification to open message
                    notification.onclick = function() {
                        window.open(`/mail/${data.id}`, '_blank');
                    };
                }
            }

            // Listen for new messages
            channel.bind('new.message', function(data) {
                console.log('New message received:', data);
                
                // Update counts
                updateCounts(data.unread_count, data.inbox_count);
                
                // Show notification
                showNotification(data);
                
                // Play notification sound
                playNotificationSound();
            });

            // Listen for read status updates
            channel.bind('message.read', function(data) {
                console.log('Message read event:', data);
                
                // Update counts
                updateCounts(data.unread_count, data.inbox_count);
            });

            function playNotificationSound() {
                try {
                    const audio = new Audio('/path/to/notification-sound.mp3');
                    audio.play().catch(e => console.log('Error playing sound:', e));
                } catch (e) {
                    console.log('Error with notification sound:', e);
                }
            }

            function showUnreadDot() {
                if (!unreadDot) {
                    const inboxLink = document.getElementById('inbox-link');
                    const newDot = document.createElement('span');
                    newDot.id = 'unread-dot';
                    newDot.className = 'absolute top-0 right-0 transform translate-x-1 -translate-y-1 h-3 w-3 bg-red-600 rounded-full';
                    inboxLink.appendChild(newDot);
                }
            }

            function hideUnreadDot() {
                if (unreadDot) {
                    unreadDot.remove();
                }
            }

            // Request notification permission
            if ('Notification' in window && Notification.permission !== 'granted') {
                Notification.requestPermission();
            }

            // Debug connection status
            pusher.connection.bind('connected', () => {
                console.log('Connected to Pusher');
            });

            pusher.connection.bind('error', error => {
                console.error('Pusher connection error:', error);
            });

            // Remove the periodic check since we're using real-time updates now
            // The real-time events will handle all updates
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.createFolder = function() {
            Swal.fire({
                title: 'New label',
                html: `
                    <div class="text-left">
                        <p class="text-sm text-gray-600 mb-2">Please enter a new label name:</p>
                        <input type="text" id="folderName" class="swal2-input border-blue-400">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Create',
                cancelButtonText: 'Cancel',
                customClass: {
                    container: '',
                    popup: 'rounded-lg',
                    title: 'text-lg font-normal text-left pl-6 pt-4',
                    htmlContainer: 'px-6',
                    input: 'border border-gray-300',
                    actions: 'p-4',
                    confirmButton: 'bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-6 rounded-md text-sm font-medium',
                    cancelButton: 'text-blue-600 hover:bg-gray-100 py-2 px-6 rounded-md text-sm font-medium'
                },
                buttonsStyling: false,
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                hideClass: {
                    popup: '',
                    backdrop: ''
                },
                preConfirm: () => {
                    const folderName = document.getElementById('folderName').value;
                    if (!folderName) {
                        Swal.showValidationMessage('Please enter a folder name');
                        return false;
                    }
                    return folderName;
                }
            }).then((result) => {
                if (result.value) {
                    fetch('{{ route("folders.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ name: result.value })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to create folder');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            text: error.message,
                            customClass: {
                                confirmButton: 'text-blue-600 hover:bg-gray-100 py-2 px-6 rounded-md text-sm font-medium'
                            },
                            buttonsStyling: false
                        });
                    });
                }
            });

            // Style adjustments after modal is shown
            setTimeout(() => {
                const modal = document.querySelector('.swal2-popup');
                if (modal) {
                    modal.style.padding = '0';
                    const input = document.getElementById('folderName');
                    if (input) {
                        input.style.width = '100%';
                        input.style.marginLeft = '0';
                        input.style.border = '1px solid #ccc';
                        input.style.borderRadius = '4px';
                        input.style.padding = '8px';
                        input.focus();
                    }
                }
            }, 100);
        }

        window.editFolder = function(folderId, folderName) {
            Swal.fire({
                title: 'Edit label',
                html: `
                    <div class="text-left">
                        <p class="text-sm text-gray-600 mb-2">Please enter a new label name:</p>
                        <input type="text" id="editFolderName" class="swal2-input border-blue-400">
                    </div>
                `,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                customClass: {
                    container: '',
                    popup: 'rounded-lg',
                    title: 'text-lg font-normal text-left pl-6 pt-4',
                    htmlContainer: 'px-6',
                    input: 'border border-gray-300',
                    actions: 'p-4',
                    confirmButton: 'bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-6 rounded-md text-sm font-medium',
                    denyButton: 'text-red-600 hover:bg-red-50 py-2 px-6 rounded-md text-sm font-medium',
                    cancelButton: 'text-blue-600 hover:bg-gray-100 py-2 px-6 rounded-md text-sm font-medium'
                },
                buttonsStyling: false,
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                hideClass: {
                    popup: '',
                    backdrop: ''
                },
                preConfirm: () => {
                    const newName = document.getElementById('editFolderName').value;
                    if (!newName) {
                        Swal.showValidationMessage('Please enter a folder name');
                        return false;
                    }
                    return { action: 'rename', name: newName };
                },
                preDeny: () => {
                    return Swal.fire({
                        title: 'Delete folder?',
                        text: 'This action cannot be undone',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            confirmButton: 'text-red-600 hover:bg-red-50 py-2 px-6 rounded-md text-sm font-medium',
                            cancelButton: 'text-gray-600 hover:bg-gray-100 py-2 px-6 rounded-md text-sm font-medium'
                        },
                        buttonsStyling: false,
                        showClass: {
                            popup: 'swal2-noanimation',
                            backdrop: 'swal2-noanimation'
                        },
                        hideClass: {
                            popup: '',
                            backdrop: ''
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            return { action: 'delete' };
                        }
                        return false;
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Handle rename
                    fetch(`/folders/${folderId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ name: result.value.name })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to rename folder');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            text: error.message,
                            customClass: {
                                confirmButton: 'text-blue-600 hover:bg-gray-100 py-2 px-6 rounded-md text-sm font-medium'
                            },
                            buttonsStyling: false,
                            showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                            },
                            hideClass: {
                                popup: '',
                                backdrop: ''
                            }
                        });
                    });
                } else if (result.isDenied && result.value?.action === 'delete') {
                    // Handle delete
                    fetch(`/folders/${folderId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to delete folder');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            text: error.message,
                            customClass: {
                                confirmButton: 'text-blue-600 hover:bg-gray-100 py-2 px-6 rounded-md text-sm font-medium'
                            },
                            buttonsStyling: false,
                            showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                            },
                            hideClass: {
                                popup: '',
                                backdrop: ''
                            }
                        });
                    });
                }
            });

            // Style adjustments after modal is shown
            setTimeout(() => {
                const modal = document.querySelector('.swal2-popup');
                if (modal) {
                    modal.style.padding = '0';
                    const input = document.getElementById('editFolderName');
                    if (input) {
                        input.style.width = '100%';
                        input.style.marginLeft = '0';
                        input.style.border = '1px solid #ccc';
                        input.style.borderRadius = '4px';
                        input.style.padding = '8px';
                        input.focus();
                        input.select();
                        input.value = folderName; // Set the current folder name
                    }
                }
            }, 100);
        }
    });
    </script>
    <style>
    .swal2-popup {
        padding: 0 !important;
    }

    .swal2-actions {
        justify-content: flex-end !important;
        border-top: 1px solid #e5e7eb;
        margin-top: 1rem !important;
    }

    .swal2-input {
        margin: 0 !important;
        box-shadow: none !important;
    }
    </style>
    <style>
    /* Ensure the sidebar takes full height */
    .sidebar {
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Ensure proper layout */
    .nav-menu {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 60px); /* Adjust for sidebar header */
        overflow: hidden;
    }

    /* Folders section */
    #folders-list {
        overflow-y: auto;
        height: calc(100vh - 450px); /* Adjusted fixed height */
        padding-bottom: 20px;
    }

    /* Custom scrollbar styling */
    #folders-list::-webkit-scrollbar {
        width: 4px;
    }

    #folders-list::-webkit-scrollbar-track {
        background: transparent;
    }

    #folders-list::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 2px;
    }

    #folders-list::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.8);
    }

    /* Firefox scrollbar styling */
    #folders-list {
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }
    </style>
    <style>
    .folder-item .folder-actions {
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }
    
    .folder-item:hover .folder-actions {
        opacity: 1;
    }
    
    .folder-actions {
        z-index: 10;
    }
    </style>
</body>
</html>
