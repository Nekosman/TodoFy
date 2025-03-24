@extends('components.layout')

@section('contents')
    <div class="p-8">
        <h1 class="text-3xl font-bold mb-6">Project: {{ $todoSession->title }}</h1>

        <!-- Grid Parent Lists -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($todoSession->parentLists as $list)
                <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">{{ $list->title }}</h2>
                        <div class="flex gap-2">
                            <button class="text-gray-400 hover:text-white transition duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="text-gray-400 hover:text-white transition duration-200">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach ($list->cards as $card)
                            <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg hover:bg-gray-600 transition duration-200">
                                <span class="text-sm">{{ $card->title }}</span>
                                <button class="text-gray-400 hover:text-white transition duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Tombol Tambah Parent List -->
            <button class="w-full h-24 border-2 border-dashed border-gray-600 text-gray-400 rounded-lg flex justify-center items-center hover:bg-gray-700 hover:text-white transition duration-200">
                <i class="fas fa-plus text-2xl"></i>
            </button>
        </div>

        <!-- Back Button -->
        <a href="{{ url('/dashboard') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
            Back
        </a>
    </div>
@endsection