<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('mail.send') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Recipient Selection -->
                        <div>
                            <label for="to_user_id" class="block text-sm font-medium text-gray-700">To:</label>
                            <select name="to_user_id" id="to_user_id" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select recipient</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->username }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</x-app-layout>