<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilaRequest;
use App\Models\Fila;
use App\Models\User;
use App\Models\Setor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FilaController extends Controller
{
    protected $model = 'App\Models\Fila';

    protected $data = [
        'title' => 'Filas',
        'url' => 'filas', // caminho da rota do resource
        'modal' => true,
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
        $this->authorize('filas.viewAny');

        $filas = Fila::listarFilas();
        return view('filas.index')->with(['data' => (object) $this->data, 'filas' => $filas]);
    }

    public function store(FilaRequest $request)
    {
        $setor_id = $request->setor_id;
        if (!Gate::allows('filas.create', Setor::find($setor_id))) {
            return view('sem-acesso');
        }

        $fila = Fila::create($request->all());
        
        # não vamos adicionar gerente automaticamente
        #$fila->users()->attach(\Auth::user(), ['funcao' => 'Gerente']);

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url'] . '/' . $fila->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fila $fila)
    {
        $this->authorize('filas.view', $fila);

        # aqui tem de validar dados do post
        ####################

        $fila->fill($request->all());

        if ($request->config) {
            $config = $request->config;
            $fila->config = json_encode($config);
        }

        $fila->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Fila $fila)
    {
        # Vamos negar acesso com mensagem apropriada
        if (!Gate::allows('filas.view', $fila)) {
            return view('sem-acesso');
        }

        if ($request->ajax()) {
            return $fila;
        } else {
            $this->data['row'] = $fila;

            #$config = $fila->config ? json_decode($fila->config) : new \StdClass;
            #$config->triagem = isset($config->triagem) ? $config->triagem : false;

            return view('filas.show')->with(['data' => (object) $this->data, 'fila' => $fila]);
        }
    }

    public function storePessoa(Request $request, Fila $fila)
    {
        $this->authorize('filas.update', $fila);

        $user = User::obterOuCriarPorCodpes($request->codpes);
        $fila->users()->detach($user->id);
        $fila->users()->attach($user->id, ['funcao' => $request->funcao]);

        $request->session()->flash('alert-info', 'Pessoa adicionada com sucesso');
        return back();
    }

    public function destroyPessoa(Request $request, Fila $fila, $id)
    {
        $this->authorize('filas.update', $fila);
        
        $currentUser = \Auth::user();
        if ($currentUser->id == $id and !$currentUser->is_admin) {
            $request->session()->flash('alert-warning', 'Não é possível remover a si mesmo.');
            return back();
        }
        $fila->users()->detach($id);
        $request->session()->flash('alert-info', 'Pessoa removida com sucesso');
        return back();
    }

    public function storeTemplateJson(Request $request, Fila $fila)
    {
        $this->authorize('filas.update', $fila);

        $newjson = $request->template;
        $fila->template = $newjson;
        $fila->save();
        $request->session()->flash('alert-info', 'Template salvo com sucesso');
        return back();
    }

    public function createTemplate(Fila $fila)
    {
        $this->authorize('filas.update', $fila);
        
        $template = json_decode($fila->template, true);
        return view('filas.template', compact('fila', 'template'));
    }

    public function storeTemplate(Request $request, Fila $fila)
    {
        $this->authorize('filas.update', $fila);
        
        $request->validate([
            'template.*.label' => 'required',
            'template.*.type' => 'required',
        ]);
        if (isset($request->campo)) {
            $request->validate([
                'new.label' => 'required',
                'new.type' => 'required',
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
