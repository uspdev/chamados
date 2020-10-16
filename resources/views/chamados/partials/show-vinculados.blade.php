<div class="mt-3">
    <div class="font-weight-bold">
    Chamados Vinculados
    <a class="btn btn-sm btn-outline-primary text-primary">
    <i class="fas fa-plus"></i>
    </a>
    </div>
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
