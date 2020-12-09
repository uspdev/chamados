<div class="ml-auto">
    <div class="dropdown">
        <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <i class="far fa-calendar-alt mr-2"></i> {{ session('ano') }}
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        @foreach(\App\Models\Chamado::anos() as $ano)
            <a class="dropdown-item" href="chamados/anos/{{ $ano }}">{{ $ano }}</a>
        @endforeach
        </div>
    </div>
</div>