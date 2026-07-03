<?php

namespace App\Services;

use Exception;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRService
{
    public function scan(string $imagePath): array
    {
        try {

            $text = (new TesseractOCR($imagePath))
                ->executable('C:\\Program Files\\Tesseract-OCR\\tesseract.exe')
                ->lang('eng')
                ->run();

            return [
                'supplier' => null,
                'date' => null,
                'invoice' => null,
                'raw_text' => $text,
                'items' => [],
            ];

        } catch (Exception $e) {

            return [
                'supplier' => null,
                'date' => null,
                'invoice' => null,
                'raw_text' => 'ERROR: '.$e->getMessage(),
                'items' => [],
            ];

        }
    }
}