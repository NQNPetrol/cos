<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatrullaController extends Controller
{
    public function index()
    {
        return view('patrullas.index');
    }
}
