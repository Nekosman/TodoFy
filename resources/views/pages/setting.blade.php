@extends('components.layout')

@section('title')
    Settings
@endsection

@section('contents')
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-orange-500">Account Settings</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your profile, History, and security settings</p>
        </div>
        <div class="w-full max-w-4xl space-y-6 md:space-y-0 md:grid md:grid-cols-2 md:gap-6">

            

            <!-- First Column - Profile -->
            <div class="w-full p-6 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-center mb-5">
                    <div class="w-full text-center border-b font-medium text-sm border-orange-500 text-orange-600">
                        Profile
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1">
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border">
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border">
                            </div>
                        </div>

                        <!-- Profile Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profile Image</label>
                            <div class="mt-1 flex flex-col sm:flex-row sm:items-center gap-4">
                                <div>
                                    <span class="block text-sm text-gray-500 mb-1">Current profile image</span>
                                    @if (auth()->user()->profile_image)
                                        <img src="{{ auth()->user()->profile_image }}"
                                            class="h-16 w-16 rounded-full object-cover">
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="cursor-pointer">
                                        <span
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Choose File
                                            <input type="file" name="profile_image" id="profile_image_input" class="sr-only"
                                                accept="image/*" onchange="updateFileName()">
                                        </span>
                                        <span id="file-chosen" class="ml-2 text-sm text-gray-500">No file
                                            chosen</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="pt-6">
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Second Column - Password -->
            <div class="w-full p-6 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-center mb-5">
                    <div class="w-full text-center border-b font-medium text-sm border-orange-500 text-orange-600">
                        NEW PASSWORD SETTING
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.security.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current
                                Password</label>
                            <div class="mt-1">
                                <input type="password" id="current_password" name="current_password" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border">
                            </div>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">New
                                Password</label>
                            <div class="mt-1">
                                <input type="password" id="new_password" name="new_password" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border">
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New
                                Password</label>
                            <div class="mt-1">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border">
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="pt-6">
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-400 hover:bg-orange-500  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateFileName() {
            const input = document.getElementById('profile_image_input');
            const label = document.getElementById('file-chosen');

            if (input.files.length > 0) {
                label.textContent = input.files[0].name;
            } else {
                label.textContent = 'No file chosen';
            }
        }
    </script>
@endpush