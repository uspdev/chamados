@section('styles')
  @parent
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="css/arquivos.css">
  <style>
    #card-arquivos {
      border: 1px solid DarkGoldenRod;
      border-top: 3px solid DarkGoldenRod;
    }

  </style>
@endsection

<a name="card_arquivos"></a>
<div class="card bg-light mb-3" id="card-arquivos">
  <div class="card-header form-inline">
    Arquivos
    @if (Gate::check('update', $chamado) && $chamado->status != 'Fechado')
      {{-- desativando quando fechado --}}
      <label for="input_arquivo">
        <span class="btn btn-sm btn-light text-primary ml-2"> <i class="fas fa-plus"></i> Adicionar</span>
      </label>
      <span data-toggle="tooltip" data-html="true" title="Tamanho máximo de cada arquivo: {{ $max_upload_size }}KB ">
        <i class="fas fa-question-circle text-secondary ml-2"></i>
      </span>
      <form id="form_arquivo" action="arquivos" method="post" enctype="multipart/form-data"
        class="w-100 d-inline-block">
        @csrf
        <input type="hidden" name="chamado_id" value="{{ $chamado->id }}">
        <input type="hidden" id="max_upload_size" name="max_upload_size" value="{{ $max_upload_size }}">

        <input type="file" name="arquivo[]" id="input_arquivo" accept="image/jpeg,image/png,application/pdf"
          class="d-none" multiple capture="environment">

        <div class="nome-arquivo w-100" id="nome_arquivo">
          <ul class="preview-files"></ul>
          <span id="limpar_input_arquivo" class="btn btn-outline-danger btn-sm" title="Limpar tudo"> <i
              class="far fa-trash-alt"></i></span>
          <span id="submit_form_arquivo" class="btn btn-outline-success btn-sm" title="Enviar"> <i
              class="fas fa-file-import"></i></span>

        </div>
      </form>
    @endif
  </div>
  <div class="card-body">
    @if (count($chamado->arquivos) > 0)
      <div class="arquivos-imagens">

        @foreach ($chamado->arquivos as $arquivo)
          @if (preg_match('/jpeg|png/i', $arquivo->mimeType))
            @if (Gate::check('update', $chamado))
              {{-- desativando quando fechado --}}
              <a onclick="ativar_exclusao()" class="d-inline-block ml-1 mr-1" data-fancybox="arquivo-galeria"
                href="arquivos/{{ $arquivo->id }}" data-caption='<form action="arquivos/{{ $arquivo->id }}" method="post">
                            @csrf
                            @method("delete")
                            <button type="submit" onclick="return confirm(&#39;Tem certeza que deseja deletar {{ $arquivo->nome_original }}?&#39;);" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                        </form>'>
                <img class="arquivo-img" width="50px" src="arquivos/{{ $arquivo->id }}"
                  alt="{{ $arquivo->nome_original }}" data-toggle="tooltip" data-placement="top"
                  title="{{ $arquivo->nome_original }}">
              </a>
            @else
              <a class="d-inline-block ml-1 mr-1" data-fancybox="arquivo-galeria" href="arquivos/{{ $arquivo->id }}">
                <img class="arquivo-img" width="50px" src="arquivos/{{ $arquivo->id }}"
                  alt="{{ $arquivo->nome_original }}" data-toggle="tooltip" data-placement="top"
                  title="{{ $arquivo->nome_original }}">
              </a>
            @endif
          @endif
        @endforeach
      </div>
      <div class="arquivos-lista">

        <ul class="list-unstyled">
          @foreach ($chamado->arquivos as $arquivo)
            @if (preg_match('/pdf/i', $arquivo->mimeType))
              <li class="modo-visualizacao">
                @if (Gate::check('update',$chamado))
                  <div class="arquivo-acoes">
                    <form action="arquivos/{{ $arquivo->id }}" method="post" class="d-inline-block">
                      @csrf
                      @method('delete')
                      <button type="submit"
                        onclick="return confirm('Tem certeza que deseja deletar {{ $arquivo->nome_original }}?');"
                        class="btn btn-outline-danger btn-sm btn-deletar btn-arquivo-acao"><i
                          class="far fa-trash-alt"></i></button>
                    </form>
                    <form class="d-inline-block">
                      <button type="button" class="btn btn-outline-warning btn-sm btn-editar btn-arquivo-acao"><i
                          class="far fa-edit"></i></button>
                    </form>
                  </div>
                @endif
                <a href="arquivos/{{ $arquivo->id }}" title="{{ $arquivo->nome_original }}"
                  class="nome-arquivo-display">
                  <i class="fas fa-file-pdf"></i>
                  <span>
                    {{ $arquivo->nome_original }}
                  </span>
                </a>
                <form action="arquivos/{{ $arquivo->id }}" method="post" class="editar-nome-arquivo-form">
                  @csrf
                  @method('patch')
                  <div class="input-wrapper">
                    <input type="text" name="nome_arquivo" class="input-nome-arquivo"
                      value="{{ pathinfo($arquivo->nome_original,PATHINFO_FILENAME) }}">
                  </div>
                  <div class="btns-wrapper">
                    <button type="submit" class="btn btn-outline-success btn-sm ml-2 btn-arquivo-acao"><i
                        class="fas fa-check"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-sm  btn-arquivo-acao limpar-edicao-nome"><i
                        class="fas fa-times"></i></button>
                  </div>
                </form>
              </li>
            @endif
          @endforeach
        </ul>

      </div>
    @else
      Sem arquivos anexados
    @endif

  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script src="js/arquivos.js"></script>
@endsection
