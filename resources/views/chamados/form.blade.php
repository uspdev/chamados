@can('admin')
<div class="form-group">
    <label for="atribuido_para">Atribuir para: </label>
    <select name="atribuido_para" class="form-control">
        <option value="" selected="">Escolher</option>
        @foreach($atendentes as $atendente)
            @if(old('atribuido_para') == '' and isset($chamado->atribuido_para))
                <option value="{{ $atendente[0] }}" {{ ( $chamado->atribuido_para == $atendente[0]) ? 'selected' : ''}}>
                    {{ $atendente[1] }}
                </option>                
            @else
                <option value="{{ $atendente[0] }}" {{ (old('atribuido_para') == $atendente[0]) ? 'selected' : ''}}>
                    {{ $atendente[1] }}
                </option>   
            @endif
        @endforeach
    </select>
</div>
@endcan

<div class="form-group">
    <label for="nome">Seu telefone ou ramal:</label>
    <input class="form-control" id="telefone" name="telefone" value="{{ Auth::user()->telefone ?? old('telefone') }}">
    <small id="telefoneHelp" class="form-text text-muted">Exemplo: 3091-4616 ou 914616</small>
</div>

<div class="form-group">
    <label for="categoria_id">Categoria:</label>
    <select name="categoria_id" class="form-control">
        <option value="" selected="">Escolha uma categoria</option>
        @foreach($categorias->sortBy('nome') as $categoria)
            @if(old('categoria_id')=='' and isset($chamado->categoria_id))
                <option value="{{ $categoria->id }}" {{ ( $chamado->categoria_id == $categoria->id) ? 'selected' : ''}}>
                    {{ $categoria->nome }}
                </option>                
            @else
                <option value="{{ $categoria->id }}" {{ (old('categoria_id') == $categoria->id) ? 'selected' : ''}}>
                    {{ $categoria->nome }}
                </option>   
            @endif

        @endforeach()
    </select>
</div>

<div class="form-group">
    <label for="predio">Prédio:</label>
    <select name="predio" class="form-control">
        <option value="" selected="">Escolha uma prédio</option>
        @foreach($predios as $predio)
            @if(old('predio')=='' and isset($chamado->predio))
                <option value="{{ $predio }}" {{ ( $chamado->predio == $predio) ? 'selected' : ''}}>
                    {{ $predio }}
                </option>                
            @else
                <option value="{{ $predio }}" {{ (old('predio') == $predio) ? 'selected' : ''}}>
                    {{ $predio }}
                </option>   
            @endif
        @endforeach()
    </select>
</div>

<div class="form-group">
    <label for="nome">Sala:</label>
    <input class="form-control" id="sala" name="sala" value="{{ $chamado->sala ?? old('sala') }}">
     <small id="salaioHelp" class="form-text text-muted">Exemplo: sala 02</small>
</div>

<div class="form-group">
    <label for="chamado">Chamado:</label>
    <textarea class="form-control" id="chamado" name="chamado" rows="4">{{ $chamado->chamado ?? old('chamado') }}</textarea>
</div>

<div class="form-group">
    <label for="nome">Patrimônio do computador:</label>
    <input class="form-control" id="patrimonio" name="patrimonio" value="{{ $chamado->patrimonio ?? old('patrimonio') }}">
     <small id="patrimonioHelp" class="form-text text-muted">Exemplo: 008.047977</small>
</div>

@can('admin')
<div class="form-group">
    <label for="nome">Número USP do(a) requisitante:</label>
    <input class="form-control" id="codpes" name="codpes" value="{{ $chamado->user->codpes ?? old('codpes') }}">
     <small id="codpesHelp" class="form-text text-muted">Exemplo: 123456</small>
</div>
@endcan

<div class="form-group">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
