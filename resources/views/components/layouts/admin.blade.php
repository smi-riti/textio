<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="{{asset('assets/dist/icons/bootstrap-icons-1.4.0/bootstrap-icons.min.css')}}"
        type="text/css">
    <!-- Bootstrap Docs -->
    <link rel="stylesheet" href="{{asset('assets/dist/css/bootstrap-docs.css')}}" type="text/css">

    <!-- Slick -->
    <link rel="stylesheet" href="{{asset('assets/libs/slick/slick.css')}}" type="text/css">
    <link href="../../css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Main style file -->
    <link rel="stylesheet" href="{{asset('assets/dist/css/app.min.css')}}" type="text/css">


    {{-- tailwind Scripts--}}
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Add Tailwind CSS base styles -->
    <style type="text/tailwindcss">
        @layer base {
            html {
                font-family: "Poppins", system-ui, sans-serif;
            }
        }
    </style>

    <!-- Your existing styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Parisienne&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: "Poppins", sans-serif !important;
        }
    </style>
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    @livewireStyles
</head>

<body>

    <div>
        <x-adminsidebar />
    </div>

    <main>
        <x-admin-header />
        <div class="">
            {{ $slot }}
        </div>
    </main>

    {{-- toastr --}}
    <div class="flex justify-end items-center ">
        <div x-data="noticesHandler()" class="fixed top-5 right-5 flex flex-col items-end space-y-3 p-4 z-50"
            @notice.window="add($event.detail)" style="pointer-events:none">
            <template x-for="notice in notices" :key="notice . id">
                <div x-show="visible.includes(notice)" x-transition:enter="transition ease-in duration-200"
                    x-transition:enter-start="transform opacity-0 translate-x-5"
                    x-transition:enter-end="transform opacity-100 translate-x-0"
                    x-transition:leave="transition ease-out duration-500"
                    x-transition:leave-start="transform opacity-100 translate-x-0"
                    x-transition:leave-end="transform opacity-0 translate-x-5" @click="remove(notice.id)"
                    class="rounded-lg px-4 py-3 w-72 bg-gray-400  shadow-lg text-black font-medium text-sm cursor-pointer flex items-center justify-between"
                    style="pointer-events:all">
                    <span x-text="notice.text"></span>
                    <button @click="remove(notice.id)" class="ml-2 text-white font-bold">×</button>
                </div>
            </template>
        </div>
    </div>

    <!-- Scripts -->
    {{-- toastr --}}

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
                    setTimeout(() => {
                        this.remove(id);
                    }, 1000);
                },
                remove(id) {
                    this.visible = this.visible.filter(notice => notice.id !== id);
                },
            };
        }
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('success', (data) => {
                const message = data[0].message;
                toastr.success(message, 'Success');
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset('assets/libs/bundle.js')}}"></script>

    <!-- Apex chart -->
    <script src="{{asset('assets/libs/charts/apex/apexcharts.min.js')}}"></script>

    <!-- Slick -->
    <script src="{{asset('assets/libs/slick/slick.min.js')}}"></script>

    <!-- Examples -->
    <script src="{{asset('assets/dist/js/examples/dashboard.js')}}"></script>

    <!-- Main Javascript file -->
    <script src="{{asset('assets/dist/js/app.min.js')}}"></script>
    @livewireScripts
    <script>
        // Add Livewire configuration
        window.livewire_app_url = "{{ config('app.url') }}";
        window.livewire_token = "{{ csrf_token() }}";
    </script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    @stack('scripts')
</body>

</html>