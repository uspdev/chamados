<div class="col-sm form-group mt-2">
    <label class="col-form-label col-sm-2" for="complexidade"><b>Complexidade:</b></label>
    <div class="col-sm-10">
        <select name="complexidade" class="form-control">
            <option value="" selected="">Escolher</option>
            @foreach($complexidades as $complexidade)
            @if(old('complexidade') == '' and isset($chamado->complexidade))
            <option value="{{ $complexidade }}" {{ ( $chamado->complexidade == $complexidade) ? 'selected' : ''}}>
                {{ $complexidade }}
            </option>
            @else
            <option value="{{ $complexidade }}" {{ (old('complexidade') == $complexidade) ? 'selected' : ''}}>
                {{ $complexidade }}
            </option>
            @endif
            @endforeach
        </select>
    </div>
</div>