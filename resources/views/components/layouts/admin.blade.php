<!-- resources/views/layouts/admin-layout.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Textio Admin Dashboard' }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Alpine.js CDN -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        primary: '#8f4da7',
                        dark: '#171717'
                    },
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }

        .menu-item-active {
            background-color: rgba(143, 77, 167, 0.1);
            border-left: 4px solid #8f4da7;
        }

        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .submenu.open {
            max-height: 200px;
        }

        .overlay {
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            pointer-events: none;
        }

        .overlay.visible {
            opacity: 0.5;
            pointer-events: auto;
        }
    </style>
    @livewireStyles
</head>

<body x-data="{ isSidebarOpen: false, toggleSidebar() { this.isSidebarOpen = !this.isSidebarOpen } }">    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 z-20 bg-black overlay lg:hidden" :class="{ 'visible': isSidebarOpen }" x-on:click="toggleSidebar()"></div>

    <!-- Layout wrapper -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-adminsidebar />

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <x-admin-header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto mt-16 p-4 bg-gray-100">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Toastr Notifications -->
    <div x-data="noticesHandler()">
        <template x-for="notice in visible" :key="notice.id">
            <div class="fixed top-4 right-4 bg-blue-500 text-white p-4 rounded shadow">
                <span x-text="notice.message"></span>
            </div>
        </template>
    </div>

    <script>
        function noticesHandler() {
            return {
                notices: [],
                visible: [],
                add(notice) {
                    notice.id = Date.now();
                    this.notices.push(notice);
                    this.fire(notice.id);
                },
                fire(id) {
                    this.visible.push(this.notices.find(notice => notice.id === id));
                    setTimeout(() => this.remove(id), 3000);
                },
                remove(id) {
                    this.visible = this.visible.filter(notice => notice.id !== id);
                },
            };
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/libs/bundle.js') }}"></script>
    <script src="{{ asset('assets/libs/charts/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/libs/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/examples/dashboard.js') }}"></script>
    <script src="{{ asset('assets/dist/js/app.min.js') }}"></script>
    @livewireScripts
    <script>
        window.livewire_app_url = "{{ config('app.url') }}";
        window.livewire_token = "{{ csrf_token() }}";
    </script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    @stack('scripts')
</body>
</html>