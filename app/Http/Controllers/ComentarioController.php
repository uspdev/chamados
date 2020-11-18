<?php

namespace App\Http\Controllers;

use App\Mail\ComentarioMail;
use App\Models\Chamado;
use App\Models\Comentario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

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
        $this->authorize('chamados.view', $chamado);

        $request->validate([
            'comentario' => ['required'],
        ]);
        $user = \Auth::user();

        $comentario = new Comentario;
        $comentario->comentario = $request->comentario;
        $comentario->chamado_id = $chamado->id;
        $comentario->user_id = $user->id;
        $comentario->tipo = 'user';
        $comentario->save();

        if (isset($request->status)) {
            if ($request->status == 'Fechado') {
                $chamado->status = 'Fechado';
                $chamado->fechado_em = Carbon::now();
            } elseif ($request->status == 'Triagem') {
                $chamado->status = 'Triagem';
                $chamado->fechado_em = null;
            }
        }
        $chamado->save();

        #if(config('app.env') == 'production')
          Mail::send(new ComentarioMail($comentario));

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
