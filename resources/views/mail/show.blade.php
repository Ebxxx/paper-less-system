<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Message Header -->
                    <div class="border-b pb-4 mb-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-semibold">{{ $message->subject }}</h2>
                                <!-- Add Marks Display -->
                                @if($message->mark)
                                    <div class="flex items-center space-x-2 mt-2">
                                        @if($message->mark->is_important)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>Important
                                            </span>
                                        @endif
                                        @if($message->mark->is_urgent)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                            </span>
                                        @endif
                                        @if($message->mark->deadline)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                {{ now() > $message->mark->deadline ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                <i class="fas fa-clock mr-1"></i>
                                                Deadline: {{ $message->mark->deadline->format('M d, Y h:i A') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center space-x-3">
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
                                <form action="{{ route('mail.toggle-star', $message) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-400 hover:text-yellow-500 focus:outline-none group relative">
                                        <i class="fas fa-star {{ $message->is_starred ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        <!-- Tooltip -->
                                        <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                            {{ $message->is_starred ? 'Starred' : 'Not Starred' }}
                                        </span>
                                    </button>
                                </form>

                                @if(auth()->id() === $message->to_user_id)
                                    <form action="{{ route('mail.toggle-archive', $message) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-gray-400 hover:text-gray-600 focus:outline-none group relative">
                                            <i class="fas fa-archive"></i>
                                            <!-- Tooltip -->
                                            <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                                {{ $message->is_archived ? 'Unarchive' : 'Archive' }}
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- User Details Section - Gmail-like style -->
                        <div class="grid grid-cols-1 gap-2 text-sm mb-4">
                            <!-- From section with expanded details -->
                            <div class="group relative">
                                <div class="flex items-center">
                                    <span class="text-gray-600 w-16">From:</span>
                                    <div class="flex-1">
                                        <span class="font-medium">{{ $message->sender->username }}</span>
                                        <span class="text-gray-600">&lt;{{ $message->sender->email }}&gt;</span>
                                        
                                        <!-- Expandable details box -->
                                        <div class="hidden group-hover:block absolute left-0 mt-2 w-96 bg-white border rounded-lg shadow-lg p-4 z-20">
                                            <div class="flex items-start space-x-4">
                                                <!-- User Avatar/Initial -->
                                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-lg font-semibold">
                                                    {{ strtoupper(substr($message->sender->username, 0, 1)) }}
                                                </div>
                                                
                                                <!-- User Details -->
                                                <div class="flex-1">
                                                    <h3 class="font-medium text-base">
                                                    {{ $message->sender->prefix }} {{ $message->sender->first_name }} {{ $message->sender->middle_name }} {{ $message->sender->last_name }} {{ $message->sender->order_title }}
                                                    </h3>
                                                    <p class="text-gray-600">{{ $message->sender->job_title }}</p>
                                                    <p class="text-gray-600">{{ $message->sender->program }} - {{ $message->sender->department }}</p>
                                                    <p class="text-gray-600">{{ $message->sender->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- To section with expanded details -->
                            <div class="group relative">
                                <div class="flex items-center">
                                    <span class="text-gray-600 w-16">To:</span>
                                    <div class="flex-1">
                                        <span class="font-medium">{{ $message->recipient->username }}</span>
                                        <span class="text-gray-600">&lt;{{ $message->recipient->email }}&gt;</span>
                                        
                                        <!-- Expandable details box -->
                                        <div class="hidden group-hover:block absolute left-0 mt-2 w-96 bg-white border rounded-lg shadow-lg p-4 z-20">
                                            <div class="flex items-start space-x-4">
                                                <!-- User Avatar/Initial -->
                                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-lg font-semibold">
                                                    {{ strtoupper(substr($message->recipient->username, 0, 1)) }}
                                                </div>
                                                
                                                <!-- User Details -->
                                                <div class="flex-1">
                                                    <h3 class="font-medium text-base">
                                                        {{ $message->recipient->prefix }} {{ $message->recipient->first_name }} {{ $message->recipient->middle_name }} {{ $message->recipient->last_name }} {{ $message->recipient->order_title }}
                                                    </h3>
                                                    <p class="text-gray-600">{{ $message->recipient->job_title }}</p>
                                                    <p class="text-gray-600">{{ $message->recipient->program }} - {{ $message->recipient->department }}</p>
                                                    <p class="text-gray-600">{{ $message->recipient->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date section -->
                            <div class="flex items-center">
                                <span class="text-gray-600 w-16">Date:</span>
                                <span class="font-medium">{{ $message->created_at->format('M d, Y, h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="prose max-w-none mb-8">
                    <h3 class="text-lg font-medium mb-4">New Message</h3>
                        <div class="border-l-4 border-gray-200 pl-4">
                            <div class="ml-11">  <!-- Content wrapper with consistent indentation -->
                                <!-- Message Text -->
                                <div class="whitespace-pre-line mb-4">
                                    {!! nl2br(e($message->content)) !!}
                                </div>

                                <!-- Attachments -->
                                @if($message->attachments->count() > 0)
                                    <div class="mt-3">
                                        <div class="text-sm text-gray-500">Attachments:</div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($message->attachments as $attachment)
                                                <a href="{{ route('mail.download', $attachment) }}" 
                                                   class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    {{ $attachment->original_filename }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Section - Moved here -->
                    <!-- @if(optional($message->attachments)->count() > 0)
                        <div class="mb-8 border-t pt-4">
                            <h3 class="text-lg font-medium mb-2">Attachments</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($message->attachments as $attachment)
                                    <div class="flex items-center p-3 border rounded-lg">
                                        <i class="fas fa-file mr-3 text-gray-400"></i>
                                        <div class="flex-grow">
                                            <div class="text-sm font-medium">{{ $attachment->original_filename }}</div>
                                            <div class="text-xs text-gray-500">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                                        </div>
                                        <a href="{{ route('mail.download', $attachment) }}" 
                                           class="ml-4 text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif -->

                    <!-- Add conversation thread section -->
                    @if($message->replies->count() > 0 || $message->parent_id)
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium mb-4">Conversation</h3>
                            
                            <div class="space-y-6">
                                @php
                                    $rootMessage = $message->parent_id ? $message->thread() : $message;
                                    $conversationMessages = collect([$rootMessage])
                                        ->merge($rootMessage->getAllReplies())
                                        ->sortBy('created_at');
                                @endphp

                                @foreach($conversationMessages as $threadMessage)
                                    <div class="border-l-4 {{ $threadMessage->id === $message->id ? 'border-blue-500' : 'border-gray-200' }} pl-4"
                                         data-message-id="{{ $threadMessage->id }}">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-start space-x-3">
                                                <!-- User Avatar -->
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-semibold">
                                                    {{ strtoupper(substr($threadMessage->sender->username, 0, 1)) }}
                                                </div>
                                                
                                                <div>
                                                    <div class="flex items-center">
                                                        <span class="font-medium">{{ $threadMessage->sender->username }}</span>
                                                        <span class="mx-2 text-gray-500">→</span>
                                                        <span class="font-medium">{{ $threadMessage->recipient->username }}</span>
                                                    </div>
                                                    <span class="text-gray-500 text-sm">{{ $threadMessage->created_at->format('M d, Y h:i A') }}</span>
                                                    
                                                    <!-- Message Marks -->
                                                    @if($threadMessage->mark)
                                                        <div class="flex items-center space-x-2 mt-1">
                                                            @if($threadMessage->mark->is_important)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    <i class="fas fa-exclamation-circle mr-1"></i>Important
                                                                </span>
                                                            @endif
                                                            @if($threadMessage->mark->is_urgent)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                                                </span>
                                                            @endif
                                                            @if($threadMessage->mark->deadline)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                                    {{ now() > $threadMessage->mark->deadline ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    Deadline: {{ $threadMessage->mark->deadline->format('M d, Y h:i A') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="read-status text-right">
                                                @if($threadMessage->read_at && $threadMessage->from_user_id === auth()->id())
                                                    <i class="fas fa-check-double text-blue-500"></i>
                                                    <span class="text-xs text-gray-500">Read {{ $threadMessage->read_at->format('M d, Y h:i A') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Message Content -->
                                        <div class="prose max-w-none text-gray-700 ml-11">
                                            {!! nl2br(e($threadMessage->content)) !!}
                                        </div>

                                        <!-- Show attachments if any -->
                                        @if($threadMessage->attachments->count() > 0)
                                            <div class="mt-3 ml-11">
                                                <div class="text-sm text-gray-500">Attachments:</div>
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    @foreach($threadMessage->attachments as $attachment)
                                                        <a href="{{ route('mail.download', $attachment) }}" 
                                                           class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                                            <i class="fas fa-paperclip mr-1"></i>
                                                            {{ $attachment->original_filename }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-6 flex space-x-4">
                        <button type="button"
                           onclick="toggleReplyForm()"
                           class="action-button">
                            <i class="fas fa-reply"></i><span class="ml-3">Reply</span>
                        </button>
                        <button type="button"
                           onclick="toggleForwardForm()"
                           class="action-button">
                            <i class="fas fa-forward"></i><span class="ml-3">Forward</span>
                        </button>
                    </div>

                    <!-- Add this temporarily for debugging -->
                    <!-- <div class="text-sm text-gray-500">Debug: Message ID: {{ $message->id }}</div> -->

                    <!-- Inline Reply Form -->
                    <div id="replyForm" class="mt-6 border-t pt-6 hidden">
                        <form action="{{ route('mail.send') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $message->id }}">
                            <!-- Add this temporarily for debugging -->
                            <!-- <div class="text-sm text-gray-500">Debug: Parent ID being sent: {{ $message->id }}</div>
                             -->
                            <input type="hidden" name="to_user_ids[]" value="{{ $message->sender->id }}">
                            <input type="hidden" name="subject" value="{{ str_starts_with($message->subject, 'Re:') ? $message->subject : 'Re: ' . $message->subject }}">

                            <!-- Pre-reply Options -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Reply:</label>
                                <div class="flex space-x-4">
                                    <button type="button" 
                                            onclick="setPreReply('approved')"
                                            class="pre-reply-btn px-4 py-2 text-sm rounded-md border">
                                        <i class="fas fa-check text-green-500 mr-2"></i>Approve
                                    </button>
                                    <button type="button" 
                                            onclick="setPreReply('rejected')"
                                            class="pre-reply-btn px-4 py-2 text-sm rounded-md border">
                                        <i class="fas fa-times text-red-500 mr-2"></i>Reject
                                    </button>
                                    <button type="button" 
                                            onclick="setPreReply('thank_you')"
                                            class="pre-reply-btn px-4 py-2 text-sm rounded-md border">
                                        <i class="fas fa-heart text-pink-500 mr-2"></i>Thank You
                                    </button>
                                </div>
                                <input type="hidden" name="pre_reply" id="preReplyInput">
                            </div>

                            <!-- Message Content -->
                            <div>
                                <textarea name="content" id="replyContent" rows="6" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Write your reply..."></textarea>
                            </div>

                            <!-- Message Options -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-6">
                                    <!-- Important Mark -->
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_important" value="1"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <i class="fas fa-exclamation-circle text-yellow-500 mr-1"></i> Mark as Important
                                        </span>
                                    </label>

                                    <!-- Urgent Mark -->
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_urgent" value="1"
                                            class="rounded border-gray-300 text-red-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Mark as Urgent
                                        </span>
                                    </label>
                                </div>

                                <!-- Deadline -->
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" id="has_deadline"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">Set Deadline</span>
                                    </label>
                                    
                                    <div id="deadline_container" class="hidden">
                                        <input type="datetime-local" name="deadline" id="deadline"
                                            class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons Bar -->
                            <div class="flex items-center space-x-2">
                                <button type="submit" 
                                    class="action-button">
                                    <i class="fas fa-paper-plane"></i><span class="ml-3">Send Reply</span>
                                </button>

                                <!-- Attachment Button -->
                                <label class="cursor-pointer inline-flex items-center px-2 py-1 text-sm bg-transparent text-gray-700 hover:bg-gray-100 rounded">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" name="attachments[]" multiple
                                        class="hidden"
                                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                </label>

                                <button type="button" 
                                    onclick="toggleReplyForm()"
                                    class="inline-flex items-center px-2 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    Cancel
                                </button>
                            </div>

                            <!-- Selected Files Display -->
                            <div id="selected-files" class="mt-2 space-y-2"></div>
                        </form>
                    </div>

                    <!-- Forward Form -->
                    <div id="forwardForm" class="mt-6 border-t pt-6 hidden">
                        <form action="{{ route('mail.send') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <!-- Add this temporarily for debugging -->
                            <!-- <div class="text-sm text-gray-500">Debug: Forwarding Message ID: {{ $message->id }}</div> -->
                            
                            <!-- Recipients Selection -->
                            <div class="relative">
                            <label class="text-sm font-medium mb-2">To:</label>
                                <label class="block text-sm font-medium text-gray-700 mb-2"></label>
                                <div class="relative">
                                    <button type="button" 
                                            onclick="toggleForwardRecipientDropdown()"
                                            class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-left cursor-pointer focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <span id="forwardSelectedRecipientsText">Select Recipients</span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </span>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="forwardRecipientDropdown" 
                                         class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                        <div class="sticky top-0 bg-white p-2 border-b">
                                            <input type="text" 
                                                   id="forwardRecipientSearch" 
                                                   placeholder="Search recipients..." 
                                                   class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        </div>
                                        @foreach(\App\Models\User::where('id', '!=', auth()->id())->where('role', '!=', 'admin')->get() as $user)
                                            <div class="recipient-option px-4 py-2 hover:bg-gray-100">
                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                    <input type="checkbox" 
                                                           name="to_user_ids[]" 
                                                           value="{{ $user->id }}" 
                                                           class="forward-recipient-checkbox rounded border-gray-300 text-blue-600">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-700">{{ $user->username }}</div>
                                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    Selected: <span id="forward-selected-count">0</span> recipients
                                </div>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label class="text-sm font-medium mb-4">Subject:</label>
                                <label for="forward_subject" class="flex-1 mt-0 block text-sm font-medium text-gray-700"></label>
                                <input type="text" name="subject" id="forward_subject" required
                                    value="Fwd: {{ $message->subject }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Message Content -->
                            <div>
                                <textarea name="content" id="forward_content" rows="6" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >

---------- Forwarded message ----------
From: {{ $message->sender->username }} <{{ $message->sender->email }}>
Date: {{ $message->created_at->format('D, M d, Y h:i A') }}
Subject: {{ $message->subject }}
To: {{ $message->recipient->username }} <{{ $message->recipient->email }}>

{{ $message->content }}</textarea>
                            </div>

                            <!-- Message Options -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-6">
                                    <!-- Important Mark -->
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_important" value="1"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <i class="fas fa-exclamation-circle text-yellow-500 mr-1"></i> Mark as Important
                                        </span>
                                    </label>

                                    <!-- Urgent Mark -->
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_urgent" value="1"
                                            class="rounded border-gray-300 text-red-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Mark as Urgent
                                        </span>
                                    </label>
                                </div>

                                <!-- Deadline -->
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" id="forward_has_deadline"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">Set Deadline</span>
                                    </label>
                                    
                                    <div id="forward_deadline_container" class="hidden">
                                        <input type="datetime-local" name="deadline" id="forward_deadline"
                                            class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons Bar -->
                            <div class="flex items-center space-x-2">
                                <button type="submit" 
                                    class="action-button">
                                    <i class="fas fa-paper-plane"></i><span class="ml-3">Forward Message</span>
                                </button>

                                <button type="button" 
                                    onclick="toggleForwardForm()"
                                    class="inline-flex items-center px-2 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                    function toggleReplyForm() {
                        const replyForm = document.getElementById('replyForm');
                        const forwardForm = document.getElementById('forwardForm');
                        replyForm.classList.toggle('hidden');
                        if (!forwardForm.classList.contains('hidden')) {
                            forwardForm.classList.add('hidden');
                        }
                    }

                    function toggleForwardForm() {
                        const forwardForm = document.getElementById('forwardForm');
                        const replyForm = document.getElementById('replyForm');
                        forwardForm.classList.toggle('hidden');
                        if (!replyForm.classList.contains('hidden')) {
                            replyForm.classList.add('hidden');
                        }
                    }

                    // Handle file selection display
                    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
                        const fileList = document.getElementById('selected-files');
                        fileList.innerHTML = '';
                        
                        Array.from(this.files).forEach(file => {
                            const fileSize = (file.size / 1024 / 1024).toFixed(2);
                            const fileItem = document.createElement('div');
                            fileItem.className = 'flex items-center justify-between text-sm text-gray-600 p-2 bg-gray-50 rounded';
                            
                            fileItem.innerHTML = `
                                <div class="flex items-center">
                                    <i class="fas fa-file mr-2"></i>
                                    <span>${file.name} (${fileSize} MB)</span>
                                </div>
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeFile(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            
                            fileList.appendChild(fileItem);
                        });
                    });

                    // Handle deadline checkbox
                    document.getElementById('has_deadline').addEventListener('change', function() {
                        const deadlineContainer = document.getElementById('deadline_container');
                        const deadlineInput = document.getElementById('deadline');
                        
                        deadlineContainer.classList.toggle('hidden');
                        if (this.checked) {
                            const now = new Date();
                            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                            deadlineInput.min = now.toISOString().slice(0, 16);
                        } else {
                            deadlineInput.value = '';
                        }
                    });

                    function removeFile(button) {
                        const fileItem = button.closest('div');
                        fileItem.remove();
                    }

                    function toggleForwardRecipientDropdown() {
                        const dropdown = document.getElementById('forwardRecipientDropdown');
                        dropdown.classList.toggle('hidden');
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        const checkboxes = document.querySelectorAll('.forward-recipient-checkbox');
                        const selectedCount = document.getElementById('forward-selected-count');
                        const selectedRecipientsText = document.getElementById('forwardSelectedRecipientsText');
                        const searchInput = document.getElementById('forwardRecipientSearch');
                        
                        // Update selected recipients text and count
                        function updateSelectedRecipients() {
                            const checkedBoxes = document.querySelectorAll('.forward-recipient-checkbox:checked');
                            const count = checkedBoxes.length;
                            selectedCount.textContent = count;
                            
                            if (count === 0) {
                                selectedRecipientsText.textContent = 'Select Recipients';
                            } else {
                                const names = Array.from(checkedBoxes).map(cb => 
                                    cb.closest('.recipient-option').querySelector('.font-medium').textContent
                                );
                                selectedRecipientsText.textContent = names.join(', ');
                            }
                        }

                        // Handle checkbox changes
                        checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', updateSelectedRecipients);
                        });

                        // Search functionality
                        searchInput.addEventListener('input', function(e) {
                            const searchTerm = e.target.value.toLowerCase();
                            document.querySelectorAll('.recipient-option').forEach(option => {
                                const username = option.querySelector('.font-medium').textContent.toLowerCase();
                                const email = option.querySelector('.text-gray-500').textContent.toLowerCase();
                                const matches = username.includes(searchTerm) || email.includes(searchTerm);
                                option.style.display = matches ? 'block' : 'none';
                            });
                        });

                        // Close dropdown when clicking outside
                        document.addEventListener('click', function(e) {
                            const dropdown = document.getElementById('forwardRecipientDropdown');
                            const button = e.target.closest('button');
                            const dropdownContent = e.target.closest('#forwardRecipientDropdown');
                            
                            if (!button && !dropdownContent && !dropdown.classList.contains('hidden')) {
                                dropdown.classList.add('hidden');
                            }
                        });
                    });

                    // Handle forward deadline checkbox
                    document.getElementById('forward_has_deadline').addEventListener('change', function() {
                        const deadlineContainer = document.getElementById('forward_deadline_container');
                        const deadlineInput = document.getElementById('forward_deadline');
                        
                        deadlineContainer.classList.toggle('hidden');
                        if (this.checked) {
                            const now = new Date();
                            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                            deadlineInput.min = now.toISOString().slice(0, 16);
                        } else {
                            deadlineInput.value = '';
                        }
                    });

                    function setPreReply(type) {
                        // Update hidden input
                        document.getElementById('preReplyInput').value = type;
                        
                        // Update textarea content based on type
                        const textarea = document.getElementById('replyContent');
                        const currentDate = new Date().toLocaleDateString();
                        const sender = "{{ auth()->user()->username }} <{{ auth()->user()->email }}>";
                        
                        let content = '';
                        switch(type) {
                            case 'approved':
                                content = `Dear {{ $message->sender->username }},
I hope this email finds you well. After careful consideration of your request, I am pleased to inform you that it has been approved.
Thank you for your patience during the review process.

Best regards,
${sender}`;
                                break;
                            case 'rejected':
                                content = `Dear {{ $message->sender->username }},
I hope this email finds you well. After careful review of your request, I regret to inform you that we are unable to approve it at this time.
Thank you for your understanding.

Best regards,
${sender}`;
                                break;
                            case 'thank_you':
                                content = `Dear {{ $message->sender->username }},
Thank you for your message. I appreciate you taking the time to reach out.

Best regards,
${sender}`;
                                break;
                        }
                        
                        textarea.value = content;
                        
                        // Update button styles
                        document.querySelectorAll('.pre-reply-btn').forEach(btn => {
                            btn.classList.remove('bg-blue-50', 'border-blue-500');
                        });
                        event.target.closest('.pre-reply-btn').classList.add('bg-blue-50', 'border-blue-500');
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 