<div class="flex items-start hover:bg-gray-50 p-2 rounded-lg" 
     x-data="{ showComposeGuide: false }">
    <div class="flex items-start w-full" @click.stop="showComposeGuide = true">
        <i class="fas fa-envelope mt-1 text-blue-500 w-5"></i>
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-800">Compose Message</p>
            <p class="text-xs text-gray-500">Learn how to compose and send messages</p>
        </div>
    </div>

    <!-- Guide Modal -->
    <div x-show="showComposeGuide" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak
         @click.away="showComposeGuide = false">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showComposeGuide = false"></div>

            <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl">
                <!-- Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                        How to Compose Messages
                    </h3>
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
                            </ul>
                                             <!-- Best Practices -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-blue-900 mb-2">Best Practices</h4>
                            <ul class="list-disc list-inside text-sm text-blue-800 space-y-2">
                                <li>Set realistic deadlines with buffer time</li>
                                <li>Consider recipient time zones</li>
                                <li>Use with Important/Urgent marks for critical tasks</li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                    <button @click="showComposeGuide = false"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 