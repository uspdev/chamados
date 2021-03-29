<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Chamado;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ArquivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'arquivo.*' => 'required|mimes:jpeg,jpg,png,pdf|max:' . config('chamados.upload_max_filesize'),
            'chamado_id' => 'required|integer|exists:chamados,id',
        ]);

        $chamado = Chamado::find($request->chamado_id);
        $this->authorize('chamados.update', $chamado);

        $fotos_nome = [];
        $arquivos_nome = [];
        foreach ($request->arquivo as $arq) {
            $arquivo = new Arquivo;
            $arquivo->chamado_id = $chamado->id;
            $arquivo->user_id = \Auth::user()->id;
            $arquivo->nome_original = $arq->getClientOriginalName();
            $arquivo->caminho = $arq->store('./arquivos/' . $chamado->created_at->year);
            $arquivo->mimeType = $arq->getClientMimeType();
            $arquivo->save();
            if (preg_match('/jpeg|jpg|png/', $arquivo->mimeType)) {
                array_push($fotos_nome, $arquivo->nome_original);
            } else {
                array_push($arquivos_nome, $arquivo->nome_original);
            }
        }

        # arquivos
        if (count($arquivos_nome) == 1) {
            Comentario::criarSystem($chamado, 'O arquivo ' . $arquivos_nome[0] . ' foi adicionado.');
        }
        if (count($arquivos_nome) > 1) {
            Comentario::criarSystem($chamado, 'Os arquivos ' . implode(', ', $arquivos_nome) . ' foram adicionados.');
        }

        # imagens
        if (count($fotos_nome) == 1) {
            Comentario::criarSystem($chamado, 'A imagem ' . $fotos_nome[0] . ' foi adicionada');
        }
        if (count($fotos_nome) > 1) {
            Comentario::criarSystem($chamado, 'As imagens ' . implode(", ", $fotos_nome) . ' foram adicionadas');
        }

        $request->session()->flash('alert-success', 'Arquivo(s) adicionado(s) com sucesso!');
        return Redirect::to(URL::previous() . "#card_arquivos");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function show(Arquivo $arquivo)
    {
        $this->authorize('chamados.view', $arquivo->chamado);

        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Arquivo $arquivo)
    {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arquivo $arquivo)
    {
        $this->authorize('chamados.update', $arquivo->chamado);

        $request->validate(
            ['nome_arquivo' => 'required'],
            ['nome_arquivo.required' => 'O nome do arquivo é obrigatório!']
        );
        $nome_antigo = $arquivo->nome_original;
        $arquivo->nome_original = $request->nome_arquivo . '.pdf';
        $arquivo->update();

        Comentario::criarSystem(
            $arquivo->chamado,
            'O arquivo ' . $nome_antigo . ' foi renomeado para ' . $arquivo->nome_original . '.'
        );

        request()->session()->flash('alert-success', 'Arquivo renomeado com sucesso!');
        return Redirect::to(URL::previous() . "#card_arquivos");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Arquivo $arquivo)
    {
        $this->authorize('chamados.update', $arquivo->chamado);

        $arquivo->delete();
        $comentario = preg_match('/jpeg|jpg|png/', $arquivo->mimeType)
        ? 'A imagem ' . $arquivo->nome_original . ' foi excluída'
        : 'O arquivo ' . $arquivo->nome_original . ' foi excluído';
        Comentario::criarSystem($arquivo->chamado, $comentario);

        $request->session()->flash('alert-success', $comentario . ' com sucesso!');
        return Redirect::to(URL::previous() . "#card_arquivos");
    }
}
