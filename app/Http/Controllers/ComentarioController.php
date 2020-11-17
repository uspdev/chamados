<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Chamado;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\ComentarioMail;
use Mail;
use Illuminate\Support\Facades\Gate;

class ComentarioController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Chamado $chamado)
    {
        $this->authorize('chamados.view',$chamado);

        $request->validate([
          'comentario'  => ['required'],
        ]);
        $user = \Auth::user();

        $comentario = new Comentario;
        $comentario->comentario = $request->comentario;
        $comentario->chamado_id = $chamado->id;
        $comentario->user_id = $user->id;
        $comentario->save();

        if(isset($request->status)) {
            if($request->status == 'Fechado') {
                $comentario->chamado->status = 'Fechado';
                $comentario->chamado->fechado_em = Carbon::now();
            }
            elseif($request->status == 'Triagem') {
                $comentario->chamado->status = 'Triagem';
                $comentario->atribuido_para = null;
                $comentario->chamado->fechado_em = null;
            }
            $comentario->chamado->save();
        }

        if(config('app.env') == 'production')
          Mail::send(new ComentarioMail($comentario,$user));

        $request->session()->flash('alert-info', 'Comentário enviado com sucesso');
        return redirect("chamados/$chamado->id");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function show(Comentario $comentario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function edit(Comentario $comentario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comentario $comentario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comentario $comentario)
    {
        //
    }
}
