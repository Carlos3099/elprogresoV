<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index()
    {
        return view('layouts.panel');
    }
    
    public function ventas()
    {
        return view('layouts.panel')->with('section', 'ventas');
    }
    

    // Agrega métodos para cada sección...
}