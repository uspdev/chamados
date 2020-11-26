{{-- Mostra o conteúdo de um setor --}}
<a name="{{ \Str::lower($setor->sigla) }}" class="font-weight-bold">{{$setor->sigla}} - {{$setor->nome}}</a>
@includewhen($user->is_admin,'setores.partials.edit-modal')
@includewhen($user->is_admin, 'setores.partials.add-modal')
<span class="badge badge-primary">{{ count($setor->users) }} pessoas</span>
<span class="badge badge-success">{{ count($setor->filas) }} filas</span>
@include('setores.partials.detalhes')
