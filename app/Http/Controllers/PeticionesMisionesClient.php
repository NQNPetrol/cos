<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeticionesMisionesClient extends Controller
{
    public function index()
    {
        return view('misiones-flytbase.peticiones-misiones-client');
    }
}
