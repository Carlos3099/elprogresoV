<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Aquí después puedes mandar datos de la BD
        return view('dashboard');
    }
}
