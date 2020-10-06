<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilaController extends Controller
{
    protected $model = 'App\Models\Fila';

    protected $data = [
        'title' => 'Filas',
        'url' => 'filas', // caminho da rota do resource
        'modal' => false,
        'showId' => false,
        'viewBtn' => true,
        'editBtn' => false,
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    use ResourceTrait {
        store as protected traitStore;
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $request->validate($this->model::rules);

        $row = $this->model::create($request->all());
        $user = \Auth::user();
        $row->user()->attach($user->id, ['funcao'=>'Gerente']);
        //return $row;

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url'] .'/'. $row->id);
    }

}
