<div class="form-group row">
    {{ html()->label($col['label'], $col['name'] ?? $col['name'])->class(['col-form-label', 'col-sm-2']) }}
    <div class="col-sm-10">
        {{ html()->text($col['name'], null)->class(['form-control']) }}
    </div>
</div>
