{{-- Mostra o icone do usuário e o respectivo card ao clicar --}}
<?php
$user_detail_id = 'user-detail-' . Str::random(5);
$usar_foto = config('chamados.usar_foto');
$setor_vinculo = $user->setores()->wherePivot('funcao', '!=', 'Gerente')->first();
$setor_sigla = $setor_vinculo->sigla ?? 'sem setor';
$setor_funcao = $setor_vinculo->pivot->funcao ?? '';
$setor_local = '';

$vinculos = array_values(array_filter(
  \Uspdev\Replicado\Pessoa::vinculos($user->codpes),
  function ($vinculo) {
    return trim(explode('-', $vinculo)[0]) !== 'Servidor Designado'; # Exclui o vinculo com o tipo 'Servidor Designado'
  }
));
$ultimo_vinculo = !empty($vinculos) ? end($vinculos) : null; # Nos testes, trazer o último vínculo funcionou bem
$tipvinext = $ultimo_vinculo
  ? trim(explode('-', $ultimo_vinculo)[0])
  : $setor_funcao; # Se não houver vínculo, usa a funcao do setor

if (config('chamados.usar_replicado') && $setor_vinculo && !empty($setor_vinculo->cod_set_replicado)) {
  try {
    $setor_replicado = \Uspdev\Replicado\Estrutura::dump($setor_vinculo->cod_set_replicado);
    if (!empty($setor_replicado['codlocusp'])) {
      $local = \Uspdev\Replicado\Estrutura::obterLocal($setor_replicado['codlocusp']);
      $setor_local = $local['nomlocusp'] ?? '';
    }
  } catch (\Throwable $e) {
    $setor_local = '';
  }
}

if ($usar_foto) {
  $foto_base64 = \Uspdev\Wsfoto::obter((int) $user->codpes);
  $foto_src = 'data:image/png;base64,' . $foto_base64;
}
?>

<a class="btn btn-sm btn-light text-primary py-0" data-toggle="collapse" href="#{{ $user_detail_id }}" role="button"
  aria-expanded="false" aria-controls="collapseExample">
  <i class="fas fa-user"></i>
</a>

<div class="collapse" id="{{ $user_detail_id }}">
  <div class="card card-body">
    <span class="text-dark">
      @if ($usar_foto)
        <div class="mb-2">
          <img src="{{ $foto_src }}" alt="Foto de {{ $user->name }}" class="img-thumbnail" style="max-width: 120px;">
        </div>
      @endif
      <div>
        {{ $user->codpes }} - {{ $user->name }}
      </div>
      <div>
        Setor: {{ $setor_sigla }} - {{ $tipvinext }}
      </div>
      @if ($setor_local)
        <div>
          Local do setor: {{ $setor_local }}
        </div>
      @endif
      <div>
        <i class="fas fa-envelope-square mr-2"></i> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
      </div>
      <div>
        <i class="fas fa-phone mr-2"></i> {{ $user->telefone ?? 'não disponível' }}
      </div>
      @if (config('chamados.sistemaPessoas'))
        <div>
          <a href="{{ config('chamados.sistemaPessoas') }}/pessoas/{{ $user->codpes }}" target="_PESSOAS">
            Ver mais em Pessoas <i class="fas fa-share"></i>
          </a>
        </div>
      @endif
    </span>
  </div>
</div>
