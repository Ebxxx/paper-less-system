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
                                                        {{ $message->sender->first_name }} {{ $message->sender->middle_name }} {{ $message->sender->last_name }}
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
                                                        {{ $message->recipient->firstname }} {{ $message->recipient->middlename }} {{ $message->recipient->lastname }}
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
                    <div class="prose max-w-none">
                        {!! nl2br(e($message->content)) !!}
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('mail.compose', ['reply_to' => $message->id]) }}" 
                           class="inline-flex items-center px-2 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fas fa-reply mr-2"></i> Reply
                        </a>
                    </div>

                    <!-- After Message Content -->
                    @if(optional($message->attachments)->count() > 0)
                        <div class="mt-6 border-t pt-4">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 