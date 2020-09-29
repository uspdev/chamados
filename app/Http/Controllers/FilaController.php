<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilaController extends Controller
{
    protected $model = 'App\Models\Fila';

    protected $data = [
        'title' => 'Filas',
        'url' => 'filas', // caminho da rota do resource
        'showId' => true,
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    use ResourceTrait;
    
}
