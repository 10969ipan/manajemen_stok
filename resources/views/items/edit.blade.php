@extends('layouts.app')

@section('title', 'Edit Barang')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Barang</h1>
        <div>
            <a href="{{ route('items.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card bg-white rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Kode Barang</label>
                        <input type="text" name="code" id="code" required value="{{ old('code', $item->code) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2 outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                        @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category_id" id="category_id" required onchange="checkCategory()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2 outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" data-name="{{ Str::lower($category->name) }}"
                                    {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $item->name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2 outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- AREA KHUSUS SIZE DAN STOK --}}
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Input Size Dinamis --}}
                            <div>
                                <label for="size" class="block text-sm font-medium text-gray-700 mb-1">Ukuran / Size</label>
                                
                                <select id="size_clothing" class="hidden mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2">
                                    <option value="">Pilih Ukuran</option>
                                    @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL'] as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>

                                <select id="size_shoes" class="hidden mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2">
                                    <option value="">Pilih Ukuran (36-46)</option>
                                    @for($i = 36; $i <= 46; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>

                                <input type="text" id="size_text" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2">
                                
                                <input type="hidden" name="size" id="real_size" value="{{ old('size', $item->size) }}">
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                                <input type="number" name="stock" id="stock" required
                                    value="{{ old('stock', $item->stock) }}" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2 outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                                @error('stock')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700">Satuan</label>
                        <select name="unit_id" id="unit_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2 outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                            <option value="">Pilih Satuan</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('unit_id', $item->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->symbol }})</option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" name="price" id="price" required
                            value="{{ old('price', $item->price) }}" min="0" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2 outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2 outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkCategory() {
            const categorySelect = document.getElementById('category_id');
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const categoryName = selectedOption ? selectedOption.getAttribute('data-name') : '';
            
            const clothingSelect = document.getElementById('size_clothing');
            const shoesSelect = document.getElementById('size_shoes');
            const textInput = document.getElementById('size_text');
            
            clothingSelect.classList.add('hidden');
            shoesSelect.classList.add('hidden');
            textInput.classList.add('hidden');
            
            if (['baju', 'kemeja', 'jaket', 'kaos', 'hoodie', 'jersey', 'celana'].some(el => categoryName.includes(el))) {
                clothingSelect.classList.remove('hidden');
            } else if (['sepatu', 'sandal', 'sneakers', 'boots'].some(el => categoryName.includes(el))) {
                shoesSelect.classList.remove('hidden');
            } else {
                textInput.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkCategory();
            
            const realSizeInput = document.getElementById('real_size');
            const oldVal = realSizeInput.value;

            // Set nilai awal ke inputan visual
            document.getElementById('size_clothing').value = oldVal;
            document.getElementById('size_shoes').value = oldVal;
            document.getElementById('size_text').value = oldVal;
            
            // Listener Update
            document.getElementById('size_clothing').addEventListener('change', function() {
                realSizeInput.value = this.value;
            });
            document.getElementById('size_shoes').addEventListener('change', function() {
                realSizeInput.value = this.value;
            });
            document.getElementById('size_text').addEventListener('input', function() {
                realSizeInput.value = this.value;
            });
        });
    </script>
@endsection