<div id="modalDetailCard" class="fixed inset-0 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative z-50">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-xl font-semibold">Card Details</h2>
            <button id="closeModalDetailCard" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal Body dengan scrolling -->
        <div class="modal-content p-4 space-y-4 overflow-y-auto max-h-[80vh]">
            <form id="updateCardForm" enctype="multipart/form-data">
                <input type="hidden" id="card_id" name="id">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="card_title" name="title"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="card_description" name="description" rows="3"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="datetime-local" id="card_due_date" name="due_date"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Due Date Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" id="card_is_due_checked" name="is_due_checked"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="card_is_due_checked" class="ml-2 block text-sm text-gray-700">
                        Mark as completed
                    </label>
                </div>

                <!-- Image Preview -->
                <div id="card_image_preview_container" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Current Image</label>
                    <img id="card_image_preview" class="mt-2 rounded-md max-h-40" src="" alt="Card Image">
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Change Image</label>
                    <input type="file" id="card_image" name="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end items-center gap-4 p-4 border-t">
            <!-- Tombol Delete -->
            <button id="btn-delete-card"
                class="flex items-center justify-center p-2 text-red-500 hover:text-red-700 transition-colors cursor-pointer"
                title="Delete Card">
                <i class="fas fa-trash"></i>
            </button>

            <!-- Tombol Update -->
            <button type="submit" form="updateCardForm" id="btn-update-card"
                class="px-4 py-2 bg-orange-400 text-white rounded-md hover:bg-orange-500 cursor-pointer transition-colors">
                Update
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Event untuk membuka modal detail card (gunakan event delegation)
        $(document).on('click', '.btn-detail-card', function() {
            let cardId = $(this).data('id');
            console.log("Detail card clicked:", cardId);

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

                    $('#modalDetailCard').removeClass('hidden');
                },
                error: function(xhr) {
                    console.error("Error:", xhr);
                }
            });
        });
        // Event untuk menutup modal detail card
        $('#closeModalDetailCard').click(function() {
            $('#modalDetailCard').addClass('hidden');
        });

        // Event untuk melakukan update card
        $('#updateCardForm').on('submit', function(e) {
            e.preventDefault();

            let id = $('#card_id').val();
            let formData = new FormData(this);

            formData.append('is_due_checked', $('#card_is_due_checked').is(':checked') ? '1' : '0');

            $.ajax({
                url: `/cards/update/${id}`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-HTTP-Method-Override': 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload(); // Reload untuk melihat perubahan
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || "Failed to update card.",
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message ||
                            "Terjadi kesalahan saat memperbarui data.",
                    });
                }
            });
        });

        // Event untuk menghapus card
        $('#btn-delete-card').click(function() {
            let cardId = $('#card_id').val();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/cards/delete/${cardId}`,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON?.message || 'Failed to delete card',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
