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

        $comentario = new Comentario;

        if (isset($request->status)) {
            if ($request->status == 'Fechado') {
                $chamado->status = 'Fechado';
                $chamado->fechado_em = Carbon::now();
                $comentario->comentario = 'O chamado foi fechado.' . PHP_EOL;

            } elseif ($request->status == 'Reabrir') {
                # ao reabrir, se houver atendente, volta para "Em Andamento" se não volta para "Triagem"
                if ($chamado->users()->wherePivot('papel', 'Atendente')->get()) {
                    $chamado->status = 'Em Andamento';
                } else {
                    $chamado->status = 'Triagem';
                }
                $chamado->fechado_em = null;
                $comentario->comentario = 'O chamado foi reaberto.' . PHP_EOL;
            }
            $chamado->save();
        }

        $comentario->comentario .= $request->comentario;
        $comentario->chamado_id = $chamado->id;
        $comentario->user_id = \Auth::user()->id;
        $comentario->tipo = 'user';
        $comentario->save();

        $request->session()->flash('alert-info', 'Comentário enviado com sucesso');
        return redirect("chamados/$chamado->id");
    }
}
