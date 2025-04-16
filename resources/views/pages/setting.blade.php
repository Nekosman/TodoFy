@extends('components.layout')

@section('title')
    Settings
@endsection

@section('contents')
    <div class="flex items-center justify-center min-h-screen bg-gray-50 py-5 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto"> <!-- Diperlebar max-width untuk menampung dua kolom -->
            <!-- Judul -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-orange-500">Account Settings</h1>
                <p class="mt-2 text-sm text-gray-600">Manage your profile, History, and security settings</p>
            </div>

            <!-- Container utama untuk dua kolom -->
            <div class=" overflow-hidden">
                <!-- Navigation Tabs (jika diperlukan) -->
                <nav class="flex items-center justify-center -mb-px"></nav>

                <!-- Container untuk dua kolom form -->
                <div class="flex flex-wrap justify-between p-6 space-x-4 "> <!-- Tambahkan padding dan justify-between -->
                    <!-- Kolom Pertama -->
                    <div class="w-full md:w-[48%] mb-8 md:mb-0 p-6 border border-gray-200 rounded-lg"> <!-- Lebar 48% dengan margin 2% -->
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
                                    <div class="mt-1 flex items-center">
                                        <div class="mr-4">
                                            <span class="block text-sm text-gray-500 mb-1">Current profile image</span>
                                            @if(auth()->user()->profile_image)
                                                <img src="{{ auth()->user()->profile_image }}" class="h-16 w-16 rounded-full object-cover">
                                            @else
                                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <label class="cursor-pointer">
                                                <span class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Choose File
                                                    <input type="file" name="profile_image" class="sr-only" accept="image/*">
                                                </span>
                                                <span class="ml-2 text-sm text-gray-500">No file chosen</span>
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

                    <!-- Kolom Kedua -->
                    <div class="w-full md:w-[48%] p-6 border border-gray-200 rounded-lg "> <!-- Lebar 48% dengan margin 2% -->
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
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                    <div class="mt-1">
                                        <input type="password" id="current_password" name="current_password" required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border">
                                    </div>
                                </div>
                        
                                <!-- New Password -->
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <div class="mt-1">
                                        <input type="password" id="new_password" name="new_password" required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border">
                                    </div>
                                </div>
                                
                                <!-- Confirm New Password -->
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
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

                    <div class="w-full lg:w-[35%] p-6 border border-gray-200 rounded-lg bg-white shadow-sm mt-5">
                        <div class="flex items-center justify-center mb-5">
                            <div class="w-full text-center border-b font-medium text-sm border-orange-500 text-orange-600">
                                Recent Activities
                            </div>
                        </div>
    
                        <div class="space-y-4">
                            @forelse($completedActivities as $activity)
                                <div class="p-4 border-b border-gray-100">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $activity->title }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <span class="font-semibold">Project:</span> 
                                                {{ $activity->parentList->todoSession->title ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <span class="font-semibold">List:</span> 
                                                {{ $activity->parentList->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Completed {{ $activity->completed_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No completed tasks yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- You can add any JavaScript here if needed -->
@endpush