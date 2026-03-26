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
    @include('chamados.partials.arquivo-add-btn')
  </div>
  <div class="card-body">
    @if (count($chamado->arquivos) > 0)
      {{-- renderizando as imagens --}}
      <div class="arquivos-imagens">
        @foreach ($chamado->arquivos as $arquivo)
          @if (preg_match('/jpeg|png/i', $arquivo->mimeType))
            @if (Gate::check('update', $chamado))
              {{-- desativando delete quando chamado fechado --}}
              <a href="arquivos/{{ $arquivo->id }}"class="d-inline-block ml-1 mr-1" onclick="ativar_exclusao()"
                data-fancybox="arquivo-galeria" data-caption='@include('chamados.partials.arquivo-del-image-btn')'>
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
      {{-- listando outros arquivos. Nao vamos mostrar a lista de imagens pois já foi redenrizado anteriormente --}}
      <div class="arquivos-lista">
        <ul class="list-unstyled">
          @foreach ($chamado->arquivos as $arquivo)
            @if (!preg_match('/jpeg|png/i', $arquivo->mimeType))
              <li class="modo-visualizacao">
                @include('chamados.partials.arquivo-edit-del-btn')
                <a href="arquivos/{{ $arquivo->id }}" title="{{ $arquivo->nome_original }}"
                  class="nome-arquivo-display">
                  @include('chamados.partials.arquivo-icon')
                  <span>{{ $arquivo->nome_original }}</span>
                </a>
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
