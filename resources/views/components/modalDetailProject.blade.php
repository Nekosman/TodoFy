<div id="modalDetailTodo" class="fixed inset-0 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative z-50">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-xl font-semibold">Detail Todo</h2>
            <button id="closeModalDetailTodo" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal Body dengan scrolling -->
        <div class="modal-content p-4 space-y-4 overflow-y-auto max-h-[80vh]">
            <form id="updateTodoForm" enctype="multipart/form-data">
                <input type="hidden" id="TodoSession_id" name="id">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Visibility -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Visibility</label>
                    <select id="visibility" name="visibility"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Choose Image</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            {{-- <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.536-3.536A4 4 0 0024 8H12a4 4 0 00-4 4v20"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg> --}}
                            <div class="flex text-sm text-gray-600">
                                <label for="img"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload a file</span>
                                    <input id="img" name="img" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            <span id="file-name" class="text-sm text-gray-700"></span>
                        </div>
                    </div>
                    <div id="current-image-container" class="mt-2 text-center">
                        <span class="text-sm text-gray-500">Current Image:</span>
                        <img id="current-image" src="" class="mt-2 mx-auto max-h-32 hidden">
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end items-center gap-4 p-4 border-t">
            <!-- Tombol Delete -->
            <button
                class="btn-delete-session flex items-center justify-center p-2 text-red-500 hover:text-red-700 transition-colors"
                data-id="" title="Delete Session">
                <i class="fas fa-trash"></i>
            </button>

            <!-- Tombol Update -->
            <button type="submit"
                class="px-4 py-2 bg-orange-400 text-white rounded-md hover:bg-orange-500 cursor-pointer transition-colors">
                Update
            </button>
        </div>
    </div>
</div>

{{-- <style>
    #modalDetailTodo {
        display: none !important;
    }
</style> --}}


@push('scripts')
    <script>
        // Event untuk membuka modal detail
        $('body').on('click', '#btn-detail-todo', function() {
            const sessionId = $(this).data('id');
            $('.btn-delete-session').data('id', sessionId);

            let TodoSession_id = $(this).data('id');
            $.ajax({
                url: `/project/${TodoSession_id}`,
                type: "GET",
                cache: false,
                success: function(response) {
                    console.log("Response dari server:", response);

                    if (!response || !response.data) {
                        console.error("Data tidak ditemukan dalam response:", response);
                        return;
                    }

                    // Isi form dengan data dari response
                    $('#TodoSession_id').val(response.data.id);
                    $('#title').val(response.data.title);
                    $('#description').val(response.data.description);
                    $('#visibility').val(response.data.visibility);

                    // Tampilkan gambar saat ini jika ada
                    if (response.data.img) {
                        $('#current-image').attr('src', response.data.img);
                        $('#current-image').removeClass('hidden');
                    } else {
                        $('#current-image').addClass('hidden');
                    }

                    $('#modalDetailTodo').removeClass('hidden');
                },
                error: function(xhr, status, error) {
                    console.error("Error saat melakukan request:", error);
                }
            });
        });

        // Event untuk menutup modal detail
        $('#closeModalDetailTodo').click(function() {
            $('#modalDetailTodo').addClass('hidden');
        });

        // Event untuk submit form update
        $('#updateTodoForm').submit(function(e) {
            e.preventDefault();

            let sessionId = $('#TodoSession_id').val(); // Ambil session_id dari input hidden
            let formData = new FormData(this);

            // AJAX update
            $.ajax({
                url: `/project/${sessionId}/update`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Tutup modal setelah sukses
                    $('#modalDetailTodo').addClass('hidden');

                    // Update data di halaman tanpa reload
                    updateTodoCard(response.data);
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message ||
                        'Terjadi kesalahan saat mengupdate data';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        });

        // Fungsi untuk mengupdate card Todo di halaman
        function updateTodoCard(updatedData) {
            // Cari card yang sesuai dengan ID yang diupdate
            const card = $(`[data-id="${updatedData.id}"]`).closest('.block');

            if (card.length) {
                // Update title
                card.find('.text-lg').text(updatedData.title);

                // Update image jika ada perubahan
                if (updatedData.img) {
                    card.find('img').attr('src', updatedData.img);
                }

                // Anda bisa menambahkan update untuk elemen lainnya sesuai kebutuhan
            }
        }

        // Menampilkan nama file yang dipilih
        $('#img').change(function() {
            const fileName = $(this).val().split('\\').pop();
            $('#file-name').text(fileName || 'No file chosen');
        });

        // Event untuk delete session
        $('body').on('click', '.btn-delete-session', function() {
            let sessionId = $(this).data('id');

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
                        url: `/project/${sessionId}/delete`,
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
                                // Refresh halaman atau update UI
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON?.message || 'Failed to delete session',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
