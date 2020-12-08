<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadoRequest;
use App\Models\Chamado;
use App\Models\Comentario;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use App\Models\Patrimonio;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
        $status_list = Chamado::status();
        $form = JSONForms::generateForm($fila);
        return view('chamados/create', compact('fila', 'chamado', 'complexidades', 'status_list', 'form'));
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
        # Vamos negar acesso com mensagem apropriada
        if (!Gate::allows('chamados.view', $chamado)) {
            return view('sem-acesso');
        }
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
        $complexidades = Chamado::complexidades(true);
        $status_list = Chamado::status(true);
        # para o form de adicionar pessoas
        $modal_pessoa['url'] = 'chamados';
        $modal_pessoa['title'] = 'Adicionar observador';
        $max_upload_size = env('APP_UPLOAD_MAX_FILESIZE') != null ? ((int) env('APP_UPLOAD_MAX_FILESIZE')) : 16;
        $form = JSONForms::generateForm($chamado->fila, $chamado);
        return view('chamados/show', compact('atendentes', 'autor', 'chamado', 'extras', 'template', 'vinculados', 'complexidades', 'status_list', 'modal_pessoa', 'max_upload_size', 'form'));
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
            $atualizacao = [];
            $novo_valor = [];
            if ($chamado->assunto != $request->assunto && !empty($request->assunto)) {
                /*  guardando os dados antigos em log para auditoria */
                Log::info(' - Edição de chamado - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Chamado: ' . $chamado->id . ' - Assunto antigo: ' . $chamado->assunto . ' - Novo Assunto: ' . $request->assunto);
                array_push($atualizacao, 'assunto');
                $chamado->assunto = $request->assunto;
            }
            if ($chamado->descricao != $request->descricao && !empty($request->descricao)) {
                /* guardando os dados antigos em log para auditoria */
                Log::info(' - Edição de chamado - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Chamado: ' . $chamado->id . ' - Descrição antiga: ' . $chamado->descricao . ' - Nova descrição: ' . $request->descricao);
                array_push($atualizacao, 'descrição');
                $chamado->descricao = $request->descricao;
            }
            if (json_encode($chamado->extras) != json_encode($request->extras) && !empty($request->extras)) {
                /* guardando os dados antigos em log para auditoria */
                Log::info(' - Edição de chamado - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Chamado: ' . $chamado->id . ' - Extras antigo: ' . $chamado->extras . ' - Novo extras: ' . json_encode($request->extras));
                array_push($atualizacao, 'formulário');
                $chamado->extras = json_encode($request->extras);
            }
            if (!empty($request->status)) {
                if ($chamado->status != $request->status) {
                    array_push($atualizacao, 'status');
                    array_push($novo_valor, $request->status);
                    $chamado->status = $request->status;
                }
            }
            if (!empty($request->complexidade)) {
                if ($chamado->complexidade != $request->complexidade) {
                    array_push($atualizacao, 'complexidade');
                    array_push($novo_valor, $request->complexidade);
                    $chamado->complexidade = $request->complexidade;
                }
            }
            /* Caso tenha alguma atualização, guarda nos registros */
            if (count($atualizacao) > 0) {
                if (count($atualizacao) == 1) {
                    $msg = 'O campo ' . $atualizacao[0] . ' foi atualizado';
                    if ($atualizacao[0] == 'status' || $atualizacao[0] == 'complexidade') {
                        $msg .= ' para ' . $novo_valor[0];
                    }
                } elseif (count($atualizacao) > 1) {
                    $msg = 'Os campos ';
                    $msg .= implode(", ", array_slice($atualizacao, 0, -1));
                    $msg .= ' e ' . $atualizacao[count($atualizacao) - 1];
                    $msg .= ' foram atualizados';
                }
                Comentario::create([
                    'user_id' => \Auth::user()->id,
                    'chamado_id' => $chamado->id,
                    'comentario' => $msg,
                    'tipo' => 'system',
                ]);
            }
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
        if (!count($chamado->users()->wherePivot('papel', 'Autor')->get())) {
            $chamado->users()->attach($user->id, ['papel' => 'Autor']);
        }
        return $chamado;
    }
    /**
     * adiciona atendentes. Pode ser mais de um
     */
    public function triagemStore(Request $request, Chamado $chamado)
    {
        $this->authorize('atendente');

        if ($request->codpes == '') {
            $request->session()->flash('alert-warning', 'É necessário selecionar um atendente');
            return Redirect::to(URL::previous() . "#card_atendente");
        }
        $atendente = User::obterPorCodpes($request->codpes);
        # Se atendente já existe não vamos adicionar novamente
        if ($chamado->users()->where(['user_id' => $atendente->id, 'papel' => 'Atendente'])->exists()) {
            $request->session()->flash('alert-info', 'Atendente já existe');
            return Redirect::to(URL::previous() . "#card_atendente");
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
        return Redirect::to(URL::previous() . "#card_atendente");
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
        $this->authorize('chamados.view', $chamado);

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
        $this->authorize('chamados.view', $chamado);

        $papel = $chamado->users()->where('users.id', $user->id)->first()->pivot->papel;
        $chamado->users()->wherePivot('papel', $papel)->detach($user);

        # verificar se sobrou algum atendente, se não, muda o status
        if (!count($chamado->users()->wherePivot('papel', 'Atendente')->get())) {
            $chamado->status = 'Triagem';
            $chamado->save();
        }
        $msg = 'O ' . strtolower($papel) . ' ' . $user->name . ' foi removido desse chamado.';
        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => $msg,
            'tipo' => 'system',
        ]);
        $request->session()->flash('alert-info', $msg);
        return Redirect::to(URL::previous() . "#card_pessoas");
    }

    /**
     * Adicionar patrimonios relacionadas ao chamado
     * autorizado a qualquer um que tenha acesso ao chamado
     * request->codpes = required, int
     */
    public function storePatrimonio(Request $request, Chamado $chamado)
    {
        if (config('chamados.usar_replicado') == 'true') {
            $request->validate([
                'numpat' => 'required|patrimonio',
            ]);
        } else {
            $request->validate([
                'numpat' => 'required',
            ]);
        }
        $patrimonio = Patrimonio::where('numpat', $request->numpat)->first();
        if (!$patrimonio) {
            $patrimonio = new Patrimonio;
            $patrimonio->numpat = $request->numpat;
            $patrimonio->save();
        }
        $chamado->patrimonios()->detach($patrimonio);
        $chamado->patrimonios()->attach($patrimonio);

        # continua ...
        return Redirect::to(URL::previous() . "#card_patrimonios");
    }

    /**
     * Remove patrimonios relacionadas ao chamado
     * Autorização: se for remover autor, ou atendente,somente para atendente ou admin
     * se for observador, qualquer um que tenha acesso ao chamado
     * $user = required
     */
    public function destroyPatrimonio(Request $request, Chamado $chamado, User $user)
    {
    }
}
