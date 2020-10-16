<div class="card bg-light mb-3">
    <div class="card-header h5">
        Arquivos
        <a class="btn btn-outline-primary btn-sm" href="chamados/{{$chamado->id}}/edit"> <i class="fas fa-plus"></i> Adicionar</a>
    </div>
    <div class="card-body">

        @forelse (['Arquivo 1', 'Arquivo 2'] as $arquivo)

        <div class="">
            {{ $arquivo }} 
        </div>

        @empty
        Não há arquivos
        @endforelse

    </div>
</div>