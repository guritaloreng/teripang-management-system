<?php

namespace App\Services\OCR;

use App\Models\TypeAlias;

class AliasMatcher
{
    public function match(string $alias)
    {
        return TypeAlias::where('alias', strtoupper(trim($alias)))->first();
    }
}