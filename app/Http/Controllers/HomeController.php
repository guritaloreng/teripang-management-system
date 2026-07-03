<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
{
    $boss = "Mr. Lei";
    $company = "Teripang Management System";

    return view('home', compact('boss', 'company'));
}
}
