<span class="font-weight-bold">Formulário</span><br>
<div class="ml-2">
    @foreach ($template as $field => $val)
        @if (empty($val->can))
            <span class="text-muted">{{ $val->label }}:</span>
            @switch($val->type)
                @case('date')
                <span>{{ Carbon\Carbon::parse($extras->$field)->format('d/m/Y') ?? '' }}</span>
                @break
                @default
                @php
                    $keyOption = $extras ? $extras->$field : null;
                @endphp
                <span>
                @if ($val->type == 'select')
                    {{ $val->value->$keyOption ?? '' }}
                @else
                    {{ $extras->$field ?? '' }}
                @endif
                </span>
            @endswitch
            <br>
        @endif

        @if (!empty($val->can) && $val->can == 'admin')
            Este campo so deve aparecer para admin
            <br>
        @endif

    @endforeach
</div>
<br>
