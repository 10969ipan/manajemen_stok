<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemSize;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // UPDATE: Tambahkan 'sizes' ke dalam with() agar bisa ditampilkan di tabel
        $query = Item::with(['category', 'unit', 'sizes']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%")
                  ->orWhere('size', 'like', "%$search%");
            })->orWhereHas('category', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $items = $query->paginate(10);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('items.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:items',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'required|string',
            'stocks' => 'nullable|array',
            'stocks.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalStock = 0;
            $sizeSummary = null;

            if ($request->has('sizes') && is_array($request->sizes)) {
                $totalStock = array_sum($request->stocks);
                $sizeSummary = implode(', ', $request->sizes);
            }

            $item = Item::create([
                'code' => $request->code,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'price' => $request->price,
                'description' => $request->description,
                'stock' => $totalStock,
                'size' => $sizeSummary,
            ]);

            if ($request->has('sizes')) {
                foreach ($request->sizes as $index => $sizeVal) {
                    if (!empty($sizeVal)) {
                        ItemSize::create([
                            'item_id' => $item->id,
                            'size' => $sizeVal,
                            'stock' => $request->stocks[$index] ?? 0,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('items.index')->with('success', 'Item berhasil dibuat.');
    }

    public function show(Item $item)
    {
        $item->load('sizes');
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $units = Unit::all();
        $item->load('sizes');
        return view('items.edit', compact('item', 'categories', 'units'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'sizes' => 'nullable|array',
            'stocks' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $item) {
            $totalStock = 0;
            $sizeSummary = null;

            if ($request->has('sizes') && is_array($request->sizes)) {
                $totalStock = array_sum($request->stocks);
                $sizeSummary = implode(', ', array_unique($request->sizes));
            }

            $item->update([
                'code' => $request->code,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'price' => $request->price,
                'description' => $request->description,
                'stock' => $totalStock,
                'size' => $sizeSummary,
            ]);

            // Reset sizes (Hapus lama, insert baru)
            $item->sizes()->delete();

            if ($request->has('sizes')) {
                foreach ($request->sizes as $index => $sizeVal) {
                    if (!empty($sizeVal)) {
                        ItemSize::create([
                            'item_id' => $item->id,
                            'size' => $sizeVal,
                            'stock' => $request->stocks[$index] ?? 0,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }
}