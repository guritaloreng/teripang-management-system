<?php

namespace App\Services\OCR;

class Parser
{
    public function parse(string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);

        $items = [];

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line == '') {
                continue;
            }

            preg_match_all('/[\d\.]+|[A-Za-z]+/', $line, $match);

            if (count($match[0]) < 4) {
                continue;
            }

            $items[] = [

                'weight' => $match[0][0],

                'alias' => strtoupper($match[0][1]),

                'price' => str_replace('.', '', $match[0][2]),

                'subtotal' => str_replace('.', '', $match[0][3])

            ];

        }

        return $items;
    }
}