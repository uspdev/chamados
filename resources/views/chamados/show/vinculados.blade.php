<div class="card bg-light mb-3">
    <div class="card-header h5">
        Chamados Vinculados
        <a class="btn btn-outline-primary btn-sm" href="chamados/{{$chamado->id}}/edit"> <i class="fas fa-plus"></i> Adicionar</a>
    </div>
    <div class="card-body">

        <ul class="ml-3 list-unstyled">
            @forelse($vinculados as $vinculado)
            <li>
                <a href="chamados/{{$vinculado->id}}">
                    {{ $vinculado->nro }}/{{ Carbon\Carbon::parse($vinculado->created_at)->format('Y') }}
                    {{ Illuminate\Support\Str::limit($vinculado->assunto, 30, ' ..') }}
                </a>
            </li>
            @empty
            nenhum
            @endforelse
        </ul>

    </div>
</div>
