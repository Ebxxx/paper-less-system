<div class="flex items-start hover:bg-gray-50 p-2 rounded-lg" 
     x-data="{ showFolderGuide: false }">
    <div class="flex items-start w-full" @click.stop="showFolderGuide = true">
        <i class="fas fa-folder mt-1 text-blue-500 w-5"></i>
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-800">Manage Folders</p>
            <p class="text-xs text-gray-500">Create and organize folders</p>
        </div>
    </div>

    <!-- Guide Modal -->
    <div x-show="showFolderGuide" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak
         @click.away="showFolderGuide = false">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showFolderGuide = false"></div>

            <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl">
                <!-- Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-folder mr-2 text-blue-500"></i>
                        How to Manage Folders
                    </h3>
                </div>

                <!-- Content -->
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Getting Started -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Getting Started</h4>
                            <p class="text-sm text-gray-600">
                                Folders help you organize your messages effectively. You can find the folder section in the sidebar below your main mailbox options.
                            </p>
                        </div>

                        <!-- Creating New Folders -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Creating New Folders</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                <li>Click the <i class="fas fa-plus text-gray-400"></i> icon next to "Folders" in the sidebar</li>
                                <li>Enter a unique name for your folder</li>
                                <li>Click "Create" to add the new folder</li>
                                <li>New folders appear immediately in your sidebar</li>
                            </ul>
                        </div>

                        <!-- Managing Existing Folders -->
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
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                    <button @click="showFolderGuide = false"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 