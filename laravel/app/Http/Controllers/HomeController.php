<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controlador de la pàgina d'inici.
 * 
 * Gestiona la visualització de la pàgina principal pública de l'aplicació.
 * 
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Mostra la pàgina d'inici.
     * 
     * @return \Illuminate\View\View Vista principal de l'aplicació.
     */
    public function index()
    {
        return view('home');
    }
}
