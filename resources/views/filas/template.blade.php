@extends('master')
@section('content')
    @parent

    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['route' => ['filas.storetemplate', $fila->id], 'id' => 'template-form', 'method' => 'POST']) !!}
            {!! Form::token() !!}
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="card-title form-inline my-0">
                        Filas <i class="fas fa-angle-right mx-2"></i> <a
                            href="filas/{{ $fila->id }}">({{ $fila->setor->sigla }}) {{ $fila->nome }} </a> &nbsp; |
                        &nbsp;
                        @include('filas.partials.template-btn-novocampo-modal') &nbsp; | &nbsp;
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    @if ($template)
                                        <div id="template-header" class="form-row">
                                            <div class="col"><strong>Campo</strong></div>
                                            @foreach ($fila->getTemplateFields() as $field)
                                                <div class="col"><strong>{{ ucfirst($field) }}</strong></div>
                                            @endforeach
                                            <div class="col"></div>
                                        </div>
                                        @foreach ($template as $tkey => $tvalue)
                                            <div class="form-row mt-2">
                                                <div class="col">{{ $tkey }}</div>
                                                @foreach ($fila->getTemplateFields() as $field)
                                                    <div class="col">
                                                        @isset($tvalue[$field])
                                                            @switch($field)
                                                                @case('type')
                                                                <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                                                    <option value='text' {{ $tvalue[$field] == 'text' ? 'selected' : '' }}>Texto</option>
                                                                    <option value='select' {{ $tvalue[$field] == 'select' ? 'selected' : '' }}>Caixa de Seleção</option>
                                                                    <option value='date' {{ $tvalue[$field] == 'date' ? 'selected' : '' }}>Data</option>
                                                                    <option value='number' {{ $tvalue[$field] == 'number' ? 'selected' : '' }}>Número</option>
                                                                </select>
                                                                @break
                                                                @case('validate')
                                                                <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                                                    <option value='' {{ $tvalue[$field] == '' ? 'selected' : '' }}>Sem validação</option>
                                                                    <option value='required' {{ $tvalue[$field] == 'required' ? 'selected' : '' }}>Obrigatório</option>
                                                                    <option value='required|integer' {{ $tvalue[$field] == 'required|integer' ? 'selected' : '' }}>Obrigatório - Somente números</option>
                                                                </select>
                                                                @break
                                                                @case('can')
                                                                <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                                                    <option value='' {{ $tvalue[$field] == '' ? 'selected' : '' }}>Exibido para todos</option>
                                                                    <option value='atendente' {{ $tvalue[$field] == 'atendente' ? 'selected' : '' }}>Somente Atendentes</option>
                                                                </select>
                                                                @break
                                                                @default
                                                                <input class="form-control" name="template[{{ $tkey }}][{{ $field }}]" value="{{ is_array($tvalue[$field]) ? json_encode($tvalue[$field], JSON_UNESCAPED_UNICODE) : $tvalue[$field] ?? '' }}">
                                                            @endswitch
                                                        @endisset
                                                        @empty($tvalue[$field])
                                                            @switch($field)
                                                                @case('validate')
                                                                <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                                                    <option value=''>Sem validação</option>
                                                                    <option value='required'>Obrigatório</option>
                                                                    <option value='required|integer' }}>Obrigatório - Somente números</option>
                                                                </select>
                                                                @break
                                                                @case('can')
                                                                <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                                                    <option value=''>Exibido para todos</option>
                                                                    <option value='atendente'>Somente Atendentes</option>
                                                                </select>
                                                                @break
                                                                @default
                                                                <input class="form-control" name="template[{{ $tkey }}][{{ $field }}]" value="">
                                                            @endswitch
                                                        @endempty
                                                    </div>
                                                @endforeach
                                                <div class="col">
                                                    <button class="btn btn-danger" type="button" onclick="apaga_campo(this)">Apagar</button>
                                                    <button class="btn btn-success" type="button" onclick="move(this, 1)">&#8679;</button>
                                                    <button class="btn btn-success" type="button" onclick="move(this, 0)">&#8681;</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        Não existe formulário para essa fila.
                                    @endif
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascripts_bottom')
    @parent
    <script>
        function apaga_campo(r) {
            if (confirm('Tem certeza que deseja deletar?')) {
                var row = r.parentNode.parentNode;
                row.remove();
                var form = document.getElementById("template-form");
                form.requestSubmit();
            }
        }

        function move(r, up) {
            var head = "template-header";
            var tail = "template-new";
            var form = document.getElementById("template-form");
            var row = r.parentNode.parentNode;
            if (up) {
                var sibling = row.previousElementSibling;
                if (sibling.id != head) {
                    row.parentNode.insertBefore(row, sibling);
                    form.requestSubmit();
                }
            } else {
                var sibling = row.nextElementSibling;
                if (sibling.id != tail) {
                    row.parentNode.insertBefore(row, sibling.nextSibling);
                    form.requestSubmit();
                }
            }
        }

    </script>
@endsection
