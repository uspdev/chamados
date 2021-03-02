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
    <div class="card-header bg-principal">
      <span class="text-muted">Chamado no.</span> {{ $chamado->nro }}/{{ $chamado->created_at->year }}
      <span class="text-muted">para</span> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}
      @include('chamados.partials.status')
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-8">
              <span class="ml-2 float-right">
                @includewhen($chamado->status != 'Fechado','chamados.show.editar-formulario-btn')
              </span>
              {{-- Informações principais --}}
              @include('chamados.show.principal')
            </div>
            <div class="col-md-4">
              {{-- formulario --}}
              @includewhen(!empty($template),'chamados.show.dados-formulario')
            </div>
          </div>
        </div>
      </div>
      {{-- card do atendente --}}
      <div class="row">
        <div class="col-md-12">
          @includewhen(Gate::check('perfilatendente') || Gate::check('perfiladmin'),'chamados.show.card-atendente')
        </div>
      </div>
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
