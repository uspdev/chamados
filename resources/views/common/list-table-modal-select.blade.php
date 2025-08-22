<div class="form-group row">
    {{ html()->label($col['label'], $col['name'] ?? $col['name'])->class(['col-form-label', 'col-sm-2']) }}
    <div class="col-sm-10">
        <?php
        $table = substr($col['name'],0,-3);
        ?>
        {{ html()->select($col['name'], $col['data'], null)->class(['form-control'])->placeholder('Selecione um ..') }}
    </div>
</div>
