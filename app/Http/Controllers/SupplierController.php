<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $suppliers = Supplier::orderBy('name')->get();
 return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Supplier::create([
        'name' => $request->name,
        'region' => $request->region,
        'phone' => $request->phone,
        'note' => $request->note,
    ]);

    return redirect()
    ->route('suppliers.index')
    ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $supplier = Supplier::findOrFail($id);

    return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

    $supplier->update([
        'name' => $request->name,
        'region' => $request->region,
        'phone' => $request->phone,
        'note' => $request->note,
    ]);

    return redirect()
        ->route('suppliers.index')
        ->with('success', 'Supplier berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);

    $supplier->delete();

    return redirect()
        ->route('suppliers.index')
        ->with('success', 'Supplier berhasil dihapus.');
    }
}
