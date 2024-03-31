<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @livewireStyles

        <style>
            .sidebar {
                width: 64px; /* Lebar awal sidebar */
                transition: width 0.3s ease; /* Efek transisi untuk perubahan lebar */
            }

            .sidebar.collapsed {
                width: 48px; /* Lebar sidebar ketika mengecil */
            }

            .sidebar-label {
                display: inline-block; /* Tampilkan teks dalam satu baris */
                margin-left: 8px; /* Jarak antara ikon dan teks */
                transition: opacity 0.3s ease; /* Efek transisi untuk kejelasan teks */
            }

            .sidebar.collapsed .sidebar-label {
                opacity: 0; /* Sembunyikan teks saat sidebar mengecil */
            }

            .sidebar.collapsed ul li:not(:first-child) {
                display: none; /* Sembunyikan semua item kecuali tombol toggle saat sidebar mengecil */
            }

            .sidebar.collapsed .sidebar-title {
                display: none; /* Sembunyikan judul sidebar saat sidebar mengecil */
            }

            #toggleSidebarButton {
                /* position: absolute; Posisi tombol */
                top: 10px;
                left: 10px;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen">
            <div class="sidebar w-64 bg-red-700 text-white p-4">
                <ul>
                    <li class="flex items-center">
                        <a href="{{ route('products.index') }}" class="sidebar-title text-xl font-bold py-2 flex items-center justify-center mr-5">
                            <img src="{{ asset('images/white-shopping-bag.png') }}" class="w-7 h-7 mr-2" alt="SIMS Web App">
                            SIMS Web App
                        </a>
                        <button id="toggleSidebarButton" type="button">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h10" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 18h16" />
                            </svg>
                        </button>
                    </li>
                    <li class="mt-4">
                        <a href="{{ route('products.index') }}" class="flex items-center p-2 text-white bg-red-600 rounded-lg hover:bg-red-500">
                            <img src="{{ asset('images/Package.png') }}" class="w-5 h-5 mr-2" alt="Produk">
                            Produk
                        </a>
                    </li>
                    {{-- <li class="mt-2">
                        <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-white bg-red-600 rounded-lg hover:bg-red-500">
                            <img src="{{ asset('images/Package.png') }}" class="w-5 h-5 mr-2" alt="Kategori Produk">
                            Kategori Produk
                        </a>
                    </li> --}}
                    <li class="mt-2">
                        <a href="{{ route('profile.show') }}" class="flex items-center p-2 text-white bg-red-600 rounded-lg hover:bg-red-500">
                            <img src="{{ asset('images/User.png') }}" class="w-5 h-5 mr-2" alt="Produk">
                            Profil
                        </a>
                    </li>
                    <li class="mt-2">
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <button type="submit" class="flex items-center p-2 text-white bg-red-600 rounded-lg hover:bg-red-500">
                                <img src="{{ asset('images/SignOut.png') }}" class="w-5 h-5 mr-2" alt="Logout">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="flex-1 p-4">
                {{-- @livewire('navigation-menu') --}}

                <main>
                    @isset($slot)
                        {{ $slot }}
                    @endisset

                    @yield('content')
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/yourcode.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleSidebarButton = document.getElementById('toggleSidebarButton');
                const sidebar = document.querySelector('.sidebar');

                toggleSidebarButton.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            });
        </script>
    </body>
</html>