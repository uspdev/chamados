@extends('master')

@section('content')
@parent
<div class="card bg-light mb-3">
    <div class="card-header h5">
        <a href="ajuda">Ajuda</a> <i class="fas fa-angle-right"></i>
        Manual do usuário
    </div>
    <div class="card-body">
        @markdown(file_get_contents(base_path().'/docs/manual-usuario.md'))
    </div>
</div>

@endsection
