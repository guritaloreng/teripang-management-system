<?php

namespace App\Http\Controllers;

use App\Models\SeaCucumberType;
use App\Models\Supplier;
use App\Services\OCRService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class OCRController extends Controller
{
    protected OCRService $ocr;

    public function __construct(OCRService $ocr)
    {
        $this->ocr = $ocr;
    }

    public function index()
    {
        return view('ocr.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photo'=>'required|image|max:10240'
        ]);

        $path = $request
            ->file('photo')
            ->store('purchase-notes','public');

        $result = $this->ocr->scan(
            storage_path('app/public/'.$path)
        );

        $suppliers = Supplier::orderBy('name')
            ->get();

        $types = SeaCucumberType::orderBy('name')
            ->get();

        return view(
            'purchases.create',
            [
                'suppliers' => $suppliers,
                'types' => $types,
                'ocrData' => $this->preparePurchaseData(
                    $result,
                    $suppliers,
                    $types
                ),
            ]
        );
    }

    public function scanSaleNote(Request $request)
    {
        $request->validate([
            'photo'=>'required|image|max:10240'
        ]);

        $path = $request
            ->file('photo')
            ->store('sale-notes','public');

        $result = $this->ocr->scan(
            storage_path('app/public/'.$path),
            'sale'
        );

        $types = SeaCucumberType::orderBy('name')
            ->get();

        $preparedData = $this->prepareSaleData(
            $result,
            $types
        );

        return view(
            'sales.create',
            [
                'types' => $types,
                'ocrData' => $preparedData,
            ]
        );
    }

    protected function preparePurchaseData(
        array $result,
        $suppliers,
        $types
    ): array
    {
        $supplierName = trim($result['supplier'] ?? '');

        $supplier = $suppliers->first(function ($supplier) use ($supplierName) {
            return $supplierName !== ''
                && strtolower($supplier->name) === strtolower($supplierName);
        });

        return [
            'supplier_id' => $supplier?->id,
            'supplier_invoice' => $result['supplier_invoice'] ?? '',
            'purchase_date' => $this->normalizeDate($result['purchase_date'] ?? ''),
            'items' => collect($result['items'] ?? [])
                ->map(function ($item) use ($types) {
                    $typeName = trim($item['sea_cucumber_type'] ?? '');

                    $type = $this->matchMasterByName(
                        $types,
                        $typeName
                    );

                    return [
                        'type_id' => $type?->id,
                        'sea_cucumber_type' => $typeName,
                        'purchase_weight' => $item['purchase_weight'] ?? '',
                        'price_per_kg' => $item['price_per_kg'] ?? '',
                    ];
                })
                ->values()
                ->toArray(),
        ];
    }

    protected function prepareSaleData(
        array $result,
        $types
    ): array
    {
        return [
            'buyer' => $result['buyer'] ?? '',
            'invoice_number' => $result['invoice_number'] ?? '',
            'sale_date' => $this->normalizeDate($result['sale_date'] ?? ''),
            'items' => collect($result['items'] ?? [])
                ->map(function ($item) use ($types) {
                    $typeName = trim($item['sea_cucumber_type'] ?? '');

                    $type = $this->matchMasterByName(
                        $types,
                        $typeName
                    );

                    return [
                        'type_id' => $type?->id,
                        'sea_cucumber_type' => $typeName,
                        'weight' => $item['weight'] ?? '',
                        'price' => $item['price_per_kg'] ?? '',
                        'status' => 'Terjual Sebagian',
                    ];
                })
                ->values()
                ->toArray(),
        ];
    }

    protected function matchMasterByName(
        $records,
        string $name
    ) {
        $normalizedName = $this->normalizeMasterName($name);

        if ($normalizedName === '') {

            return null;

        }

        $bestRecord = null;
        $bestScore = 0;

        foreach ($records as $record) {

            $normalizedRecord = $this->normalizeMasterName($record->name);

            if ($normalizedRecord === $normalizedName) {

                return $record;

            }

            similar_text(
                $normalizedName,
                $normalizedRecord,
                $score
            );

            if ($score > $bestScore) {

                $bestScore = $score;
                $bestRecord = $record;

            }

        }

        return $bestScore >= 85
            ? $bestRecord
            : null;
    }

    protected function normalizeMasterName(string $value): string
    {
        $value = strtolower(trim($value));

        return preg_replace('/[\s\-_\.]+/', '', $value) ?? '';
    }

    protected function normalizeDate(?string $date): string
    {
        if (! $date) {

            return '';

        }

        try {

            return Carbon::parse($date)->format('Y-m-d');

        } catch (\Throwable $e) {

            return '';

        }
    }
}
