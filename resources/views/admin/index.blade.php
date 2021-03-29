@extends('master')

@section('content')
  @parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <span class="h4 mt-2">Painel de Admin</span>
    </div>
  </div>

  <div>
    MAX FILE UPLOAD: {{ $file_upload_max_size }} bytes
  </div>
  <div>
    ENV<br>
    <div class="ml-3">
      <div>
        Chamados<br>
        <div class="ml-3">
          UPLOAD_MAX_FILESIZE: {{ config('chamados.upload_max_filesize') }} MB<br>
          Admins: {{ config('chamados.admins') }}
        </div>
      </div>
      <div>
        APP_ENV: {{ config('app.env') }}<br>
        APP_DEBUG: {{ config('app.debug') }}<br>
      </div>
    </div>
  </div>
  <div>
    <span class="h5">Arquivos Oauth</span><br>
    <div class="ml-3">
      @foreach ($oauth_files as $file)
        {{ $file }}<br>
      @endforeach
    </div>
  </div>


@endsection
