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
                <input type="file" name="signature" class="mt-2" accept="image/*">
                <x-input-error class="mt-2" :messages="$errors->get('signature')" />
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <!-- <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Cancel</button> -->
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
            </div>
        </div>
    </form>
</section>
