<?php

namespace App\Http\Controllers;

use App\Chamado;
use App\Categoria;
use App\User;
use Illuminate\Http\Request;
use App\Mail\ChamadoMail;
use Mail;
use Illuminate\Support\Facades\Gate;
use App\Rules\Numeros_USP;
use Carbon\Carbon;

class ChamadoController extends Controller
{

    /* Variáveis globais temporárias */
    private $predios;
    private $atendentes;

    public function __construct()
    {
        $this->middleware('auth');
        $this->predios = collect(['Administração', 'Letras','Filosofia e Ciências Sociais',
                         'História e Geografia','Casa de Cultura Japonesa','Favos','Outro']);

        $this->atendentes = [
            [5385361,'Thiago Gomes Veríssimo'],
            [2517070,'Augusto Cesar Freire Santiago'],
            [3426504,'Ricardo Fontoura'],
            [3426511,'José Roberto Visconde de Souza'],
            [2479057,'Neli Maximino'],
            [2807855,'Gilberto Vargas'],
            [7098274,'Paulo Henrique de Araújo'],
            [4988966,'Lenin Oliveira de Araújo'],
            [2989060,'Normando Peres Silva Moura'],
            [4780673,'Wellington da Silva Moura'],
            [9827360,'Ana Claudia Oze Ferraz']
        ];
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
        $chamados = Chamado::where('user_id','=',$user->id)->paginate(10);
        return view('chamados/index',compact('chamados')); 
    }

    public function todos()
    {
        /* Chamados de quem está logado */
        $this->authorize('admin');
        $chamados = Chamado::paginate(10);
        return view('chamados/index',compact('chamados')); 
    }

    public function triagem()
    {
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        $user = \Auth::user();
        $chamados = Chamado::where('status','=','Triagem')->paginate(10);
        return view('chamados/index',compact('chamados')); 
    }

    public function atender()
    {
        /* Chamados de quem está logado */
        $this->authorize('chamados.viewAny');

        $user = \Auth::user();
        $chamados = Chamado::where('status','=','Atríbuido')->
                             where('atribuido_para','=',$user->codpes)->paginate(10);

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
        return view('chamados/create',compact('categorias','predios','atendentes'));
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

        //Mail::send(new ChamadoMail($chamado,$user));

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
        return view('chamados/edit',compact('chamado','categorias','predios','atendentes'));
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
        $this->authorize('chamados.view',$chamado);
        $chamado = $this->grava($chamado, $request);

        //Mail::send(new ChamadoMail($chamado,$user));

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
        $request->validate([
          'telefone'        => ['required'],
          'sala'            => ['required'],
          'predio'          => ['required'],
          'chamado'         => ['required'],
          'categoria_id'    => ['required', 'Integer'],
        ]);

        $chamado->chamado = $request->chamado;
        $chamado->sala = $request->sala;
        $chamado->predio = $request->predio;
        
        $chamado->categoria_id = $request->categoria_id;
        $chamado->status = 'triagem';

        /* Administradores podem alterar quem fez o chamado */
        if(!empty($request->codpes) && Gate::allows('admin')) {
            $request->validate([
              'codpes'         => ['Integer',new Numeros_USP($request->codpes)],
            ]);
            $user = User::where('codpes',$request->codpes)->first();
            if (is_null($user)) {
                $user = new User;
                $user->codpes = $request->codpes;
            }
        } else {
            $user = \Auth::user(); 
        }

        /* Administradores podem atribuir */
        if(!empty($request->atribuido_para) && Gate::allows('admin')) {
            $chamado->atribuido_para = $request->atribuido_para;
            $chamado->triagem_por = $user->codpes;
            $chamado->atribuido_em = Carbon::now();
            $chamado->status = 'Atribuído';
        }

        /* Atualiza telefone da pessoa */
        $user->telefone = $request->telefone;
        $user->save();

        $chamado->user_id = $user->id;
        $chamado->save();
        return $chamado;
    }
}
