<a name="card_notificacoes"></a>
<div class="card bg-light mb-3">
  <div class="card-header">
    <div class="">Notificações</div>
  </div>
  <div class="card-body">
    <form method="POST" action="users/57">
      @method('PUT')
      @csrf
      <table class="table table-hover table-bordered table-sm ml-2">
        <tr>
          <th>Objeto</th>
          {{-- https://stackoverflow.com/questions/26983301/how-to-make-a-table-column-be-a-minimum-width --}}
          <th style="width:1%; white-space: nowrap;">Notificar por email</th>
        </tr>
        @forelse($user->filas as $fila)
          <tr>
            <td>
              Fila <a href="filas/{{ $fila->id }}">({{ $fila->setor->sigla }}) {{ $fila->nome }}</a>
              - {{ $fila->descricao }} ({{ $fila->pivot->funcao }})
            </td>
            <td>
              <input type="radio" name="emailNotification[filas][{{ $fila->id }}]" value="1"
                {{ data_get($user->config, 'notifications.email.filas.' . $fila->id, true) ? 'checked' : '' }}>
              sim
              &nbsp;
              <input type="radio" name="emailNotification[filas][{{ $fila->id }}]" value="0"
                {{ data_get($user->config, 'notifications.email.filas.' . $fila->id, true) ? '' : 'checked' }}>
              não
            </td>
          </tr>
        @empty
          Sem filas
        @endforelse
        <tr>
          <td>Atualizações nos meus chamados</td>
          <td>sempre</td>
        </tr>
        <tr>
          <td>Atualizações nos chamados que observo</td>
          <td>
            <input type="radio" name="emailNotification[observador]" value="1"
              {{ data_get($user->config, 'notifications.email.observador', true) ? 'checked' : '' }}>
            sim
            &nbsp;
            <input type="radio" name="emailNotification[observador]" value="0"
              {{ data_get($user->config, 'notifications.email.observador', true) ? '' : 'checked' }}>
            não
          </td>
        </tr>
        <tr>
          <td>Atualizações nos meus atendimentos</td>
          <td>sempre</td>
        </tr>
      </table>
      <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
    </form>
  </div>
</div>
