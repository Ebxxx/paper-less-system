<div class="flex items-start hover:bg-gray-50 p-2 rounded-lg" 
     x-data="{ showSearchGuide: false }">
    <div class="flex items-start w-full" @click.stop="showSearchGuide = true">
        <i class="fas fa-search mt-1 text-blue-500 w-5"></i>
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-800">Search Messages</p>
            <p class="text-xs text-gray-500">Learn how to search effectively</p>
        </div>
    </div>

    <!-- Guide Modal -->
    <div x-show="showSearchGuide" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak
         @click.away="showSearchGuide = false">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showSearchGuide = false"></div>

            <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl">
                <!-- Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-search mr-2 text-blue-500"></i>
                        How to Search Messages
                    </h3>
                </div>

                <!-- Content -->
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Getting Started -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Getting Started</h4>
                            <p class="text-sm text-gray-600">
                                The search bar is located at the top of your screen. As you type, results will appear in real-time.
                            </p>
                        </div>

                        <!-- What You Can Search -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">What You Can Search</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                <li>Message subjects and content</li>
                                <li>Sender and recipient names</li>
                                <li>Email addresses</li>
                                <li>Attachment names</li>
                                <li>Dates and message status</li>
                            </ul>
                        </div>

                        <!-- Search Features -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Search Features</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
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

                        <!-- Search Results -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Understanding Search Results</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                <li>Message Information:
                                    <ul class="ml-6 mt-1 list-circle">
                                        <li><i class="fas fa-user mr-2"></i>Sender name</li>
                                        <li><i class="fas fa-envelope mr-2"></i>Subject line</li>
                                        <li><i class="fas fa-clock mr-2"></i>Date and time</li>
                                        <li><i class="fas fa-paperclip mr-2"></i>Attachment indicator</li>
                                    </ul>
                                </li>
                                <li>Status Indicators:
                                    <ul class="ml-6 mt-1 list-circle">
                                        <li><i class="fas fa-envelope text-blue-500 mr-2"></i>Unread</li>
                                        <li><i class="fas fa-star text-yellow-500 mr-2"></i>Starred</li>
                                        <li><i class="fas fa-exclamation-circle text-red-500 mr-2"></i>Important</li>
                                        <li><i class="fas fa-clock text-orange-500 mr-2"></i>Deadline</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                    <button @click="showSearchGuide = false"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 