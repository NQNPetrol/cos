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
        return view('patrullas.client.index-client');
    }

    public function locationClient() 
    {
        return view('client.patrullas.location');
    }

}
