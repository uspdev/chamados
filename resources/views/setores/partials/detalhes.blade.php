<a href="#detalhes_{{ \Str::lower($setor->sigla) }}" class="btn btn-sm text-primary" data-toggle="collapse" role="button"><i class="fas fa-bars"></i></a>

<div class="ml-2 collapse" id="detalhes_{{ \Str::lower($setor->sigla) }}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <b>Gerentes</b>@include('setores.partials.adicionar-pessoas')<br>

                        <div class="ml-2">
                            @if(count($setor->users))
                            @foreach($setor->users()->wherePivot('funcao','Gerente')->get() as $user)
                            <div class="form-inline">
                                {{$user->name}} ({{ $user->pivot->funcao }})&nbsp;
                                @include('chamados.show.user-detail')
                                @include('setores.partials.btn-pessoas-delete')
                            </div>
                            @endforeach
                            @else
                            Nenhuma pessoa
                            @endif
                        </div>
                    </div>
                    <br>
                    <div>
                        <b>Filas</b><br>
                        <div class="ml-2">
                            @foreach($setor->filas as $fila)
                            <a href="filas/{{$fila->id}}">{{$fila->nome}} <i class="fas fa-share"></i></a><br>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <b>Pessoas</b><br>
                        <div class="ml-2">
                            @if(count($setor->users))
                            @foreach($setor->users()->wherePivot('funcao','!=','Gerente')->orderBy('name')->get() as $user)
                            <div class="form-inline">
                                {{$user->name}} ({{ $user->pivot->funcao }})&nbsp;
                                @include('chamados.show.user-detail')
                            </div>
                            @endforeach
                            @else
                            Nenhuma pessoa
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('javascript_bottom')
@parent
<script>
    $document.ready(function() {

    })

</script>
@endsection
