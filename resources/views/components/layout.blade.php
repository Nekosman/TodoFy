<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TODOFY |  @yield('title')</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
@vite('resources/css/app.css')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="{{ asset('js/sidebar.js') }}"></script> <!-- Tambahkan script JS -->
@stack('css')
    
<style>
    .transition-all {
        transition: all 0.3s;
    }

    .duration-300 {
        transition-duration: 300ms;
    }

    .scale-0 {
        transform: scale(0);
    }

    .rotate-[360deg] {
        transform: rotate(360deg);
    }

    .fa-flip-horizontal {
        transform: scaleX(-1);
    }
</style>
<style>
    body {
        font-family: 'League Spartan', sans-serif;
        background: #F8F9FA;
    }
</style>
</head>

<body>
    <div class="flex">
        <!-- Sidebar -->
        <div
            class="sidebar bg-orange-400 min-h-screen p-5 pt-8 relative transition-all duration-300 shadow-lg overflow-visible w-60">
            <i
                class="arrow-icon fas fa-arrow-left text-orange-400 bg-white p-3 rounded-full w-10 h-10 flex items-center justify-center absolute -right-5 top-9 border border-orange-400 cursor-pointer"></i>

            <div class="inline-flex items-center justify-center">
                <!-- Logo dengan Background -->
                <i
                    class="logo bg-white rounded-lg p-2 flex items-center justify-center w-12 h-12 mr-2 cursor-pointer duration-400 ml-5">
                    <img src="/storage/images/Flower.png" alt="Logo" class="w-8 h-8">
                </i>
                <!-- Teks -->
                <h1 class="sidebar-text text-white font-medium duration-300 text-4xl">TodoFy</h1>
            </div>

            <ul class="mt-5 duration-400 navlink ml-5">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}">
                    <li class=" flex items-center p-3 cursor-pointer hover:bg-orange-500 rounded-lg transition">
                        <i class="fas fa-home text-white w-6 text-lg"></i>
                        <span class="sidebar-text text-white duration-300 ml-3 text-lg">Dashboard</span>
                    </li>
                </a>
              
                <!-- Create -->
                <li class="flex items-center p-3 cursor-pointer hover:bg-orange-500 rounded-lg transition"
                    id="openModalCreate">
                    <a href="#" class="flex items-center">
                        <i class="fas fa-plus-circle text-white w-6 text-lg"></i>
                        <span class="sidebar-text text-white duration-300 ml-3 text-lg">Create</span>
                    </a>
                </li>

                <a href="{{ route('setting.index') }}">
                    <!-- Setting -->
                <li class=" flex items-center p-3 cursor-pointer hover:bg-orange-500 rounded-lg transition">
                    <i class="fas fa-cog text-white w-6 text-lg"></i>
                    <span class="sidebar-text text-white duration-300 ml-3 text-lg">Setting</span>
                </li>
                </a>
                
                
                <li class="flex items-center p-3 cursor-pointer hover:bg-orange-500 rounded-lg transition"
                    id="openLogout">
                    <a href="#" class="flex items-center">
                        <i class="fas fa-sign-out-alt text-white w-6 text-lg"></i>
                        <span class="sidebar-text text-white duration-300 ml-3 text-lg">Logout</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- Content -->
        <div class="p-7">

            @yield('contents')
        </div>
    </div>
</body>

@include('components.modalCreateProject')
@include('components.modalLogout')

@stack('scripts')
<script>
    // Fungsi untuk menampilkan atau menyembunyikan modal
    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden', !show);
        }
    }

    // Event Listener untuk tombol buka modal
    document.getElementById('openModalCreate')?.addEventListener('click', () => toggleModal('modalCreateTodo', true));
    document.getElementById('openLogout')?.addEventListener('click', () => toggleModal('modalLogout', true));

    // Event Listener untuk tombol tutup modal
    document.getElementById('closeModalCreateTodo')?.addEventListener('click', () => toggleModal('modalCreateTodo',
        false));
    document.getElementById('closeModalLogout')?.addEventListener('click', () => toggleModal('modalLogout', false));

    // Tutup modal saat mengklik di luar area modal
    window.addEventListener('click', (event) => {
        const modals = ['modalCreateTodo', 'modalLogout']; // List modal yang ingin ditutup
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal && event.target === modal) {
                toggleModal(modalId, false);
            }
        });
    });
</script>

</html>
