<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilaRequest;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;

class FilaController extends Controller
{

    // crud generico
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

    /**
     * Lista as filas
     */
    public function index()
    {
        $this->authorize('filas.viewAny');
        \UspTheme::activeUrl('filas');

        $filas = Fila::listarFilas();
        return view('filas.index')->with(['data' => (object) $this->data, 'filas' => $filas]);
    }

    /**
     * Criar nova fila
     */
    public function store(FilaRequest $request)
    {
        # Para criar uma nova fila precisamos do setor para autorizar
        $setor = Setor::find($request->setor_id);
        $this->authorize('filas.create', $setor);

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

        if ($request->settings) {
            $settings = $request->settings;

            $fila->settings()->setMultiple([
                'instrucoes' => $settings['instrucoes'],
            ]);
        }

        // migrar para settings ??????????????????
        if (!isset($request->config['status'])) {
            // se nao tiver status então é o form com outros dados
            // temos de mergear com o config antigo para preservar os dados
            $fila->config = array_merge(json_decode(json_encode($fila->config), true),$request->config);

        } else {

            $qtd_select = count(array_filter($request->config['status']['select'], function ($x) {
                return !empty($x);
            }));
            $qtd_select_cor = count(array_filter($request->config['status']['select_cor'], function ($x) {
                return !empty($x);
            }));
            # verifica se colocou uma cor para cada status
            if ($qtd_select == $qtd_select_cor) {
                # se não tiver entradas duplicadas
                if (count(array_unique($request->config['status']['select'])) == count($request->config['status']['select'])) {
                    # se não for usado status reservados pelo sistema
                    if (!array_intersect(array_map('strtolower', $request->config['status']['select']), array_map('strtolower', ["Fechado", "Em andamento", "Triagem", "Novo"]))) {

                        // vamos atribuir aqui o status
                        $value = $request->config['status'];
                        $status = [];
                        for ($i = 0; $i < count($value['select']); $i++) {
                            $s = new \StdClass;
                            $s->label = $value['select'][$i];
                            $s->color = $value['select_cor'][$i];
                            array_push($status, $s);
                        }
                        // temos de mergear com o config antigo para preservar os dados
                        $fila->config = array_merge(json_decode(json_encode($fila->config), true), ['status' => $status]);

                    } else {
                        $request->session()->flash('alert-danger', 'Não é possível utilizar status iguais aos do sistema ("Fechado", "Em andamento", "Novo", "Triagem")!');
                        return back()->withInput();
                    }
                } else {
                    $request->session()->flash('alert-danger', 'Não é possível utilizar status iguais!');
                    return back()->withInput();
                }
            } else {
                $request->session()->flash('alert-danger', 'É obrigatório cadastrar uma cor para cada status!');
                return back()->withInput();
            }
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
        $this->authorize('filas.view', $fila);
        \UspTheme::activeUrl('filas');

        if ($request->ajax()) {
            return $fila;
        } else {
            $data = (object) $this->data;
            $config = $fila->config;

            return view('filas.show', compact(['fila', 'data', 'config']));
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
        $request->session()->flash('alert-info', 'Template salvo com sucesso');
        return back();
    }
}
