<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ config('app.name', 'Sistem Manajemen Stok') }}</title>
    <link rel="icon" href="{{ asset('image/sima1.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#93cdfdff',
                            800: '#41b3f0ff',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .sidebar-item.active {
            background-color: #e0f2fe;
            color: #0369a1;
            border-left: 4px solid #0ea5e9;
        }

        .sidebar-item.active:hover {
            background-color: #e0f2fe;
        }

        .card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            transition: all 0.2s ease;
        }

        .pagination li a:hover {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .pagination li.active span {
            background-color: #0ea5e9;
            color: white;
        }

        .pagination li.disabled span {
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* Untuk konsistensi tampilan form controls */
        select,
        input[type="date"],
        input[type="text"] {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        select:focus,
        input[type="date"]:focus,
        input[type="text"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
    </style>
    @stack('styles')
</head>

<body class="h-full" x-data="{ mobileMenuOpen: false, profileMenuOpen: false }">
    <div class="min-h-screen flex flex-col md:flex-row">
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <div class="flex items-center justify-center h-16 px-4 bg-primary-700">
                    <div class="flex items-center">
                        <img src="{{ asset('image/sima1.png') }}" alt="SIMASTOK" class="h-12 w-12 mr-2" />
                        
                    </div>
                </div>
                <div class="flex flex-col flex-grow overflow-y-auto">
                    <div class="flex flex-col py-4">
                        <div class="px-4 mb-4">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
                                </div>
                            </div>
                        </div>
                        <nav class="flex-1 px-2 space-y-1">
                            <a href="{{ route('dashboard') }}"
                                class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i
                                    class="fas fa-tachometer-alt mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('dashboard') ? 'text-primary-600' : '' }}"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('items.index') }}"
                                class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('items.*') ? 'active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i
                                    class="fas fa-boxes mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('items.*') ? 'text-primary-600' : '' }}"></i>
                                Barang
                            </a>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('transactions.index') }}"
                                    class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('transactions.*') ? 'active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-exchange-alt mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('transactions.*') ? 'text-primary-600' : '' }}"></i>
                                    Transaksi
                                </a>
                            @endif
                            <a href="{{ route('item-requests.index') }}"
                                class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('item-requests.*') ? 'active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i
                                    class="fas fa-clipboard-list mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('item-requests.*') ? 'text-primary-600' : '' }}"></i>
                                Permintaan Barang
                            </a>

                            @if (auth()->user()->isAdmin())
                                <div class="px-4 pt-4">
                                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</h3>
                                </div>

                                <a href="{{ route('users.index') }}"
                                    class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('users.*') ? 'text-primary-600' : '' }}"></i>
                                    Pengguna
                                </a>
                                <a href="{{ route('categories.index') }}"
                                    class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('categories.*') ? 'active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-tags mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('categories.*') ? 'text-primary-600' : '' }}"></i>
                                    Kategori
                                </a>
                                <a href="{{ route('units.index') }}"
                                    class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('units.*') ? 'active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-balance-scale mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('units.*') ? 'text-primary-600' : '' }}"></i>
                                    Satuan
                                </a>

                                <div x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open"
                                        class="group w-full flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                                        <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                        <span class="flex-1 text-left">Laporan</span>
                                        <svg :class="{ 'transform rotate-180': open }"
                                            class="ml-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 transition-transform duration-200"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95" class="pl-4 space-y-1">
                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('reports.stock') }}"
                                                class="group flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('reports.stock*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                                <i class="fas fa-box mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                                Laporan Stok
                                            </a>
                                        @endif

                                        <a href="{{ route('reports.transactions') }}"
                                            class="group flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('reports.transactions*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                            <i
                                                class="fas fa-exchange-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                            Laporan Transaksi
                                        </a>
                                        <a href="{{ route('reports.requests') }}"
                                            class="group flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('reports.requests*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                            <i
                                                class="fas fa-clipboard-list mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                            Laporan Permintaan
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </nav>
                    </div>
                </div>
                <div class="p-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="group flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50">
                            <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden"
            @click="mobileMenuOpen = false">
        </div>

        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-40 flex flex-col w-full max-w-xs bg-white md:hidden">
            <div class="absolute top-0 right-0 -mr-14 p-1">
                <button @click="mobileMenuOpen = false"
                    class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600">
                    <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-center h-16 px-4 bg-primary-700">
                <div class="flex items-center">
                    <i class="fas fa-warehouse text-white text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-white">Sistem Inventaris</span>
                </div>
            </div>
            <div class="flex-1 h-0 overflow-y-auto">
                <nav class="px-2 py-5 space-y-1">
                    <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i
                            class="fas fa-tachometer-alt mr-4 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('dashboard') ? 'text-primary-600' : '' }}"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('items.index') }}" @click="mobileMenuOpen = false"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('items.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i
                            class="fas fa-boxes mr-4 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('items.*') ? 'text-primary-600' : '' }}"></i>
                        Barang
                    </a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('transactions.index') }}" @click="mobileMenuOpen = false"
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('transactions.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i
                                class="fas fa-exchange-alt mr-4 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('transactions.*') ? 'text-primary-600' : '' }}"></i>
                            Transaksi
                        </a>
                    @endif
                    <a href="{{ route('item-requests.index') }}" @click="mobileMenuOpen = false"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('item-requests.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i
                            class="fas fa-clipboard-list mr-4 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('item-requests.*') ? 'text-primary-600' : '' }}"></i>
                        Permintaan Barang
                    </a>

                    @if (auth()->user()->isAdmin())
                        <div class="px-4 pt-4">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</h3>
                        </div>

                        <a href="{{ route('users.index') }}" @click="mobileMenuOpen = false"
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i
                                class="fas fa-users mr-4 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('users.*') ? 'text-primary-600' : '' }}"></i>
                            Pengguna
                        </a>
                        <a href="{{ route('categories.index') }}" @click="mobileMenuOpen = false"
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('categories.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i
                                class="fas fa-tags mr-4 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('categories.*') ? 'text-primary-600' : '' }}"></i>
                            Kategori
                        </a>
                        <a href="{{ route('units.index') }}" @click="mobileMenuOpen = false"
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('units.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i
                                class="fas fa-balance-scale mr-4 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('units.*') ? 'text-primary-600' : '' }}"></i>
                            Satuan
                        </a>

                        <div x-data="{ open: false }" class="space-y-1">
                            <button @click="open = !open"
                                class="group w-full flex items-center px-2 py-2 text-base font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50">
                                <i class="fas fa-chart-bar mr-4 text-gray-400 group-hover:text-gray-500"></i>
                                <span class="flex-1 text-left">Laporan</span>
                                <svg :class="{ 'transform rotate-180': open }"
                                    class="ml-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 transition-transform duration-200"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" class="pl-4 space-y-1">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('reports.stock') }}" @click="mobileMenuOpen = false"
                                        class="group flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('reports.stock*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                        <i class="fas fa-box mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                        Laporan Stok
                                    </a>
                                @endif

                                <a href="{{ route('reports.transactions') }}" @click="mobileMenuOpen = false"
                                    class="group flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('reports.transactions*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                    <i class="fas fa-exchange-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                    Laporan Transaksi
                                </a>
                                <a href="{{ route('reports.requests') }}" @click="mobileMenuOpen = false"
                                    class="group flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('reports.requests*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                    <i class="fas fa-clipboard-list mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                    Laporan Permintaan
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>
            </div>
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center">
                    <div
                        class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="group flex items-center w-full px-2 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50">
                        <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <div class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <button @click="mobileMenuOpen = true"
                            class="md:hidden text-gray-500 hover:text-gray-900 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="ml-4 text-lg font-semibold text-gray-900">@yield('title')</h1>
                    </div>
                    <div class="flex items-center">
                        <div class="relative ml-3">
                            <div>
                                <button @click="profileMenuOpen = !profileMenuOpen" type="button"
                                    class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <div
                                        class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                </button>
                            </div>
                            <div x-show="profileMenuOpen" @click.away="profileMenuOpen = false" x-transition
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                tabindex="-1">

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem" tabindex="-1" id="user-menu-item-2">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <main class="p-4 sm:px-6 lg:px-8">
                    <div class="mb-6">
                        @yield('header')
                    </div>
                    
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('sweetalert/sweetalert2.all.min.js') }}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
        
        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif
    </script>
    

    @stack('scripts')
</body>

</html>