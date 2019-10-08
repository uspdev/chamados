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
    <label for="chamado">Chamado:</label>
    <textarea class="form-control" id="chamado" name="chamado" rows="7">{{ $chamado->chamado ?? old('chamado') }}</textarea>
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
