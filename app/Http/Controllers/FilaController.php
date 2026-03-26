<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilaRequest;
use App\Models\Chamado;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;

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

        if ($request->card == 'visibilidade') {

            $visibilidade = $request->settings['visibilidade'];
            if (isset($visibilidade['customCodpes']) && !is_null($visibilidade['customCodpes'])) {
                $customCodpes = trim($visibilidade['customCodpes']);

                // remove caracteres não numéricos, mantém linhas, remove linhas vazias
                $customCodpes = preg_replace(['/[^0-9\n]/', '/\n\n+/'], ['', PHP_EOL], $customCodpes);
                //remove repetidos
                $customCodpes = implode(PHP_EOL, array_unique(explode(PHP_EOL, $customCodpes)));

                $visibilidade['customCodpes'] = $customCodpes;
            }
            $fila->settings()->set('visibilidade', $visibilidade);

            // está no config mas temos de migrar para settings
            $config = $request->config ?? [];

            // vamos tratar setores pois mudou para checkbox e não usa valor 0/1 e sim local/todos
            // porém "todos" não é utilizado no sistema
            if (!isset($config['visibilidade']['setores'])) {
                $config['visibilidade']['setores'] = 'todos';
            }

            // temos de mergear com o config antigo para preservar os dados
            $fila->config = array_merge(json_decode(json_encode($fila->config), true), $config);
        }

        if ($request->card == 'config') {
            $settings = $request->settings;
            $fila->settings()->set('instrucoes', $settings['instrucoes']);

            $config = $request->config;
            $fila->config = array_merge(json_decode(json_encode($fila->config), true), $config);
        }

        if ($request->card == 'estados') {

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
        \UspTheme::activeUrl('filas');

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

    /**
     * Baixa os chamados especificados
     *
     * @param $request->ano
     * @param $fila
     * @return Stream
     */
    public function download(Request $request, Fila $fila)
    {
        $this->authorize('filas.view', $fila);
        $request->validate([
            'ano' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);
        $ano = $request->ano;

        $chamados = Chamado::listarChamadosPorFila($fila, $ano);

        // vamos pegar o template da fila para saber quais são os campos extras
        $template = array_keys(json_decode($fila->template, true));

        $arr = [];
        foreach ($chamados as $chamado) {
            $i = [];
            $i['nro'] = $chamado->nro . '/' . $ano;
            $i['status'] = $chamado->status;
            $i['assunto'] = $chamado->assunto;
            $i['descricao'] = str_replace('<br />', '', $chamado->descricao);

            $autor = $chamado->users()->wherePivot('papel', 'Autor')->first();
            $i['autor'] = $autor ? $autor->name : '';

            $i['extras'] = $chamado->extras;
            $extras = json_decode($chamado->extras, true) ?? [];
            foreach ($template as $field) {
                $i['extra_' . $field] = isset($extras[$field]) ? $extras[$field] : '';
            }

            $i['criado_em'] = $chamado->created_at->format('d/m/Y');
            $i['fechado_em'] = $chamado->fechado_em ? $chamado->fechado_em->format('d/m/Y') : '';
            $i['atualizado_em'] = $chamado->atualizadoEm ? $chamado->atualizadoEm->format('d/m/Y') : '';

            $arr[] = $i;
        }

        $writer = SimpleExcelWriter::streamDownload('chamados_' . $ano . '_fila' . $fila->id . '.xlsx')
            ->addRows($arr);
    }
}
