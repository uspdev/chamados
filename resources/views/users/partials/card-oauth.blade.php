<a name="card_notificacoes"></a>
<div class="card bg-light mb-3">
  <div class="card-header">
    <div>
      Oauth <span class="text-danger"><i class="fas fa-user-lock"></i></span>
      @if ($oauth['data'])
        (Atualizado em {{ gmdate('d/m/Y H:i:s', $oauth['time']) }})
      @endif
    </div>
  </div>
  <div class="card-body">
    @if ($oauth['data'])
      <pre>{{ $oauth['data'] }}</pre>
    @else
      sem dados
    @endif
  </div>
</div>
