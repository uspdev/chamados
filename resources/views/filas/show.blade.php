@extends('master')

<?php #dd($data) ?>

@section('content')
@parent

<div class="row">
    <div class="col-md-12">
        <span class="h4 mt-2">Detalhes da fila</span><br>

        Setor: {{ $data->row['setor']->sigla }}<br>
        Nome: {{ $data->row['nome'] }}<br>
        Descrição: {{ $data->row['descricao'] }}<br>
        <br>
        Pessoas:<br>
        @foreach($data->row['user'] as $user)
        {{ $user->name }} - {{ $user->pivot->funcao }}<br>
        @endforeach


        <?php #print_r($data->row->getAttributes()); ?>

    </div>
</div>

@endsection
