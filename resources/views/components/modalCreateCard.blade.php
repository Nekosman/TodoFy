<div id="modalCreateCard" class="fixed inset-0 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative z-50">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-xl font-semibold">Create New Card</h2>
            <button id="closeModalCreateCard" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="modal-content p-4 space-y-4 overflow-y-auto max-h-[80vh]">
            <form id="createCardForm" enctype="multipart/form-data">
                <input type="hidden" id="parent_id" name="parent_id">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title*</label>
                    <input type="text" id="card_title" name="title" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="card_description" name="description"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" id="due_date" name="due_date"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Choose Image</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600">
                                <label for="card_img"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload a file</span>
                                    <input id="card_img" name="img" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            <span id="card-file-name" class="text-sm text-gray-700"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end p-4 border-t">
            <button id="submitCreateCard" type="button"
                class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 cursor-pointer transition-colors">
                Create Card
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Event untuk membuka modal create card
        $('body').on('click', '#btn-add-card', function() {
            const parentId = $(this).closest('.parent-list-item').data('parent-id');
            $('#parent_id').val(parentId);
            $('#modalCreateCard').removeClass('hidden');
        });

        // Event untuk menutup modal create card
        $('#closeModalCreateCard').click(function() {
            $('#modalCreateCard').addClass('hidden');
        });

        // Event untuk submit form create card
        $('#submitCreateCard').click(function() {
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
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Langsung buat HTML card baru
                        const card = response.card;
                        const parentList = $(`.parent-list-item[data-parent-id="${card.parent_id}"]`);

                        if (parentList.length) {
                            const cardHtml = `
                            <div class="bg-gray-700 p-3 rounded-md mb-2 card-item">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-medium">${card.title}</h3>
                                        ${statusBadgeHtml(card.is_due_checked)}
                                    </div>
                                    <button class="text-gray-400 hover:text-white btn-detail-card" 
                                            data-id="${card.id}">
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

                            parentList.find('.space-y-3').prepend(cardHtml);
                        }

                        // Reset form
                        $('#createCardForm')[0].reset();
                        $('#modalCreateCard').addClass('hidden');
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'Failed to create card';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        });


        function statusBadgeHtml(isChecked) {
            const bgClass = isChecked ? 'bg-green-500' : 'bg-yellow-500';
            const text = isChecked ? 'Done' : 'Due';
            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${bgClass} text-white">${text}</span>`;
        }

        // Menampilkan nama file yang dipilih untuk card
        $('#card_img').change(function() {
            const fileName = $(this).val().split('\\').pop();
            $('#card-file-name').text(fileName || 'No file chosen');
        });
    </script>
@endpush
