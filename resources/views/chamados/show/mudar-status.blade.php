<div class="col-sm form-group mt-2">
    <label class="col-form-label col-sm-2" for="status"><b>Status:</b></label>
    <div class="col-sm-10">
        <select name="status" class="form-control"">
            <option value="" selected="">Escolher</option>
            @foreach($status_list as $status)
            {{-- Caso não tiver nenhum atendente --}}
            @if(!$atendentes->count() and $status == 'Atribuído' and $chamado->status == 'Triagem')
            <option value="{{ $status }}" selected>
            {{ $status }}
            </option>
            @elseif(old('status') == '' and isset($chamado->status))
            <option value="{{ $status }}" {{ ( $chamado->status == $status) ? 'selected' : ''}}>
                {{ $status }}
            </option>
            @else
            <option value="{{ $status }}" {{ (old('status') == $status) ? 'selected' : ''}}>
                {{ $status }}
            </option>
            @endif
            @endforeach
        </select>
    </div>
</div>