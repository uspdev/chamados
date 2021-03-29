<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadoRequest;
use App\Models\Chamado;
use App\Models\Comentario;
use App\Models\Fila;
use App\Models\Patrimonio;
use App\Models\Setor;
use App\Models\User;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

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
        $form = JSONForms::generateForm($fila);
        return view('chamados/create', compact('fila', 'chamado', 'form'));
    }

    /**
     * Mostra lista de filas e respectivos setores
     * para selecionar e criar novo chamado
     *
     * @return \Illuminate\Http\Response
     */
    public function listaFilas()
    {
        $this->authorize('chamados.create');
        $setores = Fila::listarFilasParaNovoChamado();
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
        # limitar as filas que o usuario pode criar, somente filas "em produção"
        $this->authorize('chamados.create');

        # na criação não precisa
        #$request->validate(JSONForms::buildRules($request, $fila));

        # transaction para não ter problema de inconsistência do DB
        $chamado = \DB::transaction(function () use ($request, $fila) {
            $chamado = new Chamado;
            $chamado->nro = Chamado::obterProximoNumero();
            $chamado->fila_id = $fila->id;
            $chamado->assunto = $request->assunto;
            $chamado->status = 'Triagem';

            $chamado->descricao = $request->descricao;
            $chamado->extras = json_encode($request->extras);

            // vamos salvar sem evento pois o autor ainda não está cadastrado
            $chamado->saveQuietly();

            $chamado->users()->attach(\Auth::user(), ['papel' => 'Autor']);

            // agora sim vamos disparar o evento
            event('eloquent.created: App\Models\Chamado', $chamado);

            return $chamado;
        });

        $request->session()->flash('alert-info', 'Chamado enviado com sucesso');

        // talvez essa confirmação deva ficar em outro lugar
        if ($chamado->fila->config->patrimonio) {
            if ($chamado->patrimonios->count() < 1) {
                $request->session()->flash('alert-danger', 'É obrigatório cadastrar um número de patrimônio!');
            }
        }

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
        $autor = $chamado->users()->wherePivot('papel', 'Autor')->first();

        $status_list = $chamado->fila->getStatusToSelect();
        $color = $chamado->fila->getColortoLabel($chamado->status);

        $max_upload_size = config('chamados.upload_max_filesize');

        $form = JSONForms::generateForm($chamado->fila, $chamado);
        $formAtendente = JSONForms::generateForm($chamado->fila, $chamado, 'atendente');

        return view('chamados/show', compact('autor', 'chamado', 'extras', 'template', 'status_list', 'color', 'max_upload_size', 'form', 'formAtendente'));
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
                    'text' => $chamado->nro . '/' . $chamado->created_at->year . ' - ' . $chamado->assunto,
                    'id' => $chamado->id,
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
        $this->authorize('chamados.update', $chamado);

        if ($request->slct_chamados != $chamado->id) {
            $request->validate([
                'acesso' => ['in:Leitura'],
            ]);
            $chamado->vinculadosIda()->detach($request->slct_chamados);
            $chamado->vinculadosIda()->attach($request->slct_chamados, ['acesso' => $request->acesso]);
            $vinculado = Chamado::find($request->slct_chamados);
            //comentário no chamado principal
            Comentario::criar([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $chamado->id,
                'comentario' => 'O chamado no. ' . $vinculado->nro . '/' . $vinculado->created_at->year . ' foi vinculado à esse chamado',
                'tipo' => 'system',
            ]);
            // comentário no chamado vinculado
            Comentario::criar([
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
        $this->authorize('chamados.update', $chamado);

        $chamado->vinculadosIda()->detach($id);
        $chamado->vinculadosVolta()->detach($id);
        $vinculado = Chamado::find($id);

        //comentário no chamado principal
        Comentario::criar([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => 'O chamado no. ' . $vinculado->nro . '/' . $vinculado->created_at->year . ' foi desvinculado desse chamado',
            'tipo' => 'system',
        ]);
        // comentário no chamado vinculado
        Comentario::criar([
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

        if ($chamado->fila->config->patrimonio) {
            if ($chamado->patrimonios->count() < 1) {
                $request->session()->flash('alert-danger', 'É obrigatório cadastrar um número de patrimônio!');
            }
        }
        $request->session()->flash('alert-info', 'Chamado enviado com sucesso');
        return redirect()->route('chamados.show', $chamado->id);
    }

    /* Evita duplicarmos código
    Está sendo usado somente no update, no store foi separado pois
    tem bem pouca coisa daqui.
     */
    private function grava(Chamado $chamado, Request $request)
    {
        $atualizacao = [];
        $novo_valor = [];

        # assunto
        if ($chamado->assunto != $request->assunto && !empty($request->assunto)) {
            /*  guardando os dados antigos em log para auditoria */
            Log::info(' - Edição de chamado - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Chamado: ' . $chamado->id . ' - Assunto antigo: ' . $chamado->assunto . ' - Novo Assunto: ' . $request->assunto);
            array_push($atualizacao, 'assunto');
            $chamado->assunto = $request->assunto;
        }

        #descricao
        if ($chamado->descricao != $request->descricao && !empty($request->descricao)) {
            /* guardando os dados antigos em log para auditoria */
            Log::info(' - Edição de chamado - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Chamado: ' . $chamado->id . ' - Descrição antiga: ' . $chamado->descricao . ' - Nova descrição: ' . $request->descricao);
            array_push($atualizacao, 'descrição');
            $chamado->descricao = $request->descricao;
        }

        # formulario (extras)
        if (json_encode($chamado->extras) != json_encode($request->extras) && !empty($request->extras)) {
            /* guardando os dados antigos em log para auditoria */
            Log::info(' - Edição de chamado - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Chamado: ' . $chamado->id . ' - Extras antigo: ' . $chamado->extras . ' - Novo extras: ' . json_encode($request->extras));
            $extras_chamados = json_decode($chamado->extras, true);
            $extras_request = $request->extras;
            /* se não for um chamado novo */
            if ($chamado->extras != null) {
                /* atualiza todos os campos que não vieram no request para não perder os mesmos */
                $atualiza_extras = false;
                foreach ($extras_chamados as $campoc => $valuec) {
                    if (!array_key_exists($campoc, $extras_request)) {
                        $extras_request[$campoc] = $extras_chamados[$campoc];
                    } else {
                        $template = json_decode($chamado->fila->template);
                        /* não vamos atualizar o registro do sistema quando for campo exclusivo do atendente  */
                        if (empty($template->$campoc->can)) {
                            $atualiza_extras = true;
                        } elseif ($template->$campoc->can != "atendente") {
                            $atualiza_extras = true;
                        }
                    }
                }
            }
            if ($atualiza_extras) {
                array_push($atualizacao, 'formulário');
            }
            $chamado->extras = json_encode($extras_request);
        }

        # status
        if (!empty($request->status)) {
            if ($chamado->status != $request->status) {
                array_push($atualizacao, 'status');
                array_push($novo_valor, $request->status);
                $chamado->status = $request->status;
            }
        }

        /* Caso tenha alguma atualização, guarda nos registros */
        if (count($atualizacao) > 0) {
            if (count($atualizacao) == 1) {
                $msg = 'O campo ' . $atualizacao[0] . ' foi atualizado';
                if ($atualizacao[0] == 'status') {
                    $msg .= ' para ' . $novo_valor[0];
                }
            } elseif (count($atualizacao) > 1) {
                $msg = 'Os campos ';
                $msg .= implode(", ", array_slice($atualizacao, 0, -1));
                $msg .= ' e ' . $atualizacao[count($atualizacao) - 1];
                $msg .= ' foram atualizados';
            }
            Comentario::criar([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $chamado->id,
                'comentario' => $msg,
                'tipo' => 'system',
            ]);
        }

        $chamado->save();
        if (!count($chamado->users()->wherePivot('papel', 'Autor')->get())) {
            $chamado->users()->attach($user->id, ['papel' => 'Autor']);
        }
        return $chamado;
    }

    /**
     * adiciona atendentes. Pode ser mais de um
     *
     * Com o storePessoa, que é mais genérico talves esse método possa ser eliminado
     * TODO: Masaki em 17/2/2021
     */
    public function triagemStore(Request $request, Chamado $chamado)
    {
        $this->authorize('chamados.view', $chamado);

        $request->validate(
            ['codpes' => 'required|codpes'],
            ['codpes.required' => 'Necessário informar atendente'],
        );

        $atendente = User::obterPorCodpes($request->codpes);

        # Se atendente já existe não vamos adicionar novamente
        if ($chamado->users()->where(['user_id' => $atendente->id, 'papel' => 'Atendente'])->exists()) {
            $request->session()->flash('alert-info', 'Atendente já existe');
            return Redirect::to(URL::previous() . "#card_atendente");
        }
        $chamado->users()->attach($atendente->id, ['papel' => 'Atendente']);
        $chamado->status = 'Em Andamento';
        $chamado->save();

        Comentario::criar([
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
        # A validação ainda precisa passar para um local mais apropriado
        $validator = Validator::make(['ano' => $request->ano], [
            'ano' => 'required|integer|in:' . implode(',', Chamado::anos()),
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        session(['ano' => $request->ano]);
        return back();
    }

    /**
     * Adicionar pessoas relacionadas ao chamado
     * autorizado a qualquer um que tenha acesso ao chamado
     * request->codpes = required, int
     * request->papel = required
     */
    public function storePessoa(Request $request, Chamado $chamado)
    {
        $this->authorize('chamados.update', $chamado);

        $request->validate(
            [
                'codpes' => 'required|integer',
                'papel' => 'required|in:' . implode(',', Chamado::pessoaPapeis()),
            ]
        );

        $papel = $request->papel;
        $codpes = $request->codpes;

        # para cadastrar autor e atendente, vamos negar se usuário não for atendente
        if ('Autor' == $papel || 'Atendente' == $papel) {
            $this->authorize('atendente');
        }

        $user = User::obterOuCriarPorCodpes($codpes);

        # O usuário já existe nesse papel?
        if ($chamado->users()->where('users.id', $user->id)->wherePivot('papel', $papel)->first()) {
            $request->session()->flash('alert-info', $papel . ' já existe.');
        } else {
            $chamado->users()->attach($user, ['papel' => $papel]);

            // se o usuario adicionado for atendente e o status for triagem
            // vamos mudar o status para Em andamento
            // Este trecho pode substituir o triagemStore. TODO
            if (('Atendente' == $papel) && ('Triagem' == $chamado->status)) {
                $chamado->status = 'Em andamento';
                $chamado->save();
            }

            Comentario::criar([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $chamado->id,
                'comentario' => 'O ' . strtolower($papel) . ' ' . $user->name . ' foi adicionado ao chamado.',
                'tipo' => 'system',
            ]);

            $request->session()->flash('alert-info', $papel . ' adicionado com sucesso.');
        }

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
        $this->authorize('chamados.update', $chamado);

        $papel = $chamado->users()->where('users.id', $user->id)->first()->pivot->papel;

        # para remover autor e atendente, vamos negar se usuário não for atendente
        if ('Autor' == $papel || 'Atendente' == $papel) {
            $this->authorize('atendente');
        }

        # vamos remover de fato
        $chamado->users()->wherePivot('papel', $papel)->detach($user);

        # verificar se sobrou algum atendente, se não, muda o status
        if (!count($chamado->users()->wherePivot('papel', 'Atendente')->get())) {
            $chamado->status = 'Triagem';
            $chamado->save();
        }
        $msg = 'O ' . strtolower($papel) . ' ' . $user->name . ' foi removido desse chamado.';
        Comentario::criar([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => $msg,
            'tipo' => 'system',
        ]);
        $request->session()->flash('alert-info', $msg);
        return Redirect::to(URL::previous() . "#card_pessoas");
    }

    /**
     * Retornando patrimônio para inserção em chamados
     * @param Request $request - numpat
     * @return json
     */
    public function listarPatrimoniosAjax(Request $request)
    {
        if ($request->term) {
            if (config('chamados.usar_replicado') == 'true') {
                $patrimonio = new Patrimonio();
                $patrimonio->numpat = str_replace('.', '', $request->term);
                if ($patrimonio->replicado()->anoorc != null) {
                    $results[] = [
                        'text' => $patrimonio->numFormatado() . ' - ' . $patrimonio->replicado()->epfmarpat . ' - ' . $patrimonio->replicado()->tippat . ' - ' . $patrimonio->replicado()->modpat,
                        'id' => $patrimonio->numpat,
                    ];
                    return response(compact('results'));
                }
            } else {
                $patrimonio = Patrimonio::where('numpat', str_replace('.', '', $request->term))->first();
                if (!$patrimonio) {
                    $patrimonio = new Patrimonio;
                    $patrimonio->numpat = str_replace('.', '', $request->term);
                    $patrimonio->save();
                }
                $results[] = [
                    'text' => $patrimonio->numFormatado(),
                    'id' => $patrimonio->numpat,
                ];
                return response(compact('results'));
            }
        }
        return null;
    }

    /**
     * Adicionar patrimonios relacionadas ao chamado
     * autorizado a qualquer um que tenha acesso ao chamado
     * request->codpes = required, int
     */
    public function storePatrimonio(Request $request, Chamado $chamado)
    {
        $this->authorize('chamados.update', $chamado);

        if (config('chamados.usar_replicado') == 'true') {
            $request->validate([
                'numpat' => 'required|patrimonio',
            ]);
        } else {
            $request->validate([
                'numpat' => 'required',
            ]);
        }

        $patrimonio = Patrimonio::where('numpat', str_replace('.', '', $request->numpat))->first();
        if (!$patrimonio) {
            $patrimonio = new Patrimonio;
            $patrimonio->numpat = str_replace('.', '', $request->numpat);
            $patrimonio->save();
        }

        $existia = $chamado->patrimonios()->detach($patrimonio);

        $chamado->patrimonios()->attach($patrimonio);

        if (!$existia) {
            $msg = 'O patrimônio ' . $patrimonio->numFormatado() . ' foi adicionado a esse chamado.';
            Comentario::criar([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $chamado->id,
                'comentario' => $msg,
                'tipo' => 'system',
            ]);

            $request->session()->flash('alert-info', $msg);
        } else {
            $request->session()->flash('alert-info', 'O patrimônio ' . $patrimonio->numFormatado() . ' já estava vinculado à esse chamado.');
        }

        return Redirect::to(URL::previous() . "#card_patrimonios");
    }

    /**
     * Remove patrimonios relacionadas ao chamado
     * Autorização: se for remover autor, ou atendente,somente para atendente ou admin
     * se for observador, qualquer um que tenha acesso ao chamado
     * $user = required
     */
    public function destroyPatrimonio(Request $request, Chamado $chamado, Patrimonio $patrimonio)
    {
        $this->authorize('chamados.update', $chamado);

        $chamado->patrimonios()->detach($patrimonio);

        $msg = 'O patrimônio ' . $patrimonio->numFormatado() . ' foi removido desse chamado.';
        Comentario::criar([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $chamado->id,
            'comentario' => $msg,
            'tipo' => 'system',
        ]);

        $request->session()->flash('alert-info', $msg);

        return Redirect::to(URL::previous() . "#card_patrimonios");
    }
}
