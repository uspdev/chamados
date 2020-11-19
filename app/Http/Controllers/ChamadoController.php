<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadoRequest;
use App\Models\Chamado;
use App\Models\Comentario;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ChamadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('chamados.viewAny');

        if (session('ano') == null) {
            session(['ano' => date('Y')]);
        }
        $chamados = Chamado::listarChamados(session('ano'));
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
        $complexidades = Chamado::complexidades();
        $form = JSONForms::generateForm($fila);
        return view('chamados/create', compact('fila', 'chamado', 'complexidades', 'form'));
    }

    /**
     * Mostra lista de filas e respectivos setores
     * para selecionar e criar novo chamado
     *
     * @return \Illuminate\Http\Response
     */
    public function listaFilas()
    {
        $setores = Setor::with(['filas' => function ($query) {
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
        # limitar _as filas que o usuario pode criar, somente filas "em produção"
        $this->authorize('chamados.create');
        $request->validate(JSONForms::buildRules($request, $fila));

        # transaction para não ter problema de inconsistência do DB
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
        # ao negar acesso à um chamado seria interessante mostrar uma mensagem ?
        # é o caso de chamado vinculado, está configurado para mostrar
        # os vinculados diretos, mas não os subsequentes. Se o fulano clicar no
        # vinculado do vinculado dá o 403
        $this->authorize('chamados.view', $chamado);

        $template = json_decode($chamado->fila->template);
        $extras = json_decode($chamado->extras);
        if (empty($template)) {
            $template = [];
        }
        $atendentes = $chamado->users()->wherePivot('papel', 'Atendente')->get();
        $autor = $chamado->users()->wherePivot('papel', 'Autor')->first();

        # estamos carregando os vinculados diretos.
        # Seria interessante vincular recursivos? Acho que não mas ...
        $vinculados = $chamado->vinculados;
        $complexidades = Chamado::complexidades();

        # para o form de adicionar pessoas
        $modal_pessoa['url'] = 'chamados';
        $modal_pessoa['title'] = 'Adicionar observador';

        $max_upload_size = env('APP_UPLOAD_MAX_FILESIZE') != null ? ((int) env('APP_UPLOAD_MAX_FILESIZE')) : 16;

        $form = JSONForms::generateForm($chamado->fila, $chamado);

        return view('chamados/show', compact('atendentes', 'autor', 'chamado', 'extras', 'template', 'vinculados', 'complexidades', 'modal_pessoa', 'max_upload_size', 'form'));
    }

    /**
     * Retornando os chamados para criar vinculo entre eles
     * @param Request $request - assunto ou número do chamado
     * @return json
     */
    public function listarChamadosAjax(Request $request)
    {
        if ($request->term) {
            if (is_numeric($request->term)) {
                //busca pelo nro, independete do ano
                $chamados = Chamado::listarChamados(null, $request->term, null);
            } else {
                //busca pelo assunto, independete do ano
                $chamados = Chamado::listarChamados(null, null, $request->term);
            }

            # vamos formatar para datatables
            $results = [];
            foreach ($chamados as $chamado) {
                $results[] = [
                    'text' => $chamado['nro'] . '/' . $chamado['created_at']->year . ' - ' . $chamado['assunto'],
                    'id' => $chamado['id'],
                ];
            }
            return response(compact('results'));
        }
        return null;
    }

    /**
     * Vincula chamados
     * @param $request->slct_chamados - nrp do chamado a ser vinculado
     * @param $request->tipo - tipo de acesso (leitura??)
     */
    public function storeChamadoVinculado(Request $request, Chamado $chamado)
    {
        $this->authorize('chamados.create');

        if ($request->slct_chamados != $chamado->id) {

            $request->validate([
                'acesso' => ['in:Leitura'],
            ]);

            $chamado->vinculadosIda()->detach($request->slct_chamados);
            $chamado->vinculadosIda()->attach($request->slct_chamados, ['acesso' => $request->acesso]);

            $vinculado = Chamado::find($request->slct_chamados);
            //comentário no chamado principal
            Comentario::create([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $chamado->id,
                'comentario' => 'O chamado no. ' . $vinculado->nro . '/' . $vinculado->created_at->year . ' foi vinculado à esse chamado',
                'tipo' => 'system',
            ]);
            // comentário no chamado vinculado
            Comentario::create([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $vinculado->id,
                'comentario' => 'Esse chamado foi vinculado ao chamado no. ' . $chamado->nro . '/' . $chamado->created_at->year,
                'tipo' => 'system',
            ]);

            $request->session()->flash('alert-info', 'Chamado vinculado com sucesso');

        } else {
            $request->session()->flash('alert-warning', 'Não é possível vincular o chamado à ele mesmo');
        }
        return Redirect::to(URL::previous() . "#card_vinculados");
    }

    /**
     * Desvincula chamados
     */
    public function deleteChamadoVinculado(Request $request, Chamado $chamado, $id)
    {
        $this->authorize('chamados.create');

        $chamado->vinculadosIda()->detach($id);
        $chamado->vinculadosVolta()->detach($id);

        $vinculado = Chamado::find($id);
        #dd($vinculado);
        //comentário no chamado principal
        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => 'O chamado no. ' . $vinculado->nro . '/' . $vinculado->created_at->year . ' foi desvinculado desse chamado',
            'tipo' => 'system',
        ]);

        // comentário no chamado vinculado
        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $vinculado->id,
            'comentario' => 'Esse chamado foi desvinculado do chamado no. ' . $chamado->nro . '/' . $chamado->created_at->year,
            'tipo' => 'system',
        ]);

        $request->session()->flash('alert-info', 'Chamado desvinculado com sucesso');
        return Redirect::to(URL::previous() . "#card_vinculados");
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
        $this->authorize('chamados.update', $chamado);

        # inicialmente atende a atualização do campo anotacoes via ajax
        if ($request->ajax()) {
            $chamado->fill($request->all());
            $chamado->save();
            return response()->json(['message' => 'success', 'data' => $chamado]);
        }

        # acho que valida atendente
        if (Gate::allows('admin') and isset($request->atribuido_para)) {
            $request->validate([
                'fila_id' => ['required|numeric'],
            ]);
        }

        # valida customforms
        # está dando erro
        //$request->validate(JSONForms::buildRules($request, $chamado->fila));
        
        $chamado = $this->grava($chamado, $request);

        /*
        if(config('app.env') == 'production')
        Mail::send(new ChamadoMail($chamado,$user));

        depois de atualizar, tem de registrar nos comentários
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
                    die('desativado');
                    /* acho que o user deveria vir direto pelo form */
                    $atendente = User::where('codpes', $request->atribuido_para)->first();
                    $chamado->users()->attach($atendente->id, ['papel' => 'Atendente']);
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
        //$chamado->users()->attach($user->id, ['papel' => 'Autor']);
        return $chamado;
    }

    /**
     * adiciona atendentes. Pode ser mais de um
     */
    public function triagemStore(Request $request, Chamado $chamado)
    {
        $this->authorize('admin');
        $chamado->complexidade = $request->complexidade;
        $atendente = User::obterPorCodpes($request->codpes);

        # Se atendente já existe não vamos adicionar novamente
        if ($chamado->users()->where(['user_id' => $atendente->id, 'papel' => 'Atendente'])->exists()) {
            $request->session()->flash('alert-info', 'Atendente já existe');
            return back();
        }

        $chamado->users()->attach($atendente->id, ['papel' => 'Atendente']);
        $chamado->status = 'Atribuído';
        $chamado->save();

        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => 'O chamado foi atribuído para o(a) atendente ' . $atendente->name,
            'tipo' => 'system',
        ]);

        $request->session()->flash('alert-info', 'Atendente adicionado com sucesso');
        return back();
    }

    /**
     * Salva o ano na sessão usada no index
     */
    public function mudaAno(Request $request)
    {
        $ano = $request->ano;
        if ($ano != null || $ano != '') {
            session(['ano' => $ano]);
        }
        return back();
    }

    /**
     * Adicionar pessoas relacionadas ao chamado
     * autorizado a qualquer um que tenha acesso ao chamado
     * request->codpes = required, int
     */
    public function storePessoa(Request $request, Chamado $chamado)
    {
        $user = User::obterOuCriarPorCodpes($request->codpes);
        $chamado->users()->attach($user, ['papel' => 'Observador']);

        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => 'O observador ' . $user->name . ' foi adicionado ao chamado.',
            'tipo' => 'system',
        ]);

        $request->session()->flash('alert-info', 'Observador adicionado com sucesso.');

        return Redirect::to(URL::previous() . "#card_pessoas");
    }

    /**
     * Remove pessoas relacionadas ao chamado
     * Autorização: se for remover autor, ou atendente,somente para atendente ou admin
     * se for observador, qualquer um que tenha acesso ao chamado
     * $user = required
     */
    public function destroyPessoa(Request $request, Chamado $chamado, User $user)
    {
        $papel = $chamado->users()->where('users.id', $user->id)->first()->pivot->papel;
        $chamado->users()->detach($user);

        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => 'O ' . strtolower($papel) . ' ' . $user->name . ' foi removido desse chamado.',
            'tipo' => 'system',
        ]);
        $request->session()->flash('alert-info', $papel . ' ' . $user->name . ' foi removido com sucesso.');

        return Redirect::to(URL::previous() . "#card_pessoas");
    }

    /**
     *
     * desativados
     *
     *
     *
     *
     */

    /**
    * **** Passou a ser feito via modal ****
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
        $complexidades = Chamado::complexidades();
        $autor = $chamado->users()->wherePivot('papel', 'Autor')->first();
        $form = JSONForms::generateForm($fila, $chamado);
        return view('chamados/edit', compact('fila', 'chamado', 'atendentes', 'form'));
    }

    public function devolver(Chamado $chamado)
    {
        return "desativado";
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
                ['papel', 'Atendente'],
            ]);
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('chamados/index', compact('chamados'));
    }

}
