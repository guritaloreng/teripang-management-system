    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Purchase $purchase)
    {
        $purchase->load(
            'items'
        );

        $suppliers = Supplier::orderBy(
            'name'
        )->get();

        $types = SeaCucumberType::orderBy(
            'name'
        )->get();

        return view(

            'purchases.edit',

            compact(

                'purchase',

                'suppliers',

                'types'

            )

        );
    }
        /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Purchase $purchase
    )
    {
        $request->validate([

            'purchase_date' => 'required|date',

            'supplier_id' => 'required|exists:suppliers,id',

            'type_id' => 'required|array|min:1',

            'purchase_weight' => 'required|array|min:1',

            'price_per_kg' => 'required|array|min:1',

        ]);

        DB::transaction(function () use ($request, $purchase) {

            $purchase->update([

                'supplier_invoice' => $request->supplier_invoice,

                'purchase_date' => $request->purchase_date,

                'supplier_id' => $request->supplier_id,

                'note' => $request->note,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Delete Old Items
            |--------------------------------------------------------------------------
            */

            $purchase->items()->delete();

            $grandTotal = 0;

            foreach ($request->type_id as $i => $typeId) {

                $subtotal =
                    $request->purchase_weight[$i]
                    *
                    $request->price_per_kg[$i];

                PurchaseItem::create([

                    'purchase_id' => $purchase->id,

                    'sea_cucumber_type_id' => $typeId,

                    'purchase_weight' => $request->purchase_weight[$i],

                    'price_per_kg' => $request->price_per_kg[$i],

                    'subtotal' => $subtotal,

                ]);

                $grandTotal += $subtotal;

            }

            $purchase->update([

                'grand_total' => $grandTotal,

            ]);

        });

        return redirect()

            ->route('purchases.show', $purchase)

            ->with(

                'success',

                'Nota pembelian berhasil diperbarui.'

            );
    }
        /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {

            /*
            |--------------------------------------------------------------------------
            | Delete Purchase Items
            |--------------------------------------------------------------------------
            */

            $purchase->items()->delete();

            /*
            |--------------------------------------------------------------------------
            | Delete Purchase
            |--------------------------------------------------------------------------
            */

            $purchase->delete();

        });

        return redirect()

            ->route('purchases.index')

            ->with(

                'success',

                'Nota pembelian berhasil dihapus.'

            );
    }
}