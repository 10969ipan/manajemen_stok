@extends('layouts.app')

@section('title', 'Detail Barang')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Detail Barang</h1>
        <div>
            <a href="{{ route('items.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            @can('admin')
                <a href="{{ route('items.edit', $item->id) }}"
                    class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Barang</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kode Barang</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Barang</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->name }}</p>
                    </div>
                    {{-- Detail Ukuran Baru --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ukuran</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->size ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kategori</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->category->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Satuan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->unit->name }} ({{ $item->unit->symbol }})</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Stok Tersedia</label>
                        <p class="mt-1 text-sm font-semibold {{ $item->stock < 10 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $item->stock }} {{ $item->unit->symbol }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Harga Satuan</label>
                        <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->description ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Riwayat Transaksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengguna</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($item->transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span
                                        class="badge {{ $transaction->type === 'in' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->quantity }}
                                    {{ $item->unit->symbol }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->note ?? '-' }}</td>
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
    </div>
@endsection