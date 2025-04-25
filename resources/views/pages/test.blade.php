@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        /* Kalender Styles */
        #calendar {
            margin: 0 auto;
            max-width: 100%;
            background-color: white;
            border-radius: 0.5rem;
            padding: 1rem;
            height: 400px; /* Tambahkan fixed height */
        }
        
        .fc {
            font-family: inherit;
        }
        
        .fc-toolbar-title {
            color: #7c3aed;
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
            width: 95%;
            margin: 0 auto;
        }
        
        #cards-table {
            width: 100% !important;
        }
    </style>
@endpush

@section('contents')
    <div class="container mx-auto px-4 py-6">
        <!-- Header dan Filter -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">All Cards</h1>
            <div class="flex space-x-2">
                <a href="?filter=all" class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter', 'all') == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">All</a>
                <a href="?filter=due" class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter') == 'due' ? 'bg-yellow-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">Due</a>
                <a href="?filter=completed" class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter') == 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">Completed</a>
                <a href="?filter=late" class="px-3 py-1 rounded transition-colors duration-200 {{ request('filter') == 'late' ? 'bg-red-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">Late</a>
            </div>
        </div>

        <!-- Kalender Section -->
        <div class="mb-8 bg-white rounded-lg shadow overflow-hidden border border-yellow-500">
            <div class="bg-yellow-50 px-6 py-3 border-b border-yellow-400">
                <h2 class="text-lg font-semibold text-yellow-500">Task Calendar</h2>
            </div>
            <div id="calendar"></div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-yellow-500">
            <div class="bg-yellow-50 px-6 py-3 border-b border-yellow-400">
                <h2 class="text-lg font-semibold text-yellow-500">Your Task Cards</h2>
            </div>
            <table class="min-w-full divide-y divide-yellow-200" id="cards-table">
                <!-- ... (table content tetap sama) ... -->
            </table>
            <div class="bg-yellow-50 px-6 py-2 border-t border-yellow-200 text-sm text-yellow-700">
                Showing <span id="table-info"></span> entries
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            // Inisialisasi Kalender
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
                    // Custom event display
                    const titleEl = document.createElement('div');
                    titleEl.innerHTML = `<strong>${arg.event.title}</strong>`;
                    
                    const listEl = document.createElement('div');
                    listEl.innerHTML = `<small>${arg.event.extendedProps.parent_list}</small>`;
                    listEl.classList.add('text-xs', 'opacity-75');
                    
                    const container = document.createElement('div');
                    container.appendChild(titleEl);
                    container.appendChild(listEl);
                    
                    return { domNodes: [container] };
                },
                eventClassNames: function(arg) {
                    return ['cursor-pointer', 'hover:opacity-90'];
                }
            });
            calendar.render();

            // Inisialisasi DataTable (tetap sama seperti sebelumnya)
            const filter = new URLSearchParams(window.location.search).get('filter') || 'all';
            const table = $('#cards-table').DataTable({
                // ... (config DataTable tetap sama) ...
            });
        });
    </script>
@endpush