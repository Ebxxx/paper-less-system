<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Create New User</h2>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 required">Username</label>
                        <input type="text" name="username" id="username" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('username') }}">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('email') }}">
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('first_name') }}">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('last_name') }}">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="button" onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                        <input type="text" name="department" id="department" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('department') }}">
                    </div>

                    <!-- Job Title -->
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700">Job Title</label>
                        <input type="text" name="job_title" id="job_title" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('job_title') }}">
                    </div>

                    <!-- Program -->
                    <div>
                        <label for="program" class="block text-sm font-medium text-gray-700">Program</label>
                        <input type="text" name="program" id="program" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('program') }}">
                    </div>

                    <!-- Signature -->
                    <div class="col-span-full">
                        <label for="signature" class="block text-sm font-medium text-gray-700">Signature</label>
                        <input type="file" name="signature" id="signature" 
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg mr-4 hover:bg-gray-200 transition duration-150 ease-in-out">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            if (field.type === 'password') {
                field.type = 'text';
                button.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                field.type = 'password';
                button.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }
    </script>
    @endpush
</x-admin-layout>