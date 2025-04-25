<!-- resources/views/components/modalMoveCard.blade.php -->
<div id="modalMoveCard" class="fixed inset-0 flex items-center justify-center p-4 z-50 hidden" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative z-50">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-xl font-semibold">Move Card</h2>
            <button id="closeModalMoveCard" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Destination List</label>
            <select id="newParentSelect" class="w-full p-2 border rounded">
                @foreach ($todoSession->parentLists as $list)
                    <option value="{{ $list->id }}">{{ $list->title }}</option>
                @endforeach
            </select>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end p-4 border-t gap-2">
            <button id="confirmMove"
                class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                Move Card
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let currentCardElement = null;
            let currentCardId = null;

            // Buka modal move
            $(document).on('click', '.btn-move-card', function() {
                currentCardElement = $(this).closest('.card-item');
                currentCardId = currentCardElement.data('card-id');
                $('#modalMoveCard').removeClass('hidden').css('display', 'flex');
            });

            // Tutup modal
            $('#closeModalMoveCard, #cancelMove').click(function() {
                $('#modalMoveCard').addClass('hidden').hide();
            });

            // Proses move dengan animasi
            $('#confirmMove').click(function() {
                const newParentId = $('#newParentSelect').val();
                const $cardElement = $(`.card-item[data-card-id="${currentCardId}"]`);

                if (!$cardElement.length) {
                    Swal.fire('Error', 'Card element not found', 'error');
                    return;
                }

                // Show loading indicator
                const $button = $(this);
                $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Moving...');

                $.ajax({
                    url: `/cards/${currentCardId}/move`,
                    type: 'PUT',
                    data: {
                        new_parent_id: newParentId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Animasi pindah card
                            $cardElement.fadeOut(300, function() {
                                $(this).detach().appendTo(
                                    $(
                                        `.parent-list-item[data-parent-id="${newParentId}"] .space-y-3`)
                                ).fadeIn(300);
                            });

                            $('#modalMoveCard').hide();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON?.message || 'Failed to move card';
                        Swal.fire('Error', errorMsg, 'error');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text('Move Card');
                    }
                });
            });
        });
    </script>
@endpush
