<?php

namespace App\Http\Controllers;

use App\Models\Fila;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\FileRequest;

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

    public function index()
    {
        $this->authorize('admin');

        $this->data['fields'] = $this->model::getFields();
        $this->data['rows'] = $this->model::get();
        #return view($this->data['url'] . '.index')->with('data', (object) $this->data);
        return view('filas.index')->with('data', (object) $this->data);
    }

    public function store(FileRequest $request)
    {
        $this->authorize('admin');

        $row = $this->model::create($request->all());
        $user = \Auth::user();
        $row->users()->attach($user->id, ['funcao' => 'Gerente']);

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url'] . '/' . $row->id);
    }

    public function storePessoa(Request $request, Fila $fila)
    {
        $user = User::where('codpes', $request->codpes)->first();
        if (empty($user)) {
            $user = User::storeByCodpes($request->codpes);
        }
        $fila->users()->detach($user->id);
        $fila->users()->attach($user->id, ['funcao' => $request->funcao]);

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
        $fila->users()->detach($id);
        $request->session()->flash('alert-info', 'Pessoa removida com sucesso');
        return back();
    }

}
