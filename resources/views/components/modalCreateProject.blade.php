<div id="modalCreateTodo" class="fixed inset-0 bg-opacity-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-xl font-semibold">Create</h2>
            <button id="closeModalCreateTodo" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="p-4 space-y-4">
            <form action="{{ route('storeSession') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        name="title">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        name="description"></textarea>
                </div>
                

                <div>
                    <label class="block text-sm font-medium text-gray-700">Choose Image</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span id="upload-text">Upload a file</span>
                                    <input id="file-upload" name="img" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            <!-- Tambahkan ini -->
                            <span id="file-name" class="text-sm text-gray-700"></span>
                        </div>
                    </div>
                </div>


                <div class="p-4 border-t flex justify-end">
                    <button class="px-4 py-2 bg-orange-400 text-white rounded-md hover:bg-orange-500 cursor-pointer"
                        type="submit">Enter</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    document.getElementById('file-upload').addEventListener('change', function(event) {
        const fileName = event.target.files[0] ? event.target.files[0].name : "No file chosen";
        document.getElementById('file-name').textContent = fileName;
    });
</script>
