@extends('components.layout')

@section('title')
    Dashboard | {{ auth()->user()->name }}
@endsection

@push('css')
    <style>
        /* Due Today Section */
        .due-today-card {
            transition: all 0.2s ease;
        }

        .due-today-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .due-soon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
@endpush

@section('contents')
    <div class="flex flex-col">
        <!-- Header with Profile and Search -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-4 mb-6">
            <div class="flex items-center space-x-4 w-full md:w-auto">
                <a href="{{ route('setting.index') }}" class="shrink-0">
                    <div class="relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                        @if (auth()->user()->profile_image)
                            <img src="{{ asset(auth()->user()->profile_image) }}" alt="Profile Image"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </a>

                <form class="w-full max-w-md">
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="project-search"
                            class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search projects..." />
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Recent Activities Section -->
            <div class="w-full h-full lg:w-1/3 min-w-[320px]  space-y-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 max-h-[600px] overflow-y-auto">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-orange-500 pb-2 mb-4">
                        Recent Activities
                    </h3>

                    <div class="space-y-3 min-h-[200px]">
                        @forelse($completedActivities as $activity)
                            <div class="p-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $activity->title }}
                                        </p>
                                        <div class="text-xs text-gray-500 mt-1 space-y-1">
                                            <p class="truncate">
                                                <span class="font-semibold">Project:</span>
                                                {{ $activity->parentList->todoSession->title ?? 'N/A' }}
                                            </p>
                                            <p class="truncate">
                                                <span class="font-semibold">List:</span>
                                                {{ $activity->parentList->title }}
                                            </p>
                                            <p class="text-gray-400 text-xs mt-1">
                                                Completed {{ $activity->completed_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center h-full"> <!-- Tambahkan container untuk kosong -->
                                <p class="text-sm text-gray-500 py-4">No completed tasks yet</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($completedActivities->hasPages())
                        <div class="mt-4">
                            {{ $completedActivities->appends(['sessions_page' => $sessions->currentPage()])->links() }}
                        </div>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-orange-500 pb-2 mb-4">
                        Due Today
                    </h3>

                    <div class="space-y-3">
                        @forelse($todayDueCards as $card)
                            <a href="{{ route('projects.show', $card->parentList->todoSession->id) }}">
                                <div class="p-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-full {{ $card->status === 'completed' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center mt-1">
                                            @if ($card->status === 'completed')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $card->title }}
                                            </p>
                                            <div class="text-xs text-gray-500 mt-1 space-y-1">
                                                <p class="truncate">
                                                    <span class="font-semibold">Project:</span>
                                                    {{ $card->parentList->todoSession->title ?? 'N/A' }}
                                                </p>
                                                <p class="truncate">
                                                    <span class="font-semibold">List:</span>
                                                    {{ $card->parentList->title }}
                                                </p>
                                                <p
                                                    class="{{ $card->status === 'completed' ? 'text-green-500' : 'text-red-500' }} text-xs mt-1 font-semibold">
                                                    @if ($card->status === 'completed')
                                                        Completed at
                                                        {{ \Carbon\Carbon::parse($card->completed_at)->format('H:i') }}
                                                    @else
                                                        Due at {{ \Carbon\Carbon::parse($card->due_date)->format('H:i') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="flex items-center justify-center">
                                <p class="text-sm text-gray-500 py-4">No tasks due today</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="flex-1">
                <h2 class="text-lg font-bold mb-4">Recently</h2>
                <div class="flex flex-wrap gap-4">
                    @foreach ($sessions as $session)
                        <div
                            class="block w-60 bg-gray-900 text-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                            <a href="{{ route('projects.show', $session->id) }}" class="block">
                                <div class="h-40 overflow-hidden">
                                    @if (!empty($session->img) && file_exists(public_path($session->img)))
                                        <img src="{{ asset($session->img) }}" class="w-full h-full object-cover"
                                            alt="Card Image">
                                    @endif
                                    {{-- <img src="{{ asset($session->img) }}" alt="Session Image"
                                    class="w-full h-full object-cover"> --}}
                                </div>
                            </a>
                            <div class="p-3 bg-gray-200 text-black font-semibold flex justify-between">
                                <div class="text-lg">
                                    {{ $session->title }}
                                </div>
                                <button
                                    class="rounded-full border-2 border-transparent flex items-center justify-center w-7 h-7 hover:bg-orange-200 hover:border-orange-400 transition duration-200 cursor-pointer"
                                    data-id="{{ $session->id }}" id="btn-detail-todo">
                                    <i class="fas fa-info fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach

                </div>
                @if ($sessions->hasPages())
                    <div class="mt-4 flex justify-center">
                        {{ $sessions->appends(['activities_page' => $completedActivities->currentPage()])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@include('components.modalDetailProject')

@push('scripts')
    <script>
        $(document).ready(function() {
            // Project search functionality
            $('#project-search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                const $projects = $('.bg-gray-900'); // Selector for project cards

                $projects.each(function() {
                    const projectTitle = $(this).find('.text-sm').text().toLowerCase();
                    $(this).toggle(projectTitle.includes(searchTerm));
                });
            });
        });

        $(document).ready(function() {
            // Cek jika ada task due today
            @if ($todayDueCards->count() > 0)
                // Animasi untuk due today section
                $('.due-today-card').hover(
                    function() {
                        $(this).addClass('shadow-md');
                    },
                    function() {
                        $(this).removeClass('shadow-md');
                    }
                );

                // Peringatan untuk task yang hampir due
                setInterval(function() {
                    $('.due-soon').toggleClass('opacity-75');
                }, 1000);
            @endif
        });
    </script>
@endpush
