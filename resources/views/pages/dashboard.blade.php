@extends('components.layout')

@section('title')
    Dashboard|TodoFy
@endsection

@section('contents')
    <div class="flex flex-col">
        <div class="flex flex-row justify-between items-center w-full">
            <div class="flex items-center space-x-4"> <!-- Pastikan search bar tetap sejajar -->
                <div class="relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                    <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd">
                        </path>
                    </svg>
                </div>

                <form class="max-w-md">
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
                        <input type="search" id="default-search"
                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="search projects.." required />
                    </div>
                </form>
            </div>
        </div>

        <div class="p-1 mt-1">
            <h2 class="text-lg font-bold mb-4">Recently</h2>
            <div class="flex flex-wrap gap-4">
                @foreach ($sessions as $session)
                    <div
                        class="block w-60 bg-gray-900 text-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                        <a href="{{ route('projects.show', $session->id) }}" class="block">
                            <div class="h-40 overflow-hidden">
                                <img src="{{ asset($session->img) }}" alt="Session Image"
                                    class="w-full h-full object-cover">
                            </div>
                        </a>
                        <div class="p-3 bg-gray-200 text-black font-semibold flex justify-between">
                            <div class="text-lg">
                                {{ $session->title }}
                            </div>
                            <button class="rounded-full border-2 border-transparent flex items-center justify-center w-7 h-7 hover:bg-orange-200 hover:border-orange-400 transition duration-200 cursor-pointer"
                            data-id="{{ $session->id }}" id="btn-detail-todo">
                            <i class="fas fa-info fa-sm"></i>
                        </button>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    </div>
@endsection

@include('components.modalDetailProject')
