<td> #{{ $chamado->id }} </a></td>
<td>@isset($chamado->fila->nome)
        {{ $chamado->fila->nome }}</li>
    @endisset
</td>
<td> <span style="color:red;"> {{ $chamado->status }} </span> </a></td>
<td> {{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }} </a></td>
<td> <a href="chamados/{{$chamado->id}}" title="Ver Chamado" class="pr-2"> <i class="fas fa-eye" style="font-size:24px"></i> {!! $chamado->chamado !!} </a> </a></td>
<!-- <td> {{ $chamado->predio }} </a></td>
<td> {{ $chamado->sala }} </a></td> -->
<td>
</td>

<ul style="list-style-type: none;">
       
        

    <li><b>status: </b> <span style="color:red;"> {{ $chamado->status }} </span> </li>
    @if($chamado->status == 'Atribuído')
        @if(config('chamados.usar_replicado') == 'true')
            @isset($chamado->triagem_por)
                <li><b>triagem por</b>: {{ \Uspdev\Replicado\Pessoa::dump($chamado->triagem_por)['nompes'] ?? '' }}</li>
            @endisset
            @isset($chamado->atribuido_para)
                <li><b>atribuído para</b>: {{ \Uspdev\Replicado\Pessoa::dump($chamado->atribuido_para)['nompes'] ?? '' }}</li>
            @endisset
        @endif

        @if(!is_null($chamado->fechado_em))
            <li><b>fechado em</b>: {{ Carbon\Carbon::parse($chamado->fechado_em)->format('d/m/Y H:i') }}</li>
        @endif

        @if($chamado->status == 'Atribuído')
            @if(config('chamados.usar_replicado') == 'true')
            {{ \Uspdev\Replicado\Bempatrimoniado::dump(trim($patrimonio))['epfmarpat'] ?? 'não encontrado'}}
            {{ \Uspdev\Replicado\Bempatrimoniado::dump(trim($patrimonio))['modpat'] ?? ''}}
            @endif
            <li><b>complexidade</b>: {{ $chamado->complexidade }}</li>
        @endif
</ul>
