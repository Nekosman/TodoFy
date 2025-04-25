@extends('components.layout')

@section('title')
    Card List
@endsection

@section('contents')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">All Cards</h1>
            <div class="flex space-x-2">
                <a href="?filter=all"
                    class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter', 'all') == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">All</a>
                <a href="?filter=due"
                    class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter') == 'due' ? 'bg-yellow-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">Due</a>
                <a href="?filter=completed"
                    class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter') == 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">Completed</a>
                <a href="?filter=late"
                    class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter') == 'late' ? 'bg-red-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">Late</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-yellow-500">
            <div class="bg-yellow-50 px-6 py-3 border-b border-yellow-400">
                <h2 class="text-lg font-semibold text-yellow-500">Your Task Cards</h2>
            </div>
            <table class="min-w-full divide-y divide-yellow-200" id="cards-table">
                <thead class="bg-yellow-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Title
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Parent
                            List</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Due
                            Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-yellow-100">
                    <!-- DataTables will populate this automatically -->
                </tbody>
            </table>
            <div class="bg-yellow-50 px-6 py-2 border-t border-yellow-200 text-sm text-yellow-700">
                Showing <span id="table-info"></span> entries
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #cards-table_wrapper {
            width: 95%;
            margin: 0 auto;
        }

        #cards-table {
            width: 100% !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            const filter = new URLSearchParams(window.location.search).get('filter') || 'all';

            const table = $('#cards-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('cards.index') }}",
                    data: {
                        filter: filter
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'parent_list',
                        name: 'parentList.title'
                    },
                    {
                        data: 'formatted_due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'status_badge',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [3, 'desc']
                ],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                pageLength: 10,
                language: {
                    paginate: {
                        previous: '‹',
                        next: '›'
                    },
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    search: 'Search:',
                    emptyTable: 'No data available in table'
                },
                dom: '<"flex justify-between items-center mb-4 mx-4"<"flex"l><"flex"f>>rt<"flex justify-between items-center mt-4 mx-4"<"flex"i><"flex"p>>',
                initComplete: function() {
                    $('.dataTables_length select').addClass(
                        'border border-yellow-300 rounded-md text-yellow-700');
                    $('.dataTables_filter input').addClass(
                        'border border-yellow-300 rounded-md px-2 py-1 text-yellow-700 focus:ring-yellow-500 focus:border-yellow-500'
                        );

                    // Add yellow accent to pagination buttons
                    $('.dataTables_paginate .paginate_button').addClass(
                        'text-yellow-700 hover:bg-yellow-100');
                },
                drawCallback: function() {
                    // Update the footer info
                    const info = this.api().page.info();
                    $('#table-info').text(`${info.start + 1}-${info.end} of ${info.recordsTotal}`);
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables error:', xhr.responseText);
                }
            });

            // Add yellow hover effect to table rows
            $('#cards-table').on('mouseenter', 'tbody tr', function() {
                $(this).addClass('bg-yellow-50');
            }).on('mouseleave', 'tbody tr', function() {
                $(this).removeClass('bg-yellow-50');
            });
        });
    </script>
@endpush
