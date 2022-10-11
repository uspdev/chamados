@extends('master')

@section('title', '#' . $chamado->nro . '/' . $chamado->created_at->year)

@section('styles')
  @parent
  <style>
    #card-principal {
      border: 1px solid blue;
    }

    .bg-principal {
      background-color: LightBlue;
      border-top: 3px solid blue;
    }

    .disable-links {
      pointer-events: none;
    }

  </style>
@endsection

@section('content')
  @parent

  <div class="card bg-light mb-3" id="card-principal">
    <div class="card-header bg-principal form-inline">
      <div class="mr-auto">
        <span class="text-muted">Chamado no.</span> {{ $chamado->nro }}/{{ $chamado->created_at->year }}
        <span class="text-muted">para</span> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}
        @includeWhen($chamado->patrimonios->isNotEmpty(), 'patrimonios.partials.patrimonio-badge')
        @includeWhen($chamado->arquivos->isNotEmpty(), 'chamados.partials.arquivo-badge')
        @include('chamados.partials.status')
        @include('chamados.partials.instrucoes-da-fila-badge')
        @include('chamados.partials.patrimonio-pendente-badge')
        <div class="small ml-3">{{ $chamado->fila->descricao }}</div>
      </div>

      {{-- <div class="text-right"><button class="btn btn-sm btn-warning">Trocar para perfil atendente</button></div> --}}
    </div>
    <div class="card-body">

      @include('chamados.partials.instrucoes-da-fila', ['hide' => $chamado->status == 'Fechado'])

      <div class="row">
        <div class="col-md-8">
          <span class="ml-2 float-right">
            @includewhen(Gate::check('update', $chamado), 'chamados.show.editar-formulario-btn')
          </span>
          {{-- Informações principais --}}
          @include('chamados.show.principal')
        </div>
        <div class="col-md-4">
          {{-- formulario --}}
          @includewhen(!empty($template), 'chamados.show.dados-formulario')
        </div>
      </div>

      {{-- card do atendente --}}
      @if (Gate::check('filas.atendente', $chamado->fila))
        <div class="row">
          <div class="col-md-12">
            @includewhen(
                Gate::check('perfilatendente') || Gate::check('perfiladmin'),
                'chamados.show.card-atendente'
            )
          </div>
        </div>
      @endif

      {{-- demais cards --}}
      <div class="row">
        <div class="col-md-8">
          @include('chamados.show.card-comentarios-user')
        </div>
        <div class="col-md-4">
          @include('chamados.show.card-arquivos')
          @include('chamados.show.card-pessoas')
          @include('chamados.show.card-vinculados')
          @include('chamados.show.card-patrimonios')
          @include('chamados.show.card-comentarios-system')
        </div>
      </div>
    </div>
  </div>
@endsection
