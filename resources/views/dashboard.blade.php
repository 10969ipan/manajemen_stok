@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <div class="flex items-center space-x-4 mt-2 md:mt-0">
            <span class="text-sm text-gray-500 flex items-center">
                <i class="far fa-calendar-alt mr-2"></i> {{ now()->format('l, d F Y') }}
            </span>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Barang Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0 bg-primary-100 rounded-lg p-4">
                    <i class="fas fa-boxes text-primary-600 text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-gray-500">Total Barang</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalItems }}</p>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-200">
                <a href="{{ route('items.index') }}"
                    class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center">
                    Lihat semua <i
                        class="fas fa-chevron-right ml-1 text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>

        <!-- Permintaan Pending Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-gray-500">Permintaan Pending</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingRequests }}</p>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-200">
                <a href="{{ route('item-requests.index') }}?status=pending"
                    class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center">
                    Lihat semua <i
                        class="fas fa-chevron-right ml-1 text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>

        <!-- Stok Rendah Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-lg p-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-gray-500">Stok Rendah</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $lowStockItems }}</p>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-200">
                <a href="{{ route('items.index') }}"
                    class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center">
                    Lihat semua <i
                        class="fas fa-chevron-right ml-1 text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @if (auth()->user()->isAdmin())
            <a href="{{ route('transactions.create') }}"
                class="bg-white p-4 rounded-lg shadow-md flex items-center justify-center text-center hover:bg-gray-50 transition-colors duration-200">
                <div>
                    <i class="fas fa-plus-circle text-primary-600 text-2xl mb-2"></i>
                    <p class="text-sm font-medium text-gray-700">Transaksi Baru</p>
                </div>
            </a>
        @endif
        <a href="{{ route('item-requests.index') }}"
            class="bg-white p-4 rounded-lg shadow-md flex items-center justify-center text-center hover:bg-gray-50 transition-colors duration-200">
            <div>
                <i class="fas fa-clipboard-list text-primary-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Permintaan Barang</p>
            </div>
        </a>
        <a href="{{ route('items.index') }}"
            class="bg-white p-4 rounded-lg shadow-md flex items-center justify-center text-center hover:bg-gray-50 transition-colors duration-200">
            <div>
                <i class="fas fa-boxes text-primary-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Daftar Barang</p>
            </div>
        </a>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('reports.stock') }}"
                class="bg-white p-4 rounded-lg shadow-md flex items-center justify-center text-center hover:bg-gray-50 transition-colors duration-200">
                <div>
                    <i class="fas fa-chart-bar text-primary-600 text-2xl mb-2"></i>
                    <p class="text-sm font-medium text-gray-700">Laporan Stok</p>
                </div>
            </a>
        @endif
    </div>

    <!-- Transaksi Terakhir -->
    @if (auth()->user()->isAdmin())
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Transaksi Terakhir</h3>
                    <p class="mt-1 text-sm text-gray-500">Riwayat pergerakan barang masuk dan keluar terakhir.</p>
                </div>
                <a href="{{ route('transactions.index') }}"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Lihat semua
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Barang
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengguna
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse(\App\Models\Transaction::with(['item', 'user'])->latest()->take(5)->get() as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction->item->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->quantity }}
                                    {{ $transaction->item->unit->symbol }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->user->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada transaksi
                                    ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Permintaan Terakhir -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Permintaan Terakhir</h3>
                <p class="mt-1 text-sm text-gray-500">Permintaan barang terakhir dari staf.</p>
            </div>
            <a href="{{ route('item-requests.index') }}"
                class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Lihat semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminta
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse(\App\Models\ItemRequest::with(['item', 'user'])->latest()->take(5)->get() as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $request->item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->quantity }}
                                {{ $request->item->unit->symbol }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($request->status === 'pending')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($request->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada permintaan
                                ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
