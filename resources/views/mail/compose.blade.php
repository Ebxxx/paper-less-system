<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('mail.send') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Recipient Selection -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">To:</label>
                            <div class="relative">
                                <button type="button" 
                                        onclick="toggleRecipientDropdown()"
                                        class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-left cursor-pointer focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <span id="selectedRecipientsText">Select Recipients</span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </span>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="recipientDropdown" 
                                     class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                    <div class="sticky top-0 bg-white p-2 border-b">
                                        <input type="text" 
                                               id="recipientSearch" 
                                               placeholder="Search recipients..." 
                                               class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                    @foreach($users as $user)
                                        @if($user->role !== 'admin')
                                            <div class="recipient-option px-4 py-2 hover:bg-gray-100">
                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                    <input type="checkbox" 
                                                           name="to_user_ids[]" 
                                                           value="{{ $user->id }}" 
                                                           class="recipient-checkbox rounded border-gray-300 text-blue-600">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-700">{{ $user->username }}</div>
                                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                Selected: <span id="selected-count">0</span> recipients
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject:</label>
                            <input type="text" name="subject" id="subject" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Message Content -->
                        <div>
                            <textarea name="content" id="content" rows="10" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <!-- Add this after the content textarea and before the action buttons -->
                        <div class="space-y-4 mt-4 border-t pt-4">
                            <div class="flex items-center space-x-6">
                                <!-- Important Mark -->
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="is_important" 
                                           value="1"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">
                                        <i class="fas fa-exclamation-circle text-yellow-500 mr-1"></i> Mark as Important
                                    </span>
                                </label>

                                <!-- Urgent Mark -->
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="is_urgent" 
                                           value="1"
                                           class="rounded border-gray-300 text-red-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Mark as Urgent
                                    </span>
                                </label>
                            </div>

                            <!-- Deadline -->
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           id="has_deadline" 
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Set Deadline</span>
                                </label>
                                
                                <div id="deadline_container" class="hidden">
                                    <input type="datetime-local" 
                                           name="deadline" 
                                           id="deadline" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons Bar -->
                        <div class="flex items-center space-x-1 border-t pt-4">
                            <!-- Send Button -->
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                Send
                            </button>

                            <!-- Attachment Button -->
                            <label class="cursor-pointer inline-flex items-center px-2 py-1 text-sm bg-transparent text-gray-700 hover:bg-gray-100 rounded">
                                <i class="fas fa-paperclip"></i>
                                <input type="file" name="attachments[]" id="attachments" multiple
                                       class="hidden"
                                       accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                            </label>

                            <!-- Format Buttons -->
                            <button type="button" class="p-1 text-sm text-gray-600 hover:bg-gray-100 rounded" title="Bold">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" class="p-1 text-sm text-gray-600 hover:bg-gray-100 rounded" title="Italic">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" class="p-1 text-sm text-gray-600 hover:bg-gray-100 rounded" title="Underline">
                                <i class="fas fa-underline"></i>
                            </button>
                            <button type="button" class="p-1 text-sm text-gray-600 hover:bg-gray-100 rounded" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" class="p-1 text-sm text-gray-600 hover:bg-gray-100 rounded" title="Emoji">
                                <i class="far fa-smile"></i>
                            </button>

                            <!-- More Options -->
                            <div class="relative ml-auto">
                                <button type="button" 
                                        class="p-1 text-sm text-gray-600 hover:bg-gray-100 rounded"
                                        onclick="toggleMoreOptions()">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="moreOptions" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                                    <div class="py-1">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-trash mr-2"></i> Discard draft
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-check mr-2"></i> Save draft
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Files Display -->
                        <div id="selected-files" class="mt-2 space-y-2"></div>

                        @if($replyTo)
                            <input type="hidden" name="parent_id" value="{{ $replyTo->id }}">
                            
                            <!-- Pre-fill the recipient -->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Assuming you're using Select2 or similar
                                    let recipientSelect = document.querySelector('select[name="to_user_ids[]"]');
                                    recipientSelect.value = ['{{ $replyTo->sender->id }}'];
                                    
                                    // Pre-fill subject with Re: if it doesn't already start with it
                                    let subjectInput = document.querySelector('input[name="subject"]');
                                    let originalSubject = '{{ $replyTo->subject }}';
                                    subjectInput.value = originalSubject.startsWith('Re:') ? originalSubject : 'Re: ' + originalSubject;
                                    
                                    // Add quoted text to content
                                    let contentTextarea = document.querySelector('textarea[name="content"]');
                                    contentTextarea.value = `\n\nOn {{ $replyTo->created_at->format('M d, Y, h:i A') }}, {{ $replyTo->sender->username }} wrote:\n> ${originalSubject}`;
                                });
                            </script>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleRecipientDropdown() {
        const dropdown = document.getElementById('recipientDropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.recipient-checkbox');
        const selectedCount = document.getElementById('selected-count');
        const selectedRecipientsText = document.getElementById('selectedRecipientsText');
        const searchInput = document.getElementById('recipientSearch');
        
        // Update selected recipients text and count
        function updateSelectedRecipients() {
            const checkedBoxes = document.querySelectorAll('.recipient-checkbox:checked');
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
            const dropdown = document.getElementById('recipientDropdown');
            const button = e.target.closest('button');
            const dropdownContent = e.target.closest('#recipientDropdown');
            
            if (!button && !dropdownContent && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
    });

    function toggleMoreOptions() {
        const moreOptions = document.getElementById('moreOptions');
        moreOptions.classList.toggle('hidden');
    }

    document.getElementById('attachments').addEventListener('change', function(e) {
        const fileList = document.getElementById('selected-files');
        fileList.innerHTML = '';
        
        Array.from(this.files).forEach(file => {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between text-sm text-gray-600 p-2 bg-gray-50 rounded';
            
            // Create file info
            const fileInfo = document.createElement('div');
            fileInfo.className = 'flex items-center';
            fileInfo.innerHTML = `
                <i class="fas fa-file mr-2"></i>
                <span>${file.name} (${fileSize} MB)</span>
            `;
            
            // Create remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'text-red-500 hover:text-red-700';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = function() {
                fileItem.remove();
                // Create a new FileList without this file
                const dt = new DataTransfer();
                const input = document.getElementById('attachments');
                const { files } = input;
                
                for (let i = 0; i < files.length; i++) {
                    const f = files[i];
                    if (f !== file) { dt.items.add(f); }
                }
                
                input.files = dt.files;
            };
            
            fileItem.appendChild(fileInfo);
            fileItem.appendChild(removeBtn);
            fileList.appendChild(fileItem);
        });
    });

    // Close more options when clicking outside
    document.addEventListener('click', function(event) {
        const moreOptions = document.getElementById('moreOptions');
        const target = event.target;
        if (!target.closest('.relative') && !moreOptions.classList.contains('hidden')) {
            moreOptions.classList.add('hidden');
        }
    });

    // Add Select2 for better multiple selection UI
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('#to_user_ids').select2({
            placeholder: 'Select recipients',
            allowClear: true,
            width: '100%',
            theme: 'classic',
            closeOnSelect: false
        });
    });

    document.getElementById('has_deadline').addEventListener('change', function() {
        const deadlineContainer = document.getElementById('deadline_container');
        const deadlineInput = document.getElementById('deadline');
        
        deadlineContainer.classList.toggle('hidden');
        if (this.checked) {
            // Set minimum date to today
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            deadlineInput.min = now.toISOString().slice(0, 16);
        } else {
            deadlineInput.value = '';
        }
    });
    </script>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
    .recipient-option:hover {
        background-color: #f3f4f6;
    }

    .recipient-checkbox {
        cursor: pointer;
    }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush
</x-app-layout>