<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class GeminiService
{
    protected string $apiKey;

    protected string $endpoint;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');

        $this->endpoint =
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='
            . $this->apiKey;
    }

    public function scan(
        string $imagePath,
        string $documentType = 'purchase'
    ): array
    {
        if (! $this->apiKey || ! File::exists($imagePath)) {

            return $this->emptyResult($documentType);

        }

        $mimeType = File::mimeType($imagePath) ?: 'image/jpeg';

        $imageData = base64_encode(
            File::get($imagePath)
        );

        $response = Http::timeout(60)
            ->post($this->endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $this->prompt($documentType),
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $imageData,
                                ],
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ],
            ]);

        if (! $response->successful()) {

            return $this->emptyResult($documentType);

        }

        $text = data_get(
            $response->json(),
            'candidates.0.content.parts.0.text',
            ''
        );

        return $this->decodeJson(
            $text,
            $documentType
        );
    }

    protected function prompt(string $documentType): string
    {
        if ($documentType === 'sale') {

            return $this->salePrompt();

        }

        return $this->purchasePrompt();
    }

    protected function purchasePrompt(): string
    {
        return implode("\n", [
            'Extract purchase invoice data for a sea cucumber ERP.',
            'Return JSON only.',
            'If a field cannot be read, use an empty string.',
            'Do not calculate grand total.',
            'Return numeric values without thousand separators.',
            'Use this exact structure:',
            '{',
            '  "supplier": "",',
            '  "supplier_invoice": "",',
            '  "purchase_date": "YYYY-MM-DD",',
            '  "items": [',
            '    {',
            '      "sea_cucumber_type": "",',
            '      "purchase_weight": "",',
            '      "price_per_kg": ""',
            '    }',
            '  ]',
            '}',
        ]);
    }

    protected function salePrompt(): string
    {
        return implode("\n", [
            'Extract sale invoice data for a sea cucumber ERP.',
            'Return JSON only.',
            'If a field cannot be read, use an empty string.',
            'Do not calculate grand total.',
            'Return numeric values without thousand separators.',
            'Use this exact structure:',
            '{',
            '  "buyer": "",',
            '  "invoice_number": "",',
            '  "sale_date": "YYYY-MM-DD",',
            '  "items": [',
            '    {',
            '      "sea_cucumber_type": "",',
            '      "weight": "",',
            '      "price_per_kg": ""',
            '    }',
            '  ]',
            '}',
        ]);
    }

    protected function decodeJson(
        string $text,
        string $documentType
    ): array
    {
        $text = trim($text);

        $text = preg_replace('/^```json\s*/i', '', $text);

        $text = preg_replace('/^```\s*/', '', $text);

        $text = preg_replace('/\s*```$/', '', $text);

        $data = json_decode($text, true);

        if (! is_array($data)) {

            return $this->emptyResult($documentType);

        }

        if ($documentType === 'sale') {

            return [
                'buyer' => $data['buyer'] ?? '',
                'invoice_number' => $data['invoice_number'] ?? '',
                'sale_date' => $data['sale_date'] ?? '',
                'items' => is_array($data['items'] ?? null)
                    ? $data['items']
                    : [],
            ];

        }

        return [
            'supplier' => $data['supplier'] ?? '',
            'supplier_invoice' => $data['supplier_invoice'] ?? '',
            'purchase_date' => $data['purchase_date'] ?? '',
            'items' => is_array($data['items'] ?? null)
                ? $data['items']
                : [],
        ];
    }

    protected function emptyResult(string $documentType = 'purchase'): array
    {
        if ($documentType === 'sale') {

            return [
                'buyer' => '',
                'invoice_number' => '',
                'sale_date' => '',
                'items' => [],
            ];

        }

        return [
            'supplier' => '',
            'supplier_invoice' => '',
            'purchase_date' => '',
            'items' => [],
        ];
    }
}
