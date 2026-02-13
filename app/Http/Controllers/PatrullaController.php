<?php

namespace App\Http\Controllers;

class PatrullaController extends Controller
{
    public function index()
    {
        return view('patrullas.index');
    }

    public function create()
    {
        return view('patrullas.create');
    }

    public function indexClient()
    {
        return view('patrullas.client.index-client');
    }
}
