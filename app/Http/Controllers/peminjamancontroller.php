<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Items;
use App\Models\Item;
use App\Models\waktu_pembelajaran;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::where("user_id", Auth::id())
            ->with(["barang", "jam"])
            ->latest()
            ->paginate(10);
        return view('user.pinjam', compact("peminjaman"));
    }

    public function show($id)
    {
        //
    }

    public function create()
    {
        $items = Item::all();
        $waktu = waktu_pembelajaran::all();

        return view('user.pinjam', compact('items', 'waktu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keperluan' => 'required|string|max:255',
            'item_id' => 'required|exists:item,id',
            'waktu_id' => 'required|exists:waktu_pembelajaran,id',
            'bukti' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti_peminjaman', 'public');
        }

        Peminjaman::create([
            'keperluan' => $validated['keperluan'],
            'user_id' => Auth::id(),
            'items_id' => $validated['item_id'],
            'waktu_id' => $validated['waktu_id'],
            'gambar_bukti' => $path,
            'status_pinjaman' => 'dipinjam',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
