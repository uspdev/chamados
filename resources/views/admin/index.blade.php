@extends('master')

@section('content')
  @parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <span class="h4 mt-2">Painel de Admin</span>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div>
        MAX FILE UPLOAD: {{ $file_upload_max_size }} bytes
      </div>
      <br>
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
      <br>
      <div>
        <span class="h5">Arquivos Oauth</span><br>
        <div class="ml-3">
          @foreach ($oauth_files as $file)
            <button class="get_oauth">{{ basename($file) }}</button><br>
          @endforeach
        </div>
      </div>
      <br>
      <div>
        <span class="h5">Arquivos de log</span><br>
        <div class="ml-3">
          <a href="admin/log-reader">Log reader</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <pre id="oauth-viewer"></pre>
    </div>
  </div>



@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $('.get_oauth').on('click', function() {
        var file = $(this).html();
        $.ajax({
          url: 'admin/get_oauth_file/' + file,
          type: 'GET',
          success: function(data) {
            console.log(data)
            $('#oauth-viewer').html(JSON.stringify(JSON.parse(data), null, 4))
          }
        })
      })

    })

  </script>
@endsection
