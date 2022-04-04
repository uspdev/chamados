@section('styles')
  @parent

  <style>
    .lista-visibilidade li:hover {
      background-color: SeaShell;
    }

    #card-fila-visibilidade {
      border: 1px solid tan;
      border-top: 3px solid tan;
    }

  </style>
@endsection

<div class="card mb-3" id="card-fila-visibilidade">
  <form method="post" action="filas/{{ $fila->id }}" class="visibilidade-show-save">
    @method('put')
    @csrf
    <input type="hidden" name="card" value="visibilidade">

    <div class="card-header">
      <i class="fas fa-eye"></i> Visibilidade
      @include('ajuda.filas.config-visibilidade')

      <button type="submit" class="btn btn-sm btn-light text-primary d-none visibilidade-salvar-btn">
        <i class="fas fa-save"></i> Salvar
      </button>
    </div>
    <div class="card-body">

      <div class="mt-3">
        <span class="text-muted mr-2">Adicionar pessoas por categoria</span>
        <div class="ml-3">
          <x-checkbox name="config[visibilidade][alunos]" label="alunos" disabled
            checked="{{ $fila->config->visibilidade->alunos ? true : false }}" class="form-check-inline" />

          <x-checkbox name="config[visibilidade][servidores]" label="servidores"
            checked="{{ $fila->config->visibilidade->servidores ? true : false }}" class="form-check-inline" />

          <x-checkbox name="config[visibilidade][todos]" label="todos (USP)"
            checked="{{ $fila->config->visibilidade->todos ? true : false }}" class="form-check-inline" />
        </div>
      </div>

      <div class="mt-3">
        <span class="text-muted">Adicionar pessoas por função no sistema</span>
        <div class="ml-3">
          <x-checkbox name="config[visibilidade][setor_gerentes]" label="gerentes de setor" class="form-check-inline"
            checked="{{ $fila->config->visibilidade->setor_gerentes ? true : false }}" />

          <x-checkbox name="config[visibilidade][fila_gerentes]" label="gerentes de fila" class="form-check-inline"
            checked="{{ $fila->config->visibilidade->fila_gerentes ? true : false }}" />
        </div>
      </div>

      <div class="mt-3">
        <span class="text-muted">Adicionar pessoas por setor</span>
        <div class="ml-3">
          <x-checkbox name="config[visibilidade][setores]" value="interno" label="{{ $fila->setor->sigla }}"
            class="form-check-inline"
            checked="{{ $fila->config->visibilidade->setores == 'interno' ? true : false }}" />
        </div>
      </div>

      <div class="mt-3">
        <span class='text-muted'>Lista de pessoas que podem abrir chamado nessa fila</span>
        <span class='badge badge-pill badge-primary'>{{ $fila->contarCustomCodpes() }}</span>
        @include('ajuda.filas.config-visibilidade-customcodpes')
        <div class="row">
          <div class="col-md-3">

            <x-textarea class="mt-2 visibilidade-show-save custom-codpes-textarea" rows="9" autoLine="0" label=""
              name="settings[visibilidade][customCodpes]"
              value="{{ $fila->settings()->get('visibilidade.customCodpes') }}" />

          </div>
          <div class="col-md-9">

            <div class="overflow-auto custom-codpes-list" style="height: 215px; margin-top:15px">
              @if ($fila->contarCustomCodpes() != 0)
                @foreach (explode(PHP_EOL, $fila->settings()->get('visibilidade.customCodpes')) as $codpes)
                  {{ $codpes }} - {{ \Uspdev\Replicado\Pessoa::retornarNome($codpes) }}<br />
                @endforeach
              @endif
            </div>

          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-sm btn-primary d-none visibilidade-salvar-btn">
        <i class="fas fa-save"></i> Salvar visibilidade
      </button>
    </div>
  </form>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {
      // mostra o botão de salvar somente se algo for alterado
      $('.visibilidade-show-save').on('change keyup paste', function() {
        $('.visibilidade-salvar-btn').removeClass('d-none')
      })

      // https://stackoverflow.com/questions/9236314/how-do-i-synchronize-the-scroll-position-of-two-divs
      $('.custom-codpes-list').on('scroll', function() {
        $('.custom-codpes-textarea').find('textarea').scrollTop($(this).scrollTop());
      })

      $('.custom-codpes-textarea').find('textarea').css('overflow', 'hidden')

    });
  </script>
@endsection
