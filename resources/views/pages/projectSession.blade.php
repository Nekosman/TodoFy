@extends('components.layout')

@section('title')
Project {{ $todoSession->title }}
@endsection

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
                                    <button class="text-gray-400 hover:text-white btn-detail-card"
                                        data-id="{{ $card->id }}">
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


{{-- @include('components.modalDetailParent')

@include('components.modalDetailCard')

@include('components.modalCreateCard') --}}

@push('scripts')
    <script>
        let modalParentInstance, modalCreateCardInstance, modalDetailCardInstance = null;


        $(document).on('click', '#btn-detail-parent', function() {
            const parentId = $(this).data('id');

            if (!modalParentInstance) {
                // Load modal pertama kali
                $.get('{{ route('modal.parent_detail') }}', function(html) {
                    $('body').append(html);
                    modalParentInstance = $('#modalDetailParent');
                    showParentModal(parentId);
                });
            } else {
                // Jika modal sudah pernah diload
                if (!modalParentInstance.is(':visible')) {
                    showParentModal(parentId);
                }
            }
        });


        function showParentModal(parentId) {
            $.ajax({
                url: `/parent-lists/detail/${parentId}`,
                type: "GET",
                success: function(response) {
                    if (!response?.data) return;

                    $('#ParentList_id').val(response.data.id);
                    $('#ParentList_title').val(response.data.title);

                    modalParentInstance.removeClass('hidden');

                    // Tambahkan ini untuk memastikan modal muncul di depan
                    modalParentInstance.css('display', 'flex');
                },
                error: function(xhr) {
                    console.error("Error:", xhr);
                    Swal.fire('Error', 'Failed to load parent details', 'error');
                }
            });
        }


        $(document).on('click', '#btn-add-card', function() {
            const parentId = $(this).closest('.parent-list-item').data('parent-id');

            if (!modalCreateCardInstance) {
                // Load modal pertama kali via AJAX
                $.get('{{ route('modal.createcard') }}', function(html) {
                    $('body').append(html);
                    modalCreateCardInstance = $('#modalCreateCard');

                    // Set parent_id di form
                    $('#parent_id').val(parentId);

                    // Inisialisasi event handlers
                    initCreateCardModal();

                    // Tampilkan modal
                    modalCreateCardInstance.removeClass('hidden');
                });
            } else {
                // Jika modal sudah pernah diload
                $('#parent_id').val(parentId);
                modalCreateCardInstance.removeClass('hidden');
            }
        });

        function initCreateCardModal() {
            // Event untuk menutup modal
            $('#closeModalCreateCard').off('click').on('click', function() {
                $('#modalCreateCard').addClass('hidden');
            });

            // Event untuk submit form
            $('#submitCreateCard').off('click').on('click', function() {
                const formData = new FormData($('#createCardForm')[0]);

                $.ajax({
                    url: '/cards/create',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 1500
                            });

                            // Reset form
                            $('#createCardForm')[0].reset();
                            $('#modalCreateCard').addClass('hidden');

                            // Update UI
                            if (response.card) {
                                addNewCardToUI(response.card);
                            }
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to create card'
                        });
                    }
                });
            });

            // Handler untuk menampilkan nama file
            $('#card_img').off('change').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $('#card-file-name').text(fileName || 'No file chosen');
            });
        }

        function addNewCardToUI(card) {
            const cardHtml = `
        <div class="bg-gray-700 p-3 rounded-md mb-2 card-item">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-2">
                    <h3 class="font-medium">${card.title}</h3>
                    ${card.is_due_checked ? 
                        '<span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Done</span>' : 
                        '<span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Pending</span>'}
                </div>
                <button class="btn-detail-card text-gray-400 hover:text-white" data-id="${card.id}">
                    <i class="fas fa-info-circle"></i>
                </button>
            </div>
            ${card.description ? `<p class="text-sm text-gray-300 mt-1">${card.description}</p>` : ''}
            <div class="flex justify-between items-center mt-1">
                <p class="text-xs text-gray-400">Due: ${card.due_date || ''}</p>
                <p class="text-xs ${card.is_due_checked ? 'text-green-300' : 'text-yellow-300'}">
                    ${card.is_due_checked ? 'Completed' : 'Pending'}
                </p>
            </div>
            ${card.img ? `<img src="/storage/images/cards/${card.img}" class="mt-2 rounded-md max-h-20">` : ''}
        </div>
    `;

            $(`.parent-list-item[data-parent-id="${card.parent_id}"] .space-y-3`).prepend(cardHtml);
        }


        $(document).on('click', '.btn-detail-card', function() {
            const cardId = $(this).data('id');

            if (!modalDetailCardInstance) {
                // Load modal pertama kali
                $.get('{{ route('modal.detailcard') }}', function(html) {
                    $('body').append(html);
                    modalDetailCardInstance = $('#modalDetailCard');
                    showDetailCard(cardId);
                });
            } else {
                // Jika modal sudah pernah diload
                if (!modalDetailCardInstance.is(':visible')) {
                    showDetailCard(cardId);
                }
            }
        });


        function showDetailCard(cardId) {
            $.ajax({
                url: `/cards/${cardId}`,
                type: "GET",
                success: function(response) {
                    // Isi form dengan data response
                    $('#card_id').val(response.data.id);
                    $('#card_title').val(response.data.title);
                    $('#card_description').val(response.data.description);

                    if (response.data.due_date) {
                        const dueDate = new Date(response.data.due_date);
                        $('#card_due_date').val(dueDate.toISOString().slice(0, 16));
                    }

                    $('#card_is_due_checked').prop('checked', response.data.is_due_checked);

                    if (response.data.img) {
                        $('#card_image_preview').attr('src',
                            `/storage/images/cards/${response.data.img}`);
                        $('#card_image_preview_container').removeClass('hidden');
                    }

                    modalDetailCardInstance.removeClass('hidden');
                    modalDetailCardInstance.removeClass('hidden');

                    // Tambahkan ini untuk memastikan modal muncul di depan
                    modalDetailCardInstance.css('display', 'flex');
                },
                error: function(xhr) {
                    console.error("Error:", xhr);
                    Swal.fire('Error', 'Failed to load parent details', 'error');
                }
            });
        }


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

        function loadModal() {
            $.get('/parent-lists/detail/{ParentList}', function(html) {
                $('body').append(html);
            });
        }
    </script>
@endpush
