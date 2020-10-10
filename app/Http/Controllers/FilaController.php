<?php

namespace App\Http\Controllers;

use App\Models\Fila;
use App\Models\User;
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
        'model' => 'App\Models\Fila',
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
        $row->user()->attach($user->id, ['funcao' => 'Gerente']);

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url'] . '/' . $row->id);
    }

    public function storePessoa(Request $request, Fila $fila)
    {
        $user = User::where('codpes', $request->codpes)->first();
        if (empty($user)) {
            $user = User::storeByCodpes($request->codpes);
        }
        $fila->user()->detach($user->id);
        $fila->user()->attach($user->id, ['funcao' => $request->funcao]);

        $request->session()->flash('alert-info', 'Pessoa adicionada com sucesso');
        return back();
    }

    public function destroyPessoa(Request $request, Fila $fila, $id)
    {
        $currentUser = \Auth::user();
        if ($currentUser->id == $id) {
        $request->session()->flash('alert-warning', 'Não é possível remover a si mesmo.');
            return back();
        }
        $fila->user()->detach($id);
        $request->session()->flash('alert-info', 'Pessoa removida com sucesso');
        return back();
    }

}
