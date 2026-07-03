<?php

namespace App\Http\Controllers;

use App\Models\Receiving;
use App\Models\ReceivingItem;
use App\Models\SeaCucumberType;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceivingController extends Controller
{
    public function index()
    {
        $receivings = Receiving::with('shipment')
            ->latest()
            ->paginate(20);

        return view('receivings.index', compact('receivings'));
    }

    public function create()
    {
        $shipments = Shipment::whereNotIn('id', function ($q) {
            $q->select('shipment_id')->from('receivings');
        })->get();

        $types = SeaCucumberType::orderBy('name')->get();

        return view('receivings.create', compact('shipments', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'shipment_id' => 'required|exists:shipments,id',

            'received_date' => 'required|date',

            'type_id' => 'required|array',

            'weight' => 'required|array'

        ]);

        DB::transaction(function () use ($request) {

            $receiving = Receiving::create([

                'shipment_id' => $request->shipment_id,

                'received_date' => $request->received_date,

                'status' => 'Open',

                'note' => $request->note

            ]);

            foreach ($request->type_id as $i => $type) {

                if (($request->weight[$i] ?? 0) <= 0) {
                    continue;
                }

                ReceivingItem::create([

                    'receiving_id' => $receiving->id,

                    'sea_cucumber_type_id' => $type,

                    'received_weight' => $request->weight[$i],

                    'remaining_weight' => $request->weight[$i]

                ]);

            }

        });

        return redirect()
            ->route('receivings.index')
            ->with('success', 'Receiving berhasil dibuat.');
    }

    public function show(Receiving $receiving)
    {
        $receiving->load('shipment', 'items.type');

        return view('receivings.show', compact('receiving'));
    }
}