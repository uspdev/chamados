<div class="form-group">
    <label for="nome">Categoria</label>
    <input class="form-control" id="nome" name="nome" value="{{ $categoria->nome ?? old('nome') }}">
</div>

<div class="form-group">
  <button type="submit" class="btn btn-primary">Enviar</button>
</div>


