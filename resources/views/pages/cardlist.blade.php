@extends('components.layout')

@section('title')
    Card List
@endsection

@push('css')
    <style>
        #calendar {
            background-color: white;
            border-radius: 0.5rem;
            padding: 1rem;
            height: 100%;
        }

        .fc {
            font-family: inherit;
        }

        .fc-toolbar-title {
            color: #edc33a;
            font-weight: 600;
        }

        .fc-button {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: white !important;
        }

        .fc-button:hover {
            opacity: 0.9;
        }

        /* Table Styles */
        #cards-table_wrapper {
            width: 100%;
        }

        #cards-table {
            width: 100% !important;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
            /* Tambahkan ini */
        }

        .table-container {
            height: auto;
            /* Tinggi menyesuaikan konten */
            min-height: 500px;
            /* Tinggi minimum */
            overflow: visible;
            /* Pastikan konten tidak dipotong */
        }

        .calendar-container {
            height: 600px;
            /* Tinggi tetap untuk kalender */
            overflow: hidden;
            /* Hindari scroll internal */
        }

        #calendar {
            height: 100% !important;
            /* Gunakan tinggi parent */
        }

        @media (max-width: 1024px) {
            .grid-container {
                grid-template-columns: 1fr;
            }

            .table-container,
            .calendar-container {
                height: auto;
                /* Di mobile kembali ke auto */
            }
        }

        .card-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f59e0b;
            overflow: hidden;
            height: 100%;
        }

        .card-header {
            background-color: #fef9c3;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #fcd34d;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #d97706;
        }
    </style>
@endpush

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

        <!-- Grid Container for Side-by-Side Layout -->
        <div class="grid-container">
            <!-- Table Section -->
            <div class="card-container table-container">
                <div class="card-header">
                    <h2 class="card-title">Your Task Cards</h2>
                </div>
                <div class="p-4">
                    <table class="min-w-full divide-y divide-yellow-200" id="cards-table">
                        <thead class="bg-yellow-100">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                    Title</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                    Parent List</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                    Due Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                    Action</th>
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

            <!-- Kalender Section -->
            <div class="card-container calendar-container">
                <div class="card-header">
                    <h2 class="card-title">Task Calendar</h2>
                </div>
                <div class="p-4 h-full">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            // Initialize Calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($calendarEvents),
                eventClick: function(info) {
                    if (info.event.url && info.event.url !== '#') {
                        window.location.href = info.event.url;
                    }
                },
                eventContent: function(arg) {
                    const titleEl = document.createElement('div');
                    titleEl.innerHTML = `<strong>${arg.event.title}</strong>`;

                    const listEl = document.createElement('div');
                    listEl.innerHTML = `<small>${arg.event.extendedProps.parent_list}</small>`;
                    listEl.classList.add('text-xs', 'opacity-75');

                    const container = document.createElement('div');
                    container.appendChild(titleEl);
                    container.appendChild(listEl);

                    return {
                        domNodes: [container]
                    };
                },
                eventClassNames: function(arg) {
                    return ['cursor-pointer', 'hover:opacity-90'];
                },
                height: 'auto' // Adjust height automatically
            });
            calendar.render();

            // Initialize DataTable
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

                    $('.dataTables_paginate .paginate_button').addClass(
                        'text-yellow-700 hover:bg-yellow-100');
                },
                drawCallback: function() {
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
