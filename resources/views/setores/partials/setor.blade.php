{{-- Mostra o conteúdo de um setor --}}
<a name="{{ \Str::lower($setor->sigla) }}" class="font-weight-bold">{{$setor->sigla}} - {{$setor->nome}}</a>
@include('setores.partials.edit-modal')
@include('setores.partials.add-modal')
<span class="badge badge-primary">{{ count($setor->users) }} pessoas</span>
<span class="badge badge-success">{{ count($setor->filas) }} filas</span>
{{-- <span class="badge badge-secondary">{{count($setor->users)}} pessoas</span> --}}
@include('setores.partials.responsavel')

