<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use App\Rules\PatrimonioRule;
use App\Utils\JSONForms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChamadoController extends Controller
{

    /* Variáveis globais temporárias */
    private $predios;
    private $atendentes;
    private $complexidades;

    public function __construct()
    {
        $this->middleware('auth');
        $this->complexidades = Chamado::complexidades();
        $this->predios = collect(Chamado::predios());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        if (Gate::allows('admin')) {
            $chamados = Chamado::all();
        }
        else {
            $user = \Auth::user();
            $chamados = $user->chamados;
        }

        return view('chamados/index', compact('chamados'));
    }

    public function todos(Request $request)
    {
        $this->authorize('admin');

        $chamados = Chamado::orderBy('created_at', 'desc');

        // search terms
        if (isset($request->status)) {
            $chamados->where('status', '=', $request->status);
        }

        if (isset($request->search)) {
            $chamados->where('chamado', 'LIKE', "%" . $request->search . "%");
        }

        $predios = [];
        $chamados = $chamados->paginate(10);
        $atendentes = [];

        return view('chamados/todos', compact('atendentes', 'chamados', 'predios'));
    }

    public function buscaid(Request $request)
    {
        $this->authorize('atendente');
        $chamado = isset($request->id) ? Chamado::find($request->id) : null;
        $mensagem = null;
        if (isset($request->id) and is_null($chamado)) {
            $mensagem = 'Não há chamado com este Id.';
        }
        return view('chamados/buscaid', compact('chamado', 'mensagem'));
    }

    public function triagem()
    {
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        $user = \Auth::user();
        $chamados = Chamado::where('status', 'Triagem')->orderBy('created_at', 'desc')->get();
        return view('chamados/index', compact('chamados'));
    }

    public function atender()
    {
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        $user = \Auth::user();
        $chamados = Chamado::whereHas('users', function($pivot) {
            $user = \Auth::user();
            $pivot->where([
                ['user_id', $user->id],
                ['funcao', 'Atendente']
            ]);
        })->orderBy('created_at', 'desc')->paginate(10);

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
        $predios = $this->predios;
        $chamado = new Chamado;
        $chamado->fila = $fila;
        $complexidades = $this->complexidades;
        $form = JSONForms::generateForm($fila);
        return view('chamados/create', compact('fila', 'predios', 'chamado', 'complexidades', 'form'));
    }

    public function listaFilas()
    {
        $setores = Setor::orderBy('sigla')->get();
        return view('chamados.listafilas', compact('setores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Fila $fila)
    {
        $this->authorize('chamados.create');
        $chamado = new Chamado;
        $chamado->fila_id = $fila->id;
        $chamado = $this->grava($chamado, $request);
        /*
        if(config('app.env') == 'production')
        Mail::send(new ChamadoMail($chamado,$user));
         */

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
        $atendente = $chamado->users()->wherePivot('funcao', 'Atendente')->first();
        $atribuidor = $chamado->users()->wherePivot('funcao', 'Atribuidor')->first();
        $autor = $chamado->users()->wherePivot('funcao', 'Autor')->first();
        $complexidades = $this->complexidades;

        return view('chamados/show', compact('atendente', 'atribuidor', 'autor', 'chamado', 'extras', 'template', 'complexidades'));
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
        $predios = $this->predios;
        $atendentes = $this->atendentes;
        $complexidades = $this->complexidades;
        $autor = $chamado->users()->wherePivot('funcao', 'Autor')->first();
        $form = JSONForms::generateForm($fila, $chamado);
        return view('chamados/edit', compact('autor', 'fila', 'chamado', 'predios', 'atendentes', 'complexidades', 'form'));
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

    /* Evita duplicarmos código */
    private function grava(Chamado $chamado, Request $request)
    {
        if ($request->status == 'devolver') {
            $chamado->status = 'Triagem';
            $chamado->complexidade = null;
            $user = \Auth::user();
        } else {
            $request->validate([
                'telefone' => ['required'],
                'assunto' => ['required'],
                'patrimonio' => ['nullable', new PatrimonioRule],
            ]);

            $chamado->assunto = $request->assunto;
            $chamado->status = 'Triagem';
            $extras = $request->extras;
            if (!empty($extras['numpat'])) {
                $request->validate([
                    'extras.numpat' => ['nullable', new PatrimonioRule],
                ]);
            }
            $chamado->extras = json_encode($request->extras);

            /* Administradores */
            if (Gate::allows('admin')) {
                /* trocar requisitante */
                if (!is_null($request->codpes)) {
                    $request->validate([
                        'codpes' => 'integer',
                    ]);
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
                    $chamado->complexidade = $request->complexidade;
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

    /* Ainda não implementado */
    public function triagemForm(Request $request, Chamado $chamado)
    {
        $this->authorize('admin');
        $atendentes = $this->atendentes;
        $complexidades = $this->complexidades;
        return view('chamados/triagem', compact('chamado'));

    }

    public function triagemStore(Request $request, Chamado $chamado)
    {
        $this->authorize('admin');
        $chamado->complexidade = $request->complexidade;
        $atendente = User::where('codpes', $request->atribuido_para)->first();
        /* TODO precisa dar dettach do atendente e do atribuidor anterior */
        $chamado->users()->attach($atendente->id, ['funcao' => 'Atendente']);
        $chamado->users()->attach(\Auth::user()->id, ['funcao'=> 'Atribuidor']);
        $chamado->status = 'Atribuído';
        $chamado->save();
        $request->session()->flash('alert-info', 'Triagem realizada com sucesso');
        return redirect()->route('chamados.show', $chamado->id);
    }

    public function devolver(Chamado $chamado)
    {
        $this->authorize('atendente');
        return view('chamados/devolver', compact('chamado'));
    }
}
