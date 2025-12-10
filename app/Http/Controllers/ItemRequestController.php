<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemRequest::with(['item', 'user', 'processedBy']);

        if (auth()->user()->isStaff()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(10);
        return view('item-requests.index', compact('requests'));
    }

    public function create()
    {
        $items = Item::all();
        return view('item-requests.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'size' => 'nullable|string', 
        ]);

        $item = Item::findOrFail($request->item_id);

        // Validasi stok berdasarkan ukuran jika ada
        if ($request->filled('size')) {
            $itemSize = $item->sizes()->where('size', $request->size)->first();
            
            if (!$itemSize) {
                return back()->withInput()->with('error', "Size '{$request->size}' not found for this item.");
            }

            if ($request->quantity > $itemSize->stock) {
                return back()
                    ->withInput()
                    ->with('error', "Requested quantity exceeds available stock for size {$request->size} (Current: {$itemSize->stock})");
            }
        } else {
            // Validasi stok global jika tidak ada ukuran
            if ($request->quantity > $item->stock) {
                return back()
                    ->withInput()
                    ->with('error', 'Requested quantity cannot exceed available stock (Current stock: ' . $item->stock . ')');
            }
        }

        ItemRequest::create([
            'item_id' => $request->item_id,
            'user_id' => auth()->id(),
            'size' => $request->size,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('item-requests.index')->with('success', 'Request submitted successfully.');
    }

    public function approve(ItemRequest $itemRequest)
    {
        if ($itemRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $item = $itemRequest->item;

        // Cek stok sebelum approve
        if ($itemRequest->size) {
            $itemSize = $item->sizes()->where('size', $itemRequest->size)->first();
            
            // Validasi keberadaan varian size
            if (!$itemSize) {
                return back()->with('error', "Varian size '{$itemRequest->size}' tidak ditemukan pada item ini.");
            }

            // Validasi jumlah stok per varian
            if ($itemSize->stock < $itemRequest->quantity) {
                return back()->with('error', "Stok tidak cukup untuk ukuran {$itemRequest->size}. Tersedia: {$itemSize->stock}");
            }
        } else {
            // Validasi stok global jika request tidak memiliki size
            if ($item->stock < $itemRequest->quantity) {
                return back()->with('error', 'Insufficient total stock to approve this request.');
            }
        }

        DB::transaction(function () use ($itemRequest, $item) {
            // 1. Update status request
            $itemRequest->update([
                'status' => 'approved',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // 2. Buat record transaksi
            $item->transactions()->create([
                'user_id' => $itemRequest->user_id,
                'type' => 'out',
                'quantity' => $itemRequest->quantity,
                'date' => now(),
                'note' => 'Approved request #' . $itemRequest->id . ($itemRequest->size ? " (Size: {$itemRequest->size})" : ""),
            ]);

            // 3. Kurangi stok varian (ItemSize) jika ada size
            if ($itemRequest->size) {
                $itemSize = $item->sizes()->where('size', $itemRequest->size)->first();
                if ($itemSize) {
                    $itemSize->decrement('stock', $itemRequest->quantity);
                }
            }

            // 4. Kurangi total stok utama (Item)
            $item->decrement('stock', $itemRequest->quantity);
        });

        return redirect()->route('item-requests.index')->with('success', 'Request approved successfully.');
    }
    
    public function show(ItemRequest $itemRequest)
    {
        if (auth()->user()->isStaff() && $itemRequest->user_id != auth()->id()) {
            abort(403);
        }

        return view('item-requests.show', compact('itemRequest'));
    }

    public function reject(Request $request, ItemRequest $itemRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        if ($itemRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $itemRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('item-requests.index')->with('success', 'Request rejected successfully.');
    }
}