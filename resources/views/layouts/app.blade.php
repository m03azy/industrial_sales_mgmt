<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        

    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col sm:ml-64 transition-all duration-300">
                <!-- Mobile Header -->
                <div class="sm:hidden flex items-center justify-between bg-indigo-900 text-white p-4">
                    <div class="font-bold text-lg">SmartSupply</div>
                    <button id="mobile-menu-btn" class="focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            const btn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');

            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        </script>

        <!-- Google Maps API -->
        <script>
            window.googleMapsApiKey = '{{ env('GOOGLE_MAPS_API_KEY', 'YOUR_API_KEY_HERE') }}';
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', 'YOUR_API_KEY_HERE') }}&libraries=places&callback=initGoogleMaps" async defer></script>
        <script>
            function initGoogleMaps() {
                window.dispatchEvent(new Event('google-maps-loaded'));
            }
        </script>
        
        @stack('scripts')
    </body>
</html>
