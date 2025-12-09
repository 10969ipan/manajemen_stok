@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Baru</h1>
        <div>
            <a href="{{ route('items.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang (SKU)</label>
                        <input type="text" name="code" id="code" required value="{{ old('code') }}"
                            placeholder="Misal: KMJ-001-M"
                            class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 border">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" id="category_id" required onchange="checkCategory()"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" data-name="{{ Str::lower($category->name) }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            placeholder="Contoh: Kemeja Flannel Kotak"
                            class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 border">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- AREA KHUSUS SIZE DAN STOK --}}
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Varian & Stok</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Input Size Dinamis --}}
                            <div>
                                <label for="size" class="block text-sm font-medium text-gray-700 mb-1">Ukuran / Size</label>
                                
                                {{-- Pilihan untuk Baju/Kemeja/Jaket --}}
                                <select id="size_clothing" class="hidden mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="">Pilih Ukuran</option>
                                    @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL'] as $s)
                                        <option value="{{ $s }}" {{ old('size') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>

                                {{-- Pilihan untuk Sepatu --}}
                                <select id="size_shoes" class="hidden mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="">Pilih Ukuran (36-46)</option>
                                    @for($i = 36; $i <= 46; $i++)
                                        <option value="{{ $i }}" {{ old('size') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>

                                {{-- Input Manual (Default) --}}
                                <input type="text" id="size_text" placeholder="Contoh: All Size, 500ml, dll"
                                    class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 border"
                                    value="{{ old('size') }}">
                                
                                {{-- Hidden Input yang dikirim ke Server --}}
                                <input type="hidden" name="size" id="real_size" value="{{ old('size') }}">

                                <p class="text-xs text-gray-500 mt-1" id="size_helper">Pilih kategori terlebih dahulu untuk rekomendasi ukuran.</p>
                                @error('size')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                                <input type="number" name="stock" id="stock" required value="{{ old('stock', 0) }}"
                                    min="0"
                                    class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 border">
                                <p class="text-xs text-gray-500 mt-1">Stok ini berlaku khusus untuk ukuran yang dipilih di samping.</p>
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <select name="unit_id" id="unit_id" required
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Satuan</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->symbol }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <input type="number" name="price" id="price" required value="{{ old('price', 0) }}"
                            min="0" step="0.01"
                            class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 border">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 border">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT LOGIKA UKURAN --}}
    <script>
        function checkCategory() {
            const categorySelect = document.getElementById('category_id');
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const categoryName = selectedOption ? selectedOption.getAttribute('data-name') : '';
            
            const clothingSelect = document.getElementById('size_clothing');
            const shoesSelect = document.getElementById('size_shoes');
            const textInput = document.getElementById('size_text');
            const helperText = document.getElementById('size_helper');
            
            // Reset tampilan
            clothingSelect.classList.add('hidden');
            shoesSelect.classList.add('hidden');
            textInput.classList.add('hidden');
            
            // Reset value input yang tersembunyi
            clothingSelect.value = '';
            shoesSelect.value = '';
            // Jangan reset textInput value agar data old() tidak hilang saat refresh visual

            if (['baju', 'kemeja', 'jaket', 'kaos', 'hoodie', 'jersey', 'celana'].some(el => categoryName.includes(el))) {
                clothingSelect.classList.remove('hidden');
                helperText.innerText = 'Kategori pakaian terdeteksi: Pilihan ukuran S-3XL.';
            } else if (['sepatu', 'sandal', 'sneakers', 'boots'].some(el => categoryName.includes(el))) {
                shoesSelect.classList.remove('hidden');
                helperText.innerText = 'Kategori alas kaki terdeteksi: Pilihan ukuran 36-46.';
            } else {
                textInput.classList.remove('hidden');
                helperText.innerText = 'Input ukuran manual (atau kosongkan jika tidak ada).';
            }
        }

        // Jalankan saat load untuk handle old input
        document.addEventListener('DOMContentLoaded', function() {
            checkCategory();
            
            // Event listener untuk sinkronisasi nilai ke input hidden 'real_size'
            const realSizeInput = document.getElementById('real_size');
            
            document.getElementById('size_clothing').addEventListener('change', function() {
                realSizeInput.value = this.value;
            });
            document.getElementById('size_shoes').addEventListener('change', function() {
                realSizeInput.value = this.value;
            });
            document.getElementById('size_text').addEventListener('input', function() {
                realSizeInput.value = this.value;
            });

            // Set value awal ke dropdown yang sesuai jika ada old value
            const oldVal = realSizeInput.value;
            if(oldVal) {
                document.getElementById('size_clothing').value = oldVal;
                document.getElementById('size_shoes').value = oldVal;
                document.getElementById('size_text').value = oldVal;
            }
        });
    </script>
@endsection