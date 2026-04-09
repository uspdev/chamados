<div style="background-color: AliceBlue; padding: 5px;">
    <b>Status:</b> {{ $chamado->status }}<br>
    <b>Autor:</b> {{ $autor->name }}<br>
    Chamado no. {{ $chamado->nro }}/{{ $chamado->created_at->format('Y') }}
    para ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}<br>
    Assunto: {{ $chamado->assunto }}<br>
    Descrição: {!! $chamado->descricao !!}<br>
    @php
        $template = json_decode($chamado->fila->template ?? '{}');
        $extras = json_decode($chamado->extras ?? '{}');
    @endphp
    @if ($template && $extras)
        <b>Campos extras:</b><br>
        @foreach ($template as $field => $val)
            @if (isset($extras->$field) && $extras->$field !== '')
                @php
                    $extraValue = $extras->$field;
                    if (($val->type ?? null) === 'select') {
                        $keyOption = (string) $extraValue;
                        $extraValue = $val->value->$keyOption ?? $extraValue;
                    } elseif (($val->type ?? null) === 'date') {
                        try {
                            $dateValue = Carbon\Carbon::parse($extraValue);
                            $extraValue = ucfirst($dateValue->locale('pt_BR')->translatedFormat('l')) . ', ' . $dateValue->format('d/m/Y');
                        } catch (\Exception $e) {
                        }
                    }
                @endphp
                <b>{{ $val->label ?? $field }}:</b> {{ $extraValue }}<br>
            @endif
        @endforeach
    @endif
    Link direto: <a href="{{config('app.url')}}/chamados/{{$chamado->id}}">{{config('app.url')}}/chamados/{{$chamado->id}}</a><br>
</div>
