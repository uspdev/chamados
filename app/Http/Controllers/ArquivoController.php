<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivoController extends Controller
{
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
    public function store(Request $request)
    {
        $request->validate([
            'arquivo.*' => 'required|mimes:jpeg,jpg,png,pdf|max:'.config('chamados.upload_max_filesize'),
            'chamado_id' => 'required|integer|exists:chamados,id',
        ]);
        $fotos_nome = [];
        $chamado_id = 0;
        foreach ($request->arquivo as $arq) {
            $arquivo = new Arquivo;
            $arquivo->chamado_id = $request->chamado_id;
            $chamado_id = $arquivo->chamado_id;
            $arquivo->user_id = \Auth::user()->id;
            $arquivo->nome_original = $arq->getClientOriginalName();
            $arquivo->caminho = $arq->store('./arquivos/' . date("Y"));
            $arquivo->mimeType = $arq->getClientMimeType();
            $arquivo->save();
            if (preg_match('/jpeg|jpg|png/', $arquivo->mimeType)) {
                array_push($fotos_nome, $arquivo->nome_original);
            } else {
                Comentario::criar([
                    'user_id' => \Auth::user()->id,
                    'chamado_id' => $arquivo->chamado_id,
                    'comentario' => 'O arquivo ' . $arquivo->nome_original . ' foi adicionado.',
                    'tipo' => 'system',
                ]);
            }
        }
        if (count($fotos_nome) > 0) {
            $comentario = (count($fotos_nome) > 1)
            ? 'As imagens ' . implode(", ", $fotos_nome) . ' foram adicionadas'
            : 'A imagem ' . $fotos_nome[0] . ' foi adicionada';

            Comentario::criar([
                'user_id' => \Auth::user()->id,
                'chamado_id' => $arquivo->chamado_id,
                'comentario' => $comentario,
                'tipo' => 'system',
            ]);
        }
        $request->session()->flash('alert-success', 'Arquivo(s) adicionado(s) com sucesso!');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function show(Arquivo $arquivo)
    {
        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Arquivo $arquivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arquivo $arquivo)
    {
        $request->validate([
            'nome_arquivo' => 'required',
        ],
            [
                'nome_arquivo.required' => 'O nome do arquivo é obrigatório!',
            ]);
        $nome_antigo = $arquivo->nome_original;
        $arquivo->nome_original = $request->nome_arquivo;
        if (substr($arquivo->nome_original, -4) != '.pdf') {
            $arquivo->nome_original .= '.pdf';
        }
        $arquivo->update();
        Comentario::criar([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $arquivo->chamado_id,
            'comentario' => 'O arquivo ' . $nome_antigo . ' foi renomeado para ' . $request->nome_arquivo . '.',
            'tipo' => 'system',
        ]);
        request()->session()->flash('alert-success', 'Arquivo renomeado com sucesso!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Arquivo $arquivo)
    {
        $arquivo->delete();
        $comentario = preg_match('/jpeg|jpg|png/', $arquivo->mimeType)
        ? 'A imagem ' . $arquivo->nome_original . ' foi excluída'
        : 'O arquivo ' . $arquivo->nome_original . ' foi excluído';
        Comentario::criar([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $arquivo->chamado_id,
            'comentario' => $comentario,
            'tipo' => 'system',
        ]);
        $request->session()->flash('alert-success', $comentario . ' com sucesso!');

        return back();
    }
}
