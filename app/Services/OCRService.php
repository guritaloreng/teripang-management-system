<?php

namespace App\Services;

use App\Services\AI\GeminiService;

class OCRService
{
    protected GeminiService $gemini;

    public function __construct()
    {
        $this->gemini = new GeminiService();
    }

    public function scan(
        string $imagePath,
        string $documentType = 'purchase'
    ): array
    {
        return $this->gemini->scan(
            $imagePath,
            $documentType
        );
    }
}
