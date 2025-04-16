@extends('components.layout')

@section('title')
    Settings
@endsection

@section('contents')
    <div class="flex items-center justify-center min-h-screen bg-gray-50 py-5 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-7xl mx-auto"> <!-- Diperlebar untuk 3 kolom -->
            
            <!-- Judul -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-orange-500">Account Settings</h1>
                <p class="mt-2 text-sm text-gray-600">Manage your profile and activities</p>
            </div>

            <!-- Container untuk tiga kolom -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Kolom Activity (Baru) -->
                <div class="w-full lg:w-[35%] p-6 border border-gray-200 rounded-lg bg-white shadow-sm">
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
                                            {{ $activity->parent->todoSession->title ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <span class="font-semibold">List:</span> 
                                            {{ $activity->parent->title }}
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

                <!-- Kolom Profile -->
                <div class="w-full lg:w-[30%] p-6 border border-gray-200 rounded-lg bg-white shadow-sm">
                    <!-- ... (kode form profile yang sudah ada) ... -->
                </div>

                <!-- Kolom Password -->
                <div class="w-full lg:w-[35%] p-6 border border-gray-200 rounded-lg bg-white shadow-sm">
                    <!-- ... (kode form password yang sudah ada) ... -->
                </div>
            </div>
        </div>
    </div>
@endsection