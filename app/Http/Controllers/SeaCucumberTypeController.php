<?php

namespace App\Http\Controllers;

use App\Models\SeaCucumberType;
use Illuminate\Http\Request;

class SeaCucumberTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = SeaCucumberType::orderBy('name')->get();

        return view('sea-cucumber-types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sea-cucumber-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sea_cucumber_types,name',
        ]);

        SeaCucumberType::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('sea-cucumber-types.index')
            ->with('success', 'Jenis teripang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SeaCucumberType $seaCucumberType)
    {
        return redirect()->route('sea-cucumber-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeaCucumberType $seaCucumberType)
    {
        return view('sea-cucumber-types.edit', compact('seaCucumberType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeaCucumberType $seaCucumberType)
    {
        $request->validate([
            'name' => 'required|unique:sea_cucumber_types,name,' . $seaCucumberType->id,
        ]);

        $seaCucumberType->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('sea-cucumber-types.index')
            ->with('success', 'Jenis teripang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeaCucumberType $seaCucumberType)
    {
        $seaCucumberType->delete();

        return redirect()
            ->route('sea-cucumber-types.index')
            ->with('success', 'Jenis teripang berhasil dihapus.');
    }
}