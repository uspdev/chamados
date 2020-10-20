@section('styles')
@parent
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="css/arquivos.css">
@endsection

<div class="card bg-light mb-3">
    <div class="card-header h5">
        Arquivos
        <a class="btn btn-outline-primary btn-sm d-none" href="chamados/{{$chamado->id}}/edit"> <i class="fas fa-plus"></i> Adicionar</a>
        <label for="input_arquivo">
            <span class="btn btn-outline-primary btn-sm"> <i class="fas fa-plus"></i> Adicionar</span>
        </label> 
        <form id="form_arquivo" action="arquivos" method="post" enctype="multipart/form-data" class="w-100 d-inline-block">
            @csrf
            <input type="hidden" name="chamado_id" value="{{$chamado->id}}">
            
            <input type="file" name="arquivo" id="input_arquivo" accept=".jpeg,.jpg,.png,.pdf" class="d-none">
            
            <div class="nome-arquivo w-100" id="nome_arquivo">
                <p></p>
                <span id="limpar_input_arquivo" class="btn btn-outline-danger btn-sm"> <i class="far fa-trash-alt"></i></span>
                <span id="submit_form_arquivo" class="btn btn-outline-success btn-sm"> <i class="fas fa-file-import"></i></span>                
            </div>
        </form>
    </div>
    <div class="card-body">
        @if (count($chamado->arquivos) > 0)
            <div class="arquivos-imagens">
               
            @foreach ($chamado->arquivos as $arquivo)
                @if (preg_match('/jpeg|png/i', $arquivo->mimeType))
                    <a onclick="ativar_exclusao()" class="d-inline-block ml-1 mr-1" data-fancybox="arquivo-galeria" href="arquivos/{{$arquivo->id}}" 
                        data-caption='
                        <form action="arquivos/{{$arquivo->id}}" method="post">
                            @csrf
                            @method("delete")
                            <button type="submit" onclick="return confirm(&#39;Tem certeza que deseja deletar {{ $arquivo->nome_original }}?&#39;);" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                        </form>
                        '>
                        <img class="arquivo-img"  width="50px" src="arquivos/{{$arquivo->id}}" alt="{{ $arquivo->nome_original }}" >
                    </a>
                    
                @endif
            @endforeach
            </div>
            <div class="arquivos-lista">
                
                <ul class="list-unstyled">    
                    @foreach ($chamado->arquivos as $arquivo)
                    @if (preg_match('/pdf/i', $arquivo->mimeType))
                    <li> 
                        <form action="arquivos/{{$arquivo->id}}" method="post" class="d-inline-block">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Tem certeza que deseja deletar {{ $arquivo->nome_original }}?');" class="btn btn-outline-danger btn-sm btn-deletar-documento"><i class="far fa-trash-alt"></i></button>
                        </form>
                        <a href="arquivos/{{$arquivo->id}}" title="{{ $arquivo->nome_original }}">
                            <i class="fas fa-file-pdf"></i>
                            {{ $arquivo->nome_original }}
                        </a>
                    </li>
                    @endif
                    
                    
                    @endforeach
                </ul>
            </div>
        @else 
         Não há arquivos
        @endif

    </div>
</div>

@section('javascripts_bottom')
@parent
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="js/arquivos.js"></script>
@endsection
