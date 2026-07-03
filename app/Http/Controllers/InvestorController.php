<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::orderBy('name')->get();

        return view('investors.index', compact('investors'));
    }

    public function create()
    {
        return view('investors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:investors,name',
            'phone'=>'nullable',
            'note'=>'nullable'
        ]);

        Investor::create($request->all());

        return redirect()
            ->route('investors.index')
            ->with('success','Investor berhasil ditambahkan.');
    }

    public function show(Investor $investor)
    {
        return redirect()->route('investors.index');
    }

    public function edit(Investor $investor)
    {
        return view('investors.edit', compact('investor'));
    }

    public function update(Request $request, Investor $investor)
    {
        $request->validate([
            'name'=>'required|unique:investors,name,'.$investor->id,
            'phone'=>'nullable',
            'note'=>'nullable'
        ]);

        $investor->update($request->all());

        return redirect()
            ->route('investors.index')
            ->with('success','Investor berhasil diupdate.');
    }

    public function destroy(Investor $investor)
    {
        $investor->delete();

        return redirect()
            ->route('investors.index')
            ->with('success','Investor berhasil dihapus.');
    }
}