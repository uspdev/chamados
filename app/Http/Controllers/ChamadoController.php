<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Comentario;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ChamadoRequest;

class ChamadoController extends Controller
{

    /* Variáveis globais temporárias */
    private $complexidades;

    public function __construct()
    {
        $this->middleware('auth');
        $this->complexidades = Chamado::complexidades();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('chamados.viewAny');
        $chamados = (Gate::allows('admin')) ? Chamado::all() : \Auth::user()->chamados;
        $ano = '2019';
        return view('chamados/index', compact('chamados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Fila $fila)
    {
        $this->authorize('chamados.create');
        $chamado = new Chamado;
        $chamado->fila = $fila;
        $complexidades = $this->complexidades;
        $form = JSONForms::generateForm($fila);
        return view('chamados/create', compact('fila', 'chamado', 'complexidades', 'form'));
    }

    public function listaFilas()
    {
        $setores = Setor::with(['filas' => function($query) {
            $query->where('estado', 'Em produção');
        }])->orderBy('sigla')->get();
        return view('chamados.listafilas', compact('setores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChamadoRequest $request, Fila $fila)
    {
        $this->authorize('chamados.create');
        $request->validate(JSONForms::buildRules($request, $fila));
        $chamado = \DB::transaction(function () use ($request, $fila) {
            $chamado = new Chamado;
            $chamado->nro = Chamado::obterProximoNumero();
            $chamado->fila_id = $fila->id;
            $chamado = $this->grava($chamado, $request);
            return $chamado;
        });

        $request->session()->flash('alert-info', 'Chamado enviado com sucesso');
        return redirect()->route('chamados.show', $chamado->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function show(Chamado $chamado)
    {
        $this->authorize('chamados.view', $chamado);

        $template = json_decode($chamado->fila->template);
        $extras = json_decode($chamado->extras);
        if (empty($template)) {
            $template = [];
        }
        $atendentes = $chamado->users()->wherePivot('funcao', 'Atendente')->get();
        $atribuidor = $chamado->users()->wherePivot('funcao', 'Atribuidor')->first();
        $autor = $chamado->users()->wherePivot('funcao', 'Autor')->first();

        # estamos carregando os vinculados diretos.
        # Seria interessante vincular recursivos? Acho que não mas ...
        $vinculados = $chamado->vinculados;
        $complexidades = $this->complexidades;

        return view('chamados/show', compact('atendentes', 'atribuidor', 'autor', 'chamado', 'extras', 'template', 'vinculados', 'complexidades'));
    }

    /**
     * Retornando os chamados para criar vinculo entre eles
     * @param Request $request - assunto ou número do chamado
     * @return json 
     */
    public function listarChamadosAjax(Request $request)
    {
        if ($request->term) {
            //se buscar pelo numero do chamado (nro) independente do ano
            if (is_numeric($request->term)) {
                $chamados = (Gate::allows('admin')) ? Chamado::where('nro',$request->term)->latest()->get() : \Auth::user()->chamados()->where('nro',$request->term)->latest()->get();
            }else{
                //se buscar por assunto pegamos os ultimos 30 chamados com esse assunto
                $request->term = str_replace(" ", "%", $request->term);
                $chamados = (Gate::allows('admin')) ? Chamado::where('assunto', 'LIKE', '%' . $request->term . '%')->latest()->take(30)->get() : \Auth::user()->chamados()->where('assunto', 'LIKE', '%' . $request->term . '%')->latest()->take(30)->get();
            }            

            $results = [];
            foreach ($chamados as $chamado) {
                $results[] = [
                    'text' => $chamado['nro'] . '/' . $chamado['created_at']->year . ' - ' . $chamado['assunto'],
                    'id' => $chamado['nro'],
                ];
            }
            return response(compact('results'));
        }
    }

    /**
     * Vincula chamados
     * @param $request->slct_chamados - nrp do chamado a ser vinculado
     * @param $request->tipo - tipo de acesso (leitura??)
     */
    public function storeChamadoVinculado(Request $request, Chamado $chamado)
    {
        if ($request->slct_chamados != $chamado->nro) {
            $chamado->vinculadosIda()->detach($request->slct_chamados);
            $chamado->vinculadosIda()->attach($request->slct_chamados, ['acesso' => $request->tipo]);

            $vinculado = Chamado::find($request->slct_chamados);
            //comentário no chamado principal
            Comentario::create([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $chamado->id,
                'comentario' => 'O chamado no. '. $vinculado->nro .'/' .$vinculado->created_at->year. ' foi vinculado à esse chamado',
            ]);
            // comentário no chamado vinculado
            Comentario::create([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $vinculado->id,
                'comentario' => 'Esse chamado foi vinculado ao chamado no. '. $chamado->nro .'/' .$chamado->created_at->year,
            ]);

            $request->session()->flash('alert-info', 'Chamado vinculado com sucesso');

        }else {
            $request->session()->flash('alert-warning', 'Não é possível vincular o chamado à ele mesmo');
        }
        return back();
    }

    /**
     * Desvincula chamados
     */
    public function deleteChamadoVinculado(Request $request, Chamado $chamado, $id)
    {   
        $chamado->vinculadosIda()->detach($id);
        $chamado->vinculadosVolta()->detach($id);

        $vinculado = Chamado::find($id);
        //comentário no chamado principal
        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => 'O chamado no. '. $vinculado->nro .'/' .$vinculado->created_at->year. ' foi desvinculado desse chamado',
        ]);

        // comentário no chamado vinculado
        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $vinculado->id,
            'comentario' => 'Esse chamado foi desvinculado do chamado no. '. $chamado->nro .'/' .$chamado->created_at->year,
        ]);

        $request->session()->flash('alert-info', 'Chamado desvinculado com sucesso');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function edit(Chamado $chamado)
    {
        $this->authorize('chamados.view', $chamado);
        $fila = $chamado->fila;
        $atendentes = [];
        $complexidades = $this->complexidades;
        $autor = $chamado->users()->wherePivot('funcao', 'Autor')->first();
        $form = JSONForms::generateForm($fila, $chamado);
        return view('chamados/edit', compact('autor', 'fila', 'chamado', 'atendentes', 'complexidades', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chamado $chamado)
    {
        if (Gate::allows('admin') and isset($request->atribuido_para)) {
            $request->validate([
                'fila_id' => ['required|numeric'],
            ]);
        }
        $request->validate(JSONForms::buildRules($request, $fila));
        $this->authorize('chamados.view', $chamado);
        $chamado = $this->grava($chamado, $request);

        /*
        if(config('app.env') == 'production')
        Mail::send(new ChamadoMail($chamado,$user));
         */

        $request->session()->flash('alert-info', 'Chamado enviado com sucesso');
        return redirect()->route('chamados.show', $chamado->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chamado $chamado)
    {
        //
    }

    /* Evita duplicarmos código  */
    private function grava(Chamado $chamado, Request $request)
    {
        if ($request->status == 'devolver') {
            $chamado->status = 'Triagem';
            $chamado->complexidade = null;
            $user = \Auth::user();
        } else {

            $chamado->status = 'Triagem';
            $chamado->assunto = $request->assunto;
            $chamado->descricao = $request->descricao;

            $chamado->extras = json_encode($request->extras);

            /* Administradores */
            if (Gate::allows('admin')) {
                /* trocar requisitante */
                if (!is_null($request->codpes)) {
                    $user = User::where('codpes', $request->codpes)->first();
                    if (is_null($user)) {
                        $user = new User;
                        $user->codpes = $request->codpes;
                    }
                } else {
                    $user = \Auth::user();
                }

                /* Atribuir */
                if (!empty($request->atribuido_para)) {
                    /* acho que o user deveria vir direto pelo form */
                    $atendente = User::where('codpes', $request->atribuido_para)->first();
                    $chamado->users()->attach($atendente->id, ['funcao' => 'Atendente']);
                    $chamado->users()->attach(\Auth::user()->id, ['funcao' => 'Atribuidor']);
                    $chamado->status = 'Atribuído';
                }
            } else {
                $user = \Auth::user();
            }

            /* Atualiza telefone da pessoa */
            /* para funcionar, precisa mexer no LoginController */
            $user->telefone = $request->telefone;
            $user->save();
        }
        $chamado->save();
        $chamado->users()->attach($user->id, ['funcao' => 'Autor']);
        return $chamado;
    }

    /**
     * adiciona atendentes. Pode ser mais de um
     */
    public function triagemStore(Request $request, Chamado $chamado)
    {
        $this->authorize('admin');
        $chamado->complexidade = $request->complexidade;
        $atendente = User::where('codpes', $request->codpes)->first();

        # Se atendente já existe não vamos adicionar novamente
        if ($chamado->users()->where(['user_id' => $atendente->id, 'funcao' => 'Atendente'])->exists()) {
            $request->session()->flash('alert-info', 'Atendente já existe');
            return back();
        }

        $chamado->users()->attach($atendente->id, ['funcao' => 'Atendente']);
        # Colocando no comentário a atribuição precisamos do papel de atribuidor??
        #$chamado->users()->attach(\Auth::user()->id, ['funcao' => 'Atribuidor']);
        $chamado->status = 'Atribuído';
        $chamado->save();

        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => 'O chamado foi atribuído para o(a) atendente ' . $atendente->name,
        ]);

        $request->session()->flash('alert-info', 'Atendente adicionado com sucesso');
        return back();
    }

    public function devolver(Chamado $chamado)
    {
        $this->authorize('atendente');
        return view('chamados/devolver', compact('chamado'));
    }

    public function todos(Request $request)
    {
        return "desativado";
        $this->authorize('admin');
        $chamados = Chamado::orderBy('created_at', 'desc');
        // search terms
        if (isset($request->status)) {
            $chamados->where('status', '=', $request->status);
        }
        if (isset($request->search)) {
            $chamados->where('chamado', 'LIKE', "%" . $request->search . "%");
        }
        $chamados = $chamados->paginate(10);
        return view('chamados/todos', compact('chamados'));
    }

    public function buscaid(Request $request)
    {
        return 'desativado';
        $this->authorize('atendente');
        $chamado = isset($request->id) ? Chamado::find($request->id) : null;
        $mensagem = null;
        if (isset($request->id) and is_null($chamado)) {
            $mensagem = 'Não há chamado com este Id.';
        }
        return view('chamados/buscaid', compact('chamado', 'mensagem'));
    }

    # acho que nao vai mais usar
    public function triagem()
    {
        return 'disable';
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');
        $user = \Auth::user();
        $chamados = Chamado::where('status', 'Triagem')->orderBy('created_at', 'desc')->get();
        return view('chamados/index', compact('chamados'));
    }

    public function atender()
    {
        return 'disable';
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        $user = \Auth::user();
        $chamados = Chamado::whereHas('users', function ($pivot) {
            $user = \Auth::user();
            $pivot->where([
                ['user_id', $user->id],
                ['funcao', 'Atendente'],
            ]);
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('chamados/index', compact('chamados'));
    }
}
