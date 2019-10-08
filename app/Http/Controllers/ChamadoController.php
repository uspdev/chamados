<?php

namespace App\Http\Controllers;

use App\Chamado;
use App\Categoria;
use Illuminate\Http\Request;
use App\Mail\ChamadoMail;
use Mail;

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
    public function abertos()
    {
        //$this->authorize('admin'.$);
        $chamados = Chamado::where('status', 'aberto')->get();
        return view('chamados/abertos',compact('chamados')); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        /*
        $this->authorize('sites.view',$site);
        return view('chamados/index',compact('site')); 
        */
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
        return view('chamados/create',compact('categorias'));
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

        $request->validate([
          'telefone'         => ['required'],
          'chamado'         => ['required'],
          'categoria_id'    => ['required', 'Integer'],
        ]);

        /* Atualiza telefone */
        $user = \Auth::user();
        $user->telefone = $request->telefone;
        $user->save();

        $chamado = new Chamado;
        $chamado->chamado = $request->chamado;
        
        $chamado->categoria_id = $request->categoria_id;
        $chamado->status = 'triagem';
        $chamado->user_id = $user->id;
        $chamado->save();

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
        //
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
        //
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
}
