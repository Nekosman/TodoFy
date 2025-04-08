@extends('components.layout')

@section('contents')
    <div class="p-5">
        <h1 class="text-3xl font-semibold mb-6">Project: {{ $todoSession->title }}</h1>

        <!-- Grid Parent Lists -->
        <div id="parent-list-container" class="flex flex-wrap gap-5 justify-center relative">
            @foreach ($todoSession->parentLists as $list)
                <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md w-full sm:w-[300px] parent-list-item"
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



                    <div class="space-y-3">
                        @foreach ($list->cards as $card)
                            <div class="bg-gray-700 p-3 rounded-md mb-2 card-item">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-medium">{{ $card->title }}</h3>
                                        {!! status_badge($card->is_due_checked, 'bg-green-500 text-white', 'bg-yellow-500 text-white') !!}
                                    </div>
                                    <button class="text-gray-400 hover:text-white" data-id="{{ $card->id }}"
                                        id="btn-detail-card">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-300 mt-1">{{ $card->description }}</p>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-400">Due: {{ $card->due_date }}</p>
                                    <p class="text-xs {{ $card->is_due_checked ? 'text-green-300' : 'text-yellow-300' }}">
                                        {{ status_text($card->is_due_checked, 'Completed', 'Pending') }}
                                    </p>
                                </div>
                                @if (!empty($card->img) && file_exists(public_path('storage/images/cards/' . $card->img)))
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


@include('components.modalDetailParent')

@include('components.modalDetailCard')

@include('components.modalCreateCard')

@push('scripts')
    <script>
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
        <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md w-full sm:w-[300px] parent-list-item"
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
    </script>
@endpush
