<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetorController extends Controller
{
    use ResourceTrait;

    protected $model = 'App\Models\Setor';

    protected $data = [
        'title' => 'Setores',
        'url' => 'setores', // caminho da rota do resource
        'modal' =>true,
        'showId' => true,
        'model' => 'App\Models\Setor'
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

}
