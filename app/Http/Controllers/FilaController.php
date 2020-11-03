<?php

namespace App\Http\Controllers;

use App\Models\Fila;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\FilaRequest;

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

    public function store(FilaRequest $request)
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
        $user = User::obterOuCriarPorCodpes($request->codpes);
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

    public function updateStatus(Request $request, Fila $fila)
    {
        $fila->estado = $request->novo_estado;
        $fila->save();
        $request->session()->flash('alert-info', 'Fila atualizada com sucesso');
        return back();
    }

    public function createTemplate(Fila $fila)
    {
        $template = json_decode($fila->template, true);
        return view('filas.template', compact('fila', 'template'));
    }

    public function storeTemplate(Request $request, Fila $fila)
    {
        $request->validate([
            'template.*.label' => 'required',
            'template.*.type' => 'required'
        ]);
        if (isset($request->campo)) {
            $request->validate([
                'new.label' => 'required',
                'new.type' => 'required'
            ]);
        }
        $template = [];
        # remove null
        if (isset($request->template)) {
            foreach ($request->template as $campo => $atributos) {
                $template[$campo] = array_filter($atributos, 'strlen');
            }
        }
        # processa select
        foreach ($template as $campo => $atributo) {
            if ($atributo['type'] == 'select') {
                $template[$campo]['value'] = json_decode($atributo['value'], true);
            }
        }
        # adiciona o campo novo
        $new = array_filter($request->new, 'strlen');
        if (isset($request->campo)) {
            $template[$request->campo] = $new;
        }
        $fila->template = json_encode($template);
        $fila->save();

        return back();
    }
}
