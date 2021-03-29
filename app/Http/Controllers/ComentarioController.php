<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Comentario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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
            } elseif ($request->status == 'Reabrir') {
                # ao reabrir, se houver atendente, volta para "Em Andamento" se não volta para "Triagem"
                if ($chamado->users()->wherePivot('papel', 'Atendente')->get()) {
                    $chamado->status = 'Em Andamento';
                } else {
                    $chamado->status = 'Triagem';
                }
                $chamado->fechado_em = null;
            }
        }
        $chamado->save();

        $request->session()->flash('alert-info', 'Comentário enviado com sucesso');
        return redirect("chamados/$chamado->id");
    }
}
