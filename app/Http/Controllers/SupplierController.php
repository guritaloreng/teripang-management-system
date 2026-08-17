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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name'],
            'region' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
        ]);

        Supplier::create([
        'name' => $validated['name'],
        'region' => $validated['region'] ?? null,
        'phone' => $validated['phone'] ?? null,
        'note' => $validated['note'] ?? null,
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
        return redirect()
            ->route('suppliers.index');
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

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255', 'unique:suppliers,name,' . $supplier->id],
        'region' => ['nullable', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:50'],
        'note' => ['nullable', 'string'],
    ]);

    $supplier->update([
        'name' => $validated['name'],
        'region' => $validated['region'] ?? null,
        'phone' => $validated['phone'] ?? null,
        'note' => $validated['note'] ?? null,
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
