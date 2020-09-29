<div class="form-group row">
    {{ Form::label($col['name'], $col['label'] ?? $col['name'], ['class' => 'col-form-label col-sm-2']) }}
    <div class="col-sm-10">
        {{ Form::text($col['name'],null,['class'=>'form-control']) }}
    </div>
</div>
