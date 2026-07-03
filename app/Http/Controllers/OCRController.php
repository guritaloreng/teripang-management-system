<?php

namespace App\Http\Controllers;

use App\Services\OCRService;
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

        return view('ocr.preview',[

            'photo'=>$path,

            'result'=>$result

        ]);
    }
}