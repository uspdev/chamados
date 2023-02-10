  <div class="card border-warning my-3 collapse" id="chamadosPendentes" style="border: 3px solid;">
    <div class="card-header bg-warning" role="button" data-toggle="collapse" data-target="#chamadosPendentes">
      Chamados pendentes de anos anteriores
      <span class="badge badge-pill badge-danger">{{ count($pendentes) }}</span>
    </div>
    <div class="card-body">

        <table class="table table-striped tabela-chamados display responsive" style="width:100%">
        <thead>
          <tr>
            <th>Nro</th>
            <th></th>
            <th>Assunto</th>
            <th>Atendente</th>
            <th>Autor</th>
            <th>Fila</th>
            <th class="text-right">Aberto em</th>
            <th class="text-right">Atualização</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($pendentes as $chamado)
            <tr>
              <td> {{ $chamado->nro }}</td>
              <td>
                @include('chamados.partials.status-small')
              </td>
              <td>
                <a href="chamados/{{ $chamado->id }}" style="{{ $chamado->formatarFechado() }}">
                  {!! $chamado->assunto !!}
                </a>
                @includeWhen($chamado->patrimonios->isNotEmpty(), 'patrimonios.partials.patrimonio-badge')
                @includeWhen($chamado->arquivos->isNotEmpty(), 'chamados.partials.arquivo-badge')
                @include('chamados.partials.status-muted')
              </td>
              <td>
                @if ($user = $chamado->pessoas('Atendente'))
                  {{ Str::limit($user->name ?? '-', 20) }}
                  @include('chamados.show.user-detail', ['user' => $user])
                  {{-- @include('chamados.partials.user-', ['user' => $user]) --}}
                @else
                  -
                @endif
              </td>
              <td>
                @if ($user = $chamado->pessoas('Autor'))
                  {{ Str::limit($user->name ?? '', 20) }}
                  @include('chamados.show.user-detail', ['user' => $user])
                @else
                  Sem autor !!
                @endif
              </td>
              <td>
                ({{ $chamado->fila->setor->sigla }})
                {{ $chamado->fila->nome }}
              </td>
              <td class="text-right">
                <span class="d-none">{{ $chamado->created_at }}</span>
                {{ $chamado->created_at->format('d/m/Y') }}
              </td>
              <td class="text-right">
                <span class="d-none">{{ $chamado->atualizadoEm }}</span>
                {{ $chamado->atualizadoEm->format('d/m/Y') }}
              </td>
            </tr>
          @endforeach

        </tbody>
      </table>

    </div>
  </div>
