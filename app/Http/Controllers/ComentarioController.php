<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Comentario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function update(Request $request, Comentario $comentario)
    {
        $this->authorize('chamados.update', $comentario->chamado);

        if (!$comentario->podeSerEditadoPor(\Auth::user())) {
            abort(403);
        }

        $request->validate([
            'comentario' => ['required'],
        ]);

        $comentarioOriginal = $comentario->comentario;
        $comentarioEditado = $request->comentario;

        if ($comentarioOriginal == $comentarioEditado) {
            $request->session()->flash('alert-info', 'Nenhuma alteração realizada');
            return redirect("chamados/{$comentario->chamado->id}");
        }

        $comentario->comentario = $comentarioEditado;
        $comentario->save();

        Log::info('Comentário editado', [
            'comentario_id' => $comentario->id,
            'chamado_id' => $comentario->chamado_id,
            'user_id' => \Auth::user()->id,
            'comentario_original' => $comentarioOriginal,
            'comentario_editado' => $comentarioEditado,
        ]);

        Comentario::criarSystem(
            $comentario->chamado,
            'O comentário de ' . e($comentario->user->name) . ' foi editado.'
        );

        $request->session()->flash('alert-info', 'Comentário editado com sucesso');
        return redirect("chamados/{$comentario->chamado->id}");
    }
}
