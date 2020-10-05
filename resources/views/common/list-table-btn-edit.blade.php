@if ($data->modal ?? false)
{{-- Botão de editar em modal --}}
<button class="btn btn-light text-primary py-0 px-0" data-toggle="tooltip" title="Editar" onclick="edit_form({{ $row->id }})">
    <i class="far fa-edit"></i>
</button>
@else
{{-- editar em outra página --}}
<a href="{{ $data->url }}/{{ $row->id }}/edit" class="btn btn-light text-primary py-0 px-0" data-toggle="tooltip" title="Editar">
    <i class="fas fa-edit"></i>
</a>
@endif
