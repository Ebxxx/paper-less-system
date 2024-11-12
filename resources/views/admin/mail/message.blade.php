<x-admin-layout>
    <div class="container mx-auto px-4">
        <!-- Message Filter & Search -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold">Message Moderation</h2>
                <div class="flex space-x-2">
                    <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="all">All Messages</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <input type="text" placeholder="Search messages..." 
                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
            </div>
        </div>

        <!-- Message List -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Message Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            <h3 class="text-lg font-semibold mt-2">Important Update Regarding Your Account</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                From: john.doe@example.com
                                <span class="mx-2">→</span>
                                To: jane.smith@example.com
                            </div>
                            <div class="text-sm text-gray-500">
                                Sent: 2024-03-15 14:30
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-gray-700">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" placeholder="Add notes..." 
                                       class="pl-8 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <i class="fas fa-comment-dots absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Reject
                            </button>
                            <button class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Approve
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Message Cards -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Approved</span>
                            <h3 class="text-lg font-semibold mt-2">Team Meeting Schedule</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                From: sarah.wilson@example.com
                                <span class="mx-2">→</span>
                                To: team@example.com
                            </div>
                            <div class="text-sm text-gray-500">
                                Sent: 2024-03-14 09:15
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">
                            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">Rejected</span>
                            <h3 class="text-lg font-semibold mt-2">Project Feedback</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                From: mike.brown@example.com
                                <span class="mx-2">→</span>
                                To: project.lead@example.com
                            </div>
                            <div class="text-sm text-gray-500">
                                Sent: 2024-03-13 16:45
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-2">
                        <p class="text-gray-700">
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </p>
                    </div>
                    
                    <div class="bg-red-50 rounded-lg p-3 text-sm text-red-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Rejection reason: Content requires revision for clarity and professionalism.
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">2</a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </nav>
        </div>
    </div>
</x-admin-layout>
