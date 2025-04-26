@extends('components.layout')

@section('title')
    Project {{ $todoSession->title }}
@endsection

@section('contents')
    <div class="p-5">


        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h1 class="text-3xl font-semibold">Project: {{ $todoSession->title }}</h1>

            <!-- Search and Filter Container -->
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-4 items-stretch">
                <!-- Search Bar -->
                <div class="relative flex-grow max-w-md">
                    <input type="text" id="searchInput" placeholder="Search lists or cards..."
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <!-- Filter Buttons -->
                <div class="flex space-x-2 justify-end">
                    @php
                        $currentFilter = request()->query('filter', 'all');
                    @endphp

                    <a href="{{ route('projects.show', ['id' => $id]) }}?filter=all"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $currentFilter === 'all' ? 'ring-2 ring-offset-2 ring-blue-500' : '' }}">
                        All
                    </a>

                    <a href="{{ route('projects.show', ['id' => $id]) }}?filter=due"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 {{ $currentFilter === 'due' ? 'ring-2 ring-offset-2 ring-yellow-500' : '' }}">
                        Due
                    </a>

                    <a href="{{ route('projects.show', ['id' => $id]) }}?filter=completed"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 {{ $currentFilter === 'completed' ? 'ring-2 ring-offset-2 ring-green-500' : '' }}">
                        Done
                    </a>

                    <a href="{{ route('projects.show', ['id' => $id]) }}?filter=late"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 {{ $currentFilter === 'late' ? 'ring-2 ring-offset-2 ring-red-500' : '' }}">
                        Late
                    </a>
                </div>
            </div>
        </div>
        <!-- Grid Parent Lists -->
        <div id="parent-list-container" class="flex flex-wrap gap-5 justify-center relative">
            @foreach ($todoSession->parentLists as $list)
                <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md w-full sm:w-[300px] parent-list-item outline-2 outline-orange-500/100"
                    data-parent-id="{{ $list->id }}">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold mr-4 parent-title">{{ $list->title }}</h2>
                        <div class="flex gap-4">
                            <button class="text-gray-400 hover:text-white transition duration-200" id="btn-add-card">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="text-gray-400 hover:text-white transition duration-200 cursor-pointer"
                                id="btn-detail-parent" data-id="{{ $list->id }}">
                                <i class="fas fa-info fa-sm"></i>
                            </button>
                        </div>
                    </div>



                    <div class="space-y-3" id="card-container">
                        @foreach ($list->cards as $card)
                            <div class="bg-gray-600 p-3 rounded-md mb-2 card-item outline-2 outline-orange-500/80"
                                data-card-id="{{ $card->id }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-medium">{{ $card->title }}</h3>
                                        {!! status_badge($card->status) !!}
                                    </div>
                                    <button class="text-gray-400 hover:text-white btn-detail-card "
                                        data-id="{{ $card->id }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-300 mt-1">{{ $card->description }}</p>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-400">Due: {{ $card->due_date?->format('Y-m-d H:i') }}</p>
                                    <p
                                        class="text-xs {{ $card->status === 'completed'
                                            ? 'text-green-300'
                                            : ($card->status === 'late'
                                                ? 'text-red-300'
                                                : 'text-yellow-300') }}">
                                        {{ status_text($card->status) }}
                                    </p>
                                    <button
                                        class=" btn-move-card cursor-pointer hover:text-orange-400 transition duration-200"
                                        data-id="{{ $card->id }}">
                                        <i class="fas fa-exchange-alt"></i>
                                        
                                    </button>
                                </div>
                                @if ($card->img && file_exists(public_path('storage/images/cards/' . $card->img)))
                                    <img src="{{ asset('storage/images/cards/' . $card->img) }}"
                                        class="mt-2 rounded-md max-h-20" alt="Card Image">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Floating -->
        <button id="add-parent-list-button"
            class="fixed bottom-5 right-5 bg-orange-500 text-white w-14 h-14 rounded-full flex justify-center items-center shadow-lg hover:bg-orange-600 transition duration-200 cursor-pointer"
            data-session-id="{{ $todoSession->id }}">
            <i class="fas fa-plus text-2xl"></i>
        </button>
    </div>
@endsection

@include('components.modalMoveCard')

@include('components.modalDetailParent')

@include('components.modalDetailCard')

@include('components.modalCreateCard')

@push('scripts')
    <script>
        /*                parent Function


                                    */
        $(document).ready(function() {
            $('#add-parent-list-button').click(function() {
                const sessionId = $(this).data('session-id');

                $.ajax({
                    url: '/parent-lists',
                    type: 'POST',
                    data: {
                        session_id: sessionId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        let newListHtml = generateParentListHTML(response.data);
                        $("#parent-list-container").append(
                            newListHtml);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to create list',
                        });
                    }
                });
            });
        });

        function generateParentListHTML(list) {
            return `
        <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md w-full sm:w-[300px] parent-list-item outline-2 outline-orange-500/100"
            data-parent-id="${list.id}">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold mr-4 parent-title">${list.title}</h2>
                <div class="flex gap-4">
                    <button class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="text-gray-400 hover:text-white transition duration-200 cursor-pointer"
                        id="btn-detail-parent" data-id="${list.id}">
                        <i class="fas fa-info fa-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
        }

        function loadModal() {
            $.get('/parent-lists/detail/{ParentList}', function(html) {
                $('body').append(html);
            });
        }

        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();

                if (searchTerm.length === 0) {
                    // Show all if search is empty
                    $('.parent-list-item').show();
                    $('.card-item').show();
                    return;
                }

                // Search parent lists
                $('.parent-list-item').each(function() {
                    const parentTitle = $(this).find('.parent-title').text().toLowerCase();
                    const parentId = $(this).data('parent-id');

                    // Search cards within this parent
                    let hasMatchingCards = false;
                    $(this).find('.card-item').each(function() {
                        const cardTitle = $(this).find('h3').text().toLowerCase();
                        const cardDescription = $(this).find('p.text-sm').text()
                            .toLowerCase();

                        if (cardTitle.includes(searchTerm) || cardDescription.includes(
                                searchTerm)) {
                            $(this).show();
                            hasMatchingCards = true;
                        } else {
                            $(this).hide();
                        }
                    });

                    // Show/hide parent based on matches
                    if (parentTitle.includes(searchTerm) || hasMatchingCards) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Clear search when clicking escape
            $(document).keyup(function(e) {
                if (e.key === "Escape") {
                    $('#searchInput').val('').trigger('input');
                }
            });
        });
    </script>
@endpush
