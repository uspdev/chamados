<div class="list_table_div_form">
  {{ html()->form('POST', $data->url)->open() }}
  {{ html()->hidden('id') }}

    @foreach ($data->model::getFields() as $col)
    @if (empty($col['type']))
    @include('common.list-table-modal-text')

    @elseif ($col['type'] == 'select')
    @include('common.list-table-modal-select')

    @endif
    @endforeach
    <div class="text-right">
        {{-- vamos adicionar o botão do modal quando for o caso --}}
        @yield('form-dismiss-btn')

        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
  {{ html()->form()->close() }}
</div>
