<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('client.patrullas.index');
    }

    public function createClient()
    {
        return view('client.patrullas.create');
    }

    public function locationClient() 
    {
        return view('client.patrullas.location');
    }

}
