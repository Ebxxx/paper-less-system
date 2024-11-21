@extends('layouts.superadmin')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Create Admin User</h2>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('superadmin.store-admin') }}">
            @csrf
            
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">
                    Username
                </label>
                <input type="text" name="username" id="username" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                       value="{{ old('username') }}" required>
            </div>

            <div class="mb-4">
                <label for="fullname" class="block text-gray-700 text-sm font-bold mb-2">
                    Full Name
                </label>
                <input type="text" name="fullname" id="fullname" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                       value="{{ old('fullname') }}" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                    Email
                </label>
                <input type="email" name="email" id="email" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                       value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    Password
                </label>
                <input type="password" name="password" id="password" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">
                    Confirm Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" 
                        class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                    Create Admin
                </button>
            </div>
        </form>
    </div>
    @endsection