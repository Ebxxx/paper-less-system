<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Sent Messages</h2>

                    @if($messages->isEmpty())
                        <p class="text-gray-500 text-center py-4">No sent messages yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($messages as $message)
                                        <tr class="hover:bg-gray-50 cursor-pointer" 
                                            onclick="window.location='{{ route('mail.show', $message->id) }}'">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $message->recipient->username }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $message->recipient->email }}
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
</x-app-layout> 