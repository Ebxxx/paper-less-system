<section>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')
        
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('success') }}
            </div>
        @endif

              <!-- Grid for prefix and order title -->
              <div class="grid grid-cols-3 gap-4">
                <div>
                    <x-input-label for="prefix" :value="__('Prefix')" />
                    <select id="prefix" name="prefix" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value=""></option>
                        <option value="Dr." {{ old('prefix', $user->prefix) === 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        <option value="Mr." {{ old('prefix', $user->prefix) === 'Mr.' ? 'selected' : '' }}>Mr.</option>
                        <option value="Ms." {{ old('prefix', $user->prefix) === 'Ms.' ? 'selected' : '' }}>Ms.</option>
                        <option value="Mrs." {{ old('prefix', $user->prefix) === 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                        <option value="Prof." {{ old('prefix', $user->prefix) === 'Prof.' ? 'selected' : '' }}>Prof.</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('prefix')" />
                </div>

                <div class="col-span-2">
                    <x-input-label for="order_title" :value="__('Academic Titles (in order)')" />
                    <x-text-input 
                        id="order_title" 
                        name="order_title" 
                        type="text" 
                        class="mt-1 block w-full" 
                        :value="old('order_title', $user->order_title)"
                        placeholder="e.g., PhD, MPH, MA or MA, MPH, PhD" 
                    />
                    <p class="mt-1 text-sm text-gray-500">Enter your academic titles in order, separated by commas</p>
                    <x-input-error class="mt-2" :messages="$errors->get('order_title')" />
                </div>
            </div>
        
        <div class="grid gap-4">
            <!-- First name, Middle name, Last name row -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <x-input-label for="first_name" :value="__('First name')" />
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>

                <div>
                    <x-input-label for="middle_name" :value="__('Middle name')" />
                    <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full" :value="old('middle_name', $user->middle_name)" />
                    <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
                </div>

                <div>
                    <x-input-label for="last_name" :value="__('Last name')" />
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>
            </div>

            <!-- Job Title, Program, Department row -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <x-input-label for="job_title" :value="__('Job Title')" />
                    <x-text-input id="job_title" name="job_title" type="text" class="mt-1 block w-full" :value="old('job_title', $user->job_title)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('job_title')" />
                </div>

                <div>
                    <x-input-label for="program" :value="__('Program')" />
                    <x-text-input id="program" name="program" type="text" class="mt-1 block w-full" :value="old('program', $user->program)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('program')" />
                </div>

                <div>
                    <x-input-label for="department" :value="__('Department')" />
                    <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" :value="old('department', $user->department)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('department')" />
                </div>
            </div>

            <!-- E-Signature section -->
            <div>
                <x-input-label :value="__('E-Signature')" class="text-blue-600" />
                <div class="mt-1 p-4 border rounded-lg bg-gray-50">
                    @if($user->signature_path)
                        <img src="{{ asset($user->signature_path) }}" alt="Current Signature" class="max-h-32 mx-auto">
                    @else
                        <p class="text-center text-gray-500">Uploaded signature display here</p>
                    @endif
                </div>
                <div class="mt-2 flex flex-col">
                    <div class="flex items-center gap-2">
                        <button type="button" 
                            onclick="openSignatureTerms()" 
                            class="text-blue-600 hover:text-blue-800 underline text-sm">
                            Upload Signature
                        </button>
                        <span id="selected-file-name" class="text-sm text-gray-600"></span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Copy the image and paste it to the document file to use digital signature
                    </p>
                </div>
                <input type="file" 
                    id="signature-input" 
                    name="signature" 
                    class="hidden" 
                    accept="image/*"
                    onchange="handleFileSelect(this)">
                <x-input-error class="mt-2" :messages="$errors->get('signature')" />
            </div>

            <!-- Terms and Agreement Modal -->
            <div id="signature-terms-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">E-Signature Terms</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500 text-left">
                                By uploading your e-signature, you agree to the following:
                                <ul class="list-disc mt-2 ml-4 text-left">
                                    <li>Your e-signature will be used for official documents</li>
                                    <li>You are the rightful owner of this signature</li>
                                    <li>You authorize its use for digital signing purposes</li>
                                    <li>You understand this is legally binding</li>
                                </ul>
                            </p>
                        </div>
                        <div class="items-center px-4 py-3">
                            <input type="checkbox" id="terms-checkbox" class="mr-2">
                            <label for="terms-checkbox" class="text-sm text-gray-600">I agree to the terms and conditions</label>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="accept-terms" 
                                class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:opacity-50"
                                disabled
                                onclick="acceptTerms()">
                                Continue
                            </button>
                            <button 
                                class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
                                onclick="closeSignatureTerms()">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <!-- <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Cancel</button> -->
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
            </div>
        </div>
    </form>
</section>

<script>
    function openSignatureTerms() {
        document.getElementById('signature-terms-modal').classList.remove('hidden');
    }

    function closeSignatureTerms() {
        document.getElementById('signature-terms-modal').classList.add('hidden');
        document.getElementById('terms-checkbox').checked = false;
        document.getElementById('accept-terms').disabled = true;
    }

    function acceptTerms() {
        document.getElementById('signature-input').click();
        closeSignatureTerms();
    }

    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('selected-file-name').textContent = `Selected: ${fileName}`;
        }
    }

    // Enable/disable accept button based on checkbox
    document.getElementById('terms-checkbox').addEventListener('change', function() {
        document.getElementById('accept-terms').disabled = !this.checked;
    });
</script>
