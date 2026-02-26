<?php

namespace App\Http\Controllers;

class PeticionesMisionesClient extends Controller
{
    public function index()
    {
        return view('misiones-flytbase.peticiones-misiones-client');
    }
}
