  <a class="btn btn-sm btn-light text-primary" data-toggle="collapse" href="#user-detail" role="button" aria-expanded="false" aria-controls="collapseExample">
      <i class="fas fa-user"></i>
  </a>

  <div class="collapse" id="user-detail">
      <div class="card card-body">
          <div>
              <i class="fas fa-envelope-square mr-2"></i> <a href="{{ $user->email }}">{{ $user->email }}</a>
          </div>
          <div>
              <i class="fas fa-phone mr-2"></i> {{ $user->telefone ?? 'não disponível' }}
          </div>
      </div>
  </div>
