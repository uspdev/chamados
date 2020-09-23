<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ChamadoMail;
use Mail;
use Illuminate\Support\Facades\Gate;
use App\Rules\Numeros_USP;
use Carbon\Carbon;
use App\Rules\PatrimonioRule;

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
        $this->atendentes = Chamado::atendentes();
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

        $user = \Auth::user();
        $chamados = Chamado::where('user_id','=',$user->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('chamados/index',compact('chamados'));
    }

    public function todos(Request $request)
    {
        $this->authorize('atendente');

        $chamados = Chamado::orderBy('created_at', 'desc');

        // search terms
        if (isset($request->status)) {
            $chamados->where('status', '=', $request->status);
        }

        if (isset($request->predio)) {
            $chamados->where('predio', '=', $request->predio);
        }

        if (isset($request->atendente)) {
            $chamados->where('atribuido_para', '=', $request->atendente);
        }

        if (isset($request->search)) {
            $chamados->where('chamado', 'LIKE', "%".$request->search."%");
        }

        $atendentes = $this->atendentes;
        $predios = $this->predios;
        $chamados = $chamados->paginate(10);

        return view('chamados/todos',compact('chamados','atendentes','predios'));
    }

    public function buscaid(Request $request)
    {
        $this->authorize('atendente');
        $chamado = isset($request->id) ? Chamado::find($request->id) : null;
        $mensagem = null;
        if(isset($request->id) and is_null($chamado)) {
            $mensagem = 'Não há chamado com este Id.';
        }
        return view('chamados/buscaid',compact('chamado','mensagem'));
    }

    public function triagem()
    {
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        $user = \Auth::user();
        $chamados = Chamado::where('status','=','Triagem')->orderBy('created_at', 'desc')->paginate(10);
        return view('chamados/index',compact('chamados'));
    }

    public function atender()
    {
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        $user = \Auth::user();
        $chamados = Chamado::where('status','=','Atríbuido')->
                             where('atribuido_para','=',$user->codpes)
                            ->orderBy('created_at', 'desc')->paginate(10);

        return view('chamados/index',compact('chamados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('chamados.create');
        $categorias = Categoria::all();
        $predios = $this->predios;
        $atendentes = $this->atendentes;
        $complexidades = $this->complexidades;
        return view('chamados/create',compact('categorias','predios','atendentes','complexidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('chamados.create');
        $chamado = new Chamado;
        $chamado = $this->grava($chamado, $request);
        /*
        if(config('app.env') == 'production')
          Mail::send(new ChamadoMail($chamado,$user));
        */

        $request->session()->flash('alert-info', 'Chamado enviado com sucesso');
        return redirect()->route('chamados.show',$chamado->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function show(Chamado $chamado)
    {
        $this->authorize('chamados.view',$chamado);
        return view('chamados/show',compact('chamado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function edit(Chamado $chamado)
    {
        $this->authorize('chamados.view',$chamado);
        $categorias = Categoria::all();
        $predios = $this->predios;
        $atendentes = $this->atendentes;
        $complexidades = $this->complexidades;
        return view('chamados/edit',compact('chamado','categorias','predios','atendentes','complexidades'));
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
        if(Gate::allows('admin') and isset($request->atribuido_para)) {
            $request->validate([
              'categoria_id' => ['required', 'Integer'],
            ]);
        }
        $this->authorize('chamados.view',$chamado);
        $chamado = $this->grava($chamado, $request);

        /*
        if(config('app.env') == 'production')
          Mail::send(new ChamadoMail($chamado,$user));
        */

        $request->session()->flash('alert-info', 'Chamado enviado com sucesso');
        return redirect()->route('chamados.show',$chamado->id);
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
        if($request->status == 'devolver') {
            $chamado->status = 'Triagem';
            $chamado->atribuido_para = null;
            $chamado->categoria_id = null;
            $chamado->triagem_por = null;
            $chamado->atribuido_em = null;
            $chamado->complexidade = null;
            $user = \Auth::user();
            $chamado->user_id = $user->id;
        }
        else {
            $request->validate([
              'telefone'        => ['required'],
              'sala'            => ['required'],
              'predio'          => ['required'],
              'chamado'         => ['required'],
              'patrimonio'      => ['nullable',new PatrimonioRule],
            ]);

            $chamado->chamado = $request->chamado;
            $chamado->patrimonio = $request->patrimonio;
            $chamado->sala = $request->sala;
            $chamado->predio = $request->predio;

            $chamado->categoria_id = $request->categoria_id;
            $chamado->status = 'triagem';

            /* Administradores */
            if(Gate::allows('admin')) {
                /* trocar requisitante */
                if(!is_null($request->codpes)) {
                    $request->validate([
                      'codpes' => ['Integer',new Numeros_USP($request->codpes)],
                    ]);
                    $user = User::where('codpes',$request->codpes)->first();
                    if (is_null($user)) {
                        $user = new User;
                        $user->codpes = $request->codpes;
                    }
                }
                else {
                    $user = \Auth::user();
                }

                /* Atribuir */
                if(!empty($request->atribuido_para)) {
                    $chamado->complexidade = $request->complexidade;
                    $chamado->atribuido_para = $request->atribuido_para;
                    $chamado->triagem_por = \Auth::user()->codpes;
                    $chamado->atribuido_em = Carbon::now();
                    $chamado->status = 'Atribuído';
                }
            } else {
                $user = \Auth::user();
            }

            /* Atualiza telefone da pessoa */
            $user->telefone = $request->telefone;
            $user->save();

            $chamado->user_id = $user->id;
        }
        $chamado->save();
        return $chamado;
    }

    /* Ainda não implementado */
    public function triagemForm(Request $request, Chamado $chamado)
    {
        $this->authorize('admin');
        $atendentes = $this->atendentes;
        $complexidades = $this->complexidades;
        return view('chamados/triagem',compact('chamado'));

    }

    public function triagemStore(Request $request, Chamado $chamado)
    {
        $this->authorize('admin');
        $chamado->complexidade = $request->complexidade;
        $chamado->atribuido_para = $request->atribuido_para;
        $chamado->triagem_por = \Auth::user()->codpes;
        $chamado->atribuido_em = Carbon::now();
        $chamado->status = 'Atribuído';
        $request->session()->flash('alert-info', 'Triagem realizada com sucesso');
        return redirect()->route('chamados.show',$chamado->id);
    }

    public function devolver(Chamado $chamado)
    {
        $this->authorize('atendente');
        return view('chamados/devolver',compact('chamado'));
    }
}
